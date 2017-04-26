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

  return $context;
}
endif;
