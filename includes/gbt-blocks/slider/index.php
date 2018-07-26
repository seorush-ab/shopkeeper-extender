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

		wp_enqueue_script(
			'getbowtied-slider-settings',
			plugins_url( 'editor.js', __FILE__ ),
			array( 'jquery' )
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
		'title_font' 		=> array(
			'type'			=> 'string',
			'default'		=> 'primary_font',
		),
		'title_size' 		=> array(
			'type'			=> 'integer',
			'default'		=> '73',
		),
		'description_font' 	=> array(
			'type'			=> 'string',
			'default'		=> 'secondary_font',
		),
		'description_size' 	=> array(
			'type'			=> 'integer',
			'default'		=> '16',
		),
		'description'		=> array(
			'type'			=> 'string',
			'default'		=> 'Slide Description',
		),
		'text_color'		=> array(
			'type'			=> 'string',
			'default'		=> '#fff',
		),
		'button_text'  		=> array(
			'type'    		=> 'string',
			'default' 		=> 'Button Text',
		),
		'button_url'		=> array(
			'type'	  		=> 'string',
			'default' 		=> '',
		),
		'button_text_color' => array(
			'type'	  		=> 'string',
			'default' 		=> '#fff',
		),
		'button_bg_color'   => array(
			'type'	  		=> 'string',
			'default' 		=> '#000',
		),
		'bg_color'			=> array(
			'type'			=> 'string',
			'default'		=> '#24282e',
		),
		'alignment'			=> array(
			'type'			=> 'string',
			'default'		=> 'center'
		),
		'button_toggle'  	=> array(
			'type'			=> 'boolean',
			'default'		=> true
		)
	),

	'render_callback' => 'getbowtied_render_slide',
) );

function getbowtied_render_slide( $attributes ) {
	extract(shortcode_atts(array(
		'imgURL'					=> '',
       	'imgID' 					=> null,
	    'imgAlt'					=> '',
		'title' 					=> 'Slide Title',
		'description' 				=> 'Slide Description',
		'title_font'				=> 'primary_font',
		'title_size'				=> '73',
		'description_font'			=> 'secondary_font',
		'description_size'			=> '16',
		'text_color'				=> '#000',
		'button_text' 				=> 'Button Text',
		'button_url'				=> '',
		'button_text_color'			=> '#fff',
		'button_bg_color'			=> '#000',
		'bg_color'					=> '#fff',
		'alignment'					=> 'center',
		'button_toggle'				=> true
	), $attributes));

	switch ($alignment)
	{
		case 'left':
			$class = 'left-align';
			break;
		case 'right':
			$class = 'right-align';
			break;
		case 'center':
			$class = 'center-align';
	}

	if (!empty($title))
	{
		$title = '<h1 class="slide-title '.$title_font.'" style="font-size:'.$title_size.'px;color:'.$text_color.';">'.$title.'</h1>';
	} else {
		$title = "";
	}

	if (!empty($description))
	{
		$description = '<p class="slide-description '.$description_font.'" style="font-size:'.$description_size.'px;color:'.$text_color.';">'.$description.'</p>';
	} else {
		$description = "";
	}

	if ($button_toggle && !empty($button_text))
	{
		$button = '<a class="slide-button" style="background-color:'.$button_bg_color.'; color:'.$button_text_color.';" href="'.$button_url.'">'.$button_text.'</a>';
	} else {
		$button = "";
	}

	if (!$button_toggle && !empty($button_url))
	{
		$slide_link = '<a href="'.$button_url.'" class="fullslidelink"></a>';
	}
	else 
	{
		$slide_link = '';
	}

	$getbowtied_image_slide = '
		
		<div class="swiper-slide '.$class.'" 
		style=	"background: '.$bg_color.' url('.$imgURL.') center center no-repeat ;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
				color: '.$text_color.'">
			'.$slide_link.'
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