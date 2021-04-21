<?php

if ( ! class_exists( 'SKCategorySecondaryDescription' ) ) :

	/**
	 * SKCategorySecondaryDescription class.
	 *
	 * Adds a Secondary Description to WooCommerce Product Category.
	 *
	 * @since 2.0
	*/
	class SKCategorySecondaryDescription {

		/**
		 * The single instance of the class.
		 *
		 * @since 2.0
		 * @var SKCategorySecondaryDescription
		*/
		protected static $_instance = null;

		/**
		 * SKCategorySecondaryDescription constructor.
		 *
		 * @since 1.4
		*/
		public function __construct() {
			add_action( 'product_cat_add_form_fields', array( $this, 'woocommerce_add_category_secondary_description' ), 10, 2 );
			add_action( 'product_cat_edit_form_fields', array( $this, 'woocommerce_edit_category_secondary_description' ), 10, 2 );
			add_action( 'created_term', array( $this, 'woocommerce_category_secondary_description_save' ), 10, 3 );
			add_action( 'edit_term', array( $this, 'woocommerce_category_secondary_description_save' ), 10, 3 );

			add_action( 'woocommerce_after_shop_loop', array( $this, 'woocommerce_category_secondary_description_output' ) );
		}

		/**
		 * Ensures only one instance of SKCategorySecondaryDescription is loaded or can be loaded.
		 *
		 * @since 2.0
		 *
		 * @return SKCategorySecondaryDescription
		*/
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Category Secondary Description Add fields.
		 *
		 * @since 2.0
		 * @return void
		*/
		public function woocommerce_add_category_secondary_description() {
			?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="product_cat_secondary_description"><?php esc_html_e( 'Secondary Description', 'shopkeeper-extender' ); ?></label>
				</th>
				<td>
					<textarea name="product_cat_secondary_description" id="product_cat_secondary_description" rows="5" cols="40"></textarea>
					<p class="description"><?php esc_html_e( 'Detailed category info to appear below the product list', 'shopkeeper-extender' ); ?></p>
				</td>
			</tr>
			<?php
		}

		/**
		 * Category Secondary Description Edit fields.
		 *
		 * @since 2.0
		 * @return void
		*/
		public function woocommerce_edit_category_secondary_description($term) {
			$secondary_description = htmlspecialchars_decode( get_term_meta( $term->term_id, 'product_cat_secondary_description', true ) );
			?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="product_cat_secondary_description"><?php esc_html_e( 'Secondary Description', 'shopkeeper-extender' ); ?></label>
				</th>
				<td>
					<textarea name="product_cat_secondary_description" id="product_cat_secondary_description" rows="5" cols="40"><?php echo esc_html($secondary_description); ?></textarea>
					<p class="description"><?php esc_html_e( 'The description will be displayed below the product list.', 'shopkeeper-extender' ); ?></p>
				</td>
			</tr>
			<?php
		}

		/**
		 * Save category secondary description.
		 *
		 * @since 2.0
		 *
		 * @param mixed $term_id Term ID being saved
		 * @param mixed $tt_id
		 * @param mixed $taxonomy Taxonomy of the term being saved
		 *
		 * @return void
		 */
		public function woocommerce_category_secondary_description_save( $term_id, $tt_id, $taxonomy ) {
			if ( isset( $_POST['product_cat_secondary_description'] ) )
				update_term_meta( $term_id, 'product_cat_secondary_description', wp_kses_post( stripslashes( $_POST['product_cat_secondary_description'] ) ) );
		}

		/**
		 * Category Secondary Description Output.
		 *
		 * @since 2.0
		 * @return void
		*/
		public function woocommerce_category_secondary_description_output($term) {
			if ( is_product_taxonomy() ) {
				$term = get_queried_object();
				$secondary_description = get_term_meta( $term->term_id, 'product_cat_secondary_description', true );
				if( $term && !empty($secondary_description) ) {
					?>
					<div class="woocommerce-category-secondary-description large-6 large-centered columns">
						<?php echo wc_format_content( htmlspecialchars_decode($secondary_description) ); ?>
					</div>
					<?php
				}
			}
		}
	}

endif;

$sk_wc_cat_header = new SKCategorySecondaryDescription;
