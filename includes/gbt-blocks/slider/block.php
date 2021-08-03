<?php
/**
 * Slider Block.
 *
 * @package shopkeeper-extender
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'enqueue_block_editor_assets', 'gbt_18_sk_slider_editor_assets' );
if ( ! function_exists( 'gbt_18_sk_slider_editor_assets' ) ) {
	/**
	 * Enqueue Editor Assets.
	 */
	function gbt_18_sk_slider_editor_assets() {

		wp_enqueue_script(
			'gbt_18_sk_slide_script',
			plugins_url( 'blocks/slide' . SK_EXT_ENQUEUE_SUFFIX . '.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			SK_EXT_VERSION,
			true
		);

		wp_enqueue_script(
			'gbt_18_sk_slider_script',
			plugins_url( 'blocks/slider' . SK_EXT_ENQUEUE_SUFFIX . '.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			SK_EXT_VERSION,
			true
		);

		add_action(
			'init',
			function() {
				wp_set_script_translations( 'gbt_18_sk_slide_script', 'shopkeeper-extender', plugin_dir_path( __FILE__ ) . 'languages' );
				wp_set_script_translations( 'gbt_18_sk_slider_script', 'shopkeeper-extender', plugin_dir_path( __FILE__ ) . 'languages' );
			}
		);

		wp_enqueue_style(
			'gbt_18_sk_slider_editor_styles',
			plugins_url( 'assets/css/backend/editor.css', __FILE__ ),
			array( 'wp-edit-blocks' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/backend/editor.css' )
		);
	}
}

add_action( 'enqueue_block_assets', 'gbt_18_sk_slider_assets' );
if ( ! function_exists( 'gbt_18_sk_slider_assets' ) ) {
	/**
	 * Enqueue Frontend Assets.
	 */
	function gbt_18_sk_slider_assets() {

		wp_enqueue_style(
			'gbt_18_sk_slider_styles',
			plugins_url( 'assets/css/frontend/style.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/frontend/style.css' )
		);

		if ( function_exists( 'getbowtied_vendor_scripts' ) ) {
			getbowtied_vendor_scripts();
		}

		wp_enqueue_style( 'swiper' );
		wp_enqueue_script( 'swiper' );

		wp_enqueue_script(
			'gbt_18_sk_slider_script',
			plugins_url( 'assets/js/slider' . SK_EXT_ENQUEUE_SUFFIX . '.js', __FILE__ ),
			array( 'jquery' ),
			SK_EXT_VERSION,
			true
		);
	}
}
