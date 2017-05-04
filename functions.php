<?php

/**
 * Check for the Timber Library
 */
if ( ! class_exists( 'Timber' ) ) :
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	return;
endif;

/**
 * Version of the theme
 *
 * @var string
 */
define( 'TIMBERPRESS_VERSION', '1.0.0' );

/**
 * Theme domain
 *
 * @var boolean
 */
define( 'TIMBERPRESS_DOMAIN', 'timberpress' );

/**
 * Timber Cache
 * Whether Timber should cache templates
 */
Timber::$cache = false;

/**
 * Timber Directory Names
 * Where the templates are stored
 */
Timber::$dirname = array(
	'views',
	'views/templates',
);

// Various clean up functions
require_once( 'library/cleanup.php' );

// Required for Foundation to work properly
require_once( 'library/foundation.php' );

// Navigation
require_once( 'library/navigation.php' );

// Add menu walkers for top-bar and off-canvas
require_once( 'library/class-timberpress-top-bar-walker.php' );
require_once( 'library/class-timberpress-mobile-walker.php' );

// Create widget areas in sidebar and footer
require_once( 'library/widget-areas.php' );

// Return entry meta information for posts
require_once( 'library/entry-meta.php' );

// Enqueue scripts
require_once( 'library/enqueue-scripts.php' );

// Theme Support
require_once( 'library/theme-support.php' );

// Add WP Customizer options
require_once( 'library/custom-header.php' );
require_once( 'library/custom-colors.php' );

// Change WP's stick post class
require_once( 'library/sticky-posts.php' );

// Configure responsive images
require_once( 'library/responsive-images.php' );

// Add filters, helpers, etc to twig
require_once( 'library/add-to-twig.php' );

// Add to global context
require_once( 'library/add-to-context.php' );

// Start a new Timber Site
new TimberSite();
