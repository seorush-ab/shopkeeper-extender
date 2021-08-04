<?php
/**
 * Categories Grid WPBakery Element.
 *
 * @package  shopkeeper-extender
 */

$args = array(
	'taxonomy'     => 'product_cat',
	'orderby'      => 'name',
	'show_count'   => 0,
	'pad_counts'   => 0,
	'hierarchical' => 1,
	'title_li'     => '',
	'hide_empty'   => 0,
);

$all_categories = get_categories( $args );

$categoryegories_list = array();
foreach ( $all_categories as $category ) {
	$categoryegories_list[] = array(
		'label' => $category->name,
		'value' => $category->term_id,
	);
}

vc_map(
	array(
		'name'        => 'Product Categories - Grid',
		'category'    => 'WooCommerce',
		'description' => '',
		'base'        => 'product_categories_grid',
		'class'       => '',
		'icon'        => 'product_categories_grid',
		'params'      => array(

			array(
				'type'        => 'dropdown',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'Product Categories',
				'param_name'  => 'product_categories_selection',
				'value'       => array(
					'Manually Pick Categories' => 'manual',
					'Display X number of Product Categories' => 'auto',
				),
				'std'         => 'auto',
			),

			array(
				'type'        => 'dropdown',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => false,
				'heading'     => 'Categories Display',
				'param_name'  => 'parent',
				'value'       => array(
					'Parent Categories Only'            => '0',
					'Parent Categories + Subcategories' => '999',
				),
				'dependency'  => array(
					'element' => 'product_categories_selection',
					'value'   => array( 'auto' ),
				),
			),

			array(
				'type'        => 'autocomplete',
				'heading'     => 'Categories',
				'param_name'  => 'ids',
				'settings'    => array(
					'multiple' => true,
					'sortable' => true,
					'values'   => $categoryegories_list,
				),
				'save_always' => true,
				'dependency'  => array(
					'element' => 'product_categories_selection',
					'value'   => array( 'manual' ),
				),
			),

			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => true,
				'heading'     => 'How many product categories to display?',
				'param_name'  => 'number',
				'value'       => '',
				'dependency'  => array(
					'element' => 'product_categories_selection',
					'value'   => array( 'auto' ),
				),
			),

			array(
				'type'        => 'dropdown',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'admin_label' => false,
				'heading'     => 'Alphabetical Order',
				'param_name'  => 'order',
				'value'       => array(
					'Ascending'  => 'asc',
					'Descending' => 'desc',
				),
				'dependency'  => array(
					'element' => 'product_categories_selection',
					'value'   => array( 'auto' ),
				),

			),

			array(
				'type'        => 'dropdown',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'heading'     => 'Hide Empty',
				'param_name'  => 'hide_empty',
				'value'       => array(
					'Yes' => '1',
					'No'  => '0',
				),
				'admin_label' => true,
			),

			array(
				'type'        => 'dropdown',
				'holder'      => 'div',
				'class'       => 'hide_in_vc_editor',
				'heading'     => 'Show Product Count',
				'param_name'  => 'show_count',
				'value'       => array(
					'No'  => '0',
					'Yes' => '1',
				),
				'admin_label' => true,
			),
		),

	)
);
