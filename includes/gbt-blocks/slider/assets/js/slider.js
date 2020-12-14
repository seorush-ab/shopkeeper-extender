jQuery(function($) {

	"use strict";

	$('.gbt_18_sk_slider').each(function() {

		var autoplay = $(this).attr('data-autoplay');
		if ($.isNumeric(autoplay)) {
			autoplay = autoplay * 1000;
		} else {
			autoplay = 10000;
		}

		var mySwiper = new Swiper( '.gbt_18_sk_slider', {

			// Optional parameters
		    direction: 'horizontal',
		    loop: true,
		    grabCursor: true,
			preventClicks: true,
			preventClicksPropagation: true,
			autoplay: {
			    delay: autoplay,
		  	},
			speed: 600,
			effect: 'slide',
			parallax: true,
		    // Pagination
		    pagination: {
			    el: '.gbt_18_sk_slider .gbt_18_sk_slider_pagination',
			    type: 'bullets',
			    clickable: true
			},
		    // Navigation
		    navigation: {
			    nextEl: '.gbt_18_sk_slider .swiper-button-next',
			    prevEl: '.gbt_18_sk_slider .swiper-button-prev',
			},
		});

	});
});
