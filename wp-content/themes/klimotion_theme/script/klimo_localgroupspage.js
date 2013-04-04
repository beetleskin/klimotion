/**
 * @author Stefan Kaiser
 */

jQuery(function($) { klimo_localgroupspage : {


		function MapControl(options) {
			var me = this;
			var defaults = {
				mapID: '#groupmap',
				map: 'lower_saxony_de'
			}
			this.opts = $.extend(defaults, options);
			this.map = 0;
			
		


			this.__contruct = function() {
				
				// prepare map
				var mapOpts = {
					map: me.opts.map,
					series: { 
						regions: [{
							scale: ['#000000','#ffffff'],
				            attribute: 'fill',
				            normalizeFunction: 'linear',
				            values: me.opts.mapVals
			          	},]
					},
					onRegionClick: me.onRegionClickHandler
				}
				// init map
				me.map = $(me.opts.mapID).vectorMap(mapOpts);
				
				
				me.ajaxDefaults = {
					type : "POST",
					url : me.opts.ajaxConfig.ajaxurl,
					dataType : "html",
					beforeSend : me.beforeSendHandler,
					success : me.successHandler,
					complete : me.completeHandler
				};
			}
			
			
			this.beforeSendHandler = function() {
				console.log('beforeSendHandler');
			}
			
			
			this.successHandler = function(msg) {
				console.log(msg);
			}
			
			
			this.completeHandler = function() {
				console.log('completeHandler');
			}
			
			
			this.onRegionClickHandler = function(event, code){
				var ajaxOpts = $.extend(me.ajaxDefaults, {
					data : {
						nonsense :  me.opts.ajaxConfig.nonsense,
						action :  me.opts.ajaxConfig.submitAction,
						district : code,
					}
				});
				
				$.ajax(ajaxOpts);
			}
			

			// call constructor
			this.__contruct();
		}

		

		$(document).ready(function() {
			var mapControl = new MapControl({
				mapID: '#groupmap',
				mapVals: localgroupspage_config.mapVals,
				ajaxConfig: localgroupspage_config.ajaxConfig,
				map: 'lower_saxony_de'
			});
		});
	}

});
