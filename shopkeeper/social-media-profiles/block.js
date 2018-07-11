( function( blocks, components, editor, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = blocks.registerBlockType;

	var AlignmentToolbar	= editor.AlignmentToolbar;
	var BlockControls       = editor.BlockControls;

	var TextControl 		= components.TextControl;

	/* Register Block */
	registerBlockType( 'getbowtied/socials', {
		title: i18n.__( 'Social Media Profiles' ),
		icon: 'groups',
		category: 'common',
		attributes: {
			items_align: {
				type: 	 'string',
				default: 'left'
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;

			return [
				el(
					BlockControls,
					{ 
						key: 'social-media-controls'
					},
					el(
						AlignmentToolbar, 
						{
							key: 'social-media-alignment',
							value: attributes.items_align,
							onChange: function( newAlignment ) {
								props.setAttributes( { items_align: newAlignment } );
							}
						} 
					),
				),
				el( 
					'div',
					{ 
						key: 'socials-wrapper-div',
						className: 'socials-wrapper-div'
					},
					el(
						'h4',
						{
							key: 'socials-wrapper-h4',
							className: 'socials-wrapper-h4',
						},
						el(
							'span',
							{
								className: 'dashicon dashicons-share',
							},
						),
						i18n.__('Social Media Icons')
					),
					el(
						'p',
						{
							key: 'socials-wrapper-p',
							className: 'socials-wrapper-p',
						},
						i18n.__('Setup profile links under Appearance > Customize > Social Media')
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
	jQuery
);