<?php
/**
 * Banner Shortcode.
 *
 * @package  shopkeeper-extender
 */

/**
 * Banner Shortcode Output.
 *
 * @param array $params The params.
 */
function sk_banner_shortcode( $params = array() ) {

	$attributes = shortcode_atts(
		array(
			'title'              => 'Title',
			'subtitle'           => 'Subtitle',
			'link_url'           => '',
			'new_tab'            => '',
			'title_color'        => '#fff',
			'subtitle_color'     => '#fff',
			'inner_stroke'       => '2px',
			'inner_stroke_color' => '#fff',
			'bg_color'           => '#464646',
			'bg_image'           => '',
			'height'             => '300',
			'bullet_text'        => '',
		),
		$params
	);

	$banner_class = '';
	if ( is_numeric( $attributes['bg_image'] ) ) {
		$attributes['bg_image'] = wp_get_attachment_url( $attributes['bg_image'] );
		$banner_class           = 'banner_with_img';
	}

	$target = $attributes['new_tab'] ? '_blank' : '_self';
	?>

	<div class="shortcode_banner_simple_height <?php echo esc_attr( $banner_class ); ?>" style="height:<?php echo esc_attr( $attributes['height'] ); ?>px">
		<a class="shortcode_banner_simple_link" href="<?php echo esc_url( $attributes['link_url'] ); ?>" target="<?php echo esc_attr( $target ); ?>" rel="noopener noreferrer" referrerpolicy="origin">
			<div class="shortcode_banner_simple_height_inner">
				<div class="shortcode_banner_simple_height_bkg" style="background-color:<?php echo esc_attr( $attributes['bg_color'] ); ?>; background-image:url(<?php echo esc_url( $attributes['bg_image'] ); ?>)"></div>
				<div class="shortcode_banner_simple_height_inside" style="border:<?php echo esc_attr( $attributes['inner_stroke'] ); ?>px solid <?php echo esc_attr( $attributes['inner_stroke_color'] ); ?>">
					<div class="shortcode_banner_simple_height_content">
						<h3 class="banner_title" style="color:<?php echo esc_attr( $attributes['title_color'] ); ?>">
							<?php echo wp_kses_post( $attributes['title'] ); ?>
						</h3>
						<p class="banner_subtitle" style="color:<?php echo esc_attr( $attributes['subtitle_color'] ); ?>">
							<?php echo wp_kses_post( $attributes['subtitle'] ); ?>
						</p>
					</div>
				</div>
			</div>
		</a>
	</div>

	<?php
}
add_shortcode( 'banner', 'sk_banner_shortcode' );
