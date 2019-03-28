<?php

/**
 * Plugin Name:       		Shopkeeper Extender
 * Plugin URI:        		https://shopkeeper.wp-theme.design/
 * Description:       		Extends the functionality of Shopkeeper with theme specific features.
 * Version:           		1.4.1
 * Author:            		GetBowtied
 * Author URI:				https://getbowtied.com
 * Text Domain:				shopkeeper-extender
 * Domain Path:				/languages/
 * Requires at least: 		5.0
 * Tested up to: 			5.1.1
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

$theme = wp_get_theme();
$parent_theme = $theme->parent();
if( ( $theme->template == 'shopkeeper' && ( $theme->version >= '2.8' || ( !empty($parent_theme) && $parent_theme->version >= '2.8' ) ) ) || $theme->template != 'shopkeeper' ) {

	// Customizer
	include_once( 'includes/customizer/class/class-control-toggle.php' );

	// Shortcodes
	include_once( 'includes/shortcodes/index.php' );

	// Social Media
	include_once( 'includes/social-media/class-social-media.php' );

	//Widgets
	include_once( 'includes/widgets/social-media.php' );

	// Addons
	if ( $theme->template == 'shopkeeper' && is_plugin_active( 'woocommerce/woocommerce.php') ) { 
		include_once( 'includes/addons/class-wc-category-header-image.php' );
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

include_once( 'includes/custom-code/class-custom-code.php' );
include_once( 'includes/social-sharing/class-social-sharing.php' );