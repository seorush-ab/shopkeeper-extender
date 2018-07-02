( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;
		
	var InspectorControls 	= wp.editor.InspectorControls;
	var RichText			= wp.editor.RichText;
	var BlockControls		= wp.editor.BlockControls;
	var MediaUpload			= wp.editor.MediaUpload;

	var TextControl 		= wp.components.TextControl;
	var ToggleControl		= wp.components.ToggleControl;
	var RangeControl		= wp.components.RangeControl;
	var ColorPalette		= wp.components.ColorPalette;
	var PanelBody			= wp.components.PanelBody;
	var PanelRow			= wp.components.PanelRow;
	var PanelColor			= wp.components.PanelColor;
	var Button				= wp.components.Button;

	/* Register Block */
	registerBlockType( 'getbowtied/banner', {
		title: i18n.__( 'Banner' ),
		icon: 'format-image',
		category: 'common',
		attributes: {
			title: {
				type: 'string',
				default: '',
			},
			subtitle: {
				type: 'string',
				default: ''
			},
		    imgURL: {
	            type: 'string',
	            attribute: 'src',
	        },
	        imgID: {
	            type: 'number',
	        },
	        imgAlt: {
	            type: 'string',
	            attribute: 'alt',
	        },
	        url: {
	        	type: 'string',
	        	default: '#',
	        },
	        blank: {
	        	type: 'boolean',
	        	default: true
	        },
			titleColor: {
				type: 'string',
				default: '#fff'
			},
			subtitleColor: {
				type: 'string',
				default: '#fff'
			},
			innerStrokeThickness: {
				type: 'integer',
				default: 2
			},
			innerStrokeColor: {
				type: 'string',
				default: '#fff'
			},
			bgColor: {
				type: 'string',
				default: '#F0EFEF'
			},
			height: {
				type: 'integer',
				default: 300,
			},
			separatorPadding: {
				type: 'integer',
				default: 5
			},
			separatorColor: {
				type: 'string',
				default: '#fff'
			},
			bullet: {
	        	type: 'boolean',
	        	default: true
	        },
	        bulletText: {
	        	type: 'string',
	        	default: '',
	        },
	        bulletBgColor: {
				type: 'string',
				default: '#fff'
			},
	        bulletTextColor: {
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
					el( PanelBody, { key: 'general-settings-panel', title: 'General Settings', initialOpen: false },
						el(
							TextControl,
							{
								key: 'banner-url',
								type: 'string',
								label: i18n.__( 'URL' ),
								value: attributes.url,
								onChange: function( newURL ) {
									props.setAttributes( { url: newURL } );
								},
							}
						),
						el(
							ToggleControl,
							{
								key: "blank-toggle",
	              				label: i18n.__( 'Open link in new tab?' ),
	              				checked: attributes.blank,
	              				onChange: function() {
									props.setAttributes( { blank: ! attributes.blank } );
								},
							}
						),
						el(
							RangeControl,
							{
								key: "banner-height",
								value: attributes.height,
								allowReset: true,
								min: 0,
								max: 1000,
								label: i18n.__( 'Height' ),
								onChange: function( newNumber ) {
									props.setAttributes( { height: newNumber } );
								},
							}
						),
					),
					el( PanelBody, { key: 'colors-panel', title: 'Colors', initialOpen: false },
						el(
							PanelColor,
							{
								key: 'title-color-panel',
								title: i18n.__( 'Title Color' ),
								colorValue: attributes.titleColor,
							},
							el(
								ColorPalette, 
								{
									key: 'title-color-pallete',
									value: attributes.titleColor,
									onChange: function( newColor) {
										props.setAttributes( { titleColor: newColor } );
									},
								} 
							),
						),
						el(
							PanelColor,
							{
								key: 'subtitle-color-panel',
								title: i18n.__( 'Subtitle Color' ),
								colorValue: attributes.subtitleColor,
							},
							el(
								ColorPalette, 
								{
									key: 'subtitle-color-palette',
									value: attributes.subtitleColor,
									onChange: function( newColor) {
										props.setAttributes( { subtitleColor: newColor } );
									},
								} 
							),
						),
						el(
							PanelColor,
							{
								key: 'bg-color-panel',
								title: i18n.__( 'Background Color' ),
								colorValue: attributes.bgColor,
							},
							el(
								ColorPalette, 
								{
									key: 'bg-color-palette',
									value: attributes.bgColor,
									onChange: function( newColor) {
										props.setAttributes( { bgColor: newColor } );
									},
								} 
							),
						),
					),
					el( PanelBody, { key: 'inner-stroke-panel', title: 'Inner Stroke', initialOpen: false },
						el(
							RangeControl,
							{
								key: "inner-stroke-thickness",
								value: attributes.innerStrokeThickness,
								allowReset: false,
								label: i18n.__( 'Inner Stroke Thickness' ),
								onChange: function( newNumber ) {
									props.setAttributes( { innerStrokeThickness: newNumber } );
								},
							}
						),
						el(
							PanelColor,
							{
								key: 'inner-stroke-color-panel',
								title: i18n.__( 'Inner Stroke Color' ),
								colorValue: attributes.innerStrokeColor,
							},
							el(
								ColorPalette, 
								{
									key: 'inner-stroke-color-palette',
									value: attributes.innerStrokeColor,
									onChange: function( newColor) {
										props.setAttributes( { innerStrokeColor: newColor } );
									},
								} 
							),
						),
					),
					el( PanelBody, { key: 'separator-panel', title: 'Separator', initialOpen: false },
						el(
							RangeControl,
							{
								key: "separator-padding",
								value: attributes.separatorPadding,
								allowReset: false,
								label: i18n.__( 'Separator Padding' ),
								onChange: function( newNumber ) {
									props.setAttributes( { separatorPadding: newNumber } );
								},
							}
						),
						el(
							PanelColor,
							{
								key: 'separator-color-panel',
								title: i18n.__( 'Separator Color' ),
								colorValue: attributes.separatorColor,
							},
							el(
								ColorPalette, 
								{
									key: 'separator-color-palette',
									value: attributes.separatorColor,
									onChange: function( newColor) {
										props.setAttributes( { separatorColor: newColor } );
									},
								} 
							),
						),
					),
					el( PanelBody, { key: 'bullet-panel', title: 'Bullet', initialOpen: false },
						el(
							ToggleControl,
							{
								key: "bullet-toggle",
	              				label: i18n.__( 'With bullet?' ),
	              				checked: attributes.bullet,
	              				onChange: function() {
									props.setAttributes( { bullet: ! attributes.bullet } );
								},
							}
						),
						!! attributes.bullet && [ 
							el( TextControl,
								{
									key: 'bullet-text',
									type: 'string',
									label: i18n.__( 'Bullet Text' ),
									value: attributes.bulletText,
									onChange: function( newText ) {
										props.setAttributes( { bulletText: newText } );
									},
								}
							),
							el( PanelColor,
								{
									key: 'bullet-bg-color-panel',
									title: i18n.__( 'Bullet Background Color' ),
									colorValue: attributes.bulletBgColor,
								},
								el(
									ColorPalette, 
									{
										key: 'bullet-bg-color-palette',
										value: attributes.bulletBgColor,
										onChange: function( newColor) {
											props.setAttributes( { bulletBgColor: newColor } );
										},
									} 
								),
							),
							el( PanelColor,
								{
									key: 'bullet-text-color-panel',
									title: i18n.__( 'Bullet Text Color' ),
									colorValue: attributes.bulletTextColor,
								},
								el(
									ColorPalette, 
									{
										key: 'bullet-text-color-palette',
										value: attributes.bulletTextColor,
										onChange: function( newColor) {
											props.setAttributes( { bulletTextColor: newColor } );
										},
									} 
								),
							),
						]
					),
				),
				el( 'h2', { key: 'block-title', className: 'block-title' }, i18n.__( 'Banner' ) ),
				el( 'div', 
					{ 
						key: 'shortcode_banner_simple_height',
						className: 'shortcode_banner_simple_height banner_with_img',
					},
					el( MediaUpload,
						{
							key: 'banner-image',
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
		              						key: 'add-image',
		              						className: 'button add-image',
		              						onClick: img.open
		              					},
		              					i18n.__( 'Upload Image' )
	              					), 
	              					!! attributes.imgID && el( Button, 
										{
											key: 'remove-image',
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
					el( 'div',
						{
							key: 'shortcode_banner_simple_height_inner',
							className: 'shortcode_banner_simple_height_inner',
						},
						el( 'div',
							{
								key: 'shortcode_banner_simple_height_bkg',
								className: 'shortcode_banner_simple_height_bkg',
								style: { backgroundColor: attributes.bgColor, backgroundImage: 'url(' + attributes.imgURL + ')' },
							}),
						el( 'div',
							{
								key: 'shortcode_banner_simple_height_inside',
								className: 'shortcode_banner_simple_height_inside',
								style: { height: attributes.height + 'px', border: attributes.innerStrokeThickness + 'px solid ' + attributes.innerStrokeColor },
							},
							el( 'div',
								{
									key: 'shortcode_banner_simple_height_content',
									className: 'shortcode_banner_simple_height_content',
								},
								el( 'div',
									{
										key: 'shortcode_banner_simple_height_content_div',
									},
									el( 'h3',
										{
											key: 'shortcode_banner_simple_height_content_h3',
											style: { color: attributes.titleColor },
										},
										el( RichText, 
											{
												key: 'banner-title',
												style: { color: attributes.titleColor },
												className: 'banner-title',
												tagName: 'h3',
												value: attributes.title,
												placeholder: i18n.__( 'Add Title' ),
												onChange: function( newTitle) {
													props.setAttributes( { title: newTitle } );
												}
											}
										),
									),
								),
								el( 'div', 
									{
										key: 'shortcode_banner_simple_height_sep',
										className: 'shortcode_banner_simple_height_sep',
										style: { margin: attributes.separatorPadding + 'px auto', backgroundColor: attributes.separatorColor },
									},
								),
								el( 'div',
									{
										key: 'shortcode_banner_simple_height_content_div2',
									},
									el( 'h4', 
										{
											key: 'shortcode_banner_simple_height_content_h4',
											style: { color: attributes.subtitleColor },
										},
										el( RichText, 
											{
												key: 'banner-subtitle',
												style: { color: attributes.subtitleColor },
												className: 'banner-subtitle',
												tagName: 'div',
												value: attributes.subtitle,
												placeholder: i18n.__( 'Add Subtitle' ),
												onChange: function( newSubtitle) {
													props.setAttributes( { subtitle: newSubtitle } );
												}
											}
										),
									),
								),
							),
						),
					),
				),
			];
		},
		save: function( props ) {
			return '';
		},
	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
);