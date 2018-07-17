( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;

	var InspectorControls 	= wp.editor.InspectorControls;
	var InnerBlock 			= wp.editor.InnerBlocks;
	var MediaUpload			= wp.editor.MediaUpload;
	var RichText			= wp.editor.RichText;

	var TextControl 		= wp.components.TextControl;
	var SelectControl		= wp.components.SelectControl;
	var PanelBody			= wp.components.PanelBody;
	var ToggleControl		= wp.components.ToggleControl;
	var Button 				= wp.components.Button;
	var PanelColor			= wp.components.PanelColor;
	var ColorPalette		= wp.components.ColorPalette;
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
	        description: {
	        	type: 'string',
	        	default: 'Slide Description'
	        },
	        text_color: {
	        	type: 'string',
	        	default: '#fff'
	        },
	        button_text: {
	        	type: 'string',
	        	default: ''
	        },
	        button_url: {
	        	type: 'string',
	        	default: ''
	        },
	        bg_color: {
	        	type: 'string',
	        	default: '#24282e'
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
								key: "slide-button-text-option",
	              				label: i18n.__( 'Button Text' ),
	              				type: 'text',
	              				value: attributes.button_text,
	              				onChange: function( newText ) {
									props.setAttributes( { button_text: newText } );
								},
							},
						),
						el(
							TextControl,
							{
								key: "slide-button-url-option",
	              				label: i18n.__( 'Button URL' ),
	              				type: 'text',
	              				value: attributes.button_url,
	              				onChange: function( newText ) {
									props.setAttributes( { button_url: newText } );
								},
							},
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
										i18n.__( '' )
									),
								),
              				]
              			},
					},
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
								className: 'editor-slide-container',
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
											color: attributes.text_color
										},
										className: 'slide-title',
										formattingControls: [],
										tagName: 'h3',
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
											color: attributes.text_color
										},
										className: 'slide-description',
										tagName: 'h4',
										value: attributes.description,
										formattingControls: [],
										placeholder: i18n.__( 'Add Subtitle' ),
										onChange: function( newSubtitle) {
											props.setAttributes( { description: newSubtitle } );
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