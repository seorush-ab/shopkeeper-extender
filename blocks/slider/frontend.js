(function( $ ) {

    'use strict';

    $( document ).ready( function( $ ) {

		$('.wp-block-gbt-slider').each(function() {

			var wrapper = $(this).find('.swiper-wrapper');

			if( wrapper.children('div.swiper-slide').length == 0 ) {

				$(this).remove();

			}
		});

    }); 

} )( jQuery );