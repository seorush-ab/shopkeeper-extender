<?php

//Custom Menu
include_once( dirname( __FILE__ ) . '/class/custom-menu.php' );
include_once( dirname( __FILE__ ) . '/class/edit_custom_walker.php' );
include_once( dirname( __FILE__ ) . '/class/custom_walker.php' );

add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'sk-extender-custom-menu-styles', plugins_url( 'assets/css/custom-menu.css', __FILE__ ), NULL );
    wp_enqueue_script( 'sk-extender-custom-menu-scripts', plugins_url( 'assets/js/custom-menu.js', __FILE__ ), array('jquery'), false, false );
    wp_enqueue_script( 'tweenmax', plugins_url( 'assets/js/TweenMax.min.js', __FILE__ ), array('jquery'), false, true );
});

?>
