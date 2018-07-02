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

function gbt_blocks() {

	require_once 'blocks/categories_grid/index.php';
	require_once 'blocks/posts_slider/index.php';
	require_once 'blocks/banner/index.php';
	require_once 'blocks/portfolio/index.php';
	require_once 'blocks/social-media-profiles/index.php';
	require_once 'blocks/slider/index.php';
}
add_action( 'init', 'gbt_blocks' );
