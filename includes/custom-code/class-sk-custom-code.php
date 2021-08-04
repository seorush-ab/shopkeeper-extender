<?php
/**
 * Adds Custom Code.
 *
 * @package  shopkeeper-extender
 */

if ( ! class_exists( 'SK_Custom_Code' ) ) :

	/**
	 * SK_Custom_Code class.
	 *
	 * @since 1.4.2
	 */
	class SK_Custom_Code {

		/**
		 * The single instance of the class.
		 *
		 * @since 1.4.2
		 * @var SK_Custom_Code
		 */
		protected static $instance = null;

		/**
		 * SK_Custom_Code constructor.
		 *
		 * @since 1.4.2
		 */
		public function __construct() {

			add_action( 'customize_register', array( $this, 'sk_custom_code_customizer' ) );
		}

		/**
		 * Ensures only one instance of SK_Custom_Code is loaded or can be loaded.
		 *
		 * @since 1.4.2
		 *
		 * @return SK_Custom_Code
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Creates customizer options.
		 *
		 * @param object $wp_customize WP Customize.
		 * @since 1.4.2
		 * @return void
		 */
		public function sk_custom_code_customizer( $wp_customize ) {

			// Section.
			$wp_customize->add_section(
				'sk_custom_code',
				array(
					'title'    => esc_attr__( 'Additional JS', 'shopkeeper-extender' ),
					'priority' => 201,
				)
			);

			$wp_customize->add_setting(
				'sk_custom_code_header_js',
				array(
					'type'       => 'option',
					'capability' => 'manage_options',
					'transport'  => 'refresh',
					'default'    => '',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'sk_custom_code_header_js',
					array(
						'type'        => 'textarea',
						'label'       => esc_attr__( 'Header JavaScript Code', 'shopkeeper-extender' ),
						'description' => wp_kses_post( 'Deprecated: The custom code added in this section is no longer applied. We recommend a plugin that securely adds your custom code. (e.g. <a href="https://wordpress.org/plugins/custom-css-js/" target="_blank">Simple Custom CSS and JS</a>). This section will be removed in a future update, but until then, you can use it to copy your custom code.' ),
						'section'     => 'sk_custom_code',
						'priority'    => 10,
					)
				)
			);

			$wp_customize->add_setting(
				'sk_custom_code_footer_js',
				array(
					'type'       => 'option',
					'capability' => 'manage_options',
					'transport'  => 'refresh',
					'default'    => '',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'sk_custom_code_footer_js',
					array(
						'type'        => 'textarea',
						'label'       => esc_attr__( 'Footer JavaScript Code', 'shopkeeper-extender' ),
						'description' => wp_kses_post( 'Deprecated: The custom code added in this section is no longer applied. We recommend a plugin that securely adds your custom code. (e.g. <a href="' . esc_url( 'https://wordpress.org/plugins/custom-css-js/' ) . '" target="_blank">Simple Custom CSS and JS</a>). This section will be removed in a future update, but until then, you can use it to copy your custom code.' ),
						'section'     => 'sk_custom_code',
						'priority'    => 10,
					)
				)
			);
		}
	}

endif;

$sk_custom_code = new SK_Custom_Code();
