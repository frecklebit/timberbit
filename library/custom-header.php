<?php
/**
 * Allows the header to be customized
 *
 * @package WordPress
 * @subpackage TimberPress
 * @since TimberPress 1.0.0
 */

define( 'TP_MOBILE_MENU_DEFAULT', 			 			'offcanvas' );
define( 'TP_OFFCANVAS_POSITION_DEFAULT', 			'position-right' );
define( 'TP_HEADER_CONTAINER_DEFAULT', 	 			'fixed' );

if ( ! function_exists( 'timberpress_register_custom_header' ) ) :
function timberpress_register_custom_header( $wp_customize ) {

	/**
	 * Allows the header to be customized
	 *
	 * @since TimberPress 1.0.0
	 */
	$wp_customize->add_panel( 'header_settings', array(
		'priority'       => 1000,
		'theme_supports' => '',
		'title'          => __( 'Header', 'timberpress' ),
		'description'    => __( 'Controls the header', 'timberpress' ),
	) );

	/**
	 * Create a section for header styles
	 *
	 * @since TimberPress 1.0.0
	 */
	$wp_customize->add_section( 'header_styles', array(
		'title'    => __( 'Header styles', 'timberpress' ),
		'panel'    => 'header_settings',
		'priority' => 1000,
	) );

	/**
	 * Set default header container
	 *
	 * @since TimberPress 1.0.0
	 */
	$wp_customize->add_setting( 'wpt_header_container', array(
		'default' => __( TP_HEADER_CONTAINER_DEFAULT, 'timberpress' ),
	) );

	/**
	 * Add options for header container
	 *
	 * @since TimberPress 1.0.0
	 */
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'wpt_header_container',
			array(
				'label'       => __( 'Container', 'timberpress' ),
				'description' => __( 'Whether the header should align with content or be fluid', 'timberpress' ),
				'type'        => 'select',
				'section'     => 'header_styles',
				'settings'    => 'wpt_header_container',
				'choices'     => array(
					'fixed' => __( 'Fixed Width (1200px)', 'timberpress' ),
					'fluid' => __( 'Fluid Width', 'timberpress' ),
				),
			)
		)
	);

	/**
	 * Create custom field for mobile navigation layout
	 *
	 * @since TimberPress 1.0.0
	 */
	$wp_customize->add_section( 'mobile_menu_layout', array(
		'title'    => __( 'Mobile navigation layout', 'timberpress' ),
		'panel'    => 'header_settings',
		'priority' => 1000,
	) );

	/**
	 * Set default navigation layout
	 *
	 * @since TimberPress 1.0.0
	 */
	$wp_customize->add_setting( 'wpt_mobile_menu_layout', array(
		'default'   => __( TP_MOBILE_MENU_DEFAULT, 'timberpress' ),
	) );

	/**
	 * Add options for navigation layout
	 *
	 * @since TimberPress 1.0.0
	 */
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'wpt_mobile_menu_layout',
			array(
				'label'       => __( 'Layout', 'timberpress' ),
				'description' => __( 'How the mobile menu should be displayed.', 'timberpress' ),
				'type'        => 'radio',
				'section'     => 'mobile_menu_layout',
				'settings'    => 'wpt_mobile_menu_layout',
				'choices'     => array(
					'topbar'    => __( 'Topbar', 'timberpress' ),
					'offcanvas' => __( 'Off-canvas', 'timberpress' ),
				),
			)
		)
	);

	/**
	 * Set the default offcanvase position
	 *
	 * @since TimberPress 1.0.0
	 */
	$wp_customize->add_setting( 'wpt_offcanvas_position', array(
		'default' => __( TP_OFFCANVAS_POSITION_DEFAULT, 'timberpress' ),
	) );

	/**
	 * Add options for offcanvas position
	 *
	 * @since TimberPress 1.0.0
	 */
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'wpt_offcanvas_position',
			array(
				'label'       => __( 'Off-canvas Positioning' ),
				'description' => __( 'Which side of the viewport it should open from.', 'timberpress' ),
				'type'        => 'radio',
				'section'     => 'mobile_menu_layout',
				'settings'    => 'wpt_offcanvas_position',
				'choices'     => array(
					'position-left'  => __( 'Position Left', 'timberpress' ),
					'position-right' => __( 'Position Right', 'timberpress' ),
				),
			)
		)
	);

}
add_action( 'customize_register', 'timberpress_register_custom_header' );
endif;

/**
 * Add class to body to help w/ CSS
 *
 * @since TimberPress 1.0.0
 * @param array $classes Array of body classes
 */
if ( ! function_exists( 'timberpress_add_mobile_menu_to_body_class' ) ) :
function timberpress_add_mobile_menu_to_body_class( $classes ) {

	$classes[] = get_theme_mod( 'mobile_menu_layout', TP_MOBILE_MENU_DEFAULT );

	return $classes;
}
add_filter( 'body_class', 'timberpress_add_mobile_menu_to_body_class' );
endif;

/**
 * Add header options to context
 *
 * @since 1.0.0
 * @param  array $context Timber context
 * @return array          Filtered context
 */
if ( ! function_exists( 'timberpress_add_custom_header_to_context' ) ) :
function timberpress_add_custom_header_to_context( $context ) {

	// Mobile menu button position
	$context['menu_button_position'] = 'left';

	if ( 'offcanvas' === get_theme_mod( 'wpt_mobile_menu_layout', TP_MOBILE_MENU_DEFAULT ) ) {

		// Mobile menu ID for menu toggle button
		$context['mobile_menu_id'] = 'off-canvas-menu';

		// Mobile menu layout
		$context['mobile_menu_layout'] = 'offcanvas';

		// Offcanvas position
		$context['offcanvas_position'] = get_theme_mod( 'wpt_offcanvas_position', TP_OFFCANVAS_POSITION_DEFAULT );

		// Mobile menu position
		if ( 'position-right' === get_theme_mod( 'wpt_offcanvas_position', TP_OFFCANVAS_POSITION_DEFAULT ) ) {
			$context['menu_button_position'] = 'right';
		}

	} else {

		// Mobile menu ID for menu toggle button
		$context['mobile_menu_id'] = 'mobile-menu';

		// Mobile menu layout
		$context['mobile_menu_layout'] = 'topbar';

	}

	// Custom header styles
	$context['header_container'] = get_theme_mod( 'wpt_header_container', 'fixed' );

	return $context;
}
add_filter( 'timber/context', 'timberpress_add_custom_header_to_context' );
endif;

/**
 * Add the opening tags for Offcanvas
 *
 * @since TimberPress 1.0.0
 */
if ( ! function_exists( 'timberpress_add_offcanvas_layout_open' ) ) :
function timberpress_add_offcanvas_layout_open() {

	// First check to see if we're using offcanvas
	if ( 'offcanvas' !== get_theme_mod( 'wpt_mobile_menu_layout', TP_MOBILE_MENU_DEFAULT ) ) {
		return;
	}

	// Add the offcanvas wrapper opening tag
	echo '<div class="off-canvas-wrapper">';

	// Add the Offcanvas menu
	echo Timber::compile( 'partials/mobile-off-canvas.twig', array(
		'mobile_menu_id'     => 'off-canvas-menu',
		'offcanvas_position' => get_theme_mod( 'wpt_offcanvas_position', TP_OFFCANVAS_POSITION_DEFAULT ),
	) );

	// Add the offcanvas content opening tag
	echo "\t" . '<div class="off-canvas-content" data-off-canvas-content>';

}
add_action( 'timberpress_layout_start', 'timberpress_add_offcanvas_layout_open' );
endif;

/**
 * Add the closing tags for Offcanvas
 *
 * @since TimberPress 1.0.0
 */
if ( ! function_exists( 'timberpress_add_offcanvas_layout_close' ) ) :
function timberpress_add_offcanvas_layout_close() {

	// First check to see if we're using offcanvas
	if ( 'offcanvas' !== get_theme_mod( 'wpt_mobile_menu_layout', TP_MOBILE_MENU_DEFAULT ) ) {
		return;
	}

	// Add the offcanvas content closing tag
	echo "\t" . '</div>';

	// Add the offcanvas wrapper closing tag
	echo '</div>';

}
add_action( 'timberpress_layout_end', 'timberpress_add_offcanvas_layout_close' );
endif;

/**
 * Add the opening wrapper for topbar if fixed width
 *
 * @since TimberPress 1.0.0
 */
if ( ! function_exists( 'timberpress_add_fixed_header_container_open' ) ) :
function timberpress_add_fixed_header_container_open() {

	// First find out if fixed container is set
	if ( 'fixed' !== get_theme_mod( 'wpt_header_container', TP_HEADER_CONTAINER_DEFAULT ) ) {
		return;
	}

	// Add opening column
	echo '<div class="row column">';

}
add_action( 'timberpress_before_header', 'timberpress_add_fixed_header_container_open' );
endif;

/**
 * Add the closing wrapper for topbar if fixed width
 *
 * @since TimberPress 1.0.0
 */
if ( ! function_exists( 'timberpress_add_fixed_header_container_close' ) ) :
function timberpress_add_fixed_header_container_close() {

	// First find out if fixed container is set
	if ( 'fixed' !== get_theme_mod( 'wpt_header_container', TP_HEADER_CONTAINER_DEFAULT ) ) {
		return;
	}

	// Add opening column
	echo '</div>';

}
add_action( 'timberpress_after_header', 'timberpress_add_fixed_header_container_close' );
endif;
