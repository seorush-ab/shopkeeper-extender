<?php
/**
 * Social Sharing.
 *
 * @package shopkeeper-extender
 */

if ( ! class_exists( 'SK_Social_Sharing' ) ) :

	/**
	 * SK_Social_Sharing class.
	 *
	 * @since 1.4.2
	 */
	class SK_Social_Sharing {

		/**
		 * The single instance of the class.
		 *
		 * @since 1.4.2
		 * @var SK_Social_Sharing
		 */
		protected static $instance = null;

		/**
		 * SK_Social_Sharing constructor.
		 *
		 * @since 1.4.2
		 */
		public function __construct() {

			if ( ! get_option( 'sk_social_sharing_options_import', false ) ) {
				$done_import = $this->import_options();
				update_option( 'sk_social_sharing_options_import', true );
			}

			$this->customizer_options();

			add_action(
				'woocommerce_single_product_summary',
				function() {
					if ( SK_Social_Sharing::string_to_bool( get_option( 'sk_sharing_options', 'yes' ) ) ) {
						$this->getbowtied_single_share_product();
					}
				},
				50
			);

			if ( self::string_to_bool( get_option( 'sk_sharing_options', 'yes' ) ) && self::string_to_bool( get_option( 'sk_sharing_options_facebook', 'yes' ) ) && self::string_to_bool( get_option( 'sk_sharing_options_facebook_meta', 'yes' ) ) ) {
				add_action( 'wp_head', array( $this, 'sk_add_facebook_meta' ), 10 );
			}
		}

		/**
		 * Add Facebook Meta.
		 *
		 * @since 1.4.2
		 */
		public function sk_add_facebook_meta() {
			if ( is_single() && is_product() ) {
				$product = wc_get_product( get_the_ID() );
				if ( $product ) {
					$image       = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'large' );
					$image       = isset( $image[0] ) ? $image[0] : '';
					$description = wp_strip_all_tags( wpautop( str_replace( '&nbsp;', '', $product->get_short_description() ) ) );
					?>

					<meta property="og:url" content="<?php the_permalink(); ?>">
					<meta property="og:type" content="product">
					<meta property="og:title" content="<?php the_title(); ?>">
					<meta property="og:description" content="<?php echo esc_html( wp_kses_post( $description ) ); ?>">
					<meta property="og:image" content="<?php echo esc_attr( wp_kses_post( $image ) ); ?>">

					<?php
				}
			}
		}

		/**
		 * Ensures only one instance of SK_Social_Sharing is loaded or can be loaded.
		 *
		 * @since 1.4.2
		 *
		 * @return SK_Social_Sharing
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Imports social sharing options stored as theme mods into the options WP table.
		 *
		 * @since 1.4.2
		 * @return void
		 */
		private function import_options() {

			update_option( 'sk_sharing_options', get_theme_mod( 'sharing_options', true ) );
		}

		/**
		 * Registers customizer options.
		 *
		 * @since 1.4.2
		 * @return void
		 */
		protected function customizer_options() {
			add_action( 'customize_register', array( $this, 'sk_social_sharing_customizer' ) );
		}

		/**
		 * Creates customizer options.
		 *
		 * @param object $wp_customize WP Customize.
		 * @since 1.4.2
		 * @return void
		 */
		public function sk_social_sharing_customizer( $wp_customize ) {

			// Social Sharing Options.
			$wp_customize->add_setting(
				'sk_sharing_options',
				array(
					'type'                 => 'option',
					'capability'           => 'manage_options',
					'sanitize_callback'    => 'SK_Social_Sharing::sanitize_checkbox',
					'sanitize_js_callback' => 'SK_Social_Sharing::string_to_bool',
					'transport'            => 'refresh',
					'default'              => 'yes',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'sk_sharing_options',
					array(
						'type'     => 'checkbox',
						'label'    => esc_attr__( 'Social Sharing Options', 'shopkeeper-extender' ),
						'section'  => 'product',
						'priority' => 20,
					)
				)
			);

			// Include Facebook.
			$wp_customize->add_setting(
				'sk_sharing_options_facebook',
				array(
					'type'                 => 'option',
					'capability'           => 'manage_options',
					'sanitize_callback'    => 'SK_Social_Sharing::sanitize_checkbox',
					'sanitize_js_callback' => 'SK_Social_Sharing::string_to_bool',
					'transport'            => 'refresh',
					'default'              => 'yes',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'sk_sharing_options_facebook',
					array(
						'type'            => 'checkbox',
						'label'           => esc_attr__( 'Include Facebook', 'shopkeeper-extender' ),
						'section'         => 'product',
						'priority'        => 20,
						'active_callback' => function() {
							return SK_Social_Sharing::string_to_bool( get_option( 'sk_sharing_options', 'yes' ) );
						},
					)
				)
			);

			// Include Facebook.
			$wp_customize->add_setting(
				'sk_sharing_options_facebook_meta',
				array(
					'type'                 => 'option',
					'capability'           => 'manage_options',
					'sanitize_callback'    => 'SK_Social_Sharing::sanitize_checkbox',
					'sanitize_js_callback' => 'SK_Social_Sharing::string_to_bool',
					'transport'            => 'refresh',
					'default'              => 'yes',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'sk_sharing_options_facebook_meta',
					array(
						'type'            => 'checkbox',
						'label'           => esc_attr__( 'Facebook Open Graph', 'shopkeeper-extender' ),
						'section'         => 'product',
						'priority'        => 20,
						'active_callback' => function() {
							return SK_Social_Sharing::string_to_bool( get_option( 'sk_sharing_options', 'yes' ) ) && SK_Social_Sharing::string_to_bool( get_option( 'sk_sharing_options_facebook', 'yes' ) );
						},
					)
				)
			);

			// Include Twitter.
			$wp_customize->add_setting(
				'sk_sharing_options_twitter',
				array(
					'type'                 => 'option',
					'capability'           => 'manage_options',
					'sanitize_callback'    => 'SK_Social_Sharing::sanitize_checkbox',
					'sanitize_js_callback' => 'SK_Social_Sharing::string_to_bool',
					'transport'            => 'refresh',
					'default'              => 'yes',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'sk_sharing_options_twitter',
					array(
						'type'            => 'checkbox',
						'label'           => esc_attr__( 'Include Twitter', 'shopkeeper-extender' ),
						'section'         => 'product',
						'priority'        => 20,
						'active_callback' => function() {
							return SK_Social_Sharing::string_to_bool( get_option( 'sk_sharing_options', 'yes' ) );
						},
					)
				)
			);

			// Include Pinterest.
			$wp_customize->add_setting(
				'sk_sharing_options_pinterest',
				array(
					'type'                 => 'option',
					'capability'           => 'manage_options',
					'sanitize_callback'    => 'SK_Social_Sharing::sanitize_checkbox',
					'sanitize_js_callback' => 'SK_Social_Sharing::string_to_bool',
					'transport'            => 'refresh',
					'default'              => 'yes',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'sk_sharing_options_pinterest',
					array(
						'type'            => 'checkbox',
						'label'           => esc_attr__( 'Include Pinterest', 'shopkeeper-extender' ),
						'section'         => 'product',
						'priority'        => 20,
						'active_callback' => function() {
							return SK_Social_Sharing::string_to_bool( get_option( 'sk_sharing_options', 'yes' ) );
						},
					)
				)
			);
		}

		/**
		 * Product social buttons.
		 *
		 * @since 1.4.2
		 * @return void
		 */
		public function getbowtied_single_share_product() {

			global $post, $product;

			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'large' );
			$image = isset( $image[0] ) ? $image[0] : '';
			?>

			<div class="product_socials_wrapper">

				<div class="share-product-text">
					<?php esc_html_e( 'Share this product', 'shopkeeper-extender' ); ?>
				</div>

				<div class="product_socials_wrapper_inner">

					<?php if ( self::string_to_bool( get_option( 'sk_sharing_options_facebook', 'yes' ) ) ) { ?>
						<div class="social_media social_media_facebook">
							<a target="_blank"
								class="icon"
								href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>"
								title="<?php esc_html_e( 'Facebook', 'shopkeeper-extender' ); ?>">
								<svg
									class="social-icon"
									xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
									width="21" height="21"
									viewBox="0 0 48 48">
									<path d="M 24 4 C 12.972066 4 4 12.972074 4 24 C 4 35.027926 12.972066 44 24 44 C 35.027934 44 44 35.027926 44 24 C 44 12.972074 35.027934 4 24 4 z M 24 7 C 33.406615 7 41 14.593391 41 24 C 41 32.380773 34.967178 39.306373 27 40.720703 L 27 29 L 30.625 29 C 31.129 29 31.555188 28.623047 31.617188 28.123047 L 31.992188 25.123047 C 32.028188 24.839047 31.938047 24.553891 31.748047 24.337891 C 31.559047 24.122891 31.287 24 31 24 L 27 24 L 27 20.5 C 27 19.397 27.897 18.5 29 18.5 L 31 18.5 C 31.552 18.5 32 18.053 32 17.5 L 32 14.125 C 32 13.607 31.604844 13.174906 31.089844 13.128906 C 31.030844 13.123906 29.619984 13 27.833984 13 C 23.426984 13 21 15.616187 21 20.367188 L 21 24 L 17 24 C 16.448 24 16 24.447 16 25 L 16 28 C 16 28.553 16.448 29 17 29 L 21 29 L 21 40.720703 C 13.032822 39.306373 7 32.380773 7 24 C 7 14.593391 14.593385 7 24 7 z"></path>
								</svg>
								<svg class="border-icon" width="100%" height="100%" viewBox="-1 -1 102 102">
									<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
								</svg>
							</a>
						</div>
					<?php } ?>

					<?php if ( self::string_to_bool( get_option( 'sk_sharing_options_twitter', 'yes' ) ) ) { ?>
						<div class="social_media social_media_twitter">
							<a target="_blank"
								class="icon"
								href="https://twitter.com/share?url=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?>"
								title="<?php esc_html_e( 'Twitter', 'shopkeeper-extender' ); ?>">
								<svg
									class="social-icon"
									xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
									width="21" height="21"
									viewBox="0 0 48 48">
									<path d="M 32 6 C 26.568583 6 22.160643 10.388731 22.042969 15.792969 C 17.240727 15.216998 14.113589 13.421507 12.195312 11.652344 C 10.067982 9.6903754 9.3945312 7.9472656 9.3945312 7.9472656 A 1.50015 1.50015 0 0 0 6.8007812 7.5996094 C 6.8007812 7.5996094 5 10 5 13.5 C 5 15.500985 5.6317952 16.981554 6.3847656 18.236328 C 6.3570276 18.223338 6.1699219 18.158203 6.1699219 18.158203 A 1.50015 1.50015 0 0 0 4.0058594 19.636719 C 4.0058594 19.636719 4.5832039 23.856843 8.5507812 26.941406 L 8.1367188 27.044922 A 1.50015 1.50015 0 0 0 7.1972656 29.244141 C 7.1972656 29.244141 7.8700527 30.382221 9.2792969 31.580078 C 10.11485 32.290298 11.34227 33.023169 12.789062 33.701172 C 11.012271 34.35044 8.362818 35 4.5 35 A 1.50015 1.50015 0 0 0 3.3710938 37.488281 C 3.3710938 37.488281 4.5173251 38.751002 6.7832031 39.849609 C 9.0490812 40.948217 12.539474 42 17.5 42 C 26.219697 42 32.484656 37.817151 36.394531 32.515625 C 40.304407 27.214099 42 20.861111 42 16 C 42 15.691547 41.980739 15.387437 41.953125 15.085938 C 44.064371 13.051602 44.856626 11.522235 44.894531 11.447266 C 45.084531 11.066266 45.01375 10.608688 44.71875 10.304688 C 44.42475 9.9996875 43.969031 9.9137969 43.582031 10.091797 L 43.419922 10.166016 C 43.280922 10.230016 43.141953 10.294422 43.001953 10.357422 C 43.408953 9.7084219 43.730125 9.014875 43.953125 8.296875 C 44.077125 7.900875 43.943234 7.4669375 43.615234 7.2109375 C 43.287234 6.9549375 42.835469 6.9275312 42.480469 7.1445312 C 41.258221 7.8873594 40.086652 8.40739 38.867188 8.7558594 C 37.072578 7.0534724 34.656873 6 32 6 z M 32 9 C 35.883178 9 39 12.116822 39 16 C 39 20.138889 37.445593 26.035901 33.980469 30.734375 C 30.515344 35.432849 25.280303 39 17.5 39 C 13.7348 39 11.230189 38.318942 9.3535156 37.582031 C 11.319341 37.276755 13.011947 36.869367 14.228516 36.398438 C 16.338182 35.581792 17.476563 34.638672 17.476562 34.638672 A 1.50015 1.50015 0 0 0 16.863281 32.044922 C 14.140556 31.364241 12.394328 30.263307 11.298828 29.345703 L 12.863281 28.955078 A 1.50015 1.50015 0 0 0 13.039062 26.099609 C 9.7939415 24.851486 8.4312292 23.086373 7.734375 21.607422 C 8.5823538 21.782967 9.3718961 22 10.5 22 A 1.50015 1.50015 0 0 0 11.169922 19.158203 C 11.169922 19.158203 8 17.7 8 13.5 C 8 12.745947 8.2088435 12.268355 8.3613281 11.697266 C 8.884507 12.400354 9.3156815 13.07859 10.160156 13.857422 C 12.734824 16.231954 16.990366 18.653154 23.419922 18.998047 A 1.50015 1.50015 0 0 0 25 17.5 L 25 16 C 25 12.116822 28.116822 9 32 9 z"></path>
								</svg>
								<svg class="border-icon" width="100%" height="100%" viewBox="-1 -1 102 102">
									<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
								</svg>
							</a>
						</div>
					<?php } ?>

					<?php if ( self::string_to_bool( get_option( 'sk_sharing_options_pinterest', 'yes' ) ) ) { ?>
						<div class="social_media social_media_pinterest">
							<a target="_blank"
								class="icon"
								href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url( the_permalink() ); ?>&amp;description=<?php the_title(); ?>&amp;media=<?php echo esc_attr( wp_kses_post( $image ) ); ?>"
								title="<?php esc_html_e( 'Pinterest', 'shopkeeper-extender' ); ?>"
								count-layout=”vertical”>
								<svg
									class="social-icon"
									xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
									width="21" height="21"
									viewBox="0 0 48 48">
									<path d="M 24 4 C 12.972066 4 4 12.972074 4 24 C 4 35.027926 12.972066 44 24 44 C 35.027934 44 44 35.027926 44 24 C 44 12.972074 35.027934 4 24 4 z M 24 7 C 33.406615 7 41 14.593391 41 24 C 41 33.406609 33.406615 41 24 41 C 22.566518 41 21.182325 40.804883 19.853516 40.472656 C 20.333373 39.496504 20.656022 38.535017 20.929688 37.529297 L 21.960938 33.460938 C 23.040937 34.480938 24.56 35 26.5 35 C 31.74 35 36 29.73 36 23.25 C 36 16.84 30.84 12 24 12 C 17.38 12 12 17.05 12 23.25 C 12 26.23 13.149922 28.940859 15.169922 30.880859 C 15.409922 31.110859 15.820391 30.980156 15.900391 30.660156 L 16.460938 28.330078 C 16.520937 28.090078 16.460313 27.840859 16.320312 27.630859 C 15.600312 26.610859 15 25.19 15 23.25 C 15 18.7 19.04 15 24 15 C 24.82 15 32 15.24 32 23.5 C 32 27.59 30.12 32 26 32 C 24.96 31.93 24.219062 31.589062 23.789062 31.039062 C 23.049063 30.059062 23.229219 28.399297 23.449219 27.529297 L 24.220703 24.509766 C 24.420703 23.709766 24.5 23.029219 24.5 22.449219 C 24.5 20.499219 23.32 19.5 22 19.5 C 20.22 19.5 19 21.389297 19 23.279297 C 19 24.309297 19.16 25.410625 19.5 26.390625 L 17.230469 35.810547 C 17.230469 35.810547 17 36.419922 17 38.419922 C 17 38.621315 17.035148 39.14999 17.050781 39.509766 C 11.123964 36.857341 7 30.926664 7 24 C 7 14.593391 14.593385 7 24 7 z"></path>
								</svg>
								<svg class="border-icon" width="100%" height="100%" viewBox="-1 -1 102 102">
									<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
								</svg>
							</a>
						</div>
					<?php } ?>

				</div>

			</div>

			<?php
		}

		/**
		 * Sanitize checkbox control.
		 *
		 * @param boolean $bool the input value.
		 * @return string sanitized value.
		 */
		public static function sanitize_checkbox( $bool ) {
			$bool = is_bool( $bool ) ? $bool : ( 'yes' === $bool || 1 === $bool || 'true' === $bool || '1' === $bool );

			return true === $bool ? 'yes' : 'no';
		}

		/**
		 * Convert string to boolean.
		 *
		 * @param string $string The string.
		 * @return boolean Converted value.
		 */
		public static function string_to_bool( $string ) {
			return is_bool( $string ) ? $string : ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
		}
	}

endif;

$sk_social_sharing = new SK_Social_Sharing();
