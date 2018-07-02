( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;

	var InspectorControls 	= wp.editor.InspectorControls;

	var TextControl 		= wp.components.TextControl;
	var RadioControl        = wp.components.RadioControl;
	var SelectControl		= wp.components.SelectControl;
	var ToggleControl		= wp.components.ToggleControl;
	var RangeControl		= wp.components.RangeControl;

	var categories_list = [];

	function escapeHtml(text) {
	  	return text
	    	.replace("&amp;", '&')
	    	.replace("&lt;", '<')
	    	.replace("&gt;", '>')
	     	.replace("&quot;", '"')
	    	.replace("&#039;", "'");
	}

	async function getCategories(categories_list) { 
	 	const categories = await wp.apiRequest( { path: '/wp/v2/categories?per_page=-1' } );

	 	var i;
	 	categories_list[0] = {value: '0', label: "All Categories"};
	 	for(i = 0; i < categories.length; i++) {
	 		var category = {value: categories[i]['id'], label: escapeHtml(categories[i]['name'])};
	 		categories_list[i+1] = category;
	 	}
	 } 

	getCategories(categories_list);

	/* Register Block */
	registerBlockType( 'getbowtied/posts-slider', {
		title: i18n.__( 'Posts Slider' ),
		icon: 'media-document',
		category: 'common',
		attributes: {
			number: {
				type: 'integer',
				default: 12
			},
			category: {
				type: 'string',
				default: 'All Categories'
			},
			categories : {
				type: 'array',
				default: categories_list
			}
		},

		edit: function( props ) {

			var attributes = props.attributes;

			return [
				el(
					InspectorControls,
					{ key: 'inspector' },
					el( 'div', { className: 'components-block-description' }, // A brief description of our block in the inspector.
						el( 'hr', {}, ),
						el( 'b', {}, i18n.__( 'Display the latest posts in the blog' ) ),
						el( 'hr', {}, ),
					),
					el(
						RangeControl,
						{
							id: "posts-number",
							value: attributes.number,
							allowReset: false,
							label: i18n.__( 'Number of Posts' ),
							onChange: function( newNumber ) {
								props.setAttributes( { number: newNumber } );
							},
						}
					),
					el(
						SelectControl,
						{
							id: "category",
							options: attributes.categories,
              				label: i18n.__( 'Category' ),
              				value: attributes.category,
              				onChange: function( newOrder ) {
              					props.setAttributes( { category: newOrder } );
							},
						}
					),
				),
				el( 'h2', { className: 'widget-title', key: "title" }, i18n.__( 'Posts Slider' ) ),
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