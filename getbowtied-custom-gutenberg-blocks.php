<?php
/**
 * Plugin Name: GetBowtied Custom Gutenberg Blocks
 * Plugin URI: http://getbowtied.com
 * Description: Gutenberg, a new editing experience for WordPress is in the works and this is a beta plugin to add a social sharing block within that upcoming editor.
 * Author: GetBowtied
 * Version: 1.0.0
 * Text Domain: getbowtied
 * Domain Path: languages
 * Requires at least: 4.7
 * Tested up to: 4.9.1
 *
 * @package   getbowtied
 * @author    GetBowtied
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Load getbowtied blocks.
 */

function gbt_gutenberg_blocks() {

	$theme = wp_get_theme();
	if ( $theme->template != 'shopkeeper') {
		return;
	}

	if( is_plugin_active( 'gutenberg/gutenberg.php' ) || is_wp_version('>=', '5.0') ) {
		require_once 'blocks/index.php';
	} else {
		add_action( 'admin_notices', 'theme_warning' );
	}
}
add_action( 'init', 'gbt_gutenberg_blocks' );

function theme_warning() {

	echo '<div class="message error woocommerce-admin-notice woocommerce-st-inactive woocommerce-not-configured">';
	echo '<p>' . esc_html( 'GetBowtied Custom Gutenberg Blocks is enabled but not effective. Please activate Gutenberg plugin in order to work.', 'gbt-blocks' ) . '</p>';
	echo '</div>';
}

function is_wp_version( $operator = '>', $version = '4.0' ) {

	global $wp_version;

	return version_compare( $wp_version, $version, $operator );
}
