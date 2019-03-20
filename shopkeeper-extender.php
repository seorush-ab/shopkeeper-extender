<?php

/**
 * Plugin Name:       		Shopkeeper Extender
 * Plugin URI:        		https://shopkeeper.wp-theme.design/
 * Description:       		Extends the functionality of Shopkeeper with Gutenberg elements.
 * Version:           		1.3.4
 * Author:            		GetBowtied
 * Author URI:				https://getbowtied.com
 * Text Domain:				shopkeeper-extender
 * Domain Path:				/languages/
 * Requires at least: 		5.0
 * Tested up to: 			5.1
 *
 * @package  Shopkeeper Extender
 * @author   GetBowtied
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// Plugin Updater
require 'core/updater/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/getbowtied/shopkeeper-extender/master/core/updater/assets/plugin.json',
	__FILE__,
	'shopkeeper-extender'
);

// Gutenberg Blocks
add_action( 'init', 'gbt_sk_gutenberg_blocks' );
if(!function_exists('gbt_sk_gutenberg_blocks')) {
	function gbt_sk_gutenberg_blocks() {

		if( is_plugin_active( 'gutenberg/gutenberg.php' ) || is_wp_version('>=', '5.0') ) {
			include_once 'includes/gbt-blocks/index.php';
		} else {
			add_action( 'admin_notices', 'theme_warning' );
		}
	}
}

if( !function_exists('theme_warning') ) {
	function theme_warning() {

		?>

		<div class="error">
			<p>Shopkeeper Extender plugin couldn't find the Block Editor (Gutenberg) on this site. It requires WordPress 5+ or Gutenberg installed as a plugin.</p>
		</div>

		<?php
	}
}

if( !function_exists('is_wp_version') ) {
	function is_wp_version( $operator, $version ) {

		global $wp_version;

		return version_compare( $wp_version, $version, $operator );
	}
}

// Shortcodes
$theme = wp_get_theme();
if ( $theme->template == 'shopkeeper') {
	include_once( 'includes/shortcodes/wp/posts-slider.php' );

	add_action( 'wp_enqueue_scripts', 'getbowtied_sk_shortcodes_styles', 99 );
	function getbowtied_sk_shortcodes_styles() {
		wp_enqueue_style('shopkeeper-posts-slider-shortcode-styles', plugins_url( 'includes/shortcodes/assets/css/posts-slider.css', __FILE__ ), NULL );
	}

	// Add Shortcodes to WP Bakery
	if ( defined(  'WPB_VC_VERSION' ) ) {
		add_action( 'init', 'getbowtied_sk_wb_shortcodes' );
		function getbowtied_sk_wb_shortcodes() {
			include_once( 'includes/shortcodes/wb/posts-slider.php' );
		}
	}
}