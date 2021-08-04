<?php
/**
 * Posts Slider Shortcode.
 *
 * @package  shopkeeper-extender
 */

/**
 * Posts Slider Shortcode Output.
 *
 * @param array $atts The attributes.
 * @param mixed $content The content.
 */
function sk_posts_slider_shortcode( $atts, $content = null ) {

	wp_enqueue_style( 'swiper' );
	wp_enqueue_script( 'swiper' );

	$attributes = shortcode_atts(
		array(
			'posts'    => '',
			'category' => '',
		),
		$atts
	);

	ob_start();

	$unique = uniqid();

	?>

	<div class="from-the-blog-wrapper">

		<div class="from-the-blog swiper-container swiper-<?php echo esc_attr( $unique ); ?>" data-id="<?php echo esc_attr( $unique ); ?>">
			<div class="swiper-wrapper">

				<?php

				$args = array(
					'post_status'    => 'publish',
					'post_type'      => 'post',
					'category_name'  => $attributes['category'],
					'posts_per_page' => $attributes['posts'],
				);

				$recent_posts = new WP_Query( $args );

				if ( $recent_posts->have_posts() ) :
					?>

					<?php
					while ( $recent_posts->have_posts() ) :
						$recent_posts->the_post();
						?>

						<?php $post_class = ( ! has_post_thumbnail() ) ? 'no_thumb' : ''; ?>

						<div class="swiper-slide">

							<div class="from_the_blog_item <?php echo esc_attr( $post_class ); ?>">

								<a class="from_the_blog_link" href="<?php the_permalink(); ?>">
									<span class="from_the_blog_img_container">
										<?php
										if ( has_post_thumbnail() ) :
											$image_id  = get_post_thumbnail_id();
											$image_url = wp_get_attachment_image_src( $image_id, 'large', true );
											?>
											<img class="from_the_blog_img" src="<?php echo esc_url( $image_url[0] ); ?>">
										<?php else : ?>
											<span class="from_the_blog_noimg"></span>
										<?php endif; ?>

									</span>
									<span class="from_the_blog_title" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></span>
								</a>

								<div class="from_the_blog_content">
									<div class="post_meta_archive entry-meta">
										<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
											title="<?php /* translators: %s: Author */ echo sprintf( esc_html__( 'View all posts by %s', 'shopkeeper-extender' ), get_the_author() ); ?>"
											rel="author">
											<?php echo get_the_author(); ?>
										</a>
										<span class="entry-meta-separator">/</span>
										<a href="<?php the_permalink(); ?>" rel="bookmark" class="entry-date"
											title="<?php /* translators: %s: Title */ echo sprintf( esc_html__( 'Permalink to %s', 'shopkeeper-extender' ), the_title_attribute( 'echo=0' ) ); ?>">
											<?php echo get_the_date(); ?>
										</a>
									</div>
								</div>
							</div>
						</div>

					<?php endwhile; ?>

				<?php endif; ?>

			</div>

			<div class="swiper-pagination"></div>

		</div>

	</div>

	<?php
	wp_reset_postdata();
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'from_the_blog', 'sk_posts_slider_shortcode' );
