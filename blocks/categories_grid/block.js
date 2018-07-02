( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;

	var InspectorControls 	= wp.editor.InspectorControls;

	var TextControl 		= wp.components.TextControl;
	var RadioControl        = wp.components.RadioControl;
	var SelectControl		= wp.components.SelectControl;
	var ToggleControl		= wp.components.ToggleControl;

	/* Register Block */
	registerBlockType( 'getbowtied/categories-grid', {
		title: i18n.__( 'Product Categories - Grid' ),
		icon: 'grid-view',
		category: 'common',
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
				type: 'integer',
				default: 12
			},
			order: {
				type: 'string',
				default: 'asc'
			},
			hide_empty: {
				type: 'boolean',
				default: 1
			},
			parent: {
				type: 'string',
				default: '0'
			},
			grid: {
				type: 'string',
				default: "el('div',{key: '1'},el( 'div', { className: 'grid-1', key:'2' } ),el( 'div', { className: 'grid-2',key:'3' },el( 'div', { className: 'grid-2a',key:'4' } ),el( 'div', { className: 'grid-2b',key:'5' } )))"
			}
		},

		edit: function( props ) {

			var attributes = props.attributes;

			function getCategoriesGrid( prodCatSelection, ids, number, order, hide_empty, parent ) {

				prodCatSelection = prodCatSelection || attributes.product_categories_selection;
				ids = ids || attributes.ids;
				number = number || attributes.number;
				order = order || attributes.order;
				parent = parent || attributes.parent;

				if(hide_empty == null) hide_empty = attributes.hide_empty;
				if(hide_empty) { hide_empty = 1; } else { hide_empty = 0 };

				var data = {
					action 		: 'getbowtied_render_frontend_categories_grid',
					attributes  : {
						'product_categories_selection' : prodCatSelection,
						'ids'						   : ids,
						'number'					   : number,
						'order'						   : order,
						'hide_empty'				   : hide_empty,
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
					{ key: 'inspector' },
					el( 'div', { className: 'components-block-description', key: 'block-description' }, // A brief description of our block in the inspector.
						el( 'hr', { key: 'hr' },),
					),
					el(
						SelectControl,
						{
							id: "categories-pick-radio-option",
							key: 'cat-select',
							options: [{value: 'auto', label: 'Display X Number of Product Categories'}, {value: 'ids', label: 'Manually Pick Categories'}],
              				label: i18n.__( 'Product Categories' ),
              				value: attributes.product_categories_selection,
              				onChange: function( newSelection ) {
              					props.setAttributes( { product_categories_selection: newSelection } );
								getCategoriesGrid( newSelection, null, null, null, null, null );
							},
						}
					),
					attributes.product_categories_selection == 'ids' &&
					el(
						TextControl,
						{
							id: "categories-ids-option",
							key: 'cat-ids',
              				label: i18n.__( 'Category IDs' ),
              				type: 'text',
              				help: i18n.__('Insert product categories IDs between commas. Example: 12,56,76'),
              				value: attributes.ids,
              				onChange: function( newIds ) {
              					props.setAttributes( { ids: newIds } );
								getCategoriesGrid( null, newIds, null, null, null, null );
							},
						},
					),
					attributes.product_categories_selection == 'auto' &&
					el( 'div', { key: 'cat-options' },
						el(
							SelectControl,
							{
								id: "categories-display-option",
								key: 'cat-display',
								options: [{value: '0', label: 'Parent Categories Only'}, {value: '1', label: 'Parent Categories + Subcategories'}],
	              				label: i18n.__( 'Categories Display' ),
	              				value: attributes.parent,
	              				onChange: function( newParent ) {
	              					props.setAttributes( { parent: newParent } );
									getCategoriesGrid( null, null, null, null, null, newParent);
								},
							}
						),
						el(
							TextControl,
							{
								id: "categories-ids-option",
								key: 'cat-number',
	              				label: i18n.__( 'How many product categories to display?' ),
	              				type: 'text',
	              				value: attributes.number,
	              				onChange: function( newNumber ) {
	              					props.setAttributes( { number: newNumber } );
	              					setTimeout(function() {
	              						getCategoriesGrid( null, null, newNumber, null, null, null );
	              					}, 500);
								},
							},
						),
						el(
							SelectControl,
							{
								id: "order-radio-option",
								key: 'order',
								options: [{value: 'asc', label: 'Ascending'}, {value: 'desc', label: 'Descending'}],
	              				label: i18n.__( 'Alphabetical Order' ),
	              				value: attributes.order,
	              				onChange: function( newOrder ) {
	              					props.setAttributes( { order: newOrder } );
									getCategoriesGrid( null, null, null, newOrder, null, null );
								},
							}
						),
					),
					el(
						ToggleControl,
						{
							id: "hide-empty-form-toggle",
							key: 'empty',
              				label: i18n.__( 'Hide Empty' ),
              				checked: attributes.hide_empty,
              				onChange: function() {
              					props.setAttributes( { hide_empty: ! attributes.hide_empty } );
								getCategoriesGrid( null, null, null, null, !attributes.hide_empty, null );
							},
						}
					),
				),
				el( 'h2', { className: 'widget-title', key: "title" }, i18n.__( 'Product Categories - Grid' ) ),
				eval( attributes.grid ),
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