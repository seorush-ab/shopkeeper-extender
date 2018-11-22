<?php

// Posts Slider

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once 'functions/function-setup.php';
include_once 'functions/function-helpers.php';

//==============================================================================
//	Frontend Output
//==============================================================================
function gbt_18_sk_render_frontend_latest_posts( $attributes ) {

	extract( shortcode_atts( array(
		'number'				=> '12',
		'categoriesSavedIDs'	=> '',
		'align'					=> 'center',
		'columns'				=> '3'
	), $attributes ) );

	$args = array(
        'post_status' 		=> 'publish',
        'post_type' 		=> 'post',
        'posts_per_page' 	=> $number
    );

    if( substr($categoriesSavedIDs, - 1) == ',' ) {
		$categoriesSavedIDs = substr( $categoriesSavedIDs, 0, -1);
	}

	if( substr($categoriesSavedIDs, 0, 1) == ',' ) {
		$categoriesSavedIDs = substr( $categoriesSavedIDs, 1);
	}

    if( $categoriesSavedIDs != '' ) $args['category'] = $categoriesSavedIDs;
    
    $recentPosts = get_posts( $args );

	ob_start();
	        
    if ( !empty($recentPosts) ) : ?>

        <div class="gbt_18_sk_latest_posts">
    
    		<div class="gbt_18_sk_latest_posts_wrapper columns-<?php echo $columns; ?> <?php echo $align; ?>">
	                    
	            <?php foreach($recentPosts as $post) : ?>
	        
	                <?php $post_format = get_post_format($post->ID); ?>

	                <div class="gbt_18_sk_latest_posts_item <?php echo $post_format ? $post_format: 'standard'; ?> <?php if ( !has_post_thumbnail($post->ID)) : ?>no_thumb<?php endif; ?>">
	                    
						<a class="gbt_18_sk_latest_posts_item_link" href="<?php echo get_post_permalink($post->ID); ?>">
							<span class="gbt_18_sk_latest_posts_img_container">
								<span class="gbt_18_sk_latest_posts_img_overlay"></span>
								
								<?php if ( has_post_thumbnail($post->ID)) :
									$image_id = get_post_thumbnail_id($post->ID);
									$image_url = wp_get_attachment_image_src($image_id,'large', true);
								?>
									<span class="gbt_18_sk_latest_posts_img gbt_18_sk_latest_posts_with_img" style="background-image: url(<?php echo esc_url($image_url[0]); ?> );"></span>
								<?php else : ?>
									<span class="gbt_18_sk_latest_posts_img gbt_18_sk_latest_posts_noimg"></span>
								<?php endif;  ?>

							</span><!--.from_the_blog_img_container-->
							<span class="gbt_18_sk_latest_posts_title" href="<?php echo get_post_permalink($post->ID); ?>"><?php echo $post->post_title; ?></span>
						</a>
	                    
	                </div>
	    
	            <?php endforeach; // end of the loop. ?>

		</div>

	</div>

	<?php

	endif;
	        
	wp_reset_query();

	return ob_get_clean();
}