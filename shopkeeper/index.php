<?php

// Shopkeeper Custom Blocks

// Register Shopkeeper Block Category  
add_filter( 'block_categories', function( $categories, $post ) {
	if ( $post->post_type !== 'post' && $post->post_type !== 'page' ) {
		return $categories;
	}
	return array_merge(
		array(
			array(
				'slug' => 'shopkeeper',
				'title' => __( 'Shopkeeper', 'gbt-blocks' ),
			),
		),
		$categories
	);
}, 10, 2 );

require_once 'categories_grid/index.php';
require_once 'latest_posts_grid/index.php';
require_once 'banner/index.php';
require_once 'portfolio/index.php';
require_once 'social-media-profiles/index.php';
require_once 'slider/index.php';