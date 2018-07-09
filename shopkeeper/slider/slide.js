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
	registerBlockType( 'getbowtied/slide', {
		title: i18n.__( 'Slide' ),
		icon: 'slides',
		category: 'common',
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
	        	default: '#000'
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
	        	default: '#fff'
	        },
		},

		edit: function( props ) {

			var attributes = props.attributes;

			return [
				el(
					InspectorControls,
					{ key: 'inspector' },
					el( 'div', { key: 'slide-block-description', className: 'components-block-description' }, // A brief description of our block in the inspector.
						el( 'b', { key: 'slide-block-description-b' }, i18n.__( 'Slide - Settings' ) ),
					),
					el(
						TextControl,
						{
							key: "slide-title-option",
              				label: i18n.__( 'Slide Title' ),
              				type: 'text',
              				value: attributes.title,
              				onChange: function( newTitle ) {
								props.setAttributes( { title: newTitle } );
							},
						},
					),
					el(
						TextControl,
						{
							key: "slide-description-option",
              				label: i18n.__( 'Slide Description' ),
              				type: 'text',
              				value: attributes.description,
              				onChange: function( newDescription ) {
								props.setAttributes( { description: newDescription } );
							},
						},
					),
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
								value: attributes.text_color,
								onChange: function( newColor) {
									props.setAttributes( { text_color: newColor } );
								},
							} 
						),
					),
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
								value: attributes.bg_color,
								onChange: function( newColor) {
									props.setAttributes( { bg_color: newColor } );
								},
							} 
						),
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
	              				! attributes.imgID && el( Button, 
	              					{ 
	              						key: 'slide-add-image',
	              						className: 'button add-image',
	              						onClick: img.open
	              					},
	              					i18n.__( 'Upload Image' )
              					), 
              					!! attributes.imgID && el( Button, 
									{
										key: 'slide-remove-image',
										className: 'button remove-image',
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
								) 
              				]
              			},
					},
				),
				!! attributes.imgID && el(
					'img',
					{
						key: 'slide-image-render',
						src: attributes.imgURL
					},
				),
			];
		},

		save: function( props ) {
			//var attributes = props.attributes;
			return 
				// !! attributes.imgID && el( 'img', 
				// 	{
				//		key: 'slide-render-image',
				// 		src: attributes.imgURL,
				// 		alt: attributes.imgAlt,
				// 	}
				// );
		},
	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	jQuery
);