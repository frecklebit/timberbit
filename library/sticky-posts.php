<?php
/**
 * Change the class for stickposts to .wp-sticky to avoid conflicts
 * with Foundation's Sticky plugin
 *
 * @package TimberPress
 * @since TimberPress 1.0.0
 */

if ( ! function_exists( 'timberpress_sticky_posts' ) ) :
function timberpress_sticky_posts( $classes ) {
	if ( in_array( 'sticky', $classes, true ) ) {
		$classes = array_diff( $classes, array( 'sticky' ) );
		$classes[] = 'wp-sticky';
	}
	return $classes;
}
add_filter( 'post_class', 'timberpress_sticky_posts' );
endif;
