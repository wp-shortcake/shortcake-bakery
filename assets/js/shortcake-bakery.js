(function( $ ) {

	var debounce = function(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) {
					func.apply(context, args);
				}
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if ( callNow ) {
				func.apply(context, args);
			}
		};
	};

	var responsiveElements = function() {
		// Don't use cached elements because YouTube injects new iframes
		$('.shortcake-bakery-responsive').each(function(){
			var el = $(this),
				parentWidth = el.parent().width();

			// Inside an iframe
			if ( window.self !== window.top ) {
				parentWidth = parent.innerWidth;
			}

			var trueHeight = 360,
				trueWidth = 640;

			if ( el.data('true-height') ) {
				trueHeight = el.data('true-height').replace( 'px', '' );
			} else if ( el.attr('height') ) {
				trueHeight = el.attr('height').replace( 'px', '' );
			}

			if ( el.data('true-width') ) {
				trueWidth = el.data('true-width').replace( 'px', '' );
			} else if ( el.attr('width') ) {
				trueWidth = el.attr('width').replace( 'px', '' );
			}

			var newHeight = ( parentWidth / trueWidth ) * trueHeight;
			$(this).css('height', newHeight + 'px' ).css('width', parentWidth + 'px');
			$(this).trigger('shortcake-bakery-responsive-resize');
		});
	}

	$(document).ready(function(){
		if ( $('.shortcake-bakery-responsive').length ) {
			responsiveElements();
			$(window).on( 'resize', debounce( responsiveElements, 100 ));
		}
	});

}( jQuery ) );
