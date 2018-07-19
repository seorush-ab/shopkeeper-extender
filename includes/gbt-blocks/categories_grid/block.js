( function( blocks, components, editor, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = blocks.registerBlockType;

	var InspectorControls 	= editor.InspectorControls;

	var TextControl 		= components.TextControl;
	var RadioControl        = components.RadioControl;
	var SelectControl		= components.SelectControl;
	var ToggleControl		= components.ToggleControl;

	/* Register Block */
	registerBlockType( 'getbowtied/categories-grid', {
		title: i18n.__( 'Product Categories - Grid' ),
		icon: 'layout',
		category: 'shopkeeper',
		supports: {
			align: [ 'center', 'wide', 'full' ],
		},
		attributes: {
			product_categories_selection: {
				type: 'string',
				default: 'auto'
			},
			ids: {
				type: 'string',
				default: '',
			},
			number: {
				type: 'number',
				default: '12'
			},
			order: {
				type: 'string',
				default: 'asc'
			},
			hide_empty: {
				type: 'boolean',
				default: false
			},
			parent: {
				type: 'string',
				default: '0'
			},
			grid: {
				type: 'string',
				default: ''
			}
		},

		edit: function( props ) {

			var attributes = props.attributes;

			function getCategoriesGrid( categories, ids, number, order, hide_empty, parent ) {

				categories 	= categories || attributes.product_categories_selection;
				ids 		= ids 		 || attributes.ids;
				number 		= number 	 || attributes.number;
				order 		= order 	 || attributes.order;
				parent 		= parent 	 || attributes.parent;

				var data = {
					action 		: 'getbowtied_render_frontend_categories_grid',
					attributes  : {
						'product_categories_selection' : categories,
						'ids'						   : ids,
						'number'					   : number,
						'order'						   : order,
						'hide_empty'				   : Number(hide_empty),
						'parent'					   : parent
					}
				};

				jQuery.post( 'admin-ajax.php', data, function(response) { 
					response = jQuery.parseJSON(response);
					props.setAttributes( { grid: response } );
				});	
			}

			return [
				el(
					InspectorControls,
					{
						key: 'categories-grid-inspector'
					},
					el(
						'div',
						{
							className: 'categories-grid-block-description',
							key: 'categories-grid-description'
						},
						el( 
							'hr',
							{
								key: 'categories-grid-hr'
							},
						),
					),
					el(
						SelectControl,
						{
							key: 'categories-grid-selection',
							options:
								[
									{ value: 'auto', label: 'Display X Number of Product Categories'},
									{ value: 'ids',  label: 'Manually Pick Categories'				},
								],
              				label: i18n.__( 'Product Categories' ),
              				value: attributes.product_categories_selection,
              				onChange: function( newSelection ) {
              					props.setAttributes( { product_categories_selection: newSelection } );
								getCategoriesGrid( newSelection, null, null, null, attributes.hide_empty, null );
							},
						}
					),
					attributes.product_categories_selection == 'ids' &&
					el(
						TextControl,
						{
							key: 'categories-grid-ids-option',
              				label: i18n.__( 'Category IDs' ),
              				type: 'text',
              				help: i18n.__('Insert product categories IDs between commas. Example: 12,56,76'),
              				value: attributes.ids,
              				onChange: function( newIds ) {
              					props.setAttributes( { ids: newIds } );
								getCategoriesGrid( null, newIds, null, null, attributes.hide_empty, null );
							},
						},
					),
					attributes.product_categories_selection == 'auto' &&
					el( 
						'div',
						{
							key: 'categories-grid-number-option'
						},
						el(
							SelectControl,
							{
								id: "categories-grid-display",
								key: 'cat-display',
								options: [{value: '0', label: 'Parent Categories Only'}, {value: '1', label: 'Parent Categories + Subcategories'}],
	              				label: i18n.__( 'Categories Display' ),
	              				value: attributes.parent,
	              				onChange: function( newParent ) {
	              					props.setAttributes( { parent: newParent } );
									getCategoriesGrid( null, null, null, null, attributes.hide_empty, newParent);
								},
							}
						),
						el(
							TextControl,
							{
								key: 'categories-grid-display-number',
	              				label: i18n.__( 'How many product categories to display?' ),
	              				type: 'text',
	              				value: attributes.number,
	              				onChange: function( newNumber ) {
	              					props.setAttributes( { number: newNumber } );
	              					setTimeout(function() {
	              						getCategoriesGrid( null, null, newNumber, null, attributes.hide_empty, null );
	              					}, 500);
								},
							},
						),
						el(
							SelectControl,
							{
								key: 'categories-grid-order',
								options:
								[
									{ value: 'asc',  label: 'Ascending'  },
									{ value: 'desc', label: 'Descending' }
								],
	              				label: i18n.__( 'Alphabetical Order' ),
	              				value: attributes.order,
	              				onChange: function( newOrder ) {
	              					props.setAttributes( { order: newOrder } );
									getCategoriesGrid( null, null, null, newOrder, attributes.hide_empty, null );
								},
							}
						),
					),
					el(
						ToggleControl,
						{
							key: "categories-grid-hide-empty",
              				label: i18n.__( 'Hide Empty' ),
              				checked: attributes.hide_empty,
              				onChange: function() {
              					props.setAttributes( { hide_empty: ! attributes.hide_empty } );
								getCategoriesGrid( null, null, null, null, !attributes.hide_empty, null );
							},
						}
					),
				),
				eval( attributes.grid ),
				attributes.grid == '' && getCategoriesGrid( null, null, null, null, attributes.hide_empty, null )
			];
		},

		save: function( props ) {
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