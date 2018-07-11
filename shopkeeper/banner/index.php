<?php

// Banner

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'enqueue_block_editor_assets', 'getbowtied_banner_editor_assets' );

if ( ! function_exists( 'getbowtied_banner_editor_assets' ) ) {
	function getbowtied_banner_editor_assets() {

		wp_enqueue_script(
			'getbowtied-banner',
			plugins_url( 'block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element', 'jquery' )
		);

		wp_enqueue_style(
			'getbowtied-banner',
			plugins_url( 'css/editor.css', __FILE__ ),
			array( 'wp-edit-blocks' )
		);
	}
}

register_block_type( 'getbowtied/banner', array(
	'attributes'      	=> array(
		'title'							=> array(
			'type'						=> 'array',
			'default'					=> array('Banner Title'),
		),
		'subtitle'						=> array(
			'type'						=> 'array',
			'default'					=> array('Banner Subtitle'),
		),
		'imgURL'						=> array(
			'type'						=> 'string',
			'default'					=> '',
		),
		'url'							=> array(
			'type'						=> 'string',
			'default'					=> '#',
		),
		'blank'							=> array(
			'type'						=> 'boolean',
			'default'					=> true,
		),
		'titleColor'					=> array(
			'type'						=> 'string',
			'default'					=> '#fff',
		),
		'subtitleColor'					=> array(
			'type'						=> 'string',
			'default'					=> '#fff',
		),
		'innerStrokeThickness'			=> array(
			'type'						=> 'integer',
			'default'					=> 2,
		),
		'innerStrokeColor'				=> array(
			'type'						=> 'string',
			'default'					=> '#fff',
		),
		'bgColor'						=> array(
			'type'						=> 'string',
			'default'					=> '#f3f3f4',
		),
		'height'						=> array(
			'type'						=> 'integer',
			'default'					=> 300,
		),
		'size'							=> array(
			'type'						=> 'string',
			'default'					=> 'default',
		),
		'separatorPadding'				=> array(
			'type'						=> 'integer',
			'default'					=> 5,
		),
		'separatorColor'				=> array(
			'type'						=> 'string',
			'default'					=> '#fff',
		),
	),

	'render_callback' => 'getbowtied_render_banner',
) );

function getbowtied_render_banner( $attributes ) {

	extract( shortcode_atts( array(
		'title' 				=> array('Banner Title'),
		'subtitle' 				=> array('Banner Subtitle'),
		'url' 					=> '#',
		'blank' 				=> '',
		'titleColor' 			=> '#fff',
		'subtitleColor' 		=> '#fff',
		'innerStrokeThickness' 	=> '2px',
		'innerStrokeColor' 		=> '#fff',
		'bgColor' 				=> '#f3f3f4',
		'imgURL' 				=> '',
		'height' 				=> 'auto',
		'size'					=> 'default',
		'separatorPadding' 		=> '5px',
		'separatorColor' 		=> '#fff'
	), $attributes));

	$banner_with_img = '';
	
	if (!empty($imgURL)) {
		$banner_with_img = 'banner_with_img';
	}
	
	$content = do_shortcode($content);

	if ($blank == 'true')
	{
		$link_tab = 'onclick="window.open(\''.$url.'\', \'_blank\');"';
	}
	else 
	{
		$link_tab = 'onclick="location.href=\''.$url.'\';"';
	}
	
	$banner_simple_height = '
		<div class="shortcode_banner_simple_height '.$banner_with_img.' ' . $size .'" '.$link_tab.'>
			<div class="shortcode_banner_simple_height_inner">
				<div class="shortcode_banner_simple_height_bkg" style="background-color:'.$bgColor.'; background-image:url('.$imgURL.')"></div>
			
				<div class="shortcode_banner_simple_height_inside" style="height:'.$height.'px; border: '.$innerStrokeThickness.'px solid '.$innerStrokeColor.'">
					<div class="shortcode_banner_simple_height_content">
						<div><h3 style="color:'.$titleColor.' !important">'. $title[0] .'</h3></div>
						<div class="shortcode_banner_simple_height_sep" style="margin:'.$separatorPadding.'px auto; background-color:'.$separatorColor.';"></div>
						<div><h4 style="color:'.$subtitleColor.' !important">'. $subtitle[0] .'</h4></div>
					</div>
				</div>
			</div>';
	
	$banner_simple_height .= '</div>';
	
	return $banner_simple_height;
}