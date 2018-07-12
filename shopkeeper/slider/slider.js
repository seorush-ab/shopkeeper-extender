( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;

	var InspectorControls 	= wp.editor.InspectorControls;
	var InnerBlock 			= wp.editor.InnerBlocks;
	var MediaUpload			= wp.editor.MediaUpload;

	var TextControl 		= wp.components.TextControl;
	var SelectControl		= wp.components.SelectControl;
	var ToggleControl		= wp.components.ToggleControl;
	var Button 				= wp.components.Button;
	var PanelColor			= wp.components.PanelColor;
	var ColorPalette		= wp.components.ColorPalette;

	/* Register Block */
	registerBlockType( 'getbowtied/slider', {
		title: i18n.__( 'Slider' ),
		icon: 'slides',
		category: 'shopkeeper',
		attributes: {
			full_height: {
				type: 'string',
				default: 'no'
			},
			custom_desktop_height: {
				type: 'string',
				default: '800px',
			},
			custom_mobile_height: {
				type: 'string',
				default: '600px'
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

			return [
				el(
					InspectorControls,
					{ key: 'inspector' },
					el( 'div', { key: 'slider-block-description', className: 'components-block-description' }, // A brief description of our block in the inspector.
						el( 'b', { key: 'slider-block-description-b' }, i18n.__( 'Slider - Settings' ) ),
					),
					el(
						SelectControl,
						{
							key: "full-height-option",
							options: [{value: 'no', label: 'Custom Height'}, {value: 'yes', label: 'Full Height'}],
							label: i18n.__( 'Height' ),
							value: attributes.full_height,
							onChange: function( newSelection ) {
								props.setAttributes( { full_height: newSelection } );
							},
						}
					),
					attributes.full_height == 'no' &&
					el( 'div', { key: "custom-height-option", },
						el(
							TextControl,
							{
								key: "desktop-height-option",
	              				label: i18n.__( 'Custom Desktop Height' ),
	              				type: 'text',
	              				value: attributes.custom_desktop_height,
	              				onChange: function( newNumber ) {
									props.setAttributes( { custom_desktop_height: newNumber } );
								},
							},
						),
						el(
							TextControl,
							{
								key: "mobile-height-option",
	              				label: i18n.__( 'Custom Mobile Height' ),
	              				type: 'text',
	              				value: attributes.custom_mobile_height,
	              				onChange: function( newNumber ) {
									props.setAttributes( { custom_mobile_height: newNumber } );
								},
							},
						),
					),
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
						TextControl,
						{
							key: "slide-numbers-color-option",
              				label: i18n.__( 'Color Slide Numbers' ),
              				type: 'text',
              				value: attributes.slide_numbers_color,
              				onChange: function( newNumber ) {
								props.setAttributes( { slide_numbers_color: newNumber } );
							},
						},
					),
				),
				el( 'div', { key: 'block-description', className: 'components-block-description' }, // A brief description of our block in the inspector.
					el( 'b', { key: 'block-description-b', className: 'components-block-description' }, i18n.__( 'Slider' ) ),
				),
				el(
					InnerBlock,
					{
						key: 'inner-block',
						allowedBlockNames: [ 'getbowtied/slide' ],
					},
				),
				el(
					InnerBlock,
					{
						key: 'inner-block',
						allowedBlockNames: [ 'getbowtied/slide' ],
					},
				),
			];
		},

		save: function( props ) {
			return '';//InnerBlock.Content;
		},
	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	jQuery
);