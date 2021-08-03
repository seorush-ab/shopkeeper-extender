<?php
/**
 * Plugin Name:             Shopkeeper Extender
 * Plugin URI:              https://shopkeeper.wp-theme.design/
 * Description:             Extends the functionality of Shopkeeper with theme specific features.
 * Version:                 2.0-beta1
 * Author:                  GetBowtied
 * Author URI:              https://getbowtied.com
 * Text Domain:             shopkeeper-extender
 * Domain Path:             /languages/
 * Requires at least:       5.0
 * Tested up to:            5.5.3
 *
 * @package  shopkeeper-extender
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

// Plugin Updater.
require 'core/updater/plugin-update-checker.php';
$my_update_checker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/getbowtied/shopkeeper-extender/master/core/updater/assets/plugin.json',
	__FILE__,
	'shopkeeper-extender'
);

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

define( 'SK_EXT_ENQUEUE_SUFFIX', SCRIPT_DEBUG ? '' : '.min' );

$version = ( isset( get_plugin_data( __FILE__ )['Version'] ) && ! empty( get_plugin_data( __FILE__ )['Version'] ) ) ? get_plugin_data( __FILE__ )['Version'] : '1.0';
define( 'SK_EXT_VERSION', $version );

if ( ! class_exists( 'Shopkeeper_Extender' ) ) :

	/**
	 * Shopkeeper_Extender class.
	 */
	class Shopkeeper_Extender {

		/**
		 * The single instance of the class.
		 *
		 * @var Shopkeeper_Extender
		 */
		protected static $instance = null;

		/**
		 * Ensures only one instance of Shopkeeper_Extender is loaded or can be loaded.
		 *
		 * @return Shopkeeper_Extender
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Shopkeeper_Extender constructor.
		 */
		public function __construct() {

			add_action( 'wp_enqueue_scripts', array( $this, 'add_plugin_styles' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_vendor_scripts' ), 99 );
			add_action( 'after_setup_theme', array( $this, 'load_plugin_files' ) );

			// Add Shortcodes to WP Bakery.
			if ( defined( 'WPB_VC_VERSION' ) ) {
				add_action( 'init', array( $this, 'add_wpbakery_shortcodes' ) );
			}
		}

		/**
		 * Load Plugin Files.
		 */
		public function load_plugin_files() {
			// Customizer.
			include_once dirname( __FILE__ ) . '/includes/customizer/repeater/class-sk-ext-customize-repeater-control.php';

			// Shortcodes.
			include_once dirname( __FILE__ ) . '/includes/shortcodes/wp/posts-slider.php';
			include_once dirname( __FILE__ ) . '/includes/shortcodes/wp/banner.php';
			include_once dirname( __FILE__ ) . '/includes/shortcodes/wp/slider.php';

			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				include_once dirname( __FILE__ ) . '/includes/shortcodes/wc/categories-grid.php';
			}

			// Social Media.
			include_once dirname( __FILE__ ) . '/includes/social-media/class-sk-social-media.php';

			// Widgets.
			include_once 'includes/widgets/class-sk-social-media-widget.php';

			// Gutenberg Blocks.
			include_once dirname( __FILE__ ) . '/includes/gbt-blocks/index.php';

			// Shopkeeper Dependent Components.
			if ( class_exists( 'Shopkeeper' ) ) {

				// Custom Menu.
				include_once dirname( __FILE__ ) . '/includes/custom-menu/index.php';

				// Custom Code Section.
				include_once dirname( __FILE__ ) . '/includes/custom-code/class-sk-custom-code.php';

				if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
					// Social Sharing Buttons.
					include_once dirname( __FILE__ ) . '/includes/social-sharing/class-sk-social-sharing.php';

					// Addons.
					include_once dirname( __FILE__ ) . '/includes/addons/class-sk-category-secondary-description.php';
					include_once dirname( __FILE__ ) . '/includes/addons/class-sk-category-header-image.php';
				}
			}
		}

		/**
		 * Add Plugin Styles.
		 */
		public function add_plugin_styles() {

			wp_enqueue_style( 'shopkeeper-extender', plugins_url( 'assets/css/styles.css', __FILE__ ), null, SK_EXT_VERSION, 'all' );
		}

		/**
		 * Add Vendor Scripts.
		 */
		public function add_vendor_scripts() {
			wp_register_style(
				'swiper',
				plugins_url( 'assets/swiper/css/swiper.min.css', __FILE__ ),
				array(),
				'6.4.1'
			);
			wp_register_script(
				'swiper',
				plugins_url( 'assets/swiper/js/swiper.min.js', __FILE__ ),
				array(),
				'6.4.1',
				true
			);
		}

		/**
		 * Add WPBakery Elements.
		 */
		public function add_wpbakery_shortcodes() {
			include_once dirname( __FILE__ ) . '/includes/shortcodes/wb/posts-slider.php';
			include_once dirname( __FILE__ ) . '/includes/shortcodes/wb/banner.php';
			include_once dirname( __FILE__ ) . '/includes/shortcodes/wb/slider.php';

			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				include_once dirname( __FILE__ ) . '/includes/shortcodes/wb/categories-grid.php';
			}
		}
	}
endif;

$shopkeeper_extender = new Shopkeeper_Extender();
