/**
 * @author Stefan Kaiser
 */

jQuery(function($) { klimo_localgroupspage : {


		function MapControl(wrapper) {
			var me = this;
			this.wrapper = wrapper
		


			this.__contruct = function() {
				colors = {};
				colors['1'] = '#A4D886';
				colors['2'] = '#FCECA2';
				vals = [];
				for(var i=0,j=47; i<j; i++){
					iStr = i.toString();
				  	vals[iStr] = i;
				};
				console.log(vals);
				$(wrapper).vectorMap({
					map: "lower_saxony_de",
					series: { 
						regions: [
						{
							scale: ['#000000','#ffffff'],
				            attribute: 'fill',
				            normalizeFunction: 'linear',
				            values: vals
			          }]
					},
					onRegionClick: function(event, code){
						alert(code); 
					}
				});
				
			}
			//jvectormaps style by marina
			  
			

			// call constructor
			this.__contruct();
		}

		

		$(document).ready(function() {
			var mapControl = new MapControl('#groupmap');
		});
	}

});
