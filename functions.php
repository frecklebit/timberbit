<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	return;
}

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

class StarterSite extends TimberSite {

	function __construct()
	{
		// Clean up WordPress defaults
		add_action( 'after_theme_setup', array( $this, 'start_cleanup' ) );

		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );

		parent::__construct();
	}

	function start_cleanup()
	{
		require_once( 'inc/class-timberbit-cleanup.php' );
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
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
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

new StarterSite();
