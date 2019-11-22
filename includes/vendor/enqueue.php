<?php

$theme = wp_get_theme();
if ( $theme->template != 'shopkeeper') {

	add_action( 'wp_enqueue_scripts', 'getbowtied_vendor_scripts', 99 );
	function getbowtied_vendor_scripts() {

		wp_register_style(
			'swiper',
			plugins_url( 'swiper/css/swiper'.SK_EXT_ENQUEUE_SUFFIX.'.css', __FILE__ ),
			array(),
			filemtime(plugin_dir_path(__FILE__) . 'swiper/css/swiper'.SK_EXT_ENQUEUE_SUFFIX.'.css')
		);
		wp_register_script(
			'swiper',
			plugins_url( 'swiper/js/swiper'.SK_EXT_ENQUEUE_SUFFIX.'.js', __FILE__ ),
			array()
		);
	}
}
