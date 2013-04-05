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
					dataType : "json",
					beforeSend : me.beforeSendHandler,
					success : me.successHandler,
					complete : me.completeHandler
				};
			}
			
			
			this.beforeSendHandler = function() {
				if($('#article_wrap').is(":animated")) {
					return false;	
				}
				
				time = ($('#article_wrap').html().length == 0)? 1 : 500;
				$('#article_wrap', me.form).animate({
					opacity : 'toggle',
					height : 'toggle'
				}, time);
				return true;
			}
			
			
			this.successHandler = function(msg) {
				if($('#article_wrap').is(":animated")) {
					$('#article_wrap').promise('fx').done(function() {
						me.fadeInResponse(msg);
					});
				} else {
					me.fadeInResponse(msg);
				}
			}
			
			
			this.fadeInResponse = function(msg) {
				console.log(msg)
				var html = "";
				// error handling
				if(msg.error != null) {
					html = msg.error;
				} else if(msg.securityError != null) {
					html = msg.securityError;
				} else if(msg.success != null) {
					html = msg.success;
				} else {
					html = msg;
				}
				
				
				$('#article_wrap').html(html);
				$('#article_wrap').animate({
					opacity : 'toggle',
					height : 'toggle'
				}, 500);
			}
			
			
			this.completeHandler = function() {
				console.log('completeHandler');
			}
			
			
			this.onRegionClickHandler = function(event, code){
				// TODO: check if we are sending right now, and return false
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
