<?php
/**
 * Adds Megamenu Menu and Image Output.
 *
 * @package  shopkeeper-extender
 */

/**
 * Custom Walker Output
 */
class SK_Ext_Custom_Menu_Output extends Walker_Nav_Menu {

	/**
	 * Start the element output.
	 *
	 * @see Walker_Nav_Menu::start_el()
	 * @since 3.0.0
	 *
	 * @global int $_wp_nav_menu_max_depth
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 * @param int    $id     Not used.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		global $wp_query;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = '';
		$value       = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names  = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names .= ( 0 === $depth && 'megamenu' === $item->megamenu ) ? ' mega-menu' : '';
		$class_names .= ( $depth > 0 && ! empty( $item->background_url ) ) ? ' with-hover-image' : '';
		$class_names  = ' class="' . esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="shopkeeper-menu-item-' . $item->ID . '"' . $value . $class_names . '>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		$prepend = '';
		$append  = '';

		if ( 0 !== $depth ) {
			$description = '';
			$append      = '';
			$prepend     = '';
		}

		$item_output = $args->before;

		if ( $depth > 0 && ! empty( $item->background_url ) ) {
			$item_output .= '<a class="has-hover-img" ' . $attributes . '><img class="menu-item-hover-image" src="' . $item->background_url . '" />';
		} else {
			$item_output .= '<a' . $attributes . '>';
		}

		$item_output .= $args->link_before . $prepend . apply_filters( 'the_title', $item->title, $item->ID ) . $append;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

		apply_filters( 'walker_nav_menu_start_lvl', $item_output, $depth, $args->background_url = $item->background_url );
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker_Nav_Menu::start_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$bg_class = '';
		$bg_style = '';
		if ( $depth <= 0 ) {
			if ( ! empty( $args->background_url ) ) {
				$bg_class = 'with_bg_image';
				$bg_style = 'style="background-image:url(' . $args->background_url . ');"';
			}
		}
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"sub-menu " . $bg_class . ' level-' . $depth . '" ' . $bg_style . ">\n";
	}
}
