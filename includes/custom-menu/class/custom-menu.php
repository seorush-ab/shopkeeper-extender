<?php

/**
 * Class that adds attributes to WordPress menus
 */
class SK_Extender_Custom_Menu_Attributes {

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'sk_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'sk_update_custom_nav_fields'), 10, 3 );

		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'sk_edit_walker'), 10, 2 );

	}

	/**
	 * Add custom fields to $item nav object in order to be used in custom Walker
	 *
	 * @return      void
	*/
	public function sk_add_custom_nav_fields( $menu_item ) {

		$menu_item->background_url = get_post_meta( $menu_item->ID, '_menu_item_background_url', true );

		return $menu_item;

	}

	/**
	 * Save menu custom fields
	 *
	 * @return      void
	*/
	public function sk_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

	    // Check if element is properly sent
	    if ( !empty( $_REQUEST['menu-item-background_url']) ) {

	    	if (isset($_REQUEST['menu-item-background_url'][$menu_item_db_id])) {
		        $background_url_value = $_REQUEST['menu-item-background_url'][$menu_item_db_id];
		        update_post_meta( $menu_item_db_id, '_menu_item_background_url', $background_url_value );
		    }
	    }

	}

	/**
	 * Define new Walker edit
	 *
	 * @return      void
	*/
	public function sk_edit_walker($walker,$menu_id) {
		return 'Walker_Nav_Menu_Edit_Custom';
	}

}
$sk_ext_custom_menu = new SK_Extender_Custom_Menu_Attributes();
