<?php
/**
 * Banner Block.
 *
 * @package shopkeeper-extender
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'enqueue_block_editor_assets', 'gbt_18_sk_banner_editor_assets' );
if ( ! function_exists( 'gbt_18_sk_banner_editor_assets' ) ) {
	/**
	 * Enqueue Editor Assets.
	 */
	function gbt_18_sk_banner_editor_assets() {

		wp_register_script(
			'gbt_18_sk_banner_script',
			plugins_url( 'block' . SK_EXT_ENQUEUE_SUFFIX . '.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element' ),
			SK_EXT_VERSION,
			true
		);

		add_action(
			'init',
			function() {
				wp_set_script_translations( 'gbt_18_sk_banner_script', 'shopkeeper-extender', plugin_dir_path( __FILE__ ) . 'languages' );
			}
		);

		wp_register_style(
			'gbt_18_sk_banner_editor_styles',
			plugins_url( 'assets/css/style.css', __FILE__ ),
			array( 'wp-edit-blocks' ),
			SK_EXT_VERSION
		);
	}
}

add_action( 'enqueue_block_assets', 'gbt_18_sk_banner_assets' );
if ( ! function_exists( 'gbt_18_sk_banner_assets' ) ) {
	/**
	 * Enqueue Frontend Assets.
	 */
	function gbt_18_sk_banner_assets() {

		wp_register_style(
			'gbt_18_sk_banner_styles',
			plugins_url( 'assets/css/style.css', __FILE__ ),
			array(),
			SK_EXT_VERSION
		);
	}
}

/**
 * Register Block.
 */
if ( function_exists( 'register_block_type' ) ) {
	register_block_type(
		'getbowtied/sk-banner',
		array(
			'style'         => 'gbt_18_sk_banner_styles',
			'editor_style'  => 'gbt_18_sk_banner_editor_styles',
			'editor_script' => 'gbt_18_sk_banner_script',
		)
	);
}
