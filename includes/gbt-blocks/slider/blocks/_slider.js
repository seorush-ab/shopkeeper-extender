( function( blocks, i18n, element ) {

	const el = element.createElement;

	/* Blocks */
	const registerBlockType   	= wp.blocks.registerBlockType;

	const InspectorControls 	= wp.editor.InspectorControls;
	const InnerBlock 			= wp.editor.InnerBlocks;
	const ColorSettings			= wp.editor.PanelColorSettings;

	const SelectControl			= wp.components.SelectControl;
	const ToggleControl			= wp.components.ToggleControl;
	const Button 				= wp.components.Button;
	const RangeControl			= wp.components.RangeControl;
	const PanelBody				= wp.components.PanelBody;

	/* Register Block */
	registerBlockType( 'getbowtied/slider', {
		title: i18n.__( 'Slider' ),
		icon: 'slides',
		category: 'shopkeeper',
		supports: {
			align: [ 'center', 'wide', 'full' ],
		},
		attributes: {
			fullHeight: {
				type: 'boolean',
				default: false
			},
			customHeight: {
				type: 'number',
				default: '800',
			},
			pagination: {
				type: 'boolean',
				default: true
			},
			paginationColor: {
				type: 'string',
				default: '#fff'
			},
			arrows: {
				type: 'boolean',
				default: true
			},
			arrowsColor: {
				type: 'string',
				default: '#fff'
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;

			function getColors() {

				let colors = [];

				if( attributes.pagination ) {
					colors.push(
						{ 
							label: i18n.__( 'Pagination Bullets' ),
							value: attributes.paginationColor,
							onChange: function( newColor) {
								props.setAttributes( { paginationColor: newColor } );
							},
						}
					);
				}

				if( attributes.arrows ) {
					colors.push(
						{ 
							label: i18n.__( 'Navigation Arrows' ),
							value: attributes.arrowsColor,
							onChange: function( newColor) {
								props.setAttributes( { arrowsColor: newColor } );
							},
						}
					);
				}

				return colors;
			}

			return [
				el(
					InspectorControls,
					{ 
						key: 'gbt_18_sk_slider_inspector'
					},
					el(
						'div',
						{
							className: 'main-inspector-wrapper',
						},
						el(
							ToggleControl,
							{
								key: "gbt_18_sk_slider_full_height",
								label: i18n.__( 'Full Height' ),
								checked: attributes.fullHeight,
								onChange: function() {
									props.setAttributes( { fullHeight: ! attributes.fullHeight } );
								},
							}
						),
						attributes.fullHeight === false &&
						el(
							RangeControl,
							{
								key: "gbt_18_sk_slider_custom_height",
								value: attributes.customHeight,
								allowReset: false,
								initialPosition: 800,
								min: 100,
								max: 1000,
								label: i18n.__( 'Custom Desktop Height' ),
								onChange: function( newNumber ) {
									props.setAttributes( { customHeight: newNumber } );
								},
							}
						),
						el(
							ToggleControl,
							{
								key: "gbt_18_sk_slider_pagination",
	              				label: i18n.__( 'Pagination Bullets' ),
	              				checked: attributes.pagination,
	              				onChange: function() {
									props.setAttributes( { pagination: ! attributes.pagination } );
								},
							}
						),
						el(
							ToggleControl,
							{
								key: "gbt_18_sk_slider_arrows",
	              				label: i18n.__( 'Navigation Arrows' ),
	              				checked: attributes.arrows,
	              				onChange: function() {
									props.setAttributes( { arrows: ! attributes.arrows } );
								},
							}
						),
						el(
							ColorSettings,
							{
								key: 'gbt_18_sk_slider_arrows_color',
								title: i18n.__( 'Colors' ),
								initialOpen: false,
								colorSettings: getColors()
							},
						),
					),
				),
				el( 
					'div',
					{ 
						key: 		'gbt_18_sk_editor_slider_wrapper',
						className: 	'gbt_18_sk_editor_slider_wrapper'
					},
					el(
						'h4',
						{
							key: 		'gbt_18_sk_editor_slider_title',
							className: 	'gbt_18_sk_editor_slider_title',
						},
						el(
							'span',
							{
								key: 'gbt_18_sk_editor_slider_dashicon',
								className: 'gbt_18_sk_editor_slider_dashicon dashicon dashicons-slides',
							},
						),
						i18n.__('Slider')
					),
				),
				el(
					InnerBlock,
					{
						key: 'gbt_18_sk_editor_slider_inner_blocks',
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
						key: 'gbt_18_sk_slider_wrapper',
						className: 'gbt_18_sk_slider_wrapper'
					},
					el( 
						'div',
						{
							key: 'gbt_18_sk_slider_container',
							className: attributes.fullHeight ? 'shortcode_getbowtied_slider gbt_18_sk_slider_container swiper-container full_height' : 'shortcode_getbowtied_slider gbt_18_sk_slider_container swiper-container',
							style:
							{
								height: attributes.customHeight + 'px'
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
						!! attributes.arrows && el(
							'div',
							{
								key: 'swiper-button-prev',
								className: 'swiper-button-prev',
								style:
								{
									color: attributes.arrowsColor
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
						!! attributes.arrows && el(
							'div',
							{
								key: 'swiper-button-next',
								className: 'swiper-button-next',
								style:
								{
									color: attributes.arrowsColor
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
						!! attributes.pagination && el(
							'div',
							{
								key: 'shortcode-slider-pagination',
								className: 'quickview-pagination shortcode-slider-pagination',
								style:
								{
									color: attributes.paginationColor
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
	window.wp.element
);