<?php

/**
 * Check for the Timber Library
 */
if ( ! class_exists( 'Timber' ) ):
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	return;
endif;

/**
 * Version of the theme
 * @var string
 */
define( 'TIMBERPRESS_VERSION', '1.0.0' );

/**
 * Theme domain
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
	'views/partials'
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

// Add nav options to Customizer
require_once( 'library/custom-nav.php' );

// Change WP's stick post class
require_once( 'library/sticky-posts.php' );

// Configure responsive images
require_once( 'library/responsive-images.php' );


class TimberPress extends TimberSite {

	function __construct()
	{
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );

		parent::__construct();
	}

	function register_post_types()
	{
		//this is where you can register custom post types
	}

	function register_taxonomies()
	{
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context )
	{
		$context['site']          = $this;
		$context['is_front']      = is_front_page();
		$context['is_home']       = is_home();
		$context['is_page']       = is_page();
		$context['is_single']     = is_single();
		$context['is_attachment'] = is_attachment();
		$context['is_category']   = is_category();
		$context['is_tag']        = is_tag();
		$context['is_date']       = is_date();
		$context['is_day']        = is_day();
		$context['is_month']      = is_month();
		$context['is_year']       = is_year();
		$context['is_time']       = is_time();
		$context['is_author']     = is_author();
		$context['is_search']     = is_search();
		$context['is_404']        = is_404();
		$context['is_paged']      = is_paged();
		$context['is_preview']    = is_preview();
		$context['is_archive']    = is_archive();

		// Breadcrumbs
		if ( function_exists( 'timberpress_breadcrumb' ) ) {
			$context['breadcrumbs'] = timberpress_breadcrumb( true, false, true );
		}

		return $context;
	}

	function myfoo( $text )
	{
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig )
	{
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}

}

new TimberPress();
