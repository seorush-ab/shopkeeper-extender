( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;

	var InspectorControls 	= wp.editor.InspectorControls;
	var InnerBlock 			= wp.editor.InnerBlocks;
	var MediaUpload			= wp.editor.MediaUpload;
	var RichText			= wp.editor.RichText;
	var AlignmentToolbar	= wp.editor.AlignmentToolbar;
    var BlockControls       = wp.editor.BlockControls;

	var TextControl 		= wp.components.TextControl;
	var SelectControl		= wp.components.SelectControl;
	var PanelBody			= wp.components.PanelBody;
	var ToggleControl		= wp.components.ToggleControl;
	var Button 				= wp.components.Button;
	var PanelColor			= wp.components.PanelColor;
	var ColorPalette		= wp.components.ColorPalette;
	var RangeControl		= wp.components.RangeControl;
	var Tooltip				= wp.components.Tooltip;

	/* Register Block */
	registerBlockType( 'getbowtied/slide', {
		title: i18n.__( 'Slide' ),
		icon: 'slides',
		category: 'shopkeeper',
		parent: [ 'getbowtied/slider' ],
		attributes: {
		    imgURL: {
	            type: 'string',
	            attribute: 'src',
	            selector: 'img',
	        },
	        imgID: {
	            type: 'number',
	        },
	        imgAlt: {
	            type: 'string',
	            attribute: 'alt',
	            selector: 'img',
	        },
	        title: {
	        	type: 'string',
	        	default: 'Slide Title',
	        },
	        title_size: {
	        	type: 'number',
	        	default: 73,
	        },
	        title_font: {
	        	type: 'string',
	        	default: 'primary_font',
	        },
	        description: {
	        	type: 'string',
	        	default: 'Slide Description'
	        },
	        description_size: {
	        	type: 'number',
	        	default: 16,
	        },
	        description_font: {
	        	type: 'string',
	        	default: 'secondary_font',
	        },
	        text_color: {
	        	type: 'string',
	        	default: '#fff'
	        },
	        button_text: {
	        	type: 'string',
	        	default: 'Button Text'
	        },
	        button_url: {
	        	type: 'string',
	        	default: '#'
	        },
	        button_toggle: {
	        	type: 'boolean',
	        	default: true
	        },
	        button_text_color: {
	        	type: 'string',
	        	default: '#fff'
	        },
	        button_bg_color: {
	        	type: 'string',
	        	default: '#000'
	        },
	        bg_color: {
	        	type: 'string',
	        	default: '#24282e'
	        },
	        alignment: {
	        	type: 'string',
	        	default: 'center'
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
						key: 'slide-inspector'
					},
					el( 
						PanelBody, 
						{ 
							key: 'slide-text-settings-panel',
							title: 'Title & Description',
							initialOpen: false,
							style:
							{
							    borderBottom: '1px solid #e2e4e7'
							}
						},
						el(
							RangeControl,
							{
								key: "slide-title-font-size",
								value: attributes.title_size,
								allowReset: false,
								initialPosition: 32,
								min: 10,
								max: 72,
								label: i18n.__( 'Title Font Size' ),
								onChange: function( newNumber ) {
									props.setAttributes( { title_size: newNumber } );
								},
							}
						),
						el(
							SelectControl,
							{
								key: "slide-title-font-family",
								options: [{value: 'primary_font', label: 'Primary Font'}, {value: 'secondary_font', label: 'Secondary Font'}],
								label: i18n.__( 'Title Font Family (preview in frontend)' ),
								value: attributes.title_font,
								onChange: function( newSelection ) {
									props.setAttributes( { title_font: newSelection } );
								},
							}
						),
						el(
							RangeControl,
							{
								key: "slide-description-font-size",
								value: attributes.description_size,
								allowReset: false,
								initialPosition: 14,
								min: 10,
								max: 72,
								label: i18n.__( 'Description Font Size' ),
								onChange: function( newNumber ) {
									props.setAttributes( { description_size: newNumber } );
								},
							}
						),
						el(
							SelectControl,
							{
								key: "slide-description-font-family",
								options: [{value: 'primary_font', label: 'Primary Font'}, {value: 'secondary_font', label: 'Secondary Font'}],
								label: i18n.__( 'Description Font Family (preview in frontend)' ),
								value: attributes.description_font,
								onChange: function( newSelection ) {
									props.setAttributes( { description_font: newSelection } );
								},
							}
						),
					),
					el( 
						PanelBody, 
						{ 
							key: 'slide-colors-settings-panel',
							title: 'Colors',
							initialOpen: false,
							style:
							{
							    borderBottom: '1px solid #e2e4e7'
							}
						},
						el(
							PanelColor,
							{
								key: 'slide-text-color-panel',
								title: i18n.__( 'Text Color' ),
								colorValue: attributes.text_color,
							},
							el(
								ColorPalette, 
								{
									key: 'slide-text-color-palette',
									colors: colors,
									value: attributes.text_color,
									onChange: function( newColor) {
										props.setAttributes( { text_color: newColor } );
									},
								} 
							),
						),
						el(
							PanelColor,
							{
								key: 'slide-bg-color-panel',
								title: i18n.__( 'Background Color' ),
								colorValue: attributes.bg_color,
							},
							el(
								ColorPalette, 
								{
									key: 'slide-bg-color-palette',
									colors: colors,
									value: attributes.bg_color,
									onChange: function( newColor) {
										props.setAttributes( { bg_color: newColor } );
									},
								} 
							),
						),
					),
					el( 
						PanelBody, 
						{ 
							key: 'slide-button-settings-panel',
							title: 'Button',
							initialOpen: false,
							style:
							{
							    borderBottom: '1px solid #e2e4e7'
							}
						},
						el(
							TextControl,
							{
								key: "slide-button-url-option",
	              				label: i18n.__( 'URL' ),
	              				type: 'text',
	              				value: attributes.button_url,
	              				onChange: function( newText ) {
									props.setAttributes( { button_url: newText } );
								},
							},
						),
						el(
							ToggleControl,
							{
								key: "slide-button-toggle",
	              				label: i18n.__( 'Slide Button' ),
	              				checked: attributes.button_toggle,
	              				onChange: function() {
									props.setAttributes( { button_toggle: ! attributes.button_toggle } );
								},
							}
						),
						!! attributes.button_toggle && el(
							PanelColor,
							{
								key: 'slide-button-text-color-panel',
								title: i18n.__( 'Button Text Color' ),
								colorValue: attributes.text_color,
							},
							el(
								ColorPalette, 
								{
									key: 'slide-button-text-color-palette',
									colors: colors,
									value: attributes.button_text_color,
									onChange: function( newColor) {
										props.setAttributes( { button_text_color: newColor } );
									},
								} 
							),
						),
						!! attributes.button_toggle && el(
							PanelColor,
							{
								key: 'slide-button-bg-color-panel',
								title: i18n.__( 'Button Background Color' ),
								colorValue: attributes.text_color,
							},
							el(
								ColorPalette, 
								{
									key: 'slide-button-bg-color-palette',
									colors: colors,
									value: attributes.button_bg_color,
									onChange: function( newColor) {
										props.setAttributes( { button_bg_color: newColor } );
									},
								} 
							),
						),
					),
				),
				el( 
					'div',
					{ 
						key: 'wp-block-slide-title-wrapper',
						className: 'wp-block-slide-title-wrapper'
					},
					el(
						'h4',
						{
							key: 'wp-block-slide-title',
							className: 'wp-block-slide-title',
						},
						el(
							'span',
							{
								key: 'wp-block-slide-title-dashicon',
								className: 'dashicon dashicons-format-image',
							},
						),
						i18n.__('Image Slide')
					),
					el(
						'span',
						{
							key: 'slide-add-media-button-dashicon',
							className: 'dashicon dashicons-arrow-down',
						},
					),
				),
				el(
					MediaUpload,
					{
						key: 'slide-image',
						type: 'image',
						buttonProps: { className: 'components-button button button-large' },
              			value: attributes.imgID,
						onSelect: function( img ) {
							props.setAttributes( {
								imgID: img.id,
								imgURL: img.url,
								imgAlt: img.alt,
							} );
						},
              			render: function( img ) { 
              				return [
	              				! attributes.imgID && el( 
									'div',
									{ 
										key: 'slide-add-media-button-wrapper',
										className: 'slide-add-media-button-wrapper'
									},
									el( Button, 
		              					{ 
		              						key: 'slide-add-image',
		              						className: 'button add-image',
		              						onClick: img.open
		              					},
		              					i18n.__( 'Add Image' )
	              					),
								),
              					!! attributes.imgID && el( Tooltip,
	              					{
	              						key: 'slide-remove-media-button-wrapper',
	              						className: 'slide-remove-media-button-wrapper',
	              						text: 'Remove Image'
	              					},
              						el( Button, 
										{
											key: 'slide-remove-image',
											className: 'button slide-remove-image dashicon dashicons-dismiss',
											onClick: function() {
												img.close;
												props.setAttributes({
									            	imgID: null,
									            	imgURL: null,
									            	imgAlt: null,
									            });
											}
										},
										i18n.__( 'Remove Image' )
									),
								),
              				]
              			},
					},
				),
				el(
					BlockControls,
					{ 
						key: 'slide-controls'
					},
					el(
						AlignmentToolbar, 
						{
							key: 'slide-alignment',
							value: attributes.alignment,
							onChange: function( newAlignment ) {
								props.setAttributes( { alignment: newAlignment } );
							}
						} 
					),
				),
				el(
					'div',
					{
						key: 'editor-slide-wrapper',
						className: 'editor-slide-wrapper',
						style:
						{
							backgroundColor: attributes.bg_color,
							backgroundImage: 'url(' + attributes.imgURL + ')'
						},
					},
					el(
						'div',
						{
							key: 'editor-slide-content',
							className: 'editor-slide-content',
						},
						el(
							'div',
							{
								key: 'editor-slide-container',
								className: 'editor-slide-container align-' + attributes.alignment,
								style:
								{
									textAlign: attributes.alignment
								}
							},
							el(
								'div',
								{
									key: 'editor-slide-title',
									className: 'editor-slide-title',
								},
								el(
									RichText, 
									{
										key: 'slide-title',
										style:
										{ 
											color: attributes.text_color,
											fontSize: attributes.title_size + 'px'
										},
										format: 'string',
										className: 'slide-title',
										formattingControls: [],
										tagName: 'h1',
										value: attributes.title,
										placeholder: i18n.__( 'Add Title' ),
										onChange: function( newTitle) {
											props.setAttributes( { title: newTitle } );
										}
									}
								),
							),
							el(
								'div',
								{
									key: 'editor-slide-description',
									className: 'editor-slide-description',
								},
								el(
									RichText, 
									{
										key: 'slide-description',
										style:
										{
											color: attributes.text_color,
											fontSize: attributes.description_size + 'px'
										},
										className: 'slide-description',
										format: 'string',
										tagName: 'h5',
										value: attributes.description,
										formattingControls: [],
										placeholder: i18n.__( 'Add Subtitle' ),
										onChange: function( newSubtitle) {
											props.setAttributes( { description: newSubtitle } );
										}
									}
								),
							),
							!! attributes.button_toggle && el(
								'div',
								{
									key: 'editor-slide-button',
									className: 'editor-slide-button',
								},
								el(
									RichText, 
									{
										key: 'slide-button-text',
										style:
										{
											color: attributes.button_text_color,
											backgroundColor: attributes.button_bg_color,
										},
										className: 'slide-button-text',
										format: 'string',
										tagName: 'h5',
										value: attributes.button_text,
										formattingControls: [],
										placeholder: i18n.__( 'Button Text' ),
										onChange: function( newText) {
											props.setAttributes( { button_text: newText } );
										}
									}
								),
							),
						),
					),
				),
			];
		},

		save: function() {
			return '';
		},
	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	jQuery
);