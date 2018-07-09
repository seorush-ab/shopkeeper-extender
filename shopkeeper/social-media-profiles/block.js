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
				type: 	 'string',
				default: 'left'
			},
			render_socials: {
				type: 'string',
				default: '',
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;

			function getSocialMediaIcons( items_align ) {

				items_align = items_align || attributes.items_align;

				var data = {
					action 		: 'getbowtied_render_backend_socials',
					attributes  : { 'items_align' : items_align	}
				};

				jQuery.post( 'admin-ajax.php', data, function(response) { 
					response = jQuery.parseJSON(response);
					props.setAttributes( { render_socials: response } );
				});	
			}

			return [
				el(
					InspectorControls,
					{ 
						key: 'social-media-inspector'
					},
					el( 'div',
						{ 
							key: 'social-media-block-description',
							className: 'socials-block-description'
						},
						el( 'hr',
							{ 
								key: 'social-media-block-description-hr' 
							},
						),
					),
					el(
						AlignmentToolbar, 
						{
							key: 'social-media-alignment',
							value: attributes.items_align,
							onChange: function( newAlignment ) {
								props.setAttributes( { items_align: newAlignment } );
								getSocialMediaIcons( newAlignment );
							}
						} 
					),
				),
				eval( attributes.render_socials ),
				attributes.render_socials == '' && getSocialMediaIcons( 'left' )	
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