/**
 * @author Stefan Kaiser
 */

/**
 * $plugins
 */

(function( $ ) {
	$.fn.adaptiveTableInput = function( options ) {
		
		var $this = this;
		var defaults = {
			'trSelector' : '',
			'tplNames' : []
		};
		
		$this.opts = $.extend(defaults, options);
		$this.templateRow = 0;
		
		return this.each( function() {
			
			// gather inputs per row
			if($this.opts.tplNames.length == 0) {
				var tplNames = [];
				$('tr:first input', this).each(function() {
					name = $(this).attr('name');
					tplNames.push(name.substring(0, name.length - 1));
				});
				$this.opts.tplNames = tplNames;
			}
			
			
			$this.noLinks = $('tr' + $this.opts.trSelector, this).length;
			$this.templateRow = $('tr' + $this.opts.trSelector, this).first().clone();
			$('input', $this.templateRow).val('');
			
			// remove-link-meta button
			$('a.removelink', $this).click(removeLink);
			
			// add-link-meta-button
			$('a.addlink', $this).click(addLink);
		});
			
			
		function removeLink() {
			var row = $(this).closest('tr' + $this.opts.trSelector);
			row.remove();
			$this.noLinks--;
			renameIDs();
		}
			
			
		function addLink() {
			var lastPair = $('tr' + $this.opts.trSelector, $this).last();
			if( $('input', lastPair).last().val() == '') {
				lastPair.fadeTo(400, 0.2).fadeTo(400, 1.0);
			} else {
				var newPair = $this.templateRow.clone();
				// for each input
				for(var i=0,j=$this.opts.tplNames.length; i<j; i++){
					$('input[name*=' + $this.opts.tplNames[i] + ']', newPair).attr('name', $this.opts.tplNames[i] + $this.noLinks);
				};
			
				$('a.removelink', newPair).click(removeLink);
				$('tbody', $this).append(newPair);
				$this.noLinks++;
			}
		}
			
			
		function renameIDs() {
			// for each row
			$('tr' + $this.opts.trSelector, $this).each(function(index, value) {
				// for each input
				for(var i=0,j=$this.opts.tplNames.length; i<j; i++){
					$('input[name*=' + $this.opts.tplNames[i] + ']', value).attr('name', $this.opts.tplNames[i] + index);
				};
			});
			$this.noLinks = $('tr' + $this.opts.trSelector, $this).length;
		}
	};
})( jQuery );




jQuery(function($) { ideaform : {
	
		function IdeaForm(wrapper) {
			var me = this;
			this.wrapper = $(wrapper);
			this.form = $('form', me.wrapper);
			this.submit = $('a#idea_submit', wrapper);
			this.linksControl = null;


			this.__contruct = function() {
				me.config = ideaform_config;
				$("#idea_links", wrapper).adaptiveTableInput({ trSelector: '.links_meta_pair'});
				$("#idea_files", wrapper).adaptiveTableInput({ trSelector: '.files_meta_pair'});
				
				
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
