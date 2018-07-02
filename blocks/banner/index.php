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
			'type'						=> 'string',
			'default'					=> '',
		),
		'subtitle'						=> array(
			'type'						=> 'string',
			'default'					=> '',
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
			'default'					=> '#F0EFEF',
		),
		'height'						=> array(
			'type'						=> 'integer',
			'default'					=> 300,
		),
		'separatorPadding'				=> array(
			'type'						=> 'integer',
			'default'					=> 5,
		),
		'separatorColor'				=> array(
			'type'						=> 'string',
			'default'					=> '#fff',
		),
		'bullet'						=> array(
			'type'						=> 'boolean',
			'default'					=> true,
		),
		'bulletText'					=> array(
			'type'						=> 'string',
			'default'					=> '',
		),
		'bulletBgColor'					=> array(
			'type'						=> 'string',
			'default'					=> '#fff',
		),
		'bulletTextColor'				=> array(
			'type'						=> 'string',
			'default'					=> '#000',
		),
	),

	'render_callback' => 'getbowtied_render_banner',
) );

function getbowtied_render_banner( $attributes ) {

	extract( shortcode_atts( array(
		'title' 				=> '',
		'subtitle' 				=> '',
		'url' 					=> '#',
		'blank' 				=> '',
		'titleColor' 			=> '#fff',
		'subtitleColor' 		=> '#fff',
		'innerStrokeThickness' 	=> '2px',
		'innerStrokeColor' 		=> '#fff',
		'bgColor' 				=> '#000',
		'imgURL' 				=> '',
		'height' 				=> 'auto',
		'separatorPadding' 		=> '5px',
		'separatorColor' 		=> '#fff',
		'bullet' 				=> true,
		'bulletText' 			=> '',
		'bulletBgColor' 		=> '#fff',
		'bulletTextColor' 		=> '#000'
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
		<div class="shortcode_banner_simple_height '.$banner_with_img.'" '.$link_tab.'>
			<div class="shortcode_banner_simple_height_inner">
				<div class="shortcode_banner_simple_height_bkg" style="background-color:'.$bgColor.'; background-image:url('.$imgURL.')"></div>
			
				<div class="shortcode_banner_simple_height_inside" style="height:'.$height.'px; border: '.$innerStrokeThickness.'px solid '.$innerStrokeColor.'">
					<div class="shortcode_banner_simple_height_content">
						<div><h3 style="color:'.$titleColor.' !important">'.$title.'</h3></div>
						<div class="shortcode_banner_simple_height_sep" style="margin:'.$separatorPadding.'px auto; background-color:'.$separatorColor.';"></div>
						<div><h4 style="color:'.$subtitleColor.' !important">'.$subtitle.'</h4></div>
					</div>
				</div>
			</div>';
	
	if ( $bullet ) {
		$banner_simple_height .= '<div class="shortcode_banner_simple_height_bullet" style="background-color:'.$bulletBgColor.'; color:'.$bulletTextColor.'"><span>'.$bulletText.'</span></div>';
	}
	
	$banner_simple_height .= '</div>';
	
	return $banner_simple_height;
}