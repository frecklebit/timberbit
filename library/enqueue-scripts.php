<?php
/**
 * Enqueue all styles and scripts
 *
 * @package TimberPress
 * @since TimberPress 1.0.0
 */

if ( ! function_exists( 'timberpress_scripts' ) ) :
function timberpress_scripts() {

	// Enqueue the main Stylesheet
	wp_enqueue_style( 'main-stylesheet', get_template_directory_uri() . '/assets/stylesheets/style.css', array(), TIMBERPRESS_VERSION, 'all' );

	// Deregister the jquery version bundled with WordPress
	wp_deregister_script( 'jquery' );

	// CDN Hosted jQuery placed in the header, as some plugins require that
	// jQuery is loaded in the header
	wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js', array(), TIMBERPRESS_VERSION, false );

	// Load our compile scripts in the footer
	wp_enqueue_script( 'foundation', get_template_directory_uri() . '/assets/javascript/foundation.js', array( 'jquery' ), TIMBERPRESS_VERSION, true );

	// Add the comment-reply library on pages where it is necessary
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'timberpress_scripts' );
endif;

/**
 * Enqueue custom customize scripts
 */
if ( ! function_exists( 'timberpress_customize_enqueue' ) ):
function timberpress_customize_enqueue() {

	// Enqueue customizer scripts
	wp_enqueue_script( 'timberpress-customize', get_template_directory_uri() . '/assets/javascript/customize.js', array( 'jquery', 'customize-controls' ), false, true );

}
add_action( 'customize_controls_enqueue_scripts', 'timberpress_customize_enqueue' );
endif;
