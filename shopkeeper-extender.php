<?php

/**
 * Plugin Name:       		Shopkeeper Extender
 * Plugin URI:        		https://github.com/getbowtied/shopkeeper-extender
 * Description:       		Extends the functionality of Shopkeeper with Gutenberg elements.
 * Version:           		1.3.2
 * Author:            		GetBowtied
 * Author URI:				https://getbowtied.com
 * Text Domain:				shopkeeper-extender
 * Domain Path:				/languages/
 * Requires at least: 		5.0
 * Tested up to: 			5.0.3
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

global $theme;
$theme = wp_get_theme();

// Plugin Updater
require 'core/updater/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/getbowtied/shopkeeper-extender/master/core/updater/assets/plugin.json',
	__FILE__,
	'shopkeeper-extender'
);

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
