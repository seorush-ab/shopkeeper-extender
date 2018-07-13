<?php

// Slider

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'enqueue_block_editor_assets', 'getbowtied_slider_editor_assets' );

if ( ! function_exists( 'getbowtied_slider_editor_assets' ) ) {
	function getbowtied_slider_editor_assets() {

		wp_enqueue_script(
			'getbowtied-slide',
			plugins_url( 'slide.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element' )
		);
		
		wp_enqueue_script(
			'getbowtied-slider',
			plugins_url( 'slider.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element' )
		);

		wp_enqueue_style(
			'getbowtied-slider',
			plugins_url( 'css/editor.css', __FILE__ ),
			array( 'wp-edit-blocks' )
		);
	}
}

add_action( 'enqueue_block_assets', 'getbowtied_slider_assets' );

if ( ! function_exists( 'getbowtied_slider_assets' ) ) {
	function getbowtied_slider_assets() {
		
		wp_enqueue_style(
			'getbowtied-slider-css',
			plugins_url( 'css/style.css', __FILE__ ),
			array()
		);
	}
}

register_block_type( 'getbowtied/slide', array(
	'attributes'      => array(
		'imgURL' 			=> array(
            'type' 			=> 'string',
        ),
	    'imgID' 			=> array(
			'type'			=> 'number',
		),
	    'imgAlt'			=> array(
            'type'			=> 'string',
	    ),
		'title' 			=> array(
			'type'			=> 'string',
			'default'		=> 'Slide Title',
		),
		'description'		=> array(
			'type'			=> 'string',
			'default'		=> 'Slide Description',
		),
		'text_color'		=> array(
			'type'			=> 'string',
			'default'		=> '#000',
		),
		'button_text'  		=> array(
			'type'    		=> 'string',
			'default' 		=> '',
		),
		'button_url'		=> array(
			'type'	  		=> 'string',
			'default' 		=> '',
		),
		'bg_color'			=> array(
			'type'			=> 'string',
			'default'		=> '#fff',
		),
	),

	'render_callback' => 'getbowtied_render_slide',
) );

function getbowtied_render_slide( $attributes ) {
	extract(shortcode_atts(array(
		'imgURL'					=> '',
       	'imgID' 					=> null,
	    'imgAlt'					=> '',
		'title' 					=> '',
		'description' 				=> '',
		'text_color'				=> '#000',
		'button_text' 				=> '',
		'button_url'				=> '',
		'bg_color'					=> '#fff',
	), $attributes));

	if (!empty($title))
	{
		$title = '<p class="slide-title" style="color:'.$text_color.';">'.$title.'</p>';
	} else {
		$title = "";
	}

	if (is_numeric($bg_image)) 
	{
		$bg_image = wp_get_attachment_url($bg_image);
	} else {
		$bg_image = "";
	}

	if (!empty($description))
	{
		$description = '<h2 class="slide-description" style="color:'.$text_color.';">'.$description.'</h2>';
	} else {
		$description = "";
	}

	if (!empty($button_text))
	{
		$button = '<a class="slide-button" style="border-color:rgb('.$text_color.'); color:rgb('.$text_color.');" href="'.$button_url.'">'.$button_text.'</a>';
	} else {
		$button = "";
	}

	$getbowtied_image_slide = '
		
		<div class="swiper-slide" 
		style=	"background: '.$bg_color.' url('.$imgURL.') center center no-repeat ;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
				color: '.$text_color.'">
			<div class="slider-content" data-swiper-parallax="-1000">
				<div class="slider-content-wrapper">
					'.$title.'
					'.$description.'
					'.$button.'
				</div>
			</div>
		</div>';
	
	return $getbowtied_image_slide;
}