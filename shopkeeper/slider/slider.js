( function( blocks, i18n, element, $ ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;

	var InspectorControls 	= wp.editor.InspectorControls;
	var InnerBlock 			= wp.editor.InnerBlocks;

	var SelectControl		= wp.components.SelectControl;
	var ToggleControl		= wp.components.ToggleControl;
	var Button 				= wp.components.Button;
	var RangeControl		= wp.components.RangeControl;
	var PanelBody			= wp.components.PanelBody;
	var PanelColor			= wp.components.PanelColor;
	var ColorPalette		= wp.components.ColorPalette;

	/* Register Block */
	registerBlockType( 'getbowtied/slider', {
		title: i18n.__( 'Slider' ),
		icon: 'slides',
		category: 'shopkeeper',
		supports: {
			align: [ 'center', 'wide', 'full' ],
		},
		attributes: {
			full_height: {
				type: 'string',
				default: 'custom'
			},
			custom_desktop_height: {
				type: 'number',
				default: '800',
			},
			custom_mobile_height: {
				type: 'number',
				default: '600'
			},
			slide_numbers: {
				type: 'boolean',
				default: true
			},
			slide_numbers_color: {
				type: 'string',
				default: '#000'
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;

			var colors = [
				{ name: 'red', 				color: '#d02e2e' },
				{ name: 'orange', 			color: '#f76803' },
				{ name: 'yellow', 			color: '#fbba00' },
				{ name: 'green', 			color: '#43d182' },
				{ name: 'blue', 			color: '#2594e3' },
				{ name: 'light-gray', 		color: '#eeeeee' },
				{ name: 'dark-gray', 		color: '#abb7c3' },
				{ name: 'black', 			color: '#000' 	 },
			];

			return [
				el(
					InspectorControls,
					{ 
						key: 'slider-inspector'
					},
					el( 
						PanelBody, 
						{ 
							key: 'slider-height-settings-panel',
							title: 'Slider Height',
							initialOpen: false,
							style:
							{
							    borderBottom: '1px solid #e2e4e7'
							}
						},
						el(
							SelectControl,
							{
								key: "full-height-option",
								options: [{value: 'custom', label: 'Custom Height'}, {value: 'full_height', label: 'Full Height'}],
								label: i18n.__( 'Height' ),
								value: attributes.full_height,
								onChange: function( newSelection ) {
									props.setAttributes( { full_height: newSelection } );
								},
							}
						),
						attributes.full_height == 'custom' &&
						el( 'div', { key: "custom-height-option", },
							el(
								RangeControl,
								{
									key: "slider-desktop-height",
									value: attributes.custom_desktop_height,
									allowReset: true,
									initialPosition: 800,
									min: 100,
									max: 1000,
									label: i18n.__( 'Custom Desktop Height' ),
									onChange: function( newNumber ) {
										props.setAttributes( { custom_desktop_height: newNumber } );
									},
								}
							),
							// el(
							// 	RangeControl,
							// 	{
							// 		key: "slider-mobile-height",
							// 		value: attributes.custom_mobile_height,
							// 		allowReset: true,
							// 		initialPosition: 600,
							// 		min: 100,
							// 		max: 1000,
							// 		label: i18n.__( 'Custom Mobile Height' ),
							// 		onChange: function( newNumber ) {
							// 			props.setAttributes( { custom_mobile_height: newNumber } );
							// 		},
							// 	}
							// ),
						),
					),
					el( 
						PanelBody, 
						{ 
							key: 'slider-numbers-settings-panel',
							title: 'Slider Numbers',
							initialOpen: false,
							style:
							{
							    borderBottom: '1px solid #e2e4e7'
							}
						},
						el(
							ToggleControl,
							{
								key: "slide-numbers-toggle",
	              				label: i18n.__( 'Slide Numbers' ),
	              				checked: attributes.slide_numbers,
	              				onChange: function() {
									props.setAttributes( { slide_numbers: ! attributes.slide_numbers } );
								},
							}
						),
						attributes.slide_numbers == true &&
						el(
							PanelColor,
							{
								key: 'slider-numbers-color-panel',
								title: i18n.__( 'Slide Numbers Color' ),
								colorValue: attributes.slide_numbers_color,
							},
							el(
								ColorPalette, 
								{
									key: 'slider-numbers-color-pallete',
									colors: colors,
									value: attributes.slide_numbers_color,
									onChange: function( newColor) {
										props.setAttributes( { slide_numbers_color: newColor } );
									},
								} 
							),
						),
					),
				),
				el( 
					'div',
					{ 
						key: 'wp-block-slider-title-wrapper',
						className: 'wp-block-slider-title-wrapper'
					},
					el(
						'h4',
						{
							key: 'wp-block-slider-title',
							className: 'wp-block-slider-title',
						},
						el(
							'span',
							{
								key: 'wp-block-slider-dashicon',
								className: 'dashicon dashicons-slides',
							},
						),
						i18n.__('Slider')
					),
				),
				el(
					InnerBlock,
					{
						key: 'inner-block',
						allowedBlocksNames: [ 'getbowtied/slide' ],
					},
				),
			];
		},

		save: function( props ) {
			attributes = props.attributes;
			return el( 
					'div',
					{
						key: 'wp-block-gbt-slider',
						className: 'wp-block-gbt-slider'
					},
					el( 
						'div',
						{
							key: 'shortcode_getbowtied_slider',
							className: 'shortcode_getbowtied_slider swiper-container ' + attributes.full_height,
							style:
							{
								height: attributes.custom_desktop_height + 'px'
							}
						},
						el(
							'div',
							{
								key: 'swiper-wrapper',
								className: 'swiper-wrapper'
							},
							el( InnerBlock.Content, { key: 'slide-content' } )
						),
						!! attributes.slide_numbers && el(
							'div',
							{
								key: 'shortcode-slider-pagination',
								className: 'quickview-pagination shortcode-slider-pagination',
								style:
								{
									color: attributes.slide_numbers_color
								}
							}
						)
					)
				);
		},
	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	jQuery
);