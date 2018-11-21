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
	registerBlockType( 'getbowtied/sk-latest-posts', {
		title: i18n.__( 'Latest Posts Grid' ),
		icon: el( SVG, { xmlns:'http://www.w3.org/2000/svg', viewBox:'0 0 24 24' },
				el( Path, { d:'M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12zM10 9h8v2h-8zm0 3h4v2h-4zm0-6h8v2h-8z' } ) 
			),
		category: 'shopkeeper',
		supports: {
			align: [ 'center', 'wide', 'full' ],
		},
		attributes: {
			/* posts source */
			queryPosts: {
				type: 'string',
				default: '',
			},
			queryPostsLast: {
				type: 'string',
				default: '',
			},
			/* loader */
			isLoading: {
				type: 'bool',
				default: false,
			},
			/* Display by category */
			categoriesIDs: {
				type: 'string',
				default: ',',
			},
			categoriesSavedIDs: {
				type: 'string',
				default: '',
			},
			/* First Load */
			firstLoad: {
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

			attributes.doneFirstLoad 		= attributes.doneFirstLoad || false;
			attributes.categoryOptions 		= attributes.categoryOptions || [];
			attributes.doneFirstPostsLoad 	= attributes.doneFirstPostsLoad || false;
			attributes.result 				= attributes.result || [];

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

			function _verifyCatIDs( optionsIDs ) {

				let catArr = attributes.categoriesIDs;
				let categoriesIDs = attributes.categoriesIDs;

				if( catArr.substr(0,1) == ',' ) {
					catArr = catArr.substr(1);
				}
				if( catArr.substr(catArr.length - 1) == ',' ) {
					catArr = catArr.substring(0, catArr.length - 1);
				}

				if( catArr != ',' && catArr != '' ) {

					let newCatArr = catArr.split(',');
					let newArr = [];
					for (let i = 0; i < newCatArr.length; i++) {
						if( optionsIDs.indexOf(newCatArr[i]) == -1 ) {
							categoriesIDs = categoriesIDs.replace(',' + newCatArr[i].toString() + ',', ',');
						}
					}
				}

				if( attributes.categoriesIDs != categoriesIDs ) {
					props.setAttributes({ queryPosts: _buildQuery(categoriesIDs, attributes.number) });
					props.setAttributes({ queryPostsLast: _buildQuery(categoriesIDs, attributes.number) });
				}

				props.setAttributes({ categoriesIDs: categoriesIDs });
				props.setAttributes({ categoriesSavedIDs: categoriesIDs });
			}

			function _buildQuery( arr, nr ) {
				let query = '';

				if( arr.substr(0,1) == ',' ) {
					arr = arr.substr(1);
				}
				if( arr.substr(arr.length - 1) == ',' ) {
					arr = arr.substring(0, arr.length - 1);
				}

				if( arr != ',' && arr != '' ) {
					query = '/wp/v2/posts?categories=' + arr + '&per_page=' + nr;
				}

				return query;
			}

			function _isChecked( needle, haystack ) {
				let idx = haystack.indexOf(needle.toString());
				if ( idx > - 1) {
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
				return ( (attributes.queryPosts.length == 0) || (attributes.queryPosts === attributes.queryPostsLast) );
			}

			function _isLoading() {
				if ( attributes.isLoading  === true ) {
					return 'is-busy';
				} else {
					return '';
				}
			}

			//==============================================================================
			//	Show posts functions
			//==============================================================================

			function getPosts() {
				let query = attributes.queryPosts;
				props.setAttributes({ queryPostsLast: query});

				if (query != '') {
					apiFetch({ path: query }).then(function (posts) {
						props.setAttributes({ result: posts});
						props.setAttributes({ isLoading: false});
						props.setAttributes({ doneFirstPostsLoad: true});
					});
				}
			}

			function renderResults() {
				if ( attributes.firstLoad === true ) {
					apiFetch({ path: '/wp/v2/posts?per_page=12' }).then(function (posts) {
						props.setAttributes({ result: posts });
						props.setAttributes({ firstLoad: false });
						let query = '/wp/v2/posts?per_page=12';
						props.setAttributes({queryPosts: query});
						props.setAttributes({ queryPostsLast: query});
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

				let categories_list = [];
				let options = [];
				let optionsIDs = [];
				let sorted = [];
			
				apiFetch({ path: '/wp/v2/categories?per_page=-1' }).then(function (categories) {

				 	for( let i = 0; i < categories.length; i++) {
	        			options[i] = {'label': categories[i].name.replace(/&amp;/g, '&'), 'value': categories[i].id, 'parent': categories[i].parent, 'count': categories[i].count };
				 		optionsIDs[i] = categories[i].id.toString();
				 	}

				 	sorted = _sortCategories(0, options);
		        	props.setAttributes({categoryOptions: sorted });
		        	_verifyCatIDs(optionsIDs);
		        	props.setAttributes({ doneFirstLoad: true});
				});
			}

			function renderCategories( parent = 0, level = 0 ) {
				let categoryElements = [];
				let catArr = attributes.categoryOptions;
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
											checked: _isChecked(','+catArr[i].value+',', attributes.categoriesIDs),
											onChange: function onChange(evt){
												let newCategoriesSelected = attributes.categoriesIDs;
												let index = newCategoriesSelected.indexOf(',' + evt.target.value + ',');
												if (evt.target.checked === true) {
													if (index == -1) {
														newCategoriesSelected += evt.target.value + ',';
													}
												} else {
													if (index > -1) {
														newCategoriesSelected = newCategoriesSelected.replace(',' + evt.target.value + ',', ',');
													}
												}
												props.setAttributes({ categoriesIDs: newCategoriesSelected });
												props.setAttributes({ queryPosts: _buildQuery(newCategoriesSelected, attributes.number) });
											},
										}, 
									),
									catArr[i].label,
									el(
										'sup',
										{},
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
							className: 'main-inspector-wrapper',
						},
						el( 'label', { className: 'components-base-control__label' }, i18n.__('Categories:') ),
						el(
							'div',
							{
								className: 'category-result-wrapper',
							},
							attributes.categoryOptions.length < 1 && attributes.doneFirstLoad === false && getCategories(),
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
									let newCategoriesSelected = attributes.categoriesIDs;
									props.setAttributes({ queryPosts: _buildQuery(newCategoriesSelected, newNumber) });
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
									props.setAttributes({ categoriesSavedIDs: attributes.categoriesIDs });
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
						attributes.result.length < 1 && attributes.doneFirstPostsLoad === false && getPosts(),
						renderResults()
					),
				),
			];
		},

		save: function(props) {
			return null;
		},
	} );

} )(
	window.wp,
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element
);