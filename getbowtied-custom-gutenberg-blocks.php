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

/**
 * Load getbowtied blocks.
 */

function gbt_gutenberg_blocks() {

	switch( get_current_theme() ) {

		case 'Shopkeeper':
			require_once 'shopkeeper/index.php';
			break;
		case 'Mr. Tailor':
			break;
		case 'The Retailer':
			break;
		case 'Merchandiser':
			break;
		default:
			add_action( 'admin_notices', 'theme_warning' );
			break;
	}
}
add_action( 'init', 'gbt_gutenberg_blocks' );

function theme_warning() {

	echo '<div class="message error woocommerce-admin-notice woocommerce-st-inactive woocommerce-not-configured">';
	echo '<p>' . esc_html( 'GetBowtied Custom Gutenberg Blocks is enabled but not effective. Please activate a GetBowtied theme in order to work.', 'gbt-blocks' ) . '</p>';
	echo '</div>';
}
