/**
 * @author Stefan Kaiser
 */

jQuery(function($) { ideaform : {


		function IdeaForm(wrapper) {
			var me = this;


			this.__contruct = function() {
				console.log(ideaform_config)
			}
			
			
			// call constructor
			this.__contruct();
		}

		

		$(document).ready(function() {
			var ideaForm = new IdeaForm('body');
		});
	}

});
