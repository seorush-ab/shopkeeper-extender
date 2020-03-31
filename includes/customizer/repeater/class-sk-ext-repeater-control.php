<?php
/**
 * Options Repeater control class
 *
 * @package SK_Customize_Repeater_Control
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	include ABSPATH . WPINC . '/class-wp-customize-control.php';
}

/**
 * Class SK_Customize_Repeater_Control
 */
class SK_Ext_Customize_Repeater_Control extends WP_Customize_Control {

	/**
	 * Social Field ID.
	 *
     * @var string
	 */
	public $id;

	/**
	 * Social Media Profiles.
	 *
	 * Used for icons dropdown list.
	 *
     * @var array
	 */
	public $profiles = array();

	/**
	 * Constructor.
	 *
	 * Supplied `$args` override class property defaults.
	 *
	 * If `$args['settings']` is not defined, use the $id as the setting ID.
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Optional. Arguments to override class property defaults.
	 */
	public function __construct( $manager, $id, $args = array() ) {

		parent::__construct( $manager, $id, $args );

		if ( !empty( $args['profiles'] ) ) {
			$this->profiles = $args['profiles'];
		}

		if ( !empty( $id ) ) {
			$this->id = $id;
		}
	}

	/**
	 * Render the control's content.
	 */
	public function render_content() {

		// Get default options.
		$this_default = json_decode( $this->setting->default );

		// Get values.
		$values = $this->value();
		$json = json_decode( $values );

		if ( ! is_array( $json ) ) {
			$json = array( $values );
		}
		?>

		<div class="customizer-repeater-general-control-repeater customizer-repeater-general-control-droppable">
			<?php
			if ( ( count( $json ) == 1 && '' === $json[0] ) || empty( $json ) ) {
				if ( ! empty( $this_default ) ) {
					$this->iterate_array( $this_default ); ?>
					<input type="hidden"
					id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
					class="customizer-repeater-colector"
					value="<?php echo esc_textarea( json_encode( $this_default ) ); ?>"/>
					<?php
				} else {
					$this->iterate_array(); ?>
					<input type="hidden"
					id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
					class="customizer-repeater-colector"/>
					<?php
				}
			} else {
				$this->iterate_array( $json ); ?>
				<input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?>
				class="customizer-repeater-colector" value="<?php echo esc_textarea( $this->value() ); ?>"/>
				<?php
			} ?>
		</div>
		<button type="button" class="button add_field customizer-repeater-new-field">
			<?php esc_html_e( 'Add a Profile', 'shopkeeper-extender' ); ?>
		</button>

		<?php
	}

	/**
	 * Render the social media's content.
	 *
	 * @param array $array Social media profiles list.
	 */
	private function iterate_array( $array = array() ) {

		if( !empty( $array ) ) {
			foreach( $array as $icon ) {
				?>
				<div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable">
					<div class="customizer-repeater-customize-control-title">
						<?php echo !empty( $icon->title ) ? esc_html( $icon->title ) : esc_html( 'Social Media Profile', 'shopkeeper-extender' ); ?>
					</div>
					<div class="customizer-repeater-box-content-hidden">
						<?php

						$id 		= !empty( $icon->id ) ? $icon->id : '';
						$link 		= !empty( $icon->link ) ? $icon->link : '';
						$title 		= !empty( $icon->title ) ? $icon->title : '';
						$choice 	= !empty( $icon->choice ) ? $icon->choice : '';
						$icon_slug 	= !empty( $icon->icon_slug ) ? $icon->icon_slug : '';
						$image_url 	= !empty( $icon->image_url ) ? $icon->image_url : '';

						$this->image_type_choice( $choice );
						$this->image_control( $image_url, $choice );
						$this->theme_default_icon_control( $icon_slug, $choice );

						$this->input_control( array(
							'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Title:','shopkeeper-extender' ), $this->id, 'customizer_repeater_title_control' ),
							'class' => 'customizer-repeater-title-control',
							'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' ),
							'sanitize_callback' => 'esc_html',
						), $title );

						$this->input_control( array(
							'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Link:','shopkeeper-extender' ), $this->id, 'customizer_repeater_link_control' ),
							'class' => 'customizer-repeater-link-control',
							'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
							'sanitize_callback' => 'esc_url_raw',
						), $link );

						?>

						<input type="hidden" class="social-repeater-box-id" value="<?php echo esc_attr( $id ); ?>">
						<button type="button" class="social-repeater-general-control-remove-field">
							<?php esc_html_e( 'Remove', 'shopkeeper-extender' ); ?>
						</button>
						<span>|</span>
						<button type="button" class="social-repeater-general-control-done-field">
							<?php esc_html_e( 'Done', 'shopkeeper-extender' ); ?>
						</button>
					</div>
				</div>
				<?php
			}
		} else { ?>
			<div class="customizer-repeater-general-control-repeater-container">
				<div class="customizer-repeater-customize-control-title">
					<?php esc_html_e( 'Social Media Profile', 'shopkeeper-extender' ); ?>
				</div>
				<div class="customizer-repeater-box-content-hidden">
					<?php

					$this->image_type_choice();
					$this->theme_default_icon_control();
					$this->image_control();

					$this->input_control( array(
						'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Title:','shopkeeper-extender' ), $this->id, 'customizer_repeater_title_control' ),
						'class' => 'customizer-repeater-title-control',
						'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' ),
						'sanitize_callback' => 'esc_html',
					) );

					$this->input_control( array(
						'label' => apply_filters('repeater_input_labels_filter', esc_html__( 'Link:','shopkeeper-extender' ), $this->id, 'customizer_repeater_link_control' ),
						'class' => 'customizer-repeater-link-control',
						'type'  => apply_filters('customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
						'sanitize_callback' => 'esc_url_raw',
					) );

					?>
					<input type="hidden" class="social-repeater-box-id">
					<button type="button" class="social-repeater-general-control-remove-field">
						<?php esc_html_e( 'Remove', 'shopkeeper-extender' ); ?>
					</button>
					<span>|</span>
					<button type="button" class="social-repeater-general-control-done-field">
						<?php esc_html_e( 'Done', 'shopkeeper-extender' ); ?>
					</button>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Render the image type select.
	 *
	 * @param string $value Social media image type.
	 */
	private function image_type_choice( $value = 'customizer_repeater_theme_default' ) {
		?>
		<span class="customize-control-title">
			<?php esc_html_e('Image Type:','shopkeeper-extender');?>
		</span>

		<select class="customizer-repeater-image-choice">
			<option value="customizer_repeater_theme_default" <?php selected( $value, 'customizer_repeater_theme_default' );?>>
				<?php esc_html_e( 'Theme Default Icons','shopkeeper-extender' ); ?>
			</option>
			<option value="customizer_repeater_image" <?php selected( $value, 'customizer_repeater_image' );?>>
				<?php esc_html_e( 'Custom Image','shopkeeper-extender' ); ?>
			</option>
		</select>
		<?php
	}

	/**
	 * Render the input field.
	 *
	 * @param array  $options Input settings.
	 * @param string $value Input value.
	 */
	 private function input_control( $options, $value = '' ) {
		?>
		<span class="customize-control-title"><?php esc_html_e( $options['label'] ); ?></span>
		<input type="text" value="<?php esc_attr_e( $value ); ?>" class="<?php esc_attr_e( $options['class'] ); ?>" placeholder="<?php esc_attr_e( $options['label'] ); ?>"/>
		<?php
	}

	/**
	 * Render the theme default icon dropdown.
	 *
	 * @param string $value Select value.
	 * @param string $show Determines if the dropdown should be displayed based on image type choice.
	 */
	private function theme_default_icon_control( $value = 'facebook', $show = '' ) {
		?>
		<div class="customizer-repeater-theme-default-icon-control" <?php echo ( $show === 'customizer_repeater_image' || empty( $show ) ) ? 'style="display:none;"' : ''; ?>>
			<span class="customize-control-title">
				<?php esc_html_e('Icon:','shopkeeper-extender'); ?>
			</span>

			<select class="customizer-repeater-theme-default-icon-choice">
				<?php foreach( $this->profiles as $profile ) { ?>
					<option value="<?php esc_attr_e( $profile['slug'] ); ?>" <?php selected( $value, $profile['slug'] ); ?>>
						<?php esc_html_e( $profile['name'],'shopkeeper-extender'); ?>
					</option>
				<?php } ?>
			</select>
		</div>
		<?php
	}

	/**
	 * Render the image control.
	 *
	 * @param string $value Select value.
	 * @param string $show Determines if the control should be displayed based on image type choice.
	 */
	private function image_control( $value = '', $show = '' ) {
		?>
		<div class="customizer-repeater-image-control" <?php echo ( $show === 'customizer_repeater_theme_default' || empty( $show ) ) ? 'style="display:none;"' : ''; ?>>
			<span class="customize-control-title">
				<?php esc_html_e('Image:','shopkeeper-extender')?>
			</span>

			<input type="text" class="widefat custom-media-url" value="<?php esc_attr_e( $value ); ?>">
			<input type="button" class="button button-secondary customizer-repeater-custom-media-button" value="<?php esc_attr_e( 'Add Image','shopkeeper-extender' ); ?>" />
		</div>
		<?php
	}

	/**
	 * Enqueue control related scripts/styles.
	 */
	 public function enqueue() {

		wp_enqueue_style(
			'sk-ext-customizer-repeater-styles',
			plugins_url( 'assets/css/customizer_repeater.css', __FILE__ ),
			array(),
			SK_EXT_VERSION
		);

		wp_enqueue_script(
			'sk-ext-customizer-repeater-script',
			plugins_url( 'assets/js/customizer_repeater.js', __FILE__ ),
			array( 'jquery', 'jquery-ui-draggable' )
		);
	}
}
