/**
 * @author Stefan Kaiser
 */

jQuery(function($) { ideaform : {
	
	
		function LinksControl(wrapper) {
			// TODO: this class is a exact copy of the one in klimoPT_admin.js ... merge!
			var me = this;
			this.wrapper = wrapper;
			this.noLinks = 0;
			this.templateRow = 0;
			this.textSelect = 'linktext_';
			this.urlSelect = 'linkurl_'
			


			this.__contruct = function() {
				me.noLinks = $('tr.links_meta_pair', me.wrapper).length;
				me.templateRow = $('tr.links_meta_pair', me.wrapper).first().clone();
				$('input', me.templateRow).val('');
				
				// remove-link-meta button
				$('a.removelink', me.wrapper).click(me.removeLink);
				
				// add-link-meta-button
				$('a#addlink', me.wrapper).click(me.addLink);
			}
			
			
			this.removeLink = function() {
				row = $(this).closest('tr.links_meta_pair')
				row.remove();
				me.noLinks--;
				me.renameIDs();
			}
			
			
			this.addLink = function() {
				lastPair = $('tr.links_meta_pair', me.wrapper).last();
				if( $('input', lastPair).last().val() == '') {
					lastPair.fadeTo(400, 0.2).fadeTo(400, 1.0);
				} else {
					newPair = me.templateRow.clone();
					$('input[name*=' + me.textSelect + ']', newPair).attr('name', me.textSelect + me.noLinks);
					$('input[name*=' + me.urlSelect + ']', newPair).attr('name', me.urlSelect + me.noLinks);
					$('a.removelink', newPair).click(me.removeLink);
					$('tbody', me.wrapper).append(newPair);
					me.noLinks++;
				}
			}
			
			
			this.renameIDs = function() {
				i = 0;
				$('tr.links_meta_pair', me.wrapper).each(function(index, value) {
  					$('input[name*=' + me.textSelect + ']', value).attr('name', me.textSelect + i);
  					$('input[name*=' + me.urlSelect + ']', value).attr('name', me.urlSelect + i);
  					i++;
  				});
  				me.noLinks = $('.links_meta_pair', me.wrapper).length;
			}
			
			
			
			// call constructor
			this.__contruct();
		}

		
		function IdeaForm(wrapper) {
			var me = this;
			this.wrapper = $(wrapper);
			this.form = $('form', me.wrapper);
			this.submit = $('a#idea_submit', wrapper);
			this.linksControl = null;


			this.__contruct = function() {
				me.config = ideaform_config;
				me.linksControl = new LinksControl($("#idea_links", wrapper));
				
				
				// aims autosuggest
				$('input#idea_aims', me.wrapper).autoSuggest(
					me.config.as_aims, 
					{
						selectedItemProp : "name",
						searchObjProps : "name",
						minChars : 1,
						startText : "Ziele ...",
						emptyText : "neues Ziel mit TAB",
						asHtmlID : "idea_aims"
					}
				);
				
				
				
				// declare ajaxForm
				var formOptions = {
					beforeSubmit : me.beforeSubmit,
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
				me.submit.click(function() {
					me.form.submit();
					return false;
				});
			}
			
			this.beforeSubmit = function(formData, jqForm, options) {
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
					alert("xxx this should never happen!")
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
				
				$("html, body").animate({ scrollTop: 0 }, "slow");
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
