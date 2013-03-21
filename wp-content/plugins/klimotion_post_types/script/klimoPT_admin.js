/**
 * @author Stefan Kaiser
 */

jQuery(function($) { adminidea : {


		function LinksControl(wrapper) {
			var me = this;
			// this.f = textfield;
			// this.max = maxChars;
			// this.counterHTML;

			this.__contruct = function() {
				alert("asdf");
				/*me.counterHTML = $('<div class="tfcounter"><span>0</span>/' + me.max + '</div>');
				me.f.after(me.counterHTML);
				me.f.bind('keydown', me.observeInput);
				me.f.bind('keyup', me.observeInput);*/
			}
			
			// call constructor
			this.__contruct();
		}

		

		$(document).ready(function() {
			var linksControl = new LinksControl('body');
		});
	}

});
