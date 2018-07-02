<?php
/**
 * GetBowtied Portfolio
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
add_action( 'enqueue_block_editor_assets', 'getbowtied_portfolio_editor_assets' );
function getbowtied_portfolio_editor_assets() {
	// Scripts.
	wp_enqueue_script(
		'getbowtied-portfolio',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'jquery' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
	);

	// Styles.
	// wp_enqueue_style(
	// 	'getbowtied-banner',
	// 	plugins_url( 'css/editor.css', __FILE__ ),
	// 	array( 'wp-edit-blocks' ),
	// 	filemtime( plugin_dir_path( __FILE__ ) . 'css/editor.css' )
	// );
}

register_block_type( 'getbowtied/portfolio', array(
	'attributes'      => array(
		'itemsNumber'					=> array(
			'type'						=> 'integer',
			'default'					=> 6,
		),
		'category'						=> array(
			'type'						=> 'string',
			'default'					=> '',
		),
		'showFilters'					=> array(
			'type'						=> 'boolean',
			'default'					=> true,
		),
		'orderBy'						=> array(
			'type'						=> 'string',
			'default'					=> 'date',
		),
		'order'							=> array(
			'type'						=> 'string',
			'default'					=> 'asc',
		),
		'grid'							=> array(
			'type'						=> 'string',
			'default'					=> '',
		),
		'itemsPerRow'					=> array(
			'type'						=> 'integer',
			'default'					=> 3,
		),
	),

	'render_callback' => 'getbowtied_render_portfolio',
) );

/**
 * Renders the `getbowtied/portfolio` block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string Returns portfolio.
 */
function getbowtied_render_portfolio($attributes, $content = null) {
	
	global $post;
	
	$sliderrandomid = rand();
	
	extract(shortcode_atts(array(
		"itemsNumber" 				=> '9999',
		"category" 					=> '',
		"showFilters" 				=> true,
		"orderBy" 					=> 'date',
		"order" 					=> 'desc',
		"grid" 						=> 'default',
		"itemsPerRow" 				=> 3
	), $attributes));
	ob_start();
	?>
    
    <?php
				
	if ($orderBy == "alphabetical") $orderBy = 'title';
	
	$args = array(					
		'post_status' 			=> 'publish',
		'post_type' 			=> 'portfolio',
		'posts_per_page' 		=> $itemsNumber,
		'portfolio_categories' 	=> $category,
		'orderby' 				=> $orderBy,
		'order' 				=> $order,
	);
	
	$portfolioItems = new WP_Query( $args );
	
	while ( $portfolioItems->have_posts() ) : $portfolioItems->the_post();
		
		$terms = get_the_terms( get_the_ID(), 'portfolio_categories' ); // get an array of all the terms as objects.
		
		if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
			foreach($terms as $term) {
				$portfolio_categories_queried[$term->slug] = $term->name;
			}
		}
		
	endwhile;

	if ( count($portfolio_categories_queried) > 0 ) :
	
		$portfolio_categories_queried = array_unique($portfolio_categories_queried);
		
		if ($grid == "default") {
			$items_per_row_class = ' default_grid items_per_row_'.$itemsPerRow;
		} else {
			$items_per_row_class = '';
		}
		
		?>
	    
		<div class="portfolio-isotope-container<?php echo esc_html($items_per_row_class);?>">
	                
	        <?php if ($category == "") : ?>
	        <?php if ($showFilters) : ?>
	        <div class="portfolio-filters">            
	            <?php
				
				if ( !empty( $portfolio_categories_queried ) && !is_wp_error( $portfolio_categories_queried ) ){
	                echo '<ul class="filters-group list-centered">';
	                    echo '<li class="filter-item is-checked" data-filter="*">' . __("Show all", "shopkeeper") . '</li>';
	                foreach ( $portfolio_categories_queried as $key => $value ) {
	                    echo '<li class="filter-item" data-filter=".' . $key . '">' . $value . '</li>';
	                }
	                echo '</ul>';
	            }
				           
	            ?>            
	        </div>
	        <?php endif; ?>
	        <?php endif; ?>
	        
	        <div class="portfolio-isotope">
	            
	            <div class="portfolio-grid-sizer"></div>
	                
	                <?php
					
	                $post_counter = 0;
	                                
	                while ( $portfolioItems->have_posts() ) : $portfolioItems->the_post();
	                    
						$post_counter++;
	                    
						$related_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'large' );
	                    
	                    $terms_slug = get_the_terms( get_the_ID(), 'portfolio_categories' ); // get an array of all the terms as objects.

	                    $term_slug_class = "";
	                    
	                    if ( !empty( $terms_slug ) && !is_wp_error( $terms_slug ) ){
	                        foreach ( $terms_slug as $term_slug ) {
	                            $term_slug_class .=  $term_slug->slug . " ";
	                        }
	                    }
	                    
	                    if (get_post_meta( $post->ID, 'portfolio_color_meta_box', true )) {
	                        $portfolio_color_option = get_post_meta( $post->ID, 'portfolio_color_meta_box', true );
	                    } else {
	                        $portfolio_color_option = "none";
	                    }
						
						$portfolio_item_width = "";
						$portfolio_item_height = "";
						
						switch ($grid) {
							
							case "grid1":							
								
								switch ($post_counter) {
									case (($post_counter == 1)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "height2";
										break;
									case (($post_counter == 2)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "";
										break;
									case (($post_counter == 7)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "";
										break;
									case (($post_counter == 8)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "height2";
										break;
									default :
										$portfolio_item_width = "";
										$portfolio_item_height = "";
								}							
								break;
								
							case "grid2":
								
								switch ($post_counter) {
									case (($post_counter == 3)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "height2";
										break;
									case (($post_counter == 8)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "";
										break;
									case (($post_counter == 13)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "";
										break;
									default :
										$portfolio_item_width = "";
										$portfolio_item_height = "";
								}							
								break;
								
							case "grid3":
							
								switch ($post_counter) {
									case (($post_counter == 3)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "";
										break;
									case (($post_counter == 8)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "";
										break;
									case (($post_counter == 11)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "";
										break;
									case (($post_counter == 14)) :
										$portfolio_item_width = "width2";
										$portfolio_item_height = "";
										break;
									default :
										$portfolio_item_width = "";
										$portfolio_item_height = "";
								}							
								break;
								
							default:
								
								$portfolio_item_width = "";
								$portfolio_item_height = "";
								
						}
	                    
	                ?>

	                    <div class="portfolio-box hidden <?php echo esc_html($portfolio_item_width); ?> <?php echo esc_html($portfolio_item_height); ?> <?php echo esc_html($term_slug_class); ?>">
	                        
	                        <a href="<?php echo get_permalink(get_the_ID()); ?>" class="portfolio-box-inner hover-effect-link" style="background-color:<?php echo esc_html($portfolio_color_option); ?>">
	                            
	                            <div class="portfolio-content-wrapper hover-effect-content">
	                                
	                                <?php if ($related_thumb[0] != "") : ?>
	                                    <span class="portfolio-thumb hover-effect-thumb" style="background-image: url(<?php echo esc_url($related_thumb[0]); ?>)"></span>
	                                <?php endif; ?>
	                                
	                                <h2 class="portfolio-title hover-effect-title"><?php the_title(); ?></h2>
	                                
	                                <p class="portfolio-categories hover-effect-text"><?php echo strip_tags (get_the_term_list(get_the_ID(), 'portfolio_categories', "", ", "));?></p>
	                                 
	                            </div>
	                            
	                        </a>
	                        
	                    </div>
	                
	                <?php endwhile; // end of the loop. ?>

	        </div><!--portfolio-isotope-->
	    
	    </div><!--portfolio-isotope-container-->
	
	<?php

	endif;

	wp_reset_query();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_action('wp_ajax_getbowtied_render_portfolio_categories', 'getbowtied_render_portfolio_categories');
function getbowtied_render_portfolio_categories() {

	$args = array(
		'type'                     => 'post',
		'child_of'                 => 0,
		'parent'                   => '',
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => 1,
		'hierarchical'             => 1,
		'exclude'                  => '',
		'include'                  => '',
		'number'                   => '',
		'taxonomy'                 => 'portfolio_categories',
		'pad_counts'               => false
	);

	$categories = get_categories($args);

	$output_categories = array();

	$i = 0;
	$output_categories[$i] = array( 'value' => 'All', 'label' => 'All Categories');

	foreach($categories as $category) { 
		$i++;
		$output_categories[$i] = array( 'value' => htmlspecialchars_decode($category->name), 'label' => htmlspecialchars_decode($category->name));
	}

	echo json_encode($output_categories);
	exit;
}