<?php

//==============================================================================
//	Main Editor Styles
//==============================================================================
wp_enqueue_style(
	'getbowtied-sk-product-blocks-editor-styles',
	plugins_url( 'assets/css/editor.css', __FILE__ ),
	array( 'wp-edit-blocks' )
);

//==============================================================================
//	Main JS
//==============================================================================
add_action( 'admin_init', 'getbowtied_sk_product_blocks_scripts' );
if ( ! function_exists( 'getbowtied_sk_product_blocks_scripts' ) ) {
	function getbowtied_sk_product_blocks_scripts() {

		wp_enqueue_script(
			'getbowtied-sk-product-blocks-editor-scripts',
			plugins_url( 'assets/js/main.js', __FILE__ ),
			array( 'wp-blocks', 'jquery' )
		);

	}
}

require_once 'categories_grid/block.php';
require_once 'posts_grid/block.php';
require_once 'banner/block.php';
require_once 'portfolio/block.php';
require_once 'social_media_profiles/block.php';
require_once 'slider/block.php';