<?php
/**
 * Banner WPBakery Element.
 *
 * @package  shopkeeper-extender
 */

vc_map(
	array(
		'name'        => 'Banner',
		'category'    => 'Content',
		'description' => 'Place Banner',
		'base'        => 'banner',
		'class'       => '',
		'icon'        => 'banner',


		'params'      => array(

			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Title',
				'param_name'  => 'title',
			),

			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Subtitle',
				'param_name'  => 'subtitle',
				'admin_label' => false,
			),

			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'URL',
				'param_name'  => 'link_url',
			),

			array(
				'type'        => 'checkbox',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Open link in new tab?',
				'param_name'  => 'new_tab',
				'value'       => array(
					'Yes' => 'true',
				),
			),

			array(
				'type'        => 'colorpicker',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Title Color',
				'param_name'  => 'title_color',
			),

			array(
				'type'        => 'colorpicker',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Subtitle Color',
				'param_name'  => 'subtitle_color',
			),

			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Inner Stroke Thickness',
				'param_name'  => 'inner_stroke',
			),

			array(
				'type'        => 'colorpicker',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Inner Stroke Color',
				'param_name'  => 'inner_stroke_color',
			),

			array(
				'type'        => 'colorpicker',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Background Color',
				'param_name'  => 'bg_color',
			),

			array(
				'type'        => 'attach_image',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Background Image',
				'param_name'  => 'bg_image',
			),

			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Height',
				'param_name'  => 'height',
			),

			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Bullet Text',
				'description' => '[Deprecated Option]',
				'param_name'  => 'bullet_text',
				'dependency'  => array(
					'element' => 'with_bullet',
					'value'   => array( 'yes' ),
				),
			),
		),
	)
);
