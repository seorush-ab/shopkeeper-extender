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
	registerBlockType( 'getbowtied/latest-posts-grid', {
		title: i18n.__( 'Latest Posts Grid' ),
		icon: el( SVG, { xmlns:'http://www.w3.org/2000/svg', viewBox:'0 0 24 24' },
				el( Path, { d:'M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12zM10 9h8v2h-8zm0 3h4v2h-4zm0-6h8v2h-8z' } ) 
			),
		category: 'shopkeeper',
		supports: {
			align: [ 'center', 'wide', 'full' ],
		},
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
			/* First Load */
			firstLoad: {
				type: 'boolean',
				default: true
			},
			firstLoad1: {
				type: 'boolean',
				default: true
			},
			/* Number of Posts */
			number: {
				type: 'number',
				default: '12'
			},
			/* Columns */
			columns: {
				type: 'number',
				default: '3'
			},
		},

		edit: function( props ) {

			var attributes = props.attributes;

			//==============================================================================
			//	Helper functions
			//==============================================================================

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

			function _destroyTempAtts() {
				props.setAttributes({ querySearchString: ''});
				props.setAttributes({ querySearchResults: []});
			}

			//==============================================================================
			//	Show posts functions
			//==============================================================================

			function getPosts() {
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
					apiFetch({ path: '/wp/v2/posts?per_page=12' }).then(function (posts) {
						props.setAttributes({ result: posts });
						props.setAttributes({ firstLoad1: false });
						let query = '/wp/v2/posts?per_page=12';
						props.setAttributes({queryProducts: query});
						props.setAttributes({ queryProductsLast: query});
						let IDs = '';
						for ( let i = 0; i < posts.length; i++) {
							IDs += posts[i].id + ',';
						}
						props.setAttributes({ productIDs: IDs});
					});
				}

				let posts = attributes.result;
				let postElements = [];
				let wrapper = [];

				if( posts.length > 0) {

					for ( let i = 0; i < posts.length; i++ ) {

						let img = '';
						let img_class = 'gbt_18_sk_editor_latest_posts_noimg';
						if ( posts[i]['fimg_url'] ) { img = posts[i]['fimg_url']; img_class = 'gbt_18_sk_editor_latest_posts_with_img'; } else { img_class = 'gbt_18_sk_editor_latest_posts_noimg'; img = ''; };

						postElements.push(
							el( "div", 
								{
									key: 		'gbt_18_sk_editor_latest_posts_item_' + posts[i].id, 
									className: 	'gbt_18_sk_editor_latest_posts_item'
								},
								el( "a", 
									{
										key: 		'gbt_18_sk_editor_latest_posts_item_link',
										className: 	'gbt_18_sk_editor_latest_posts_item_link'
									},
									el( "span", 
										{ 
											key: 		'gbt_18_sk_editor_latest_posts_img_container',
											className: 	'gbt_18_sk_editor_latest_posts_img_container'
										},
										el( "span", 
											{
												key: 'gbt_18_sk_editor_latest_posts_img_overlay',
												className: 'gbt_18_sk_editor_latest_posts_img_overlay'
											}
										),
										el( "span", 
											{
												key: 		'gbt_18_sk_editor_latest_posts_img',
												className: 	'gbt_18_sk_editor_latest_posts_img ' + img_class,
												style: 		{ backgroundImage: 'url(' + img + ')' }
											}
										)
									),
									el( "span", 
										{
											key: 		'gbt_18_sk_editor_latest_posts_title',
											className:  'gbt_18_sk_editor_latest_posts_title',
											dangerouslySetInnerHTML: { __html: posts[i]['title']['rendered'] }
										}
									)
								)
							)
						);
					}
				} 
				
				return postElements;
			}

			//==============================================================================
			//	Display Categories
			//==============================================================================

			function getCategories() {

				if( attributes.firstLoad == true) {

					let categories_list = [];
					let options = [];
					let sorted = [];
				
					apiFetch({ path: '/wp/v2/categories?per_page=-1' }).then(function (categories) {

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
													let query = '/wp/v2/posts?categories=' + attributes.queryCategorySelected.join(',') + '&per_page=' + attributes.number;
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
						key: 'sk-latest-posts-inspector'
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
								key: "sk-latest-posts-number",
								className: 'range-wrapper',
								value: attributes.number,
								allowReset: false,
								initialPosition: 12,
								min: 1,
								max: 20,
								label: i18n.__( 'Number of Posts' ),
								onChange: function onChange(newNumber){
									props.setAttributes( { number: newNumber } );
									let query = '/wp/v2/posts?categories=' + attributes.queryCategorySelected.join(',') + '&per_page=' + newNumber;
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
									_destroyTempAtts();
									getPosts();
								},
							},
							_isLoadingText(),
						),
						el( 'hr', {} ),
						el(
							RangeControl,
							{
								key: "sk-latest-posts-columns",
								value: attributes.columns,
								allowReset: false,
								initialPosition: 3,
								min: 1,
								max: 4,
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
						key: 		'gbt_18_sk_latest_posts',
						className: 	'gbt_18_sk_latest_posts'	
					},
					el(
						'div',
						{
							key: 		'gbt_18_sk_editor_latest_posts_wrapper',
							className: 	'gbt_18_sk_editor_latest_posts_wrapper columns-' + attributes.columns,
						},
						renderResults()
					),
				),
			];
		},

		save: function( props ) {

			let attributes = props.attributes;

			function renderFrontend() {

				let posts = attributes.result;
				let postElements = [];

				if( posts.length > 0) {

					for ( let i = 0; i < posts.length; i++ ) {

						let img = '';
						let img_class = 'gbt_18_sk_latest_posts_noimg';

						if ( posts[i]['fimg_url'] ) { 
							img = posts[i]['fimg_url']; 
							img_class = 'gbt_18_sk_latest_posts_with_img'; 
						} else { 
							img_class = 'gbt_18_sk_latest_posts_noimg'; 
							img = ''; 
						};

						postElements.push(
							el( "div", 
								{
									key: 		'gbt_18_sk_latest_posts_item_' + posts[i].id, 
									className: 	'gbt_18_sk_latest_posts_item'
								},
								el( "a", 
									{
										key: 		'gbt_18_sk_latest_posts_item_link',
										className: 	'gbt_18_sk_latest_posts_item_link'
									},
									el( "span", 
										{ 
											key: 		'gbt_18_sk_latest_posts_img_container',
											className: 	'gbt_18_sk_latest_posts_img_container'
										},
										el( "span", 
											{
												key: 'gbt_18_sk_latest_posts_img_overlay',
												className: 'gbt_18_sk_latest_posts_img_overlay'
											}
										),
										el( "span", 
											{
												key: 		'gbt_18_sk_latest_posts_img',
												className: 	'gbt_18_sk_latest_posts_img ' + img_class,
												style: 		{ backgroundImage: 'url(' + img + ')' }
											}
										)
									),
									el( "span", 
										{
											key: 		'gbt_18_sk_latest_posts_title',
											className:  'gbt_18_sk_latest_posts_title',
											dangerouslySetInnerHTML: { __html: posts[i]['title']['rendered'] }
										}
									)
								)
							)
						);
					}
				} 
				
				return postElements;
			}

			return el( 'div',
				{
					key: 		'gbt_18_sk_latest_posts',
					className: 	'gbt_18_sk_latest_posts'
				},
				el( 'div',
					{
						key: 		'gbt_18_sk_latest_posts_wrapper',
						className: 	'gbt_18_sk_latest_posts_wrapper columns-' + attributes.columns
					},
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