<?php

//==============================================================================
//	Main Editor Styles
//==============================================================================
wp_enqueue_style(
	'getbowtied-sk-blocks-editor-styles',
	plugins_url( 'assets/css/editor.css', __FILE__ ),
	array( 'wp-edit-blocks' )
);

//==============================================================================
//	Main JS
//==============================================================================
add_action( 'admin_init', 'getbowtied_sk_blocks_scripts' );
if ( ! function_exists( 'getbowtied_sk_blocks_scripts' ) ) {
	function getbowtied_sk_blocks_scripts() {

		wp_enqueue_script(
			'getbowtied-sk-blocks-editor-scripts',
			plugins_url( 'assets/js/main.js', __FILE__ ),
			array( 'wp-blocks', 'jquery' )
		);

	}
}

if( is_plugin_active( 'woocommerce/woocommerce.php') ) {
	include_once 'categories_grid/block.php';
}

include_once 'posts_grid/block.php';
include_once 'banner/block.php';
include_once 'portfolio/block.php';
include_once 'social_media_profiles/block.php';
include_once 'slider/block.php';