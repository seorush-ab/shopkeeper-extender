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

// Helpers
include_once( 'includes/helpers/helpers.php' );

// Vendor
include_once( 'includes/vendor/enqueue.php' );

// Customizer
include_once( 'includes/customizer/class/class-control-toggle.php' );

// Shortcodes
include_once( 'includes/shortcodes/index.php' );

// Social Media
include_once( 'includes/social-media/class-social-media.php' );
include_once( 'includes/widgets/social-media.php' );

// Addons
$theme = wp_get_theme();
if ( $theme->template == 'shopkeeper' && is_plugin_active( 'woocommerce/woocommerce.php') ) { 
	include_once( 'includes/addons/class-wc-category-header-image.php' );
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