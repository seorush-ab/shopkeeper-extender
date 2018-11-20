<?php

// Posts Slider

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//==============================================================================
//	Enqueue Editor Assets
//==============================================================================
add_action( 'enqueue_block_editor_assets', 'gbt_18_sk_latest_posts_editor_assets' );
if ( ! function_exists( 'gbt_18_sk_latest_posts_editor_assets' ) ) {
	function gbt_18_sk_latest_posts_editor_assets() {
		
		wp_enqueue_script(
			'gbt_18_sk_latest_posts_script',
			plugins_url( 'block.js', __FILE__ ),
			array( 'wp-api-request', 'wp-blocks', 'wp-i18n', 'wp-element' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
		);

		wp_enqueue_style(
			'gbt_18_sk_latest_posts_editor_styles',
			plugins_url( 'assets/css/editor.css', __FILE__ ),
			array( 'wp-blocks' )
		);
	}
}

//==============================================================================
//	Enqueue Frontend Assets
//==============================================================================
add_action( 'enqueue_block_assets', 'gbt_18_sk_latest_posts_assets' );
if ( ! function_exists( 'gbt_18_sk_latest_posts_assets' ) ) {
	function gbt_18_sk_latest_posts_assets() {
		
		wp_enqueue_style(
			'gbt_18_sk_latest_posts_styles',
			plugins_url( 'assets/css/style.css', __FILE__ ),
			array()
		);
	}
}

if ( function_exists( 'register_block_type' ) ) {
	register_block_type( 'getbowtied/sk-latest-posts', array(
		'attributes'      					=> array(
			'number'						=> array(
				'type'						=> 'number',
				'default'					=> '12',
			),
			'categoriesIDs'					=> array(
				'type'						=> 'string',
				'default'					=> '',
			),
			'align'							=> array(
				'type'						=> 'string',
				'default'					=> 'center',
			),
			'columns'						=> array(
				'type'						=> 'number',
				'default'					=> '3'
			),
		),

		'render_callback' => 'getbowtied_render_frontend_latest_posts_grid',
	) );
}

//==============================================================================
//	Frontend Output
//==============================================================================
function getbowtied_render_frontend_latest_posts_grid( $attributes ) {

	extract( shortcode_atts( array(
		'number'		=> '12',
		'categoriesIDs'	=> '',
		'align'			=> 'center',
		'columns'		=> '3'
	), $attributes ) );

	$args = array(
        'post_status' 		=> 'publish',
        'post_type' 		=> 'post',
        'posts_per_page' 	=> $number
    );

    if( substr($categoriesIDs, - 1) == ',' ) {
		$categoriesIDs = substr( $categoriesIDs, 0, -1);
	}

    if( $categoriesIDs != '' ) $args['category'] = $categoriesIDs;
    
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

//==============================================================================
//	Post Featured Image Helper
//==============================================================================
add_action('rest_api_init', 'gbt_18_sk_register_rest_post_images' );
function gbt_18_sk_register_rest_post_images(){
    register_rest_field( array('post'),
        'fimg_url',
        array(
            'get_callback'    => 'gbt_18_sk_get_rest_post_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function gbt_18_sk_get_rest_post_featured_image( $object, $field_name, $request ) {
    if( $object['featured_media'] ){
        $img = wp_get_attachment_image_src( $object['featured_media'], 'app-thumb' );
        return $img[0];
    }
    return false;
}