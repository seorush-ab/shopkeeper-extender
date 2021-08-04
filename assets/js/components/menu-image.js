jQuery(function($) {

	"use strict";

	var menuItems = $('.main-navigation ul li.with-hover-image');

	menuItems.each( function() {
		var menuItem = $(this);
		var image = $(this).find('.menu-item-hover-image');
		if( image.length ) {

			menuItem.on("mouseenter", function(e) {
				image.css( {
					'opacity': '1',
					'visibility': 'visible',
				} );
			});

			menuItem.on("mouseleave", function(e) {
				image.css( {
					'opacity': '0',
					'visibility': 'hidden'
				} );
			});
		}
	});
});
