<?php
/**
 * Categories Grid Shortcode.
 *
 * @package  shopkeeper-extender
 */

/**
 * Categories Grid Shortcode Output.
 *
 * @param array $params The attributes.
 */
function sk_product_categories_shortcode( $params = array() ) {

	$attributes = shortcode_atts(
		array(
			'product_categories_selection' => 'auto',
			'ids'                          => '',
			'number'                       => 12,
			'order'                        => 'asc',
			'hide_empty'                   => 1,
			'show_count'                   => 0,
			'parent'                       => '0',
		),
		$params
	);

	$args = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => $attributes['hide_empty'],
		'number'     => $attributes['number'],
	);

	if ( 'auto' === $attributes['product_categories_selection'] ) {
		$args['orderby'] = 'title';
		$args['order']   = $attributes['order'];
		$args['parent']  = 0;
	} else {

		if ( isset( $attributes['ids'] ) && ! empty( $attributes['ids'] ) ) {
			$attributes['ids'] = explode( ',', $attributes['ids'] );
			$attributes['ids'] = array_map( 'trim', $attributes['ids'] );

			$args['orderby'] = 'include';
			$args['include'] = $attributes['ids'];
		}
	}

	ob_start();

	$cat_counter = 0;

	$categories = get_terms(
		'product_cat',
		$args
	);

	?>

	<div class="categories_grid">

		<?php
		foreach ( $categories as $category ) :

			$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );

			$cat_counter++;

			switch ( count( $categories ) ) {
				case 1:
					$cat_class = 'one_cat_' . $cat_counter;
					break;
				case 2:
					$cat_class = 'two_cat_' . $cat_counter;
					break;
				case 3:
					$cat_class = 'three_cat_' . $cat_counter;
					break;
				case 4:
					$cat_class = 'four_cat_' . $cat_counter;
					break;
				case 5:
					$cat_class = 'five_cat_' . $cat_counter;
					break;
				default:
					$cat_class = ( $cat_counter < 7 ) ? $cat_counter : 'more_than_6';
					break;
			}
			?>

			<div class="category_<?php echo esc_attr( $cat_class ); ?>">
				<div class="category_grid_box">
					<span class="category_item_bkg" style="background-image:url(<?php echo esc_url( wp_get_attachment_url( $thumbnail_id ) ); ?>)"></span>
					<a href="<?php echo esc_url( get_term_link( $category->slug, 'product_cat' ) ); ?>" class="category_item">
						<span class="category_name"><?php echo esc_html( $category->name ); ?>

							<?php if ( $attributes['show_count'] ) : ?>
								<span class="category_count"><?php echo esc_html( $category->count ); ?></span>
							<?php endif; ?>
						</span>
					</a>
				</div>
			</div>

		<?php endforeach; ?>

		<div class="clearfix"></div>
	</div>
	<?php

	wp_reset_postdata();

	return ob_get_clean();
}

add_shortcode( 'product_categories_grid', 'sk_product_categories_shortcode' );
