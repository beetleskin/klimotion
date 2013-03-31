/**
 * @author Stefan Kaiser
 */

jQuery(function($) { adminidea : {


		function LinksControl(wrapper) {
			// TODO: this class is a exact copy of the one in klimoPT_frontend_forms.js ... merge!
			var me = this;
			this.wrapper = wrapper;
			this.noLinks = 0;
			this.templateRow = 0;
			this.textSelect = '_linktext_';
			this.urlSelect = '_linkurl_'
			


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

		

		$(document).ready(function() {
			var linksControl = new LinksControl('#idea-post-meta-links');
		});
	}

});
