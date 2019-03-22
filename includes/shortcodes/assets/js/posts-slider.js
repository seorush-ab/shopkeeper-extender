jQuery(function($) {
	
	"use strict";

	$('.from-the-blog.swiper-container').each(function() {

		var myPostsSwiper = new Swiper($(this), {
			slidesPerView: 3,
			breakpoints: {
				640: {
					slidesPerView: 2,
				},

				480: {
					slidesPerView: 1,
				},
			}
		});
	});

});