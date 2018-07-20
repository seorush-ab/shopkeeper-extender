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
			slide_numbers: {
				type: 'boolean',
				default: true
			},
			slide_numbers_color: {
				type: 'string',
				default: '#fff'
			},
			nav_arrows: {
				type: 'boolean',
				default: true
			},
			nav_arrows_color: {
				type: 'string',
				default: '#fff'
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
							title: 'Height',
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
									allowReset: false,
									initialPosition: 800,
									min: 100,
									max: 1000,
									label: i18n.__( 'Custom Desktop Height' ),
									onChange: function( newNumber ) {
										props.setAttributes( { custom_desktop_height: newNumber } );
									},
								}
							),
						),
					),
					el( 
						PanelBody, 
						{ 
							key: 'slider-numbers-settings-panel',
							title: 'Pagination',
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
	              				label: i18n.__( 'Pagination Bullets' ),
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
								title: i18n.__( 'Pagination Bullets Color' ),
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
					el( 
						PanelBody, 
						{ 
							key: 'slider-nav-arrows-settings-panel',
							title: 'Navigation Arrows',
							initialOpen: false,
							style:
							{
							    borderBottom: '1px solid #e2e4e7'
							}
						},
						el(
							ToggleControl,
							{
								key: "slide-nav-arrows-toggle",
	              				label: i18n.__( 'Navigation Arrows' ),
	              				checked: attributes.nav_arrows,
	              				onChange: function() {
									props.setAttributes( { nav_arrows: ! attributes.nav_arrows } );
								},
							}
						),
						attributes.nav_arrows == true &&
						el(
							PanelColor,
							{
								key: 'slider-nav-arrows-color-panel',
								title: i18n.__( 'Navigation Arrows Color' ),
								colorValue: attributes.nav_arrows_color,
							},
							el(
								ColorPalette, 
								{
									key: 'slider-nav-arrows-color-pallete',
									colors: colors,
									value: attributes.nav_arrows_color,
									onChange: function( newColor) {
										props.setAttributes( { nav_arrows_color: newColor } );
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
						!! attributes.nav_arrows && el(
							'div',
							{
								key: 'swiper-button-prev',
								className: 'swiper-button-prev',
								style:
								{
									color: attributes.nav_arrows_color
								}
							},
							el(
								'i',
								{
									key: 'spk-icon-left-arrow-thin-large',
									className: 'spk-icon spk-icon-left-arrow-thin-large',
								}
							)
						),
						!! attributes.nav_arrows && el(
							'div',
							{
								key: 'swiper-button-next',
								className: 'swiper-button-next',
								style:
								{
									color: attributes.nav_arrows_color
								}
							},
							el(
								'i',
								{
									key: 'spk-icon-right-arrow-thin-large',
									className: 'spk-icon spk-icon-right-arrow-thin-large',
								}
							)
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