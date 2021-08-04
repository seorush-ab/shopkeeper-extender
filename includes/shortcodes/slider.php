<?php
/**
 * Slider Shortcode.
 *
 * @package  shopkeeper-extender
 */

/**
 * Slider Shortcode Output.
 *
 * @param array $params The attributes.
 * @param mixed $content The content.
 */
function sk_slider_shortcode( $params = array(), $content = null ) {

	wp_enqueue_style( 'swiper' );
	wp_enqueue_script( 'swiper' );

	$attributes = shortcode_atts(
		array(
			'full_height'              => 'yes',
			'custom_height'            => '',
			'hide_arrows'              => '',
			'hide_bullets'             => '',
			'color_navigation_bullets' => '#000000',
			'color_navigation_arrows'  => '#000000',
			'custom_autoplay_speed'    => 10,
		),
		$params
	);

	ob_start();

	$extra_class = ( 'no' === $attributes['full_height'] && ! empty( $attributes['custom_height'] ) ) ? '' : 'full_height';

	$unique = uniqid();
	?>

	<div class="shortcode_getbowtied_slider swiper-container swiper-<?php echo esc_attr( $unique ); ?> <?php echo esc_attr( $extra_class ); ?>" style="height:<?php echo esc_attr( $attributes['custom_height'] ); ?>px;width: 100%" data-autoplay="<?php echo esc_attr( $attributes['custom_autoplay_speed'] ); ?>" data-id="<?php echo esc_attr( $unique ); ?>">
		<div class="swiper-wrapper">
			<?php echo do_shortcode( $content ); ?>
		</div>

		<?php if ( ! $attributes['hide_arrows'] ) { ?>
			<div style="color:<?php echo esc_attr( $attributes['color_navigation_arrows'] ); ?>" class="swiper-button-prev"></div>
			<div style="color:<?php echo esc_attr( $attributes['color_navigation_arrows'] ); ?>" class="swiper-button-next"></div>
		<?php } ?>

		<?php if ( ! $attributes['hide_bullets'] ) { ?>
			<div style="color:<?php echo esc_attr( $attributes['color_navigation_bullets'] ); ?>" class="shortcode-slider-pagination"></div>
		<?php } ?>

	</div>

	<?php
	wp_reset_postdata();
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode( 'slider', 'sk_slider_shortcode' );

/**
 * Slide Shortcode Output.
 *
 * @param array $params The attributes.
 * @param mixed $content The content.
 */
function sk_image_slide_shortcode( $params = array(), $content = null ) {

	$attributes = shortcode_atts(
		array(
			'title'                   => '',
			'title_font_size'         => '64px',
			'title_line_height'       => '',
			'title_font_family'       => 'primary_font',
			'description'             => '',
			'description_font_size'   => '21px',
			'description_line_height' => '',
			'description_font_family' => 'primary_font',
			'text_color'              => '#000000',
			'button_text'             => '',
			'button_url'              => '',
			'link_whole_slide'        => '',
			'button_color'            => '#000000',
			'button_text_color'       => '#FFFFFF',
			'bg_color'                => '#CCCCCC',
			'bg_image'                => '',
			'text_align'              => 'left',

		),
		$params
	);

	ob_start();

	if ( is_numeric( $attributes['bg_image'] ) ) {
		$attributes['bg_image'] = wp_get_attachment_url( $attributes['bg_image'] );
	}

	?>

	<div class="swiper-slide <?php echo esc_attr( $attributes['text_align'] ); ?>" style="background-color:<?php echo esc_attr( $attributes['bg_color'] ); ?>;background-image:url(<?php echo esc_url( $attributes['bg_image'] ); ?>);color:<?php echo esc_attr( $attributes['text_color'] ); ?>">
		<?php if ( $attributes['link_whole_slide'] && ! empty( $attributes['button_url'] ) ) { ?>
			<a href="<?php echo esc_url( $attributes['button_url'] ); ?>" class="fullslidelink"></a>
		<?php } ?>

		<div class="slider-content" data-swiper-parallax="-50%">
			<div class="slider-content-wrapper">
				<?php if ( ! empty( $attributes['title'] ) ) { ?>
					<?php $attributes['title_line_height'] = $attributes['title_line_height'] ? $attributes['title_line_height'] : $attributes['title_font_size']; ?>
					<h2 class="slide-title <?php echo esc_attr( $attributes['title_font_family'] ); ?>" style="color:<?php echo esc_attr( $attributes['text_color'] ); ?>;font-size:<?php echo esc_attr( $attributes['title_font_size'] ); ?>;line-height:<?php echo esc_attr( $attributes['title_line_height'] ); ?>">
						<?php echo wp_kses_post( $attributes['title'] ); ?>
					</h2>
				<?php } ?>

				<?php if ( ! empty( $attributes['description'] ) ) { ?>
					<?php $attributes['description_line_height'] = $attributes['description_line_height'] ? $attributes['description_line_height'] : $attributes['description_font_size']; ?>
					<p class="slide-description <?php echo esc_attr( $attributes['description_font_family'] ); ?>" style="color:<?php echo esc_attr( $attributes['text_color'] ); ?>;font-size:<?php echo esc_attr( $attributes['description_font_size'] ); ?>;line-height:<?php echo esc_attr( $attributes['description_line_height'] ); ?>">
						<?php echo wp_kses_post( $attributes['description'] ); ?>
					</p>
				<?php } ?>

				<?php if ( ! empty( $attributes['button_text'] ) ) { ?>
					<a class="button" style="color:<?php echo esc_attr( $attributes['button_text_color'] ); ?>; background:<?php echo esc_attr( $attributes['button_color'] ); ?>" href="<?php echo esc_url( $attributes['button_url'] ); ?>">
						<?php echo esc_html( $attributes['button_text'] ); ?>
					</a>
				<?php } ?>
			</div>
		</div>
	</div>

	<?php
	wp_reset_postdata();
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode( 'image_slide', 'sk_image_slide_shortcode' );
