/**
 * @author Stefan Kaiser
 */

jQuery(function($) { adminidea : {


		function LinksControl(wrapper) {
			var me = this;
			this.wrapper = wrapper;
			this.noLinks = 0;
			this.templateRow = 0;


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
						$('input[name*=_linktext_]', newPair).attr('name', '_linktext_' + me.noLinks);
  						$('input[name*=_linkurl_]', newPair).attr('name', '_linkurl_' + me.noLinks);
  						$('a.removelink', newPair).click(me.removeLink);
						$('tbody', me.wrapper).append(newPair);
						me.noLinks++;
					}
			}
			
			
			this.renameIDs = function() {
				i = 0;
				$('tr.links_meta_pair', me.wrapper).each(function(index, value) {
  					$('input[name*=_linktext_]', value).attr('name', '_linktext_' + i);
  					$('input[name*=_linkurl_]', value).attr('name', '_linkurl_' + i);
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
