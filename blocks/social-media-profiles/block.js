( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;

	var InspectorControls 	= wp.editor.InspectorControls;
	var AlignmentToolbar	= wp.editor.AlignmentToolbar;

	var TextControl 		= wp.components.TextControl;

	/* Register Block */
	registerBlockType( 'getbowtied/socials', {
		title: i18n.__( 'Social Media Profiles' ),
		icon: 'groups',
		category: 'common',
		attributes: {
			items_align: {
				type: 'string',
				default: 'left'
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;

			return [
				el(
					InspectorControls,
					{ key: 'inspector' },
					el( 'div', { key: 'socials-block-description', className: 'socials-block-description' },
						el( 'hr', { key: 'socials-block-description-hr' }, ),
					),
					el(
						AlignmentToolbar, 
						{
							key: 'socials-alignment',
							value: attributes.items_align,
							onChange: function( newAlignment ) {
								props.setAttributes( { items_align: newAlignment } )
							}
						} 
					),
				),
				el( 'h2', { key: 'socials-title', className: 'widget-title' }, i18n.__( 'Social Media Profiles' ) ),
				el( 'div', { key: 'socials-desc-image', className: 'social-image' } ),
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
	jQuery
);