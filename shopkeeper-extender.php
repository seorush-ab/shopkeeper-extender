<?php

/**
 * Plugin Name:       		Shopkeeper Extender
 * Plugin URI:        		https://shopkeeper.wp-theme.design/
 * Description:       		Extends the functionality of Shopkeeper with Gutenberg elements.
 * Version:           		1.3.5
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

// Enqueue vendor
add_action( 'wp_enqueue_scripts', 'getbowtied_vendor_scripts', 99 );
function getbowtied_vendor_scripts() {

	$theme = wp_get_theme();
	if ( $theme->template != 'shopkeeper') {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_style(
			'gbt_18_sk_swiper_style',
			plugins_url( 'includes/vendor/swiper/css/swiper'.$suffix.'.css', __FILE__ ),
			array(),
			filemtime(plugin_dir_path(__FILE__) . 'includes/vendor/swiper/css/swiper'.$suffix.'.css')
		);
		wp_register_script(
			'gbt_18_sk_swiper_script',
			plugins_url( 'includes/vendor/swiper/js/swiper'.$suffix.'.js', __FILE__ ),
			array()
		);
	}
}

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

// Customizer
include_once( 'includes/customizer/class/class-control-toggle.php' );

// Shortcodes
include_once( 'includes/shortcodes/wp/posts-slider.php' );
include_once( 'includes/shortcodes/wp/banner.php' );
include_once( 'includes/shortcodes/wp/slider.php' );

if ( is_plugin_active( 'woocommerce/woocommerce.php') ) {
	include_once( 'includes/shortcodes/wc/categories-grid.php' );
}

add_action( 'wp_enqueue_scripts', 'getbowtied_sk_shortcodes_styles', 99 );
function getbowtied_sk_shortcodes_styles() {
	wp_register_style('shopkeeper-posts-slider-shortcode-styles', plugins_url( 'includes/shortcodes/assets/css/posts-slider.css', __FILE__ ), NULL );
	wp_register_style('shopkeeper-banner-shortcode-styles', plugins_url( 'includes/shortcodes/assets/css/banner.css', __FILE__ ), NULL );
	wp_register_style('shopkeeper-slider-shortcode-styles', plugins_url( 'includes/shortcodes/assets/css/slider.css', __FILE__ ), NULL );
}

add_action( 'wp_enqueue_scripts', 'getbowtied_sk_shortcodes_scripts', 99 );
function getbowtied_sk_shortcodes_scripts() {
	wp_register_script('shopkeeper-posts-slider-shortcode-script', plugins_url( 'includes/shortcodes/assets/js/posts-slider.js', __FILE__ ), array('jquery') );
	wp_register_script('shopkeeper-slider-shortcode-script', plugins_url( 'includes/shortcodes/assets/js/slider.js', __FILE__ ), array('jquery') );
}

// Add Shortcodes to WP Bakery
if ( defined(  'WPB_VC_VERSION' ) ) {
	add_action( 'init', 'getbowtied_sk_wb_shortcodes' );
	function getbowtied_sk_wb_shortcodes() {
		include_once( 'includes/shortcodes/wb/posts-slider.php' );
		include_once( 'includes/shortcodes/wb/banner.php' );
		include_once( 'includes/shortcodes/wb/slider.php' );

		if ( is_plugin_active( 'woocommerce/woocommerce.php') ) {
			include_once( 'includes/shortcodes/wb/categories-grid.php' );
		}
	}
}

// Social Media
include_once( 'includes/social-media/class-social-media.php' );
include_once( 'includes/widgets/social-media.php' );

// Addons
$theme = wp_get_theme();
if ( $theme->template == 'shopkeeper' && is_plugin_active( 'woocommerce/woocommerce.php') ) { 
	include_once( 'includes/addons/class-wc-category-header-image.php' );
}