<?php
/**
 * Add global data to Timber Context
 *
 * @package TimberPress
 * @since TimberPress 1.0.0
 */

if ( ! function_exists( 'timberpress_add_to_context' ) ) :
function timberpress_add_to_context( $context ) {

	// Add breadcrumbs to context
	if ( function_exists( 'timberpress_breadcrumb' ) ) {
		$context['breadcrumbs'] = timberpress_breadcrumb( true, false, true );
	}

	/**
	 * Custom logo and header text context
	 *
	 * @since TimberPress 1.0.0
	 */
	$context['has_custom_logo']     = has_custom_logo();
	$context['display_header_text'] = true;

	if ( has_custom_logo() ) {
		$context['the_custom_logo'] = get_custom_logo();
	}

	if ( has_custom_logo() && ! get_theme_mod( 'header_text' ) ) {
		$context['display_header_text'] = false;
	}

	/**
	 * WordPress Menus
	 */
	$context['topbar_menu'] = new TimberMenu();

  return $context;
}
add_filter( 'timber/context', 'timberpress_add_to_context' );
endif;
