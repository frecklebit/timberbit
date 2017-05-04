<?php
/**
 * Customize output of menus for Foundation mobile walker
 *
 * @package TimberPress
 * @since TimberPress 1.0.0
 */
if ( ! class_exists( 'Timberpress_Mobile_Walker' ) ) :
class Timberpress_Mobile_Walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"vertical nested menu\">";
	}
}
endif;
