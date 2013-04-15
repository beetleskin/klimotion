/**
 * @author Stefan Kaiser
 */


jQuery(function($) { ideaform : {
	
		function IdeaForm(wrapper) {
			var me = this;
			this.wrapper = $(wrapper);
			this.form = $('form', me.wrapper);
			this.submit = $('button#idea_submit', wrapper);
			this.linksControl = null;


			this.__contruct = function() {
				me.config = ideaform_config;
				$("#idea_links", wrapper).adaptiveTableInput({ trSelector: '.links_meta_pair', maxRows: 10});
				$("#idea_files", wrapper).adaptiveTableInput({ trSelector: '.files_meta_pair', maxRows: 6});
				
				
				// aims autosuggest
				$('input#idea_aims', me.wrapper).autoSuggest(
					me.config.as_aims.items, 
					{
						selectedItemProp : "name",
						searchObjProps : "name",
						minChars : 1,
						startText : "Ziele ...",
						emptyText : "neues Ziel mit TAB",
						asHtmlID : "idea_aims"
					}
				);
				
				
				// district multiselect
				$('select#idea_group').multiselect({
					multiple: false,
					selectedList: 1,
					header: false,
					noneSelectedText: "Wähle einen Landkreis",
				});
				
				
				// textfield counters
				$('textarea[maxlength]', me.wrapper).inputCounter();
				
				
				// declare ajaxForm
				var formOptions = {
					beforeSerialize : me.beforeSerialize,
					success : me.successHandler,
					error : me.transmissionErrorHandler,
					url : me.config.ajaxConfig.ajaxurl,
					dataType : "json",
					data : {
						'action' : me.config.ajaxConfig.submitAction,
						'klimoIdeaFormNonce' : me.config.ajaxConfig.klimoIdeaFormNonce
					},
				};
				
				me.form.ajaxForm(formOptions);
				me.submit.click(function(event) {
					if( $(this).attr('nopriv') !== 'undefined' ) {
						$("html body").animate({ scrollTop: 0 }, "slow", function(){
							$('#errormessage', me.form).fadeTo(400, 0.2).fadeTo(400, 1.0);
						});
					} else {
						me.form.submit();
					}
					
					// no further click handling
					return false;
				});
			}
			
			this.beforeSerialize = function(formData, jqForm, options) {
				tinyMCE.get("ideadescription").save();
			}
			
			this.successHandler = function(response, statusText, xhr, $form) {
				
				// error handling
				if(response.error != null) {
					me.errorHandler(response.error);
				} else if(response.securityError != null) {
					me.securityErrorHandler(response.securityError);
				} else if(response.success != null) {
					me.submitSuccessHandler(response);
				} else {
					console.log("transmittion error: ", response, statusText, xhr);
				}
			}
			
			this.transmissionErrorHandler = function(param) {
				me.form.animate({
					opacity : 'toggle',
					height : 'toggle'
				}, "slow", function(event) {
					var errorDiv = $('<div id="connectionerror"><p>Hoppla, da ist was schief gelaufen. Bitte versuch es später noch einmal.</p></div>');
					errorDiv.css('display', 'none');
					me.form.replaceWith(errorDiv);
					errorDiv.animate({
						opacity : 'toggle',
						height : 'toggle'
					}, "slow");
				});
				
				$("html body").animate({ scrollTop: 0 }, "slow");
				setTimeout("location.reload()", 5000);
			}
			
			this.errorHandler = function(errors) {
				// remove all errors
				$('.formerror', me.form).removeClass('formerror');

				$('#errormessage', me.form).animate({
					opacity : 'toggle',
					height : 'toggle'
				}, 600, function(event) {
					$('#errormessage', me.form).empty();
					
					for (var e=0; e < errors.length; e++) {
						if(errors[e].element != null && errors[e].message != null) {
							// display error message
							$('#errormessage', me.form).append('<p>' + errors[e].message + '</p>');
							// add css class to errorous element
							$('[name="' + errors[e].element + '"]', me.form).addClass('formerror');
						}
					}

					if(errors.length > 0) {
						$('#errormessage', me.form).animate({
							opacity : 'toggle',
							height : 'toggle'
						}, "slow");
					}
				});
				
				$("html, body").animate({ scrollTop: 0 }, "slow");
			}
			
			
			this.securityErrorHandler = function(securityError) {
				if(securityError.message != null) {
					me.form.animate({
						opacity : 'toggle',
						height : 'toggle'
					}, "slow", function(event) {
						var errorDiv = $(securityError.message);
						errorDiv.css('display', 'none');

						me.form.replaceWith(errorDiv);
						errorDiv.animate({
							opacity : 'toggle',
							height : 'toggle'
						}, "slow");
						me.form.scrollTop(300);
					});

					setTimeout("location.reload()", 5000);
				} else {
					// fallback strategy ...
					location.reload();
				}
			}
			
			this.submitSuccessHandler = function(response) {
				me.form.animate({
					opacity : 'toggle',
					height : 'toggle'
				}, "slow", function(event) {
					var successDiv = $(response.success);
					successDiv.css('display', 'none');

					me.form.replaceWith(successDiv);
					successDiv.animate({
						opacity : 'toggle',
						height : 'toggle'
					}, "slow");
				});
			}
			
			
			
			
			// call constructor
			this.__contruct();
		}

		

		$(document).ready(function() {
			var ideaForm = new IdeaForm('#ideaform_wrap');
		});
	}

});
