<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//	Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_sk_portfolio_editor_assets' );
if ( ! function_exists( 'gbt_18_sk_portfolio_editor_assets' ) ) {
	function gbt_18_sk_portfolio_editor_assets() {
		
		wp_enqueue_script(
			'gbt_18_sk_portfolio_script',
			plugins_url( 'block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
		);

		wp_enqueue_style(
			'gbt_18_sk_portfolio_editor_styles',
			plugins_url( 'assets/css/editor.css', __FILE__ ),
			array( 'wp-blocks' )
		);
	}
}

//==============================================================================
//	Enqueue Frontend Assets
//==============================================================================
add_action( 'enqueue_block_assets', 'gbt_18_sk_portfolio_assets' );
if ( ! function_exists( 'gbt_18_sk_portfolio_assets' ) ) {
	function gbt_18_sk_portfolio_assets() {
		
		wp_enqueue_style(
			'gbt_18_sk_portfolio_styles',
			plugins_url( 'assets/css/style.css', __FILE__ ),
			array()
		);
	}
}

//==============================================================================
//	Portfolio Helpers
//==============================================================================
add_action('rest_api_init', 'gbt_18_sk_register_rest_portfolio_images' );
function gbt_18_sk_register_rest_portfolio_images(){
    register_rest_field( array('portfolio'),
        'fimg_url',
        array(
            'get_callback'    => 'gbt_18_sk_get_rest_portfolio_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( array('portfolio'),
        'categories',
        array(
            'get_callback'    => 'gbt_18_sk_get_rest_portfolio_categories',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( array('portfolio'),
        'color_meta_box',
        array(
            'get_callback'    => 'gbt_18_sk_get_rest_portfolio_color_meta_box',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function gbt_18_sk_get_rest_portfolio_featured_image( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
        $img = wp_get_attachment_image_src( $object['featured_media'], 'large' );
        return $img[0];
    }
    return false;
}

function gbt_18_sk_get_rest_portfolio_categories( $object, $field_name, $request ) {
	$categories = [];
	$args = array(
		'orderby'	=> 'name',
		'order'		=> 'ASC'
	);
    $terms = get_the_terms( $object['id'], 'portfolio_categories', $args );
	if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
		foreach($terms as $term) {
			$categories[] = [ 'slug' => $term->slug, 'name' => $term->name ];
		}
	}

    return $categories;
}

function gbt_18_sk_get_rest_portfolio_color_meta_box( $object, $field_name, $request ) {
    $color = get_post_meta( $object['id'], 'portfolio_color_meta_box' )[0];
    if( $color == '' ) $color = '#000';

    return $color;
}