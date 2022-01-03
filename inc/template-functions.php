<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package brilliance
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function brilliance_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'brilliance_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function brilliance_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'brilliance_pingback_header' );


if ( ! function_exists( 'brilliance_branding' ) ) {
	function brilliance_branding() { 
		/**
		 * Get Custom Logo if exist
		 */
		if ( has_custom_logo() ) {
			the_custom_logo();
		} 
		else {	

			// Display the Text title with link 
			/* translator %s : link of main page. translator %s 2: Site title  */
			echo sprintf('<h1 class="c-header__title site-title"><a class="c-header__title__anchor" href="%s" rel="home">%s</a></h1>',
			esc_attr(esc_url( home_url( '/' ))),
			esc_html(get_bloginfo( 'name' )));
			}
	}
}