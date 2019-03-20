<?php

add_action( 'customize_register', 'sk_social_media_profiles' );

function sk_social_media_profiles( $wp_customize ) {

	global $social_media_profiles;

	$wp_customize->add_setting( 'sk_top_bar_social_icons', array(
		'type'		 => 'option',
		'capability' => 'manage_options',
		'transport'  => 'refresh',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Control(
			$wp_customize,
			'sk_top_bar_social_icons',
			array( 
				'type'			=> 'checkbox',
				'label'       	=> esc_attr__( 'Top Bar Social Icons', 'shopkeeper' ),
				'section'     	=> 'top_bar',
				'priority'    	=> 10,
				'default'		=> false,
			)
		)
	);

	$wp_customize->add_setting( 'sk_footer_social_icons', array(
		'type'		 => 'option',
		'capability' => 'manage_options',
		'transport'  => 'refresh',
	) );

	$wp_customize->add_control( 
		new WP_Customize_Control(
			$wp_customize,
			'sk_footer_social_icons',
			array( 
				'type'			=> 'checkbox',
				'label'       	=> esc_attr__( 'Social Networking Icons', 'shopkeeper' ),
				'section'     	=> 'footer',
				'priority'    	=> 10,
				'default'		=> false,
			)
		)
	);

	// Section
	$wp_customize->add_section( 'social_media', array(
 		'title'       => esc_attr__( 'Social Media', 'shopkeeper' ),
 		'priority'    => 10,
 	) );

	// Fields
	foreach($social_media_profiles as $social) :

		$wp_customize->add_setting( 'sk_' . $social['link'], array(
			'type'		 => 'option',
			'capability' => 'manage_options',
			'transport'  => 'refresh',
			'default' 	 => '',
		) );

		$wp_customize->add_control( 
			new WP_Customize_Control(
				$wp_customize,
				'sk_' . $social['link'],
				array( 
					'type'			=> 'text',
					'label'       	=> esc_attr__( $social['name'], 'shopkeeper' ),
					'section'     	=> 'social_media',
					'priority'    	=> 10,
				)
			)
		);

	endforeach;
}