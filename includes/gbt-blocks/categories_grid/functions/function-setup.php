<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//	Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_sk_categories_grid_editor_assets' );
if ( ! function_exists( 'gbt_18_sk_categories_grid_editor_assets' ) ) {
	function gbt_18_sk_categories_grid_editor_assets() {

		wp_register_script(
			'gbt_18_sk_categories_grid_script',
			plugins_url( 'block'.SK_EXT_ENQUEUE_SUFFIX.'.js', dirname(__FILE__) ),
			array( 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element', 'jquery' )
		);

		wp_localize_script( 'gbt_18_sk_categories_grid_script', 'getbowtied_pbw',
	        array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'woo_placeholder_image'	=>	function_exists('wc_placeholder_img_src')? wc_placeholder_img_src() : '' ));

		add_action( 'init', function() {
			wp_set_script_translations( 'gbt_18_sk_categories_grid_script', 'shopkeeper-extender', plugin_dir_path( __FILE__ ) . 'languages' );
		});

		wp_register_style(
			'gbt_18_sk_categories_grid_editor_styles',
			plugins_url( 'assets/css/styles.css', dirname(__FILE__) ),
			array( 'wp-edit-blocks' ),
			filemtime(plugin_dir_path(__FILE__) . '../assets/css/styles.css')
		);
	}
}

//==============================================================================
//	Enqueue Frontend Assets
//==============================================================================
add_action( 'enqueue_block_assets', 'gbt_18_sk_categories_grid_assets' );
if ( ! function_exists( 'gbt_18_sk_categories_grid_assets' ) ) {
	function gbt_18_sk_categories_grid_assets() {

		$theme = wp_get_theme();
		if( !class_exists('Shopkeeper') ) {
			wp_enqueue_style(
				'gbt_18_sk_categories_grid_styles',
				plugins_url( 'assets/css/styles.css', dirname(__FILE__) ),
				array(),
				filemtime(plugin_dir_path(__FILE__) . '../assets/css/styles.css')
			);
		}
	}
}

//==============================================================================
//	Register Block
//==============================================================================
if ( function_exists( 'register_block_type' ) ) {
	register_block_type( 'getbowtied/sk-categories-grid', array(
		'editor_style'  	=> 'gbt_18_sk_categories_grid_editor_styles',
		'editor_script'		=> 'gbt_18_sk_categories_grid_script',
		'attributes'      	=> array(
			'categoryIDs'					=> array(
				'type'						=> 'string',
				'default'					=> '',
			),
			'queryCategories' 				=> array(
                'type'                      => 'string',
                'default'                   => '',
            ),
			'queryCategoriesLast' 			=> array(
                'type'                      => 'string',
                'default'                   => '',
            ),
			'queryDisplayType'				=> array(
				'type'						=> 'string',
				'default'					=> 'all_categories',
			),
			'isLoading'  					=> array(
				'type'    					=> 'boolean',
				'default' 					=> false,
			),
			'querySearchString'				=> array(
				'type'						=> 'string',
				'default'					=> '',
			),
			'querySearchNoResults'			=> array(
				'type'						=> 'boolean',
				'default'					=> false,
			),
			'parentOnly'					=> array(
				'type'						=> 'boolean',
				'default'					=> false,
			),
			'hideEmpty'  					=> array(
				'type'    					=> 'boolean',
				'default' 					=> false,
			),
			'orderby' 						=> array(
				'type'						=> 'string',
				'default'					=> 'menu_order',
			),
			'productCount'  				=> array(
				'type'    					=> 'boolean',
				'default' 					=> true,
			),
			'limit'							=> array(
				'type'						=> 'integer',
				'default'					=> 8,
			),
			'columns'						=> array(
				'type'						=> 'number',
				'default'					=> '3'
			),
			'align'							=> array(
				'type'						=> 'string',
				'default'					=> 'center',
			),
		),

		'render_callback' => 'gbt_18_sk_render_frontend_categories_grid',
	) );
}
