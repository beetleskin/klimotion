/**
 * @author Stefan Kaiser
 */

(function( $ ) {
	$.fn.adaptiveTableInput = function( options ) {
		
		// settings
		var defaults = {
			'trSelector' : '',
			'tplNames' : [],
			'maxRows' : 5,
			'addSelector' : 'a.addlink',
			'removeSelector' : 'a.removelink'
		};
		var opts = $.extend(defaults, options);
		
		
		
		// TODO: decent variable storage
		return this.each( function() {
			
			var $this = this;
			$this.templateRow = 0;
			$this.addLinkElement = $(opts.addSelector, $this);
		
			// gather inputs per row
			if(opts.tplNames.length == 0) {
				var tplNames = [];
				$('tr:first input', this).each(function() {
					name = $(this).attr('name');
					tplNames.push(name.substring(0, name.length - 1));
				});
				opts.tplNames = tplNames;
			}
			
			
			$this.noLinks = $('tr' + opts.trSelector, this).length;
			$this.templateRow = $('tr' + opts.trSelector, this).first().clone();
			$('input', $this.templateRow).val('');
			
			// events
			$(opts.removeSelector, $this).click(removeLink);
			$this.addLinkElement.click(addLink);
			
			
			
			
			function removeLink() {
				var row = $(this).closest('tr' + opts.trSelector);
				row.remove();
				$this.noLinks--;
				renameIDs();
				$this.addLinkElement.show();
				
				return this;
			}
				
				
				
			function addLink() {
				var lastPair = $('tr' + opts.trSelector, $this).last();
				if( $('input', lastPair).last().val() == '') {
					lastPair.fadeTo(400, 0.2).fadeTo(400, 1.0);
				} else {
					var newPair = $this.templateRow.clone();
					// for each input
					for(var i=0,j=opts.tplNames.length; i<j; i++){
						$('input[name*=' + opts.tplNames[i] + ']', newPair).attr('name', opts.tplNames[i] + $this.noLinks);
					};
				
					$('a.removelink', newPair).click(removeLink);
					$('tbody', $this).append(newPair);
					$this.noLinks++;
					
					
					if($this.noLinks >= opts.maxRows) {
						$this.addLinkElement.hide();
					}
				}
				
				return this;
			}
				
				
				
			function renameIDs() {
				// for each row
				$('tr' + opts.trSelector, $this).each(function(index, value) {
					// for each input
					for(var i=0,j=opts.tplNames.length; i<j; i++){
						$('input[name*=' + opts.tplNames[i] + ']', value).attr('name', opts.tplNames[i] + index);
					};
				});
				$this.noLinks = $('tr' + opts.trSelector, $this).length;
				
				return this;
			}
		});
	};
})( jQuery );
