<?php
/**
 * Custom Menu.
 *
 * @package  shopkeeper-extender
 */

require_once dirname( __FILE__ ) . '/class/class-sk-ext-navwalker-image.php';
require_once dirname( __FILE__ ) . '/class/class-sk-ext-navwalker-image-output.php';

add_action(
	'wp_enqueue_scripts',
	function() {
		wp_enqueue_style( 'sk-extender-custom-menu-styles', plugins_url( 'assets/css/custom-menu.css', __FILE__ ), null );
		wp_enqueue_script( 'sk-extender-custom-menu-scripts', plugins_url( 'assets/js/custom-menu.js', __FILE__ ), array( 'jquery' ), false, false );
		wp_enqueue_script( 'TweenMax', plugins_url( 'assets/js/TweenMax.min.js', __FILE__ ), array( 'jquery' ), false, true );
	}
);
