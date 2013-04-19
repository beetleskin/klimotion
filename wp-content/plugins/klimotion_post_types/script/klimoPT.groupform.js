/**
 * @author Stefan Kaiser
 */


jQuery(function($) { ideaform : {
	
		function GroupForm(wrapper) {
			var me = this;
			this.wrapper = $(wrapper);
			this.form = $('form', me.wrapper);
			this.submit = $('button#group_submit', wrapper);
			this.linksControl = null;


			this.__contruct = function() {
				me.config = groupform_config;
				
				
				// scopes autosuggest
				$('input#group_scopes', me.wrapper).autoSuggest(
					me.config.as_scopes.items, 
					{
						selectedItemProp : "name",
						searchObjProps : "name",
						minChars : 2,
						startText : "Schule / etc.",
						emptyText : "neues Ziel mit TAB",
						asHtmlID : "group_scopes"
					}
				);
				
				 
				// district multiselect
				$('select#group_district').multiselect({
					multiple: false,
					selectedList: 1,
					noneSelectedText: "Wähle einen Landkreis",
				}).multiselectfilter({
					label: false,
					placeholder: 'Filter ...'
				});
				
				
				
				// declare ajaxForm
				var formOptions = {
					beforeSerialize : me.beforeSerialize,
					success : me.successHandler,
					error : me.transmissionErrorHandler,
					url : me.config.ajaxConfig.ajaxurl,
					dataType : "json",
					data : {
						'action' : me.config.ajaxConfig.submitAction,
						'klimoGroupFormNonce' : me.config.ajaxConfig.klimoGroupFormNonce
					},

				};
				
				me.form.ajaxForm(formOptions);
				me.submit.click(function() {

					if( $(this).attr('nopriv') !== undefined ) {
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
				var isFormValid = true;
				
				// save rich editor content to textfield
				tinyMCE.get("groupdescription").save();
				
				// validate file size
				$('input[type="file"]', me.wrapper).each(function() {
					var f = this.files[0];
					if( f != undefined && f.size > me.config.ajaxConfig.file_size_max) {
						me.errorHandler([{
							element: this, 
							message: "<p>Bilder dürfen nicht größer als " + (me.config.ajaxConfig.file_size_max / 1000000) + " MB groß sein.</p>",
						}]);
						isFormValid = false;
						return false;
					}
				});
				
				return isFormValid;
				
			}
			
			this.successHandler = function(response, statusText, xhr) {
				
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
			var groupForm = new GroupForm('#groupform_wrap');
		});
	}

});
