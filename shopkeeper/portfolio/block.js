( function( blocks, i18n, element ) {

	var el = element.createElement;

	/* Blocks */
	var registerBlockType   = wp.blocks.registerBlockType;
		
	var InspectorControls 	= wp.editor.InspectorControls;
	var RichText			= wp.editor.RichText;
	var BlockControls		= wp.editor.BlockControls;

	var TextControl 		= wp.components.TextControl;
	var ToggleControl		= wp.components.ToggleControl;
	var RangeControl		= wp.components.RangeControl;
	var SelectControl		= wp.components.SelectControl;

	var categories_list = [];

	function getCategories()
	{
		var data = {
			action : 'getbowtied_render_portfolio_categories'
		};

		jQuery.post( 'admin-ajax.php', data, function(response) { 
			response = jQuery.parseJSON(response);
			categories_list = response;
		});	
	}

	getCategories();

	/* Register Block */
	registerBlockType( 'getbowtied/portfolio', {
		title: i18n.__( 'Portfolio' ),
		icon: 'format-gallery',
		category: 'shopkeeper',
		attributes: {
			itemsNumber: {
				type: 'integer',
				default: 6,
			},
			category: {
				type: 'string',
				default: '',
			},
			showFilters: {
				type: 'boolean',
				default: true,
			},
			orderBy: {
				type: 'string',
				default: 'date',
			},
			order: {
				type: 'string',
				default: 'asc',
			},
			grid: {
				type: 'string',
				default: 'default',
			},
			itemsPerRow: {
				type: 'integer',
				default: 3,
			},
			categories : {
				type: 'array',
				default: []
			},
			render_portfolio: {
				type: 'string',
				default: '',
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;
			setTimeout(function(){ 
				props.setAttributes( { categories: categories_list } );
			}, 1000 );

			function getPortfolioGrid( itemsNumber, category, orderBy, order, grid, itemsPerRow ) {

				itemsNumber 	= itemsNumber 	|| attributes.itemsNumber;
				category 		= category 		|| attributes.category;
				orderBy 		= orderBy 	 	|| attributes.orderBy;
				order 			= order 	 	|| attributes.order;
				grid 			= grid 	 		|| attributes.grid;
				itemsPerRow 	= itemsPerRow 	|| attributes.itemsPerRow;

				var data = {
					action 		: 'getbowtied_render_backend_portfolio',
					attributes  : {
						'itemsNumber' 		: itemsNumber,
						'category'			: category,
						'orderBy'			: orderBy,
						'order'				: order,
						'grid'				: grid,
						'itemsPerRow' 		: itemsPerRow
					}
				};

				jQuery.post( 'admin-ajax.php', data, function(response) { 
					response = jQuery.parseJSON(response);
					props.setAttributes( { render_portfolio: response } );
				});	
			}

			var grid_layouts =
			[
				{ value: 'default', label: 'Default - Equal Boxes' },
				{ value: 'grid1', 	label: 'Masonry Style - V1' },
				{ value: 'grid2', 	label: 'Masonry Style - V2' },
				{ value: 'grid3',	label: 'Masonry Style - V3' }
			];

			return [
				el(
					InspectorControls,
					{ key: 'inspector' },
					el( 'hr', { key: 'portfolio-hr' } ),
					el(
						TextControl,
						{
							key: 'portfolio-items',
							type: 'number',
							label: i18n.__( 'How many portfolio items would you like to show?' ),
							value: attributes.itemsNumber,
							onChange: function( newNumber ) {
								props.setAttributes( { itemsNumber: newNumber } );
								getPortfolioGrid( newNumber, null, null, null, null, null );
							},
						}
					),
					el(
						SelectControl,
						{
							key: 'portfolio-category',
							options: attributes.categories,
              				label: i18n.__( 'Portfolio Category' ),
              				value: attributes.category,
              				onChange: function( newCategory ) {
              					props.setAttributes( { category: newCategory } );
              					getPortfolioGrid( null, newCategory, null, null, null, null );
							},
						}
					),
					attributes.category == '' && el(
						ToggleControl,
						{
							key: "portfolio-filters-toggle",
              				label: i18n.__( 'Show Filters?' ),
              				checked: attributes.showFilters,
              				onChange: function() {
								props.setAttributes( { showFilters: ! attributes.showFilters } );
							},
						}
					),
					el(
						SelectControl,
						{
							key: 'portfolio-orderby',
							options: [{ value: 'date', label: 'Date' }, { value: 'alphabetical', label: 'Alphabetical' }],
              				label: i18n.__( 'Order By' ),
              				value: attributes.orderBy,
              				onChange: function( newOrderBy ) {
              					props.setAttributes( { orderBy: newOrderBy } );
              					getPortfolioGrid( null, null, newOrderBy, null, null, null );
							},
						}
					),
					el(
						SelectControl,
						{
							key: 'portfolio-order',
							options: [{ value: 'asc', label: 'Ascending' }, { value: 'desc', label: 'Descending' }],
              				label: i18n.__( 'Order' ),
              				value: attributes.order,
              				onChange: function( newOrder ) {
              					props.setAttributes( { order: newOrder } );
              					getPortfolioGrid( null, null, null, newOrder, null, null );
							},
						}
					),
					el(
						SelectControl,
						{
							key: 'portfolio-grid',
							options: grid_layouts,
              				label: i18n.__( 'Grid Layout Styles' ),
              				value: attributes.grid,
              				onChange: function( newGrid ) {
              					props.setAttributes( { grid: newGrid } );
              					getPortfolioGrid( null, null, null, null, newGrid, null );
							},
						}
					),
					attributes.grid == 'default' && el(
						TextControl,
						{
							key: 'portfolio-items-per-row',
							type: 'number',
							label: i18n.__( 'Items per Row' ),
							value: attributes.itemsPerRow,
							onChange: function( newNumber ) {
								props.setAttributes( { itemsPerRow: newNumber } );
								getPortfolioGrid( null, null, null, null, null, newNumber );
							},
						}
					),
				),
				el( 'h2', { key: 'block-title', className: 'block-title' }, i18n.__( 'Portfolio' ) ),
				eval( attributes.render_portfolio ),
				attributes.render_portfolio == '' && getPortfolioGrid( null, null, null, null, null, null )		
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