/**
 * @author Stefan Kaiser
 */

jQuery(function($) { klimo_localgroupspage : {


		function MapControl(wrapper) {
			var me = this;
			this.wrapper = wrapper
		


			this.__contruct = function() {
				$(wrapper).vectorMap({
					map: "lower_saxony_de",
				});
			}
			//hier alle hinpinselnnnn
			
			
			// call constructor
			this.__contruct();
		}

		

		$(document).ready(function() {
			var mapControl = new MapControl('#groupmap');
		});
	}

});
