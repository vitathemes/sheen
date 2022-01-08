<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package brilliance
 */

/**
 * Enqueue scripts and styles. (Unplugable function. Required for loading assets)
 */
function brilliance_scripts() {
	// WordPress default enqueue
	wp_enqueue_style( 'brilliance-style', get_stylesheet_uri(), array(), BRILLIANCE_VERSION );
	wp_style_add_data( 'brilliance-style', 'rtl', 'replace' );

	// enqueue css
	wp_enqueue_style( 'brilliance-main-style', get_template_directory_uri() . '/assets/css/style.css', array(), BRILLIANCE_VERSION );

	// enqueue js
	wp_enqueue_script( 'brilliance-navigation', get_template_directory_uri() . './assets/js/navigation.js', array(), BRILLIANCE_VERSION, true );
	wp_enqueue_script( 'brilliance-vendors-script', get_template_directory_uri() . './assets/js/vendors.js', array(), BRILLIANCE_VERSION, true );
	wp_enqueue_script( 'brilliance-main-script', get_template_directory_uri() . './assets/js/main.js', array(), BRILLIANCE_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'brilliance_scripts' );


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


if ( ! function_exists( 'brilliance_typography' )) {
	
	// Kirki color variables
	function brilliance_typography() {

		(get_theme_mod( 'theme_primary_color' ) == "" ) ? $brilliance_theme_primary_color = "#000000" : $brilliance_theme_primary_color = get_theme_mod( 'theme_primary_color' ); 

		(get_theme_mod( 'theme_primary_accent_color' ) == "" ) ? $brilliance_theme_primary_accent_color = "#FF00B8" : $brilliance_theme_primary_accent_color = get_theme_mod( 'theme_primary_accent_color' ); 

		(get_theme_mod( 'theme_headings_color' ) == "" ) ? $brilliance_theme_headings_color = "#060606" : $brilliance_theme_headings_color = get_theme_mod( 'theme_headings_color' ); 

		(get_theme_mod( 'theme_primary_text_color' ) == "" ) ? $brilliance_theme_primary_text_color = "#060606" : $brilliance_theme_primary_text_color = get_theme_mod( 'theme_primary_text_color' ); 

		(get_theme_mod( 'theme_secondary_text_color' ) == "" ) ? $brilliance_theme_secondary_text_color = "#767676" : $brilliance_theme_secondary_text_color = get_theme_mod( 'theme_secondary_text_color' ); 

		(get_theme_mod( 'theme_tertiary_text_color' ) == "" ) ? $brilliance_theme_tertiary_text_color = "#E1E1E1" : $brilliance_theme_tertiary_text_color = get_theme_mod( 'theme_tertiary_text_color' ); 

		(get_theme_mod( 'theme_error_color' ) == "" ) ? $brilliance_theme_error_color = "#FF3636" : $brilliance_theme_error_color = get_theme_mod( 'theme_error_color' ); 

		$html = ':root {	
					--brilliance_theme_primary_color: 			    ' . $brilliance_theme_primary_color . ';
					--brilliance_theme_primary_accent_color: 	    ' . $brilliance_theme_primary_accent_color . ';
					--brilliance_theme_headings_color:   		    ' . $brilliance_theme_headings_color . ';
					--brilliance_theme_primary_text_color:     		' . $brilliance_theme_primary_text_color . ';
					--brilliance_theme_secondary_text_color:   		' . $brilliance_theme_secondary_text_color . ';
					--brilliance_theme_tertiary_text_color:   		' . $brilliance_theme_tertiary_text_color . ';
					--brilliance_theme_error_color:   		        ' . $brilliance_theme_error_color . ';
				}';
							
		return $html;
		
	}
}

if ( ! function_exists( 'brilliance_theme_settings' )) : 
	function brilliance_theme_settings() {
		$brilliance_theme_typography = brilliance_typography();

		/* Translator %s : Sanitized typography function  */
		echo sprintf('<style>%s</style>' , esc_html($brilliance_theme_typography) );
	}
endif;

add_action( 'admin_head', 'brilliance_theme_settings' );
add_action( 'wp_head', 'brilliance_theme_settings' );