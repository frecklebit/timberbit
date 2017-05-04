<?php
/**
 * Register Menus
 *
 * @link http://codex.wordpress.org/Function_Reference/register_nav_menus#Examples
 * @package TimberPress
 * @since TimberPress 1.0.0
 */

register_nav_menus( array(
	'top-bar-r'  => 'Right Top Bar',
	'mobile-nav' => 'Mobile',
) );

// Desktop navigation - right top bar
// @link http://codex.wordpress.org/Function_Reference/wp_nav_menu
if ( ! function_exists( 'timberpress_top_bar_r' ) ) :
function timberpress_top_bar_r() {
	wp_nav_menu( array(
		'container'      => false,
		'menu_class'     => 'dropdown menu',
		'items_wrap'     => '<ul id="%1$s" class="%2$s desktop-menu" data-dropdown-menu>%3$s</ul>',
		'theme_location' => 'top-bar-r',
		'depth'          => 3,
		'fallback_cb'    => false,
		'walker'         => new Timberpress_Top_Bar_Walker(),
	) );
}
endif;

// Mobile navigation -topbar (default) or offcanvas
if ( ! function_exists( 'timberpress_mobile_nav' ) ) :
function timberpress_mobile_nav() {
	wp_nav_menu( array(
		'container'      => false,
		'menu'           => __( 'mobile-nav', 'timberpress' ),
		'menu_class'     => 'vertical menu',
		'theme_location' => 'mobile-nav',
		'items_wrap'     => '<ul id="%1$s" class="%2$s" data-accordion-menu>%3$s</ul>',
		'fallback_cb'    => false,
		'walker'         => new Timberpress_Mobile_Walker(),
	) );
}
endif;

/**
 * Add support for buttons in the top-bar menu:
 * 1) In WordPress admin, go to Appearace -> Menus
 * 2) Click 'Screen Options' form the top panel and enable 'CSS Classes' and 'Link Relationship' (XFN)
 * 3) On your menu item, type 'has-form' in the CSS-classes field. Type 'button' in the XFN field
 * 4) Save menu. Your menu item will now appear as a button in your top-menu
 */
if ( ! function_exists( 'timberpress_add_menuclass' ) ) :
function timberpress_add_menuclass( $ulclass ) {
	$find = array( '/<a rel="button"/', '/<a title=".*?" rel="button"/' );
	$replace = array( '<a rel="button" class="button"', '<a rel="button" class="button"' );
	return preg_replace( $find, $replace, $ulclass, 1 );
}
add_filter( 'wp_nav_menu', 'timberpress_add_menuclass' );
endif;

/**
 * Adapted for Foundation from http://thewebtaylor.com/articles/wordpress-creating-breadcrumbs-without-a-plugin
 *
 * @param bool $showhome should the breadcrumb be shown when on homepage (only one deactivated entry for home).
 * @param bool $separatorclass should a separator class be added (in case :before is not an option).
 */
if ( ! function_exists( 'timberpress_breadcrumb' ) ) :
function timberpress_breadcrumb( $showhome = true, $separatorclass = false, $return = false ) {

	$context = array();
	$trail   = array();

	// Settings
	$separator  = ( $separatorclass ) ? '&gt;' : false;
	$home_title = 'Home';

	// Build the breadcrumbs
	$context['container_id']     = 'breadcrumbs';
	$context['container_class']  = 'breadcrumbs';

	// Get the query & post information
	global $post, $wp_query;
	$category = get_the_category();

	// Do not display on homepage
	if ( ! is_home() ) {

		// Home page
		array_push( $trail, array(
			'container_class' => 'item-home',
			'link'            => get_home_url(),
			'title'           => $home_title,
			'separator'       => $separator
		) );

		if ( is_single() && ! is_attachment() ) {

			// Single post (Only display the first category)
			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'item-cat',
					'item-cat-' . $category[0]->term_id,
					'item-cat-' . $category[0]->category_nicename,
				) ),
				'link'      => get_category_link( $category[0]->term_id ),
				'title'     => $category[0]->cat_name,
				'separator' => $separator
			) );

			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'current',
					'item-post',
					'item-post-' . $post->ID,
				) ),
				'title'   => get_the_title(),
				'current' => true,
			) );

		} elseif ( is_category() ) {

			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'item-current',
					'item-cat-' . $category[0]->term_id,
					'item-cat-' . $category[0]->category_nicename,
				) ),
				'title'   => $category[0]->cat_name,
				'current' => true,
			) );

		} elseif ( is_page() ) {

			// Standard page
			if ( $post->post_parent ) {

				// If child page, get parents
				$anc = get_post_ancestors( $post->ID );

				// Get parents in the right order
				$anc = array_reverse( $anc );

				// Parent page loop
				foreach ( $anc as $ancestor ) {

					array_push( $trail, array(
						'container_class' => join( ' ', array(
							'item-parent',
							'item-parent item-parent-' . $ancestor,
							'item-page',
							'item-page-' . $ancestor
						) ),
						'link'      => get_permalink( $ancestor ),
						'title'     => get_the_title( $ancestor ),
						'separator' => $separator
					) );

				}

			}

			// Current Page
			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'current',
					'item-page-' . $post->ID,
					'item-page-' . $post->post_name,
				) ),
				'title'   => get_the_title(),
				'current' => true
			) );

		} elseif ( is_tag() ) {

			// Tag page
			// Get tag information
			$term_id  = get_query_var( 'tag_id' );
			$taxonomy = 'post_tag';
			$args     = 'include=' . $term_id;
			$terms    = get_terms( $taxonomy, $args );

			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'current',
					'item-tag-' . $terms[0]->term_id,
					'item-tag-' . $terms[0]->slug,
				) ),
				'title'   => $terms[0]->name,
				'current' => true,
			) );

		} elseif ( is_day() ) {

			// Day archive
			// Year link
			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'item-year',
					'item-year-' . get_the_time( 'Y' ),
				) ),
				'link'      => get_year_link( get_the_time( 'Y' ) ),
				'title'     => get_the_time( 'Y' ) . ' Archives',
				'separator' => $separator
			) );

			// Month link
			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'item-month',
					'item-month-' . get_the_time( 'm' ),
				) ),
				'link'      => get_year_link( get_the_time( 'm' ) ),
				'title'     => get_the_time( 'm' ) . ' Archives',
				'separator' => $separator
			) );

			// Day Display
			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'current',
					'item-day',
					'item-day-' . get_the_time( 'j' ),
				) ),
				'link'  => get_year_link( get_the_time( 'j' ) ),
				'title' => join( ' ', array(
					get_the_time( 'jS' ),
					get_the_time( 'M' ),
					'Archives'
				) ),
				'current' => true,
			) );

		} elseif ( is_month() ) {

			// Month Archive
			// Year Link
			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'item-year',
					'item-year-' . get_the_time( 'Y' ),
				) ),
				'link'      => get_year_link( get_the_time( 'Y' ) ),
				'title'     => get_the_time( 'Y' ) . ' Archives',
				'separator' => $separator
			) );

			// Month display
			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'current',
					'item-month',
					'item-month-' . get_the_time( 'm' ),
				) ),
				'title'   => get_the_time( 'M' ) . ' Archives',
				'current' => true,
			) );

		} elseif ( is_year() ) {

			// Year display
			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'current',
					'item-year',
					'item-year-' . get_the_time( 'Y' ),
				) ),
				'title'   => get_the_time( 'Y' ) . ' Archives',
				'current' => true,
			) );

		} elseif ( is_author() ) {

			// Author archive
			global $author;
			$userdata = get_userdata( $author );

			// Month display
			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'current',
					'item-author',
					'item-author-' . $userdata->user_nicename,
				) ),
				'title'   => 'Author: ' . $userdata->user_nicename,
				'current' => true,
			) );

		} elseif ( get_query_var( 'paged' ) ) {

			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'current',
					'item-paged',
					'item-paged-' . get_query_var( 'paged' ),
				) ),
				'title' => join( ' ', array(
					__( 'Page', 'timberpress' ),
					get_query_var( 'paged' ),
				) ),
				'current' => true,
			) );

		} elseif ( is_search() ) {

			array_push( $trail, array(
				'container_class' => join( ' ', array(
					'current',
					'item-search',
					'item-search-' . get_search_query(),
				) ),
				'title'   => 'Search results for: ' . get_search_query(),
				'current' => true
			) );

		} elseif ( is_404() ) {

			array_push( $trail, array(
				'container_class' => 'error-404',
				'title'           => 'Error 404',
				'current'         => true,
			) );

		}

	} else {

		if ( $showhome ) {

			array_push( $trail, array(
				'container_class' => 'current item-home',
				'title'           => $home_title,
				'current'         => true
			) );

		}

	}

	$context['trail'] = $trail;

	if ( ! $return ) {
		Timber::render( 'partials/breadcrumb.twig', $context );
	} else {
		return Timber::compile( 'partials/breadcrumb.twig', $context );
	}
}
endif;
