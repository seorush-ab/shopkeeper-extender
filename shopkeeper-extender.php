<?php

/**
 * Plugin Name:       		Shopkeeper Extender
 * Plugin URI:        		https://github.com/getbowtied/Hook-Me-Up
 * Description:       		Extends the functionality of Shopkeeper with Gutenberg elements.
 * Version:           		1.0
 * Author:            		GetBowtied
 * Author URI:        		https://getbowtied.com
 * Requires at least: 		4.9
 * Tested up to: 			4.9.7
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

/**
 * Load getbowtied blocks.
 */

function gbt_gutenberg_blocks() {

	$theme = wp_get_theme();
	if ( $theme->template != 'shopkeeper') {
		return;
	}

	if( is_plugin_active( 'gutenberg/gutenberg.php' ) || is_wp_version('>=', '5.0') ) {
		require_once 'includes/gbt-blocks/index.php';
	} else {
		add_action( 'admin_notices', 'theme_warning' );
	}
}
add_action( 'init', 'gbt_gutenberg_blocks' );

function theme_warning() {

	echo '<div class="message error woocommerce-admin-notice woocommerce-st-inactive woocommerce-not-configured">';
	echo '<p>GetBowtied Custom Gutenberg Blocks is enabled but not effective. Please activate Gutenberg plugin in order to work.</p>';
	echo '</div>';
}

function is_wp_version( $operator = '>', $version = '4.0' ) {

	global $wp_version;

	return version_compare( $wp_version, $version, $operator );
}
