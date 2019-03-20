<?php

// Import Social Media Options from Theme Mods

if( !get_option( 'social_media_options_import', false ) ) {
	$done_import = import_options();
	if( $done_import ) {
		update_option( 'social_media_options_import', true );
	}
}

function import_options() {
	$done_import = true;

	global $social_media_profiles;

	foreach( $social_media_profiles as $social) {
		if( get_theme_mod( $social['link'] ) ) {
			update_option( 'sk_' . $social['link'], get_theme_mod( $social['link'], '' ) );
			if( get_option( 'sk_' . $social['link'] ) ) {
				remove_theme_mod( $social['link'] );
			} else {
				$done_import = false;
			}
		}
	}

	if( get_theme_mod( 'top_bar_social_icons' ) ) {
		update_option( 'sk_top_bar_social_icons', get_theme_mod( 'top_bar_social_icons' ) );
		if( get_option( 'sk_top_bar_social_icons' ) ) {
			remove_theme_mod( 'top_bar_social_icons' );
		} else {
			$done_import = false;
		}
	}

	if( get_theme_mod( 'footer_social_icons' ) ) {
		update_option( 'sk_footer_social_icons', get_theme_mod( 'footer_social_icons' ) );
		if( get_option( 'sk_footer_social_icons' ) ) {
			remove_theme_mod( 'footer_social_icons' );
		} else {
			$done_import = false;
		}
	}

	return $done_import;
}