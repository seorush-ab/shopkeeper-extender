( function( blocks, components, editor, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = blocks.registerBlockType;
		
	var InspectorControls 	= editor.InspectorControls;
	var RichText			= editor.RichText;
	var BlockControls		= editor.BlockControls;
	var MediaUpload			= editor.MediaUpload;

	var TextControl 		= components.TextControl;
	var ToggleControl		= components.ToggleControl;
	var RangeControl		= components.RangeControl;
	var ColorPalette		= components.ColorPalette;
	var PanelBody			= components.PanelBody;
	var PanelRow			= components.PanelRow;
	var PanelColor			= components.PanelColor;
	var Button				= components.Button;

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
				type: 'number',
				default: '2'
			},
			innerStrokeColor: {
				type: 'string',
				default: '#fff'
			},
			bgColor: {
				type: 'string',
				default: '#8393a7'
			},
			height: {
				type: 'number',
				default: '300',
			},
			separatorPadding: {
				type: 'number',
				default: '5'
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
						key: 'banner-inspector'
					},
					el( 
						PanelBody, 
						{ 
							key: 'banner-general-settings-panel',
							title: 'General Settings',
							initialOpen: false,
							style:
							{
							    borderBottom: '1px solid #e2e4e7'
							}
						},
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
								key: "banner-blank-toggle",
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
								initialPosition: 300,
								min: 0,
								max: 1000,
								label: i18n.__( 'Height' ),
								onChange: function( newNumber ) {
									props.setAttributes( { height: newNumber } );
								},
							}
						),
					),
					el( 
						PanelBody,
						{ 
							key: 'banner-colors-panel',
							title: 'Colors',
							initialOpen: false
						},
						el(
							PanelColor,
							{
								key: 'banner-title-color-panel',
								title: i18n.__( 'Title Color' ),
								colorValue: attributes.titleColor,
							},
							el(
								ColorPalette, 
								{
									key: 'banner-title-color-pallete',
									colors: colors,
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
								key: 'banner-subtitle-color-panel',
								title: i18n.__( 'Subtitle Color' ),
								colorValue: attributes.subtitleColor,
							},
							el(
								ColorPalette, 
								{
									key: 'banner-subtitle-color-palette',
									colors: colors,
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
								key: 'banner-bg-color-panel',
								title: i18n.__( 'Background Color' ),
								colorValue: attributes.bgColor,
							},
							el(
								ColorPalette, 
								{
									key: 'banner-bg-color-palette',
									colors: colors,
									value: attributes.bgColor,
									onChange: function( newColor) {
										props.setAttributes( { bgColor: newColor } );
									},
								} 
							),
						),
					),
					el(
						PanelBody,
						{ 
							key: 'banner-inner-stroke-panel',
							title: 'Inner Stroke',
							initialOpen: false
						},
						el(
							RangeControl,
							{
								key: "banner-inner-stroke-thickness",
								value: attributes.innerStrokeThickness,
								initialPosition: '2',
								allowReset: true,
								label: i18n.__( 'Inner Stroke Thickness' ),
								onChange: function( newNumber ) {
									props.setAttributes( { innerStrokeThickness: newNumber } );
								},
							}
						),
						el(
							PanelColor,
							{
								key: 'banner-inner-stroke-color-panel',
								title: i18n.__( 'Inner Stroke Color' ),
								colorValue: attributes.innerStrokeColor,
							},
							el(
								ColorPalette, 
								{
									key: 'banner-inner-stroke-color-palette',
									colors: colors,
									value: attributes.innerStrokeColor,
									onChange: function( newColor) {
										props.setAttributes( { innerStrokeColor: newColor } );
									},
								} 
							),
						),
					),
					el(
						PanelBody,
						{ 
							key: 'banner-separator-panel',
							title: 'Separator',
							initialOpen: false
						},
						el(
							RangeControl,
							{
								key: "banner-separator-padding",
								value: attributes.separatorPadding,
								initialPosition: '5',
								allowReset: true,
								label: i18n.__( 'Separator Padding' ),
								onChange: function( newNumber ) {
									props.setAttributes( { separatorPadding: newNumber } );
								},
							}
						),
						el(
							PanelColor,
							{
								key: 'banner-separator-color-panel',
								title: i18n.__( 'Separator Color' ),
								colorValue: attributes.separatorColor,
							},
							el(
								ColorPalette, 
								{
									key: 'banner-separator-color-palette',
									colors: colors,
									value: attributes.separatorColor,
									onChange: function( newColor) {
										props.setAttributes( { separatorColor: newColor } );
									},
								} 
							),
						),
					),
					el(
						PanelBody,
						{
							key: 'banner-bullet-panel',
							title: 'Bullet',
							initialOpen: false
						},
						el(
							ToggleControl,
							{
								key: "banner-bullet-toggle",
	              				label: i18n.__( 'With bullet?' ),
	              				checked: attributes.bullet,
	              				onChange: function() {
									props.setAttributes( { bullet: ! attributes.bullet } );
								},
							}
						),
						!! attributes.bullet && [ 
							el(
								TextControl,
								{
									key: 'banner-bullet-text',
									type: 'string',
									label: i18n.__( 'Bullet Text' ),
									value: attributes.bulletText,
									onChange: function( newText ) {
										props.setAttributes( { bulletText: newText } );
									},
								}
							),
							el(
								PanelColor,
								{
									key: 'banner-bullet-bg-color-panel',
									title: i18n.__( 'Bullet Background Color' ),
									colorValue: attributes.bulletBgColor,
								},
								el(
									ColorPalette, 
									{
										key: 'banner-bullet-bg-color-palette',
										colors: colors,
										value: attributes.bulletBgColor,
										onChange: function( newColor) {
											props.setAttributes( { bulletBgColor: newColor } );
										},
									} 
								),
							),
							el(
								PanelColor,
								{
									key: 'banner-bullet-text-color-panel',
									title: i18n.__( 'Bullet Text Color' ),
									colorValue: attributes.bulletTextColor,
								},
								el(
									ColorPalette, 
									{
										key: 'banner-bullet-text-color-palette',
										colors: colors,
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
				el(
					'h2',
					{
						key: 'banner-block-title',
					}, 
					i18n.__( 'Banner11' )
				),
				el(
					'div', 
					{ 
						key: 'shortcode_banner_simple_height',
						className: 'shortcode_banner_simple_height banner_with_img',
					},
					el(
						MediaUpload,
						{
							key: 'banner-image-upload',
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
		              					Button, 
		              					{ 
		              						key: 'banner-add-image-button',
		              						className: 'button add-image',
		              						onClick: img.open
		              					},
		              					i18n.__( 'Add Image' )
	              					), 
	              					!! attributes.imgID && el(
	              						Button, 
										{
											key: 'banner-remove-image-button',
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
									), 
	              				];
	              			},
						},
					),
					el(
						'div',
						{
							key: 'shortcode_banner_simple_height_inner',
							className: 'shortcode_banner_simple_height_inner',
						},
						el(
							'div',
							{
								key: 'shortcode_banner_simple_height_bkg',
								className: 'shortcode_banner_simple_height_bkg',
								style:
								{
									backgroundColor: attributes.bgColor,
									backgroundImage: 'url(' + attributes.imgURL + ')'
								},
							}
						),
						el(
							'div',
							{
								key: 'shortcode_banner_simple_height_inside',
								className: 'shortcode_banner_simple_height_inside',
								style:
								{
									height: attributes.height + 'px',
									border: attributes.innerStrokeThickness + 'px solid ' + attributes.innerStrokeColor
								},
							},
							el(
								'div',
								{
									key: 'shortcode_banner_simple_height_content',
									className: 'shortcode_banner_simple_height_content',
								},
								el(
									'div',
									{
										key: 'shortcode_banner_simple_height_content_div',
									},
									el(
										'h3',
										{
											key: 'shortcode_banner_simple_height_content_h3',
											style:
											{
												color: attributes.titleColor
											},
										},
										el(
											RichText, 
											{
												key: 'banner-title',
												style:
												{ 
													color: attributes.titleColor
												},
												className: 'banner-title',
												value: attributes.title,
												placeholder: i18n.__( 'Add Title' ),
												onChange: function( newTitle) {
													props.setAttributes( { title: newTitle } );
												}
											}
										),
									),
								),
								el(
									'div', 
									{
										key: 'shortcode_banner_simple_height_sep',
										className: 'shortcode_banner_simple_height_sep',
										style:
										{
											margin: attributes.separatorPadding + 'px auto',
											backgroundColor: attributes.separatorColor
										},
									},
								),
								el(
									'div',
									{
										key: 'shortcode_banner_simple_height_content_div2',
									},
									el(
										'h4', 
										{
											key: 'shortcode_banner_simple_height_content_h4',
											style:
											{
												color: attributes.subtitleColor
											},
										},
										el(
											RichText, 
											{
												key: 'banner-subtitle',
												style:
												{
													color: attributes.subtitleColor
												},
												className: 'banner-subtitle',
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
		save: function() {
			return '';
		},
	} );

} )(
	window.wp.blocks,
	window.wp.components,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
);