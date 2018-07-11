<?php

// Categories Grid

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'enqueue_block_editor_assets', 'getbowtied_categories_grid_editor_assets' );

if ( ! function_exists( 'getbowtied_categories_grid_editor_assets' ) ) {
	function getbowtied_categories_grid_editor_assets() {

		wp_enqueue_script(
			'getbowtied-categories-grid',
			plugins_url( 'block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-editor', 'wp-i18n', 'wp-element', 'jquery' )
		);

		wp_enqueue_style(
			'getbowtied-categories-grid-css',
			plugins_url( 'css/editor.css', __FILE__ ),
			array( 'wp-edit-blocks' )
		);

		wp_localize_script( 'getbowtied-categories-grid', 'ajax_object',
	            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
}

register_block_type( 'getbowtied/categories-grid', array(
	'attributes'      => array(
		'product_categories_selection' 	=> array(
			'type'						=> 'string',
			'default'					=> 'auto',
		),
		'ids'							=> array(
			'type'						=> 'string',
			'default'					=> '',
		),
		'number'						=> array(
			'type'						=> 'integer',
			'default'					=> 12,
		),
		'hide_empty'  					=> array(
			'type'    					=> 'boolean',
			'default' 					=> false,
		),
		'order'		  					=> array(
			'type'	  					=> 'string',
			'default' 					=> 'asc',
		),
		'parent'						=> array(
			'type'						=> 'string',
			'default'					=> '0',
		),
	),

	'render_callback' => 'getbowtied_render_frontend_categories_grid',
) );

function getbowtied_render_frontend_categories_grid( $attributes ) {

	extract( shortcode_atts( array(
		'product_categories_selection'	=> 'auto',
		'ids'							=> '',
		'number'     					=> 12,
		'order'      					=> 'asc',
		'hide_empty'				 	=> false,
		'parent'     					=> '0'
	), $attributes ) );

	if ( isset( $attributes[ 'ids' ] ) ) {
		$ids = explode( ',', $attributes[ 'ids' ] );
		$ids = array_map( 'trim', $ids );
	} else {
		$ids = array();
	}

	if ($product_categories_selection == "auto") {

		$args = array(
			'orderby'    => 'title',
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'number'	 => $number,
			'pad_counts' => true
		);

	} else {

		$args = array(
			'orderby'    => 'include',
			'hide_empty' => $hide_empty,
			'include'    => $ids,
			'pad_counts' => true,
		);

		$parent = 999;

	}

	if ( $parent == '0' ) {
		$args['parent'] ='0' ;
	}

	$product_categories = get_terms( 'product_cat', $args );

	if ( $hide_empty ) {
		foreach ( $product_categories as $key => $category ) {
			if ( $category->count == 0 ) {
				unset( $product_categories[ $key ] );
			}
		}
	}

	ob_start();

	$cat_counter = 0;

	$cat_number = count($product_categories);

	if ( $product_categories ) {

		foreach ( $product_categories as $category ) {

				   
			$thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
			$image = wp_get_attachment_url( $thumbnail_id );
			$cat_class = "";

			$cat_counter++;                                        

			switch ($cat_number) {
				case 1:
					$cat_class = "one_cat_" . $cat_counter;
					break;
				case 2:
					$cat_class = "two_cat_" . $cat_counter;
					break;
				case 3:
					$cat_class = "three_cat_" . $cat_counter;
					break;
				case 4:
					$cat_class = "four_cat_" . $cat_counter;
					break;
				case 5:
					$cat_class = "five_cat_" . $cat_counter;
					break;
				default:
					if ($cat_counter < 7) {
						$cat_class = $cat_counter;
					} else {
						$cat_class = "more_than_6";
					}
			}
			
			?>

			<div class="category_<?php echo $cat_class; ?>">
				<div class="category_grid_box">
					<span class="category_item_bkg" style="background-image:url(<?php echo esc_url($image); ?>)"></span> 
					<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>" class="category_item" >
						<span class="category_name"><?php echo esc_html($category->name); ?></span>
					</a>
				</div>
			</div>

			<?php

		}
		
		?>
					
			<div class="clearfix"></div>
					
		<?php

	}

	woocommerce_reset_loop();

	return '<div class="row"><div class="categories_grid">' . ob_get_clean() . '</div></div>';
}

add_action('wp_ajax_getbowtied_render_frontend_categories_grid', 'getbowtied_render_backend_categories_grid');
function getbowtied_render_backend_categories_grid() {

	$attributes = $_POST['attributes'];
	$output = '';

	extract( shortcode_atts( array(
		'product_categories_selection'	=> 'auto',
		'ids'							=> '',
		'number'     					=> 12,
		'order'      					=> 'asc',
		'hide_empty'				 	=> false,
		'parent'     					=> '0'
	), $attributes ) );

	if ( isset( $attributes[ 'ids' ] ) ) {
		$ids = explode( ',', $attributes[ 'ids' ] );
		$ids = array_map( 'trim', $ids );
	} else {
		$ids = array();
	}

	if ($product_categories_selection == "auto") {

		$args = array(
			'orderby'    => 'title',
			'order'      => $order,
			'hide_empty' => $hide_empty,
			'number'	 => $number,
			'pad_counts' => true
		);

	} else {

		$args = array(
			'orderby'    => 'include',
			'hide_empty' => $hide_empty,
			'include'    => $ids,
			'pad_counts' => true,
		);

		$parent = 999;

	}

	if ( $parent == '0' ) {
		$args['parent'] ='0' ;
	}

	$product_categories = get_terms( 'product_cat', $args );

	if ( $hide_empty ) {
		foreach ( $product_categories as $key => $category ) {
			if ( $category->count == 0 ) {
				unset( $product_categories[ $key ] );
			}
		}
	}

	$cat_counter = 0;

	$cat_number = count($product_categories);

	if ( $product_categories ) {

		foreach ( $product_categories as $category ) {
   
			$thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
			$image = wp_get_attachment_url( $thumbnail_id );
			$cat_class = "";

			$cat_counter++;                                        

			switch ($cat_number) {
				case 1:
					$cat_class = "one_cat_" . $cat_counter;
					break;
				case 2:
					$cat_class = "two_cat_" . $cat_counter;
					break;
				case 3:
					$cat_class = "three_cat_" . $cat_counter;
					break;
				case 4:
					$cat_class = "four_cat_" . $cat_counter;
					break;
				case 5:
					$cat_class = "five_cat_" . $cat_counter;
					break;
				default:
					if ($cat_counter < 7) {
						$cat_class = $cat_counter;
					} else {
						$cat_class = "more_than_6";
					}
			}

			$output .= 'el("div",{className:"category_'.$cat_class.'", key:"category_'.$cat_class.'_'.$cat_counter.'"},el("div",{className:"category_grid_box", key:"category_grid_box_'.$cat_counter.'"},
		el("span",{className:"category_item_bkg",key:"category_item_bkg_'.$cat_counter.'",style:{backgroundImage:"url('.esc_url($image).')"}},),el("a",{className:"category_item",key:"category_item_'.$cat_counter.'"},el("span",{className:"category_name",key:"category_item_'.$cat_counter.'"},"'.htmlspecialchars_decode($category->name).'")))),';

		}

	}

	$output_final = 'el("div",{className:"categories_grid",key:"categories_grid"},'.$output.'el("div",{className:"clearfix",key:"clearfix"}))'; 

	woocommerce_reset_loop();

	echo json_encode($output_final);
	exit;
}
