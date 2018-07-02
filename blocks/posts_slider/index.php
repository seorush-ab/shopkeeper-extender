<?php
/**
 * GetBowtied Posts Slider
 *
 * @package   getbowtied
 * @author    GetBowtied
 * @license   @@pkg.license
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue the block's assets for the editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
add_action( 'enqueue_block_editor_assets', 'getbowtied_posts_slider_editor_assets' );
function getbowtied_posts_slider_editor_assets() {
	// Scripts.
	wp_enqueue_script(
		'getbowtied-posts-slider',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'jquery' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
	);
}

register_block_type( 'getbowtied/posts-slider', array(
	'attributes'      => array(
		'number'						=> array(
			'type'						=> 'integer',
			'default'					=> 12,
		),
		'category'						=> array(
			'type'						=> 'string',
			'default'					=> '',
		),
	),

	'render_callback' => 'getbowtied_render_posts_slider',
) );

/**
 * Renders the `getbowtied/posts_slider` block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string Returns posts_slider.
 */
function getbowtied_render_posts_slider( $attributes ) {

	$sliderrandomid = rand();

	extract( shortcode_atts( array(
		'number'	=> '',
		'category'	=> '',
	), $attributes ) );

	ob_start();
	?> 
    
    <script>
	jQuery(document).ready(function($) {
		var blog_slider = new Swiper("#from-the-blog-<?php echo $sliderrandomid ?>", {
			slidesPerView: 3,
			breakpoints: {
				640: {
					slidesPerView: 2,
				},

				480: {
					slidesPerView: 1,
				},
			}
		});
	});
	</script>
    
	<div class="row">
    <div class="from-the-blog-wrapper">
	
        <div id="from-the-blog-<?php echo $sliderrandomid ?>" class="swiper-container">
        <div class="swiper-wrapper">
					
			<?php
    
            $args = array(
                'post_status' 		=> 'publish',
                'post_type' 		=> 'post',
                'category' 			=> $category,
                'posts_per_page' 	=> $number
            );
            
            $recentPosts = get_posts( $args );
            
            if ( !empty($recentPosts) ) : ?>
                        
                <?php foreach($recentPosts as $post) : ?>
            
                    <?php $post_format = get_post_format($post->ID); ?>
                    
                    <div class="swiper-slide">

	                    <div class="from_the_blog_item <?php echo $post_format ? $post_format: 'standard'; ?> <?php if ( !has_post_thumbnail($post->ID)) : ?>no_thumb<?php endif; ?>">
	                        
							<a class="from_the_blog_link" href="<?php echo get_post_permalink($post->ID); ?>">
								<span class="from_the_blog_img_container">
									<span class="from_the_blog_overlay"></span>
									
									<?php if ( has_post_thumbnail($post->ID)) :
										$image_id = get_post_thumbnail_id($post->ID);
										$image_url = wp_get_attachment_image_src($image_id,'large', true);
									?>
										<span class="from_the_blog_img" style="background-image: url(<?php echo esc_url($image_url[0]); ?> );"></span>
										<span class="with_thumb_icon"></span>
									<?php else : ?>
										<span class="from_the_blog_noimg"></span>
										<span class="no_thumb_icon"></span>
									<?php endif;  ?>
									
									<?php if ( has_post_thumbnail($post->ID)) :
										$image_id = get_post_thumbnail_id($post->ID);
										$image_url = wp_get_attachment_image_src($image_id,'large', true);
									?>
										<span class="from_the_blog_img" style="background-image: url(<?php echo esc_url($image_url[0]); ?> );"></span>
										<span class="with_thumb_icon"></span>
									<?php else : ?>
										<span class="from_the_blog_noimg"></span>
										<span class="no_thumb_icon"></span>
									<?php endif;  ?>
								</span><!--.from_the_blog_img_container-->
								<span class="from_the_blog_title" href="<?php get_post_permalink($post->ID); ?>"><?php echo $post->post_title; ?></span>
							</a>
	                        
	                        <div class="from_the_blog_content">
	                            <div class="post_meta_archive"><?php shopkeeper_entry_archives(); ?></div>                       
	                        </div>
	                        
	                    </div>

                    </div>
        
                <?php endforeach; // end of the loop. ?>
                
            <?php

            endif;
            
            ?> 
              
        </div>
        </div>

	</div>
    </div>
	
	<?php

	wp_reset_query();

	return ob_get_clean();

}