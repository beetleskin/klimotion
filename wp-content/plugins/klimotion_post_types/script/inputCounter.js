/**
 * @author Stefan Kaiser
 */

(function( $ ) {
	$.fn.inputCounter = function( options ) {
		
		// settings
		var defaults = {
			css_class: 'tfcounter',
		};
		var opts = $.extend(defaults, options);
		
		
		
		return this.each( function() {
			var $this = $(this);
			$this.maxChars = $this.attr('maxlength');
			var html = $('<div ' + 'class="' + opts.css_class + '"><span>0</span>/' + $this.maxChars + '</div>');
			$this.before(html);
			$this.counterSpan = $('span', $this.prev());
			
			$this.bind('keydown', observeInput);
			$this.bind('keyup', observeInput);
			
			
			function observeInput() {
				$this.counterSpan.html($this.val().length);
			}
		});
	};
})( jQuery );
