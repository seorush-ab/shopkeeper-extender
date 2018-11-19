( function( wp, blocks, i18n, element ) {

	const el = element.createElement;

	/* Blocks */
	const registerBlockType   	= wp.blocks.registerBlockType;

	const InspectorControls 	= wp.editor.InspectorControls;

	const TextControl 			= wp.components.TextControl;
	const RadioControl       	= wp.components.RadioControl;
	const SelectControl			= wp.components.SelectControl;
	const ToggleControl			= wp.components.ToggleControl;
	const RangeControl			= wp.components.RangeControl;
	const SVG 					= wp.components.SVG;
	const Path 					= wp.components.Path;

	const apiFetch 				= wp.apiFetch;

	/* Register Block */
	registerBlockType( 'getbowtied/sk-portfolio', {
		title: i18n.__( 'Portfolio' ),
		icon: 'format-gallery',
		category: 'shopkeeper',
		supports: {
			align: [ 'center', 'wide', 'full' ],
		},
		styles: [
			{ name: 'default', label:  'Equal Boxes', isDefault: true },
			{ name: 'masonry_1', label:  'Masonry Style V1' },
			{ name: 'masonry_2', label:  'Masonry Style V2' },
			{ name: 'masonry_3', label:  'Masonry Style V3' },
		],
		attributes: {
			productIDs: {
				type: 'string',
				default: '',
			},
			/* Products source */
			result: {
				type: 'array',
				default: [],
			},
			queryProducts: {
				type: 'string',
				default: '',
			},
			queryProductsLast: {
				type: 'string',
				default: '',
			},
			/* loader */
			isLoading: {
				type: 'bool',
				default: false,
			},
			/* Display by category */
			queryCategoryOptions: {
				type: 'array',
				default: [],
			},
			queryCategorySelected: {
				type: 'array',
				default: [],
			},
			selectedCategories: {
				type: 'array',
				default: [],
			},
			/* First Load */
			firstLoad: {
				type: 'boolean',
				default: true
			},
			firstLoad1: {
				type: 'boolean',
				default: true
			},
			/* Number of Portfolio Items */
			number: {
				type: 'number',
				default: '12'
			},
			/* Columns */
			columns: {
				type: 'number',
				default: '3'
			},
			/* Filters */
			showFilters: {
				type: 'boolean',
				default: false,
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;
			let className  = props.className;

			if( className.indexOf('is-style-') == -1 ) { className += ' is-style-default'; }

			//==============================================================================
			//	Helper functions
			//==============================================================================

			function getWrapperClass() {
				if( className.indexOf('is-style-default') >= 0 ) {
					return 'gbt_18_sk_editor_portfolio_wrapper items_per_row_' + attributes.columns;
				}
				return 'gbt_18_sk_editor_portfolio_wrapper';
			}

			function _sortCategories( index, arr, newarr = [], level = 0) {
				for ( let i = 0; i < arr.length; i++ ) {
					if ( arr[i].parent == index) {
						arr[i].level = level;
						newarr.push(arr[i]);
						_sortCategories(arr[i].value, arr, newarr, level + 1 );
					}
				}

				return newarr;
			}

			function _isChecked( needle, haystack ) {
				let idx = haystack.indexOf(needle.toString());
				if ( idx != - 1) {
					return true;
				}
				return false;
			}

			function _categoryClassName(parent, value) {
				if ( parent == 0) {
					return 'parent parent-' + value;
				} else {
					return 'child child-' + parent;
				}
			}

			function _isLoadingText(){
				if ( attributes.isLoading  === false ) {
					return i18n.__('Update');
				} else {
					return i18n.__('Updating');
				}
			}

			function _isDonePossible() {
				return ( (attributes.queryProducts.length == 0) || (attributes.queryProducts === attributes.queryProductsLast) );
			}

			function _isLoading() {
				if ( attributes.isLoading  === true ) {
					return 'is-busy';
				} else {
					return '';
				}
			}

			//==============================================================================
			//	Show portfolio items functions
			//==============================================================================

			function getPortfolioItems() {
				let query = attributes.queryProducts;
				props.setAttributes({ queryProductsLast: query});

				if (query != '') {
					apiFetch({ path: query }).then(function (products) {
						props.setAttributes({ result: products});
						props.setAttributes({ isLoading: false});
						let IDs = '';
						for ( let i = 0; i < products.length; i++) {
							IDs += products[i].id + ',';
						}
						props.setAttributes({ productIDs: IDs});
					});
				}
			}

			function renderResults() {
				if ( attributes.firstLoad1 === true ) {
					apiFetch({ path: '/wp/v2/portfolio-item?per_page=12' }).then(function (portfolio_items) {
						props.setAttributes({ result: portfolio_items });
						props.setAttributes({ firstLoad1: false });
						let query = '/wp/v2/portfolio-item?per_page=12';
						props.setAttributes({queryProducts: query});
						props.setAttributes({ queryProductsLast: query});
						let IDs = '';
						for ( let i = 0; i < portfolio_items.length; i++) {
							IDs += portfolio_items[i].id + ',';
						}
						props.setAttributes({ productIDs: IDs});
					});
				}

				let portfolio_items = attributes.result;
				let postElements = [];
				let wrapper = [];

				if( portfolio_items.length > 0) {

					for ( let i = 0; i < portfolio_items.length; i++ ) {

						let portfolio_image = [];
						if ( portfolio_items[i]['fimg_url'] ) { 
							portfolio_image.push(
								el( 'span',
									{
										key: 		'gbt_18_sk_editor_portfolio_item_thumbnail',
										className: 	'gbt_18_sk_editor_portfolio_item_thumbnail',
										style:
										{
											backgroundImage: 'url(' + portfolio_items[i]['fimg_url'] + ')'
										}
									}
								)
							);
						};
 
						postElements.push(
							el( "div", 
								{
									key: 		'gbt_18_sk_editor_portfolio_item_box_' + portfolio_items[i].id, 
									className: 	'gbt_18_sk_editor_portfolio_item_box'
								},
								el( 'a',
									{
										key: 		'gbt_18_sk_editor_portfolio_item_link',
										className: 	'gbt_18_sk_editor_portfolio_item_link',
										style:
										{
											backgroundColor: portfolio_items[i]['color_meta_box']
										}
									},
									el( "div", 
										{
											key: 		'gbt_18_sk_editor_portfolio_item_content', 
											className: 	'gbt_18_sk_editor_portfolio_item_content'
										},
										portfolio_image,
										el( 'h2',
											{
												key: 'gbt_18_sk_editor_portfolio_item_title',
												className: 'gbt_18_sk_editor_portfolio_item_title',
												dangerouslySetInnerHTML: { __html: portfolio_items[i]['title']['rendered'] }
											}
										),
									)
								)
							)
						);
					}
				} 

				wrapper.push(
					el( 'div',
						{
							key: 		'gbt_18_sk_editor_portfolio_items',
							className: 	'gbt_18_sk_editor_portfolio_items'
						},
						postElements
					)
				);

				return wrapper;
			}

			//==============================================================================
			//	Display Categories
			//==============================================================================

			function getCategories() {

				if( attributes.firstLoad == true) {

					let categories_list = [];
					let options = [];
					let sorted = [];
				
					apiFetch({ path: '/wp/v2/portfolio-category?per_page=-1' }).then(function (categories) {

					 	for( let i = 0; i < categories.length; i++) {
		        			options[i] = {'label': categories[i].name.replace(/&amp;/g, '&'), 'value': categories[i].id, 'parent': categories[i].parent, 'count': categories[i].count };
					 	}

					 	sorted = _sortCategories(0, options);
			        	props.setAttributes({queryCategoryOptions: sorted });
			        	props.setAttributes({firstLoad: false });
					});
				}
			}

			function renderCategories( parent = 0, level = 0 ) {

				getCategories();

				let categoryElements = [];
				let catArr = attributes.queryCategoryOptions;
				if ( catArr.length > 0 )
				{
					for ( let i = 0; i < catArr.length; i++ ) {
						if ( catArr[i].parent !=  parent ) { continue; };
						categoryElements.push(
							el(
								'li',
								{
									className: 'level-' + catArr[i].level,
								},
								el(
								'label',
									{
										className: _categoryClassName( catArr[i].parent, catArr[i].value ) + ' ' + catArr[i].level,
									},
									el(
									'input', 
										{
											type:  'checkbox',
											key:   'category-checkbox-' + catArr[i].value,
											value: catArr[i].value,
											'data-index': i,
											'data-parent': catArr[i].parent,
											checked: _isChecked(catArr[i].value, attributes.queryCategorySelected),
											onChange: function onChange(evt){
												let idx = Number(evt.target.dataset.index);
												if (evt.target.checked === true) {
													let qCS = attributes.queryCategorySelected;
													let index = qCS.indexOf(evt.target.value);
													if (index == -1) {
														qCS.push(evt.target.value);
													}
													for (let j = idx + 1; j < catArr.length - 1; j++) {
														if ( catArr[idx].level < catArr[j].level) {
															let index2 = qCS.indexOf(catArr[j].value.toString());
															if (index2 == -1) {
																qCS.push(catArr[j].value.toString());
															}
														} else {
															break;
														}
													}
													props.setAttributes({ queryCategorySelected: qCS });
												} else {
													let qCS = attributes.queryCategorySelected;
													let index = qCS.indexOf(evt.target.value);
													if (index > -1) {
													  qCS.splice(index, 1);
													}
													for (let j = idx + 1; j < catArr.length - 1; j++) {
														if ( catArr[idx].level < catArr[j].level) {
															let index2 = qCS.indexOf(catArr[j].value.toString());
															if (index2 > -1) {
																qCS.splice(index2, 1);
															}
															} else {
															break;
														}
													}
													props.setAttributes({ queryCategorySelected: qCS });
												};
												if ( attributes.queryCategorySelected.length > 0 ) {
													let query = '/wp/v2/portfolio-item?portfolio-category=' + attributes.queryCategorySelected.join(',') + '&per_page=' + attributes.number;
													props.setAttributes({ queryProducts: query});
												} else {
													props.setAttributes({ queryProducts: '' });
												}
											},
										}, 
									),
									catArr[i].label,
									el(
										'sup',
										{
											className: 'category-count',
										},
										catArr[i].count,
									),
								),
								renderCategories( catArr[i].value, level+1)
							),
						);
					} 
				}	
				if (categoryElements.length > 0 ) {
					let wrapper = el('ul', {className: 'level-' + level}, categoryElements);
					return wrapper;		
				} else {
					return;
				}
			}

			return [
				el(
					InspectorControls,
					{
						key: 'sk-portfolio-inspector'
					},
					el(
						'div',
						{
							className: 'products-main-inspector-wrapper',
						},
						el( 'label', { className: 'components-base-control__label' }, i18n.__('Categories:') ),
						el(
							'div',
							{
								className: 'category-result-wrapper',
							},
							renderCategories(),
						),
						el(
							RangeControl,
							{
								key: "sk-portfolio-number",
								className: 'range-wrapper',
								value: attributes.number,
								allowReset: false,
								initialPosition: 12,
								min: 1,
								max: 20,
								label: i18n.__( 'Number of Portfolio Items' ),
								onChange: function onChange(newNumber){
									props.setAttributes( { number: newNumber } );
									let query = '/wp/v2/portfolio-item?portfolio-category=' + attributes.queryCategorySelected.join(',') + '&per_page=' + newNumber;
									props.setAttributes({ queryProducts: query});
								},
							}
						),
						el(
							'button',
							{
								className: 'render-results components-button is-button is-default is-primary is-large ' + _isLoading(),
								disabled: _isDonePossible(),
								onClick: function onChange(e) {
									props.setAttributes({ isLoading: true });
									getPortfolioItems();
								},
							},
							_isLoadingText(),
						),
						el( 'hr', {} ),
						el(
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
						props.className.indexOf('is-style-default') !== -1 && el(
							RangeControl,
							{
								key: "sk-portfolio-columns",
								value: attributes.columns,
								allowReset: false,
								initialPosition: 3,
								min: 2,
								max: 5,
								label: i18n.__( 'Columns' ),
								onChange: function( newColumns ) {
									props.setAttributes( { columns: newColumns } );
								},
							}
						),
					),
				),
				el( 'div',
					{
						key: 		'gbt_18_sk_editor_portfolio',
						className: 	'gbt_18_sk_editor_portfolio ' + className
					},
					el( 'div',
						{
							key: 		'gbt_18_sk_editor_portfolio_wrapper',
							className: 	getWrapperClass(),
						},
						renderResults(),
					),
				)
			];
		},

		save: function( props ) {

			let attributes = props.attributes;

			attributes.className = attributes.className || 'is-style-default';

			function renderFrontend() {

				let portfolio_items = attributes.result;
				let postElements = [];
				let wrapper = [];

				if( portfolio_items.length > 0) {

					for ( let i = 0; i < portfolio_items.length; i++ ) {

						let portfolio_image = [];
						if ( portfolio_items[i]['fimg_url'] ) { 
							portfolio_image.push(
								el( 'span',
									{
										key: 		'portfolio-thumb',
										className: 	'portfolio-thumb hover-effect-thumb',
										style:
										{
											backgroundImage: 'url(' + portfolio_items[i]['fimg_url'] + ')'
										}
									}
								)
							);
						};

						let categories = '';
						let cat_slug_classes = '';
						for( let j = 0; j < portfolio_items[i]['categories'].length; j++ ) {
							categories +=  portfolio_items[i]['categories'][j]['name'];
							cat_slug_classes += portfolio_items[i]['categories'][j]['slug'] + ' ';
							if( (j+1) < portfolio_items[i]['categories'].length ) categories += ', ';
						}
 
						postElements.push(
							el( "div", 
								{
									key: 		'portfolio-box_' + portfolio_items[i].id, 
									className: 	'portfolio-box hidden ' + cat_slug_classes
								},
								el( 'a',
									{
										key: 		'portfolio-box-inner-link',
										className: 	'portfolio-box-inner hover-effect-link',
										href: portfolio_items[i]['link'],
										style:
										{
											backgroundColor: portfolio_items[i]['color_meta_box']
										}
									},
									el( "div", 
										{
											key: 		'portfolio-content-wrapper', 
											className: 	'portfolio-content-wrapper hover-effect-content'
										},
										portfolio_image,
										el( 'h2',
											{
												key: 'portfolio-title',
												className: 'portfolio-title hover-effect-title',
											},
											i18n.__( portfolio_items[i]['title']['rendered'] )
										),
										el( 'p',
											{
												key: 'portfolio-categories',
												className: 'portfolio-categories hover-effect-text',
											},
											categories
										)
									)
								)
							)
						);
					}
				} 

				wrapper.push(
					el( 'div',
						{
							key: 		'portfolio-isotope',
							className: 	'portfolio-isotope',
						},
						el( 'div',
							{
								key: 		'portfolio-grid-sizer',
								className: 	'portfolio-grid-sizer'
							}
						),
						el( 'div',
						{
							key: 		'portfolio-grid-items',
							className: 	'portfolio-grid-items'
						},
						postElements
						)
					)
				);
				
				return wrapper;
			}

			function getColumns() {
				if ( attributes.className.indexOf('is-style-default') >= 0 ) {
					return 'default_grid items_per_row_' + attributes.columns;
				} else {
					return '';
				}
			}

			function getFilters() {

				let filters = [];
				let wrapper = [];
				let categories = [];

				let portfolio_items = attributes.result;
				if( portfolio_items.length > 0) {
					for ( let i = 0; i < portfolio_items.length; i++ ) {
						for ( let j = 0; j < portfolio_items[i]['categories'].length; j++ ) {
							categories.push(portfolio_items[i]['categories'][j]);
						}
					}
				}

				categories = Array.from(new Set(categories.map(JSON.stringify))).map(JSON.parse);
				categories.sort(function(a,b) {
				    if ( a.name < b.name )
				        return -1;
				    if ( a.name > b.name )
				        return 1;
				    return 0;
				});

				if( categories.length > 0 ) {

					for( let i = 0; i < categories.length; i++ ) {
						filters.push(
							el( 'li',
								{
									key: 'filter-item-' + i,
									className: 'filter-item',
									'data-filter': '.' + categories[i]['slug']
								},
								categories[i]['name']
							)
						);
					}

					wrapper.push(
						el( 'div',
							{
								key: 		'portfolio-filters',
								className: 	'portfolio-filters',
							},
							el( 'ul',
								{
									key: 		'filters-group',
									className: 	'filters-group list-centered',
								},
								el( 'li',
									{
										key: 'filter-item-all',
										className: 'filter-item is-checked',
										'data-filter': '*',
									},
									i18n.__( 'Show all' )
								),
								filters
							)
						)
					);

					return wrapper;
				}

				return '';
			}

			return el( 'div',
				{
					key: 		'gbt_18_sk_portfolio',
					className: 	'gbt_18_sk_portfolio wp-block-gbt-portfolio ' + attributes.className
				},
				el( 'div',
					{
						key: 		'gbt_18_sk_portfolio_wrapper',
						className: 	'portfolio-isotope-container gbt_18_sk_portfolio_container ' + getColumns()
					},
					attributes.showFilters === true && getFilters(),
					renderFrontend(),
				),
			);
		},
	} );

} )(
	window.wp,
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element
);