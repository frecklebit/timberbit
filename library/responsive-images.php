<?php
/**
 * Configure responsive images
 *
 * @package TimberPress
 * @since TimberPress 1.0.0
 */

// Add featured image sizes
//
// Sizes are optimized and cropped for landscape aspect ratio
// and optimized for HiDPI displays on 'small' and 'medium' screen sizes.
add_image_size( 'featured-small', 640, 200, true ); // name, width, height, crop
add_image_size( 'featured-medium', 1280, 400, true );
add_image_size( 'featured-large', 1440, 400, true );
add_image_size( 'featured-xlarge', 1920, 400, true );

// Add additional image sizes
add_image_size( 'tp-small', 640 );
add_image_size( 'tp-medium', 1024 );
add_image_size( 'tp-large', 1200 );
add_image_size( 'tp-xlarge', 1920 );

// Register the new images sizes for use in the add media modal in wp-admin
function timberpress_custom_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'tp-small'  => __( 'TP Small' ),
		'tp-medium' => __( 'TP Medium' ),
		'tp-large'  => __( 'TP Large' ),
		'tp-xlarge' => __( 'TP XLarge' ),
	) );
}
add_filter( 'image_size_names_choose', 'timberpress_custom_sizes' );

// Add custom image sizes attribute to enhance responsive image
// functionality for content images
function timberpress_adjust_image_sizes_attr( $sizes, $size ) {

	// Acutal width of image
	$width = $size[0];

	// Full width page template
	if ( is_page_template( 'views/templates/page-full-width.twig' ) ) {
		if ( 1200 < $width ) {
			$sizes = '(max-width: 1199px) 98vw, 1200px';
		} else {
			$sizes = '(max-width: 1199px) 98vw, ' . $width . 'px';
		}
	} else {
		if ( 770 < $width ) {
			$sizes = '(max-width: 639px) 98vw, (max-width: 1199px) 64vw, 770px';
		} else {
			$sizes = '(max-width: 639px) 98vw, (max-width: 1199px) 64vw, ' . $width . 'px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'timberpress_adjust_image_sizes_attr' );

// Remove inline width and height attributes for post thumbnails
function timberpress_remove_thumbnail_dimension( $html, $post_id, $post_image_id ) {
	$html = preg_replace( '/(width|height)=\"\d*\"\s?/', '', $html );
	return $html;
}
add_filter( 'post_thumbnail_html', 'timberpress_remove_thumbnail_dimension', 10, 3 );
