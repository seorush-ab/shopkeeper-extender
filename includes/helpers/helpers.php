<?php

function sk_bool_to_string( $bool ) {
	$bool = is_bool( $bool ) ? $bool : ( 'yes' === $bool || 1 === $bool || 'true' === $bool || '1' === $bool );

	return true === $bool ? 'yes' : 'no';
}

function sk_string_to_bool( $string ) {
	return is_bool( $string ) ? $string : ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
}

function sk_sanitize_checkbox( $input ) {
	return sk_bool_to_string($input);
}

function sk_sanitize_repeater( $input ) {
	$input_decoded = json_decode($input,true);

	if(!empty($input_decoded)) {
		foreach ($input_decoded as $boxk => $box ){
			foreach ($box as $key => $value){
				$input_decoded[$boxk][$key] = wp_kses_post( force_balance_tags( $value ) );
			}
		}

		return json_encode($input_decoded);
	}

	return $input;
}

/**
 * Checks if topbar is enabled.
 */
function sk_ext_is_topbar_enabled(){

    return get_theme_mod( 'top_bar_switch', false );
}

// Other Plugins

function gbt_other_plugins_enqueue_scripts() {
   wp_enqueue_script( 'gbt-other-plugins', plugins_url( 'js/other_plugins.js', __FILE__ ), array('jquery') );
}
add_action( 'admin_enqueue_scripts', 'gbt_other_plugins_enqueue_scripts' );



// Admin notices

function shopkeeper_extender_notifications() {

	if ( !get_option('dismissed-sk-ext-notification-remove-additional-js', FALSE) ) {
		?>
		<div class="notice-error settings-error notice is-dismissible remove-additional-js-notice" style="border-width: 3px; border-color: #d63638;">
			<p style="padding: 30px">
				<strong style="color: #d63638">IMPORTANT NOTICE!</strong> <strong>YOUR ACTION IS REQUIRED: </strong>Please migrate your code from <em>Appearance > Customize > Additional JS</em>. This section will be removed in the next update and your code may be at risk.<br /><a href="<?php echo admin_url("customize.php?autofocus[section]=sk_custom_code"); ?>"><strong>Migrate your code now</strong></a> or <a href="https://www.getbowtied.com/documentation/shopkeeper/important-notice/" target="_blank"><strong>Read more</strong></a>.
			</p>
		</div>
		<?php
	}

	return;
}
add_action( 'admin_notices', 'shopkeeper_extender_notifications' );


// Admin dismiss notices

function shopkeeper_extender_notifications_dismiss() {
	if( $_POST['notice'] == 'remove-additional-js-notice' ) {
		update_option('dismissed-sk-ext-notification-remove-additional-js', TRUE );
	}
}
add_action( 'wp_ajax_sk_ext_notifications_dismiss', 'shopkeeper_extender_notifications_dismiss' );
