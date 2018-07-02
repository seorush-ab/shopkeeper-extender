<?php
/**
 * getbowtied Slider
 *
 * @package   getbowtied
 * @author    GetBowtied
 * @license   @@pkg.license
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue the block's assets for the editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function getbowtied_slider_editor_assets() {
	// Scripts.
	wp_enqueue_script(
		'getbowtied-slider',
		plugins_url( 'slider.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'jquery' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'slider.js' )
	);

	wp_enqueue_script(
		'getbowtied-slide',
		plugins_url( 'slide.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'jquery' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'slide.js' )
	);

	// Styles.
	wp_enqueue_style(
		'getbowtied-slider',
		plugins_url( 'editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' )
	);
}
add_action( 'enqueue_block_editor_assets', 'getbowtied_slider_editor_assets' );

/**
 * Enqueue the block's assets for the frontend.
 *
 * @since 1.0.0
 */
function getbowtied_slider_frontend_assets() {
	// Styles.
	wp_enqueue_style(
		'getbowtied-slider-frontend',
		plugins_url( 'slider.css', __FILE__ ),
		array( 'wp-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'slider.css' )
	);
}
add_action( 'enqueue_block_assets', 'getbowtied_slider_frontend_assets' );

/**
 * Renders the `getbowtied/categories_grid` block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string Returns product categories grid.
 */
function getbowtied_render_slider( $attributes ) {

	extract(shortcode_atts(array(
		'full_height' 		  	   	=> 'no',
		'custom_desktop_height' 	=> '800px',
		'custom_mobile_height' 	  	=> '600px',
		'slide_numbers'		  		=> 'true',
		'slide_numbers_color' 		=> '#000'
	), $attributes));

	if ( $full_height == 'no' && ( !empty($custom_desktop_height) || !empty($custom_mobile_height) ) ) {
		$extra_class = '';
	} else {
		$extra_class = 'full_height';
	}

	if ($full_height == 'no' && !empty($custom_desktop_height)) {
		$desktop_height = 'height:'.$custom_desktop_height.';';
	} else {
		$desktop_height = '';
	}

	if ($full_height == 'no' && !empty($custom_mobile_height)) {
		$mobile_height = '@media all and (max-width: 768px){.shortcode_getbowtied_slider{ height:'.$custom_mobile_height.'!important;}}';
	} else {
		$mobile_height = '';
	}

	$getbowtied_slider = '
		
		<div class="shortcode_getbowtied_slider swiper-container '.$extra_class.'" style="'.$desktop_height.' width: 100%">
			<div class="swiper-wrapper">';
			
			//$getbowtied_slider .= render('getbowtied/slide', $atts );

			$getbowtied_slider = '</div>';

    if ($slide_numbers):
    	$getbowtied_slider .= '<div class="quickview-pagination shortcode-slider-pagination" style="color: ' . $slide_numbers_color . '"></div>';
    endif;

	$getbowtied_slider .=	'</div>';

	$getbowtied_slider .= '<style>'.$mobile_height.' .swiper-pagination-bullet-active:after{ background-color: '.$slide_numbers_color.' } </style>';
	
	return $getbowtied_slider;
}

register_block_type( 'getbowtied/slider', 'getbowtied/slide', array(
	'attributes'      => array(
		'full_height' 	=> array(
			'type'						=> 'string',
			'default'					=> 'no',
		),
		'custom_desktop_height'			=> array(
			'type'						=> 'string',
			'default'					=> '800px',
		),
		'custom_mobile_height'			=> array(
			'type'						=> 'string',
			'default'					=> '600px',
		),
		'slide_numbers'  					=> array(
			'type'    					=> 'string',
			'default' 					=> 'true',
		),
		'slide_numbers_color'		  	=> array(
			'type'	  					=> 'string',
			'default' 					=> '#000',
		),
	),

	'render_callback' => 'getbowtied_render_slider',
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
		$description = '<h2 class="slide-description" style="color:rgb('.$text_color.');">'.$description.'</h2>';
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