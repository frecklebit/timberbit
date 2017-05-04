<?php
/**
 * Allows custom colors for the theme
 *
 * @package WordPress
 * @subpackage TimberPress
 * @since TimberPress 1.0.0
 */

 // Make like easy and store color options in an Array
 // Grouped by section
 $custom_colors = array(
 	'header_styles' => array(
 		'background_color' => array(
 			'label'    => __( 'Background color', TIMBERPRESS_DOMAIN ),
 			'default'  => '#262121',
 			'settings' => 'wpt_header_background_color',
			''
 		),
 		'text_color' => array(
 			'label'    => __( 'Text color', TIMBERPRESS_DOMAIN ),
 			'default'  => '#fefefe',
 			'settings' => 'wpt_header_text_color',
 		),
 		'link_color' => array(
 			'label'    => __( 'Link color', TIMBERPRESS_DOMAIN ),
 			'default'  => '#ce9426',
 			'settings' => 'wpt_header_link_color',
 		),
 		'link_hover_color' => array(
 			'label'    => __( 'Link hover color', TIMBERPRESS_DOMAIN ),
 			'default'  => '#ce8600',
 			'settings' => 'wpt_header_link_hover_color',
 		)
 	),
 );

/**
 * Add custom colors to WP Customizer
 *
 * @since  TimberPress 1.0.0
 * @param  object $wp_customize WP_Customize_manager object
 */
if ( ! function_exists( 'timberpress_register_custom_colors' ) ) :
function timberpress_register_custom_colors( $wp_customize ) {
	global $custom_colors;

	foreach ( $custom_colors as $section => $settings ) {
		foreach ( $settings as $control ) {

			// Add setting and set default color
			$wp_customize->add_setting( $control['settings'], array(
				'default' => $control['default'],
			) );

			// Add color control to options
			$wp_customize->add_control( new WP_Customize_Color_Control(
				$wp_customize,
				$control['settings'],
				array(
					'label'    => $control['label'],
					'section'  => $section,
					'settings' => $control['settings'],
				)
			) );

		}
	}
}
add_action( 'customize_register', 'timberpress_register_custom_colors', 10 );
endif;

/**
 * Add colors to wp_head
 */
if ( ! function_exists( 'timberpress_custom_colors_css' ) ):
function timberpress_custom_colors_css() {

	global $custom_colors;
	$context = array();

	foreach ( $custom_colors as $section => $settings ) {
		foreach ( $settings as $control ) {

			// Drop wpt_ for Twig variable
			$variable = str_replace( 'wpt_', '', $control['settings'] );

			// Set context
			$context[ $variable ] = get_theme_mod( $control['settings'], $control['default'] );

		}
	}

	Timber::render( 'partials/custom-colors-css.twig', $context );

}
add_action( 'wp_head', 'timberpress_custom_colors_css', 20 );
endif;
