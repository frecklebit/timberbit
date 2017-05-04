<?php
/**
 * Register widget areas
 *
 * @package TimberPress
 * @since TimberPress 1.0.0
 */
if ( ! function_exists( 'timberpress_sidebar_widgets' ) ) :
function timberpress_sidebar_widgets() {
	// Left Sidebar
	register_sidebar( array(
		'id'            => 'left_sidebar_widgets',
		'name'          => __( 'Left sidebar widgets', 'timberpress' ),
		'description'   => __( 'Drag widgets to this sidebar container.', 'timberpress' ),
		'before_widget' => '<article id="%1$s" class="widget %2$s">',
		'after_widget'  => '</article>',
		'before_title'  => '<h6>',
		'after_title'   => '</h6>'
	) );

	// Right Sidebar
	register_sidebar( array(
		'id'            => 'right_sidebar_widgets',
		'name'          => __( 'Right sidebar widgets', 'timberpress' ),
		'description'   => __( 'Drag widgets to this sidebar container.', 'timberpress' ),
		'before_widget' => '<article id="%1$s" class="widget %2$s">',
		'after_widget'  => '</article>',
		'before_title'  => '<h6>',
		'after_title'   => '</h6>'
	) );

	// Footer Widgets
	register_sidebar( array(
		'id'            => 'footer_widgets',
		'name'          => __( 'Footer widgets', 'timberpress' ),
		'description'   => __( 'Drag widgets to this footer container.', 'timberpress' ),
		'before_widget' => '<article id="%1$s" class="large-4 columns widget %2$s">',
		'after_widget'  => '</article>',
		'before_title'  => '<h6>',
		'after_title'   => '</h6>'
	) );
}
add_action( 'widgets_init', 'timberpress_sidebar_widgets' );
endif;

/**
 * Add sidebar locations to context.
 *
 * @param  array $context Timber context
 * @return array 				  Filtered context
 */
if ( ! function_exists( 'timberpress_add_sidebar_widgets_to_context' ) ) :
function timberpress_add_sidebar_widgets_to_context( $context ) {

	$context['left_sidebar_widgets']  = Timber::get_widgets( 'left_sidebar_widgets' );
	$context['right_sidebar_widgets'] = Timber::get_widgets( 'right_sidebar_widgets' );
	$context['footer_widgets']        = Timber::get_widgets( 'footer_widgets' );

	return $context;
}
add_filter( 'timber/context', 'timberpress_add_sidebar_widgets_to_context' );
endif;
