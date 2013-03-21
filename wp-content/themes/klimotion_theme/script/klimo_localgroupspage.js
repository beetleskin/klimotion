/**
 * @author Stefan Kaiser
 */

jQuery(function($) { klimo_localgroupspage : {


		function Mapcontrol(wrapper) {
			var me = this;
			this.wrapper = wrapper
		


			this.__contruct = function() {
				$(wrapper).vectorMap({
					map: "de_mill_en",
				});
			}
			
			
			// call constructor
			this.__contruct();
		}

		

		$(document).ready(function() {
			var mapsControl = new MapsControl('#groupmap');
		});
	}

});
