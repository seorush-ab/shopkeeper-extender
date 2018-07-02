( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;
		
	var InspectorControls 	= wp.editor.InspectorControls;
	var RichText			= wp.editor.RichText;
	var BlockControls		= wp.editor.BlockControls;
	var AlignmentToolbar	= wp.editor.AlignmentToolbar;
	var MediaUpload			= wp.editor.MediaUpload;

	var TextControl 		= wp.components.TextControl;
	var RadioControl        = wp.components.RadioControl;
	var SelectControl		= wp.components.SelectControl;
	var ToggleControl		= wp.components.ToggleControl;
	var RangeControl		= wp.components.RangeControl;
	var ColorPalette		= wp.components.ColorPalette;
	var TextareaControl 	= wp.components.TextareaControl;
	var PanelBody			= wp.components.PanelBody;
	var PanelRow			= wp.components.PanelRow;
	var PanelColor			= wp.components.PanelColor;
	var FormToggle			= wp.components.FormToggle;
	var Button 				= wp.components.Button;
	var Toolbar 			= wp.components.Toolbar;

	/* Register Block */
	registerBlockType( 'getbowtied/inspector-controls', {
		title: i18n.__( 'Inspector Controls' ),
		icon: 'admin-settings',
		category: 'common',
		attributes: {
			bgColor: {
				type: 'string',
				default: '#F0EFEF'
			},
			fontColor: {
				type: 'string',
				default: '#363436'
			},
			fontSize: {
				type: 'string',
				default: '12'
			},
			titleSize: {
				type: 'string',
				default: '18'
			},
			buttonURL: {
				type: 'string',
				default: '',
			},
			insertButton: {
				type: 'boolean',
				default: true
			},
			buttonText: {
				type: 'string',
				default: 'Button'
			},
			title: {
				type: 'string',
				default: 'Here is the title!',
				selector: '.title'
			},
			content: {
				type: 'string',
				selector: '.content'
			},
			quote: {
				type: 'string',
				default: 'This is a quote.'
			},
			alignment: {
		        type: 'string',
		    },
		    size: {
		        type: 'string',
		    },
		    display: {
		    	type: 'string',
		    	default: 'full-width'
		    },
		    layout: {
		    	type: 'string',
		    	default: 'with-image'
		    },
		    imgURL: {
	            type: 'string',
	            source: 'attribute',
	            attribute: 'src',
	            selector: 'img',
	        },
	        imgID: {
	            type: 'number',
	        },
	        imgAlt: {
	            type: 'string',
	            source: 'attribute',
	            attribute: 'alt',
	            selector: 'img',
	        }
		},

		edit: function( props ) {

			var attributes = props.attributes;

			return [
				el(
					InspectorControls,
					{ key: 'inspector' },
					el( 'div', { className: 'components-block-description' }, // A brief description of our block in the inspector.
						el( 'p', {}, i18n.__( 'Here you can insert a block description.' ) ),
					),
					el( PanelBody, { title: 'Text Settings', initialOpen: false },
						el( PanelRow, {}, 
							el(
								PanelColor,
								{
									title: i18n.__( 'Text Color' ),
									colorValue: attributes.fontColor,
								},
								el(
									ColorPalette, 
									{
										value: attributes.fontColor,
										onChange: function( newColor) {
											props.setAttributes( { fontColor: newColor } );
										},
									} 
								),
							),
						),
						el( PanelRow, {}, 
							el(
								RangeControl,
								{
									value: attributes.titleSize,
									allowReset: true,
									label: i18n.__('Title Size'),
									onChange: function( newSize ) {
										props.setAttributes( { titleSize: newSize } );
									},
								}
							),
						),
						el( PanelRow, {}, 
							el(
								RangeControl,
								{
									value: attributes.fontSize,
									allowReset: true,
									label: i18n.__('Font Size'),
									onChange: function( newSize ) {
										props.setAttributes( { fontSize: newSize } );
									},
								}
							),
						),
					),
					el( PanelBody, { title: 'Button Settings', initialOpen: false },
						el( PanelRow, {}, 
							el(
								ToggleControl,
								{
									id: "button-toggle",
		              				label: i18n.__( 'Insert Button' ),
		              				checked: attributes.insertButton,
		              				onChange: function() {
										props.setAttributes( { insertButton: ! attributes.insertButton } );
									},
								}
							),
						),
						el( PanelRow, {}, 
							el(
								TextControl,
								{
									type: 'string',
									label: i18n.__( 'Button Text' ),
									value: attributes.buttonText,
									onChange: function( newText ) {
										props.setAttributes( { buttonText: newText } );
									},
								}
							),
						),
						el( PanelRow, {}, 
							el(
								TextControl,
								{
									type: 'url',
									label: i18n.__( 'Button URL' ),
									value: attributes.buttonURL,
									onChange: function( newURL ) {
										props.setAttributes( { buttonURL: newURL } );
									},
								}
							),
						),
					),
					el( PanelBody, { title: 'Layout Settings', initialOpen: false },
						el( PanelRow, {}, 
							el( RadioControl, 
								{
									selected: attributes.display,
									label: i18n.__( 'Content Display' ),
									options: [ { value: 'boxed', label: 'Boxed' }, { value: 'full-width', label: 'Full Width' } ],
									onChange: function( newDisplay ) {
										props.setAttributes( { display: newDisplay } )
									}
								} 
							),
						),
						el(
							SelectControl,
							{
								id: "layout-option",
								options: [{value: 'with-image', label: 'With Image'}, {value: 'without-image', label: 'Without Image'}],
	              				label: i18n.__( 'Block Layout' ),
	              				value: attributes.layout,
	              				onChange: function( newSelection ) {
									props.setAttributes( { layout: newSelection } );
								},
							}
						),
					),
					el( PanelBody, { title: 'General Settings', initialOpen: false },
						el( PanelRow, {}, 
							el(
								TextareaControl,
								{
									label: i18n.__( 'Quote' ),
									rows: 3,
									value: attributes.quote,
									onChange: function( newQuote ) {
										props.setAttributes( { quote: newQuote } );
									}
								},
							),
						),
						el( PanelRow, {}, 
							el(
								PanelColor,
								{
									title: i18n.__( 'Border Color' ),
									colorValue: attributes.bgColor,
								},
								el(
									ColorPalette, 
									{
										value: attributes.bgColor,
										onChange: function( newColor) {
											props.setAttributes( { bgColor: newColor } );
										},
									} 
								),
							),
						),
						el( PanelRow, {}, 
							el( 'b', {}, i18n.__('Text Align') ),
							el( AlignmentToolbar, 
								{
									value: attributes.alignment,
									onChange: function( newAlignment ) {
										props.setAttributes( { alignment: newAlignment } )
									}
								} 
							),
						),
						el( PanelRow, {}, 
							el( 'b', {}, i18n.__('Text Size') ),
							el( Toolbar, 
								{
									value: attributes.size,
									onChange: function( newAlignment ) {
										props.setAttributes( { size: newAlignment } )
									}
								} 
							),
						),
					),						
				),
				el( 'h2', { className: 'widget-title' }, i18n.__( 'Inspector Controls' ) ),
				el( 'div', 
					{ 
						className: 'output-div ' + attributes.display,
						style: { borderColor: attributes.bgColor, textAlign: attributes.alignment },
					},
					! attributes.imgID && attributes.layout == 'with-image' && el( MediaUpload,
						{
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
								return el( Button, { onClick: img.open }, i18n.__( 'Upload Image' ) )
							},
						},
					),
					!! attributes.imgID && attributes.layout == 'with-image' && el( 'p',
						{
							className: 'image-wrapper',
						},
						el( 'img', 
							{
								src: attributes.imgURL,
								alt: attributes.imgAlt,
							}
						),
						el( Button, 
							{
								className: 'button remove-image',
								onClick: function() {
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
					el( RichText, 
						{
							style: { color: attributes.fontColor, fontSize: attributes.titleSize+'px', textAlign: attributes.alignment },
							className: 'title',
							tagName: 'h3',
							value: attributes.title,
							placeholder: i18n.__( 'This is the title!' ),
							onChange: function( newTitle) {
								props.setAttributes( { title: newTitle } );
							}
						}
					),
					el( 'i', { className: 'quote' }, '"'+attributes.quote+'"' ),
					el( RichText, 
						{
							style: { color: attributes.fontColor, fontSize: attributes.fontSize+'px', textAlign: attributes.alignment },
							className: 'content',
							tagName: 'div',
							value: attributes.content,
							placeholder: i18n.__( 'Here is the content.' ),
							onChange: function( newContent) {
								props.setAttributes( { content: newContent } );
							}
						}
					),
					!! attributes.insertButton && el(
						'a', { className: 'button', href: attributes.buttonURL },
						i18n.__( attributes.buttonText ),
					),
				),
			];
		},

		save: function( props ) {
			var attributes = props.attributes;
			return el( 'div', 
					{ 
						className: 'output-div ' + attributes.display,
						style: { borderColor: attributes.bgColor, textAlign: attributes.alignment },
					},
					!! attributes.imgID && el( 'img', 
						{
							src: attributes.imgURL,
							alt: attributes.imgAlt,
						}
					),
					el( 'h3', 
						{
							style: { color: attributes.fontColor, fontSize: attributes.titleSize, textAlign: attributes.alignment },
							className: 'title'
						},
						attributes.title
					),
					el( 'i', { className: 'quote', style: { textAlign: attributes.alignment } }, '"' + attributes.quote + '"' ),
					el( 'div', 
						{
							style: { color: attributes.fontColor, fontSize: attributes.fontSize, textAlign: attributes.alignment },
							className: 'content'
						},
						attributes.content				
					),
					!! attributes.insertButton && el( 'a', 
						{
							className: 'button', 
							href: attributes.buttonURL
						},
						i18n.__( attributes.buttonText ),
					),
				);
		},
	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
);