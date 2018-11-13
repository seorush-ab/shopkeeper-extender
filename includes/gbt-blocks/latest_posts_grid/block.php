<?php

// Posts Slider

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//	Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_sk_latest_posts_editor_assets' );
if ( ! function_exists( 'gbt_18_sk_latest_posts_editor_assets' ) ) {
	function gbt_18_sk_latest_posts_editor_assets() {
		
		wp_enqueue_script(
			'gbt_18_sk_latest_posts_script',
			plugins_url( 'block.js', __FILE__ ),
			array( 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
		);

		wp_enqueue_style(
			'gbt_18_sk_latest_posts_editor_styles',
			plugins_url( 'assets/css/editor.css', __FILE__ ),
			array( 'wp-blocks' )
		);
	}
}

//==============================================================================
//	Enqueue Frontend Assets
//==============================================================================
add_action( 'enqueue_block_assets', 'gbt_18_sk_latest_posts_assets' );
if ( ! function_exists( 'gbt_18_sk_latest_posts_assets' ) ) {
	function gbt_18_sk_latest_posts_assets() {
		
		wp_enqueue_style(
			'gbt_18_sk_latest_posts_styles',
			plugins_url( 'assets/css/style.css', __FILE__ ),
			array()
		);
	}
}

//==============================================================================
//	Post Featured Image Helper
//==============================================================================
add_action('rest_api_init', 'gbt_18_sk_register_rest_images' );
function gbt_18_sk_register_rest_images(){
    register_rest_field( array('post'),
        'fimg_url',
        array(
            'get_callback'    => 'gbt_18_sk_get_rest_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function gbt_18_sk_get_rest_featured_image( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
        $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
        return $img[0];
    }
    return false;
}