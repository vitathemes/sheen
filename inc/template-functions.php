<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package brilliance
 */

/**
 * Enqueue scripts and styles. (Unplugable function. Required for loading theme css/js assets)
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

	// Dash icons
	wp_enqueue_style('dashicons');

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

		(get_theme_mod( 'theme_primary_text_white_color' ) == "" ) ? $brilliance_theme_primary_text_white_color = "#FBFBFB" : $brilliance_theme_primary_text_white_color = get_theme_mod( 'theme_primary_text_white_color' ); 

		(get_theme_mod( 'theme_secondary_text_color' ) == "" ) ? $brilliance_theme_secondary_text_color = "#767676" : $brilliance_theme_secondary_text_color = get_theme_mod( 'theme_secondary_text_color' ); 

		(get_theme_mod( 'theme_tertiary_text_color' ) == "" ) ? $brilliance_theme_tertiary_text_color = "#E1E1E1" : $brilliance_theme_tertiary_text_color = get_theme_mod( 'theme_tertiary_text_color' ); 

		(get_theme_mod( 'theme_error_color' ) == "" ) ? $brilliance_theme_error_color = "#FF3636" : $brilliance_theme_error_color = get_theme_mod( 'theme_error_color' ); 

		(get_theme_mod( 'theme_border_color' ) == "" ) ? $brilliance_theme_border_color = "#333333" : $brilliance_theme_border_color = get_theme_mod( 'theme_border_color' ); 

		(get_theme_mod( 'theme_border_secondary_color' ) == "" ) ? $brilliance_theme_border_secondary_color = "#333333" : $brilliance_theme_border_secondary_color = get_theme_mod( 'theme_border_secondary_color' ); 

		$html = ':root {	
					--brilliance_theme_primary_color: 			    ' . $brilliance_theme_primary_color . ';
					--brilliance_theme_primary_accent_color: 	    ' . $brilliance_theme_primary_accent_color . ';
					--brilliance_theme_headings_color:   		    ' . $brilliance_theme_headings_color . ';
					--brilliance_theme_primary_text_color:     		' . $brilliance_theme_primary_text_color . ';
					--brilliance_theme_primary_text_white_color:    ' . $brilliance_theme_primary_text_white_color . ';
					--brilliance_theme_secondary_text_color:   		' . $brilliance_theme_secondary_text_color . ';
					--brilliance_theme_tertiary_text_color:   		' . $brilliance_theme_tertiary_text_color . ';
					--brilliance_theme_error_color:   		        ' . $brilliance_theme_error_color . ';
					--brilliance_theme_border_color:   		        ' . $brilliance_theme_border_color . ';
					--brilliance_theme_border_secondary_color:   	' . $brilliance_theme_border_secondary_color . ';
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


if ( ! function_exists( 'brilliance_modify_libwp_post_type' ) ) {
	function brilliance_modify_libwp_post_type( $brilliance_postTypeName ) {
		/**
		 * Modify LibWP post type name (If libwp plugin exist)
		 */
		$brilliance_postTypeName = 'projects';
		return $brilliance_postTypeName;
	} 
	add_filter('libwp_post_type_1_name', 'brilliance_modify_libwp_post_type');
}
  
if ( ! function_exists('brilliance_modify_libwp_post_type_argument') ) {	  
	function brilliance_modify_libwp_post_type_argument ( $brilliance_postTypeArguments ) {
		/**
		 * Modify LibWP post type arguments (If libwp plugin exist)
		 */
		$brilliance_postTypeArguments['labels'] = [
			'name'          => _x('Projects', 'Post type general name', 'brlliance'),
			'singular_name' => _x('Project', 'Post type singular name', 'brlliance'),
			'menu_name'     => _x('Projects', 'Admin Menu text', 'brlliance'),
			'add_new'       => __('Add New', 'brlliance'),
			'edit_item'     => __('Edit Project', 'brlliance'),
			'view_item'     => __('View Project', 'brlliance'),
			'all_items'     => __('All Projects', 'brlliance'),
		];
		
		$brilliance_postTypeArguments['rewrite']['slug'] 		= 'projects';
		$brilliance_postTypeArguments['public'] 				= true;
		$brilliance_postTypeArguments['show_ui'] 				= true;
		$brilliance_postTypeArguments['menu_position'] 			= 5;
		$brilliance_postTypeArguments['show_in_nav_menus']  	= true;
		$brilliance_postTypeArguments['show_in_admin_bar']  	= true;
		$brilliance_postTypeArguments['hierarchical'] 			= true;
		$brilliance_postTypeArguments['can_export'] 			= true;
		$brilliance_postTypeArguments['has_archive'] 			= true;
		$brilliance_postTypeArguments['exclude_from_search'] 	= false;
		$brilliance_postTypeArguments['publicly_queryable'] 	= true;
		$brilliance_postTypeArguments['capability_type'] 		= 'post';
		$brilliance_postTypeArguments['show_in_rest'] 			= true;
		$brilliance_postTypeArguments['supports'] 				= array( 'title', 'editor' , 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields' , 'comments' );
	
		return $brilliance_postTypeArguments;
	}  
	
	add_filter('libwp_post_type_1_arguments', 'brilliance_modify_libwp_post_type_argument');
  
}

if ( ! function_exists('brilliance_modify_libwp_taxonomy_name')) {
	function brilliance_modify_libwp_taxonomy_name($brilliance_taxonomyName) {
		/**
		* Modify LibWP taxonomy name (If libwp plugin exist)
		*/
		$brilliance_taxonomyName = 'project_category';
		return $brilliance_taxonomyName;
	}
	add_filter('libwp_taxonomy_1_name', 'brilliance_modify_libwp_taxonomy_name');
}
  
if ( ! function_exists('brilliance_modify_libwp_taxonomy_post_type_name')) {
	function brilliance_modify_libwp_taxonomy_post_type_name($brilliance_taxonomyPostTypeName) {
		/**
	    * Modify LibWP taxonomy post type name (If libwp plugin exist)
		*/
		$brilliance_taxonomyPostTypeName = 'projects';
		return $brilliance_taxonomyPostTypeName;
	}
	add_filter('libwp_taxonomy_1_post_type', 'brilliance_modify_libwp_taxonomy_post_type_name');
}
	
if ( ! function_exists('brilliance_modify_libwp_taxonomy_argument') ) {

function brilliance_modify_libwp_taxonomy_argument($brilliance_taxonomyArguments) {
	/**
	* Modify LibWP taxonomy name (If libwp plugin exist)
	*/
	$brilliance_taxonomyArguments['labels'] = [
		'name'          => _x('Project Categories', 'taxonomy general name', 'brlliance'),
		'singular_name' => _x('Project Category', 'taxonomy singular name', 'brlliance'),
		'search_items'  => __('Search Project Categories', 'brlliance'),
		'all_items'     => __('All Project Categories', 'brlliance'),
		'edit_item'     => __('Edit Project Category', 'brlliance'),
		'add_new_item'  => __('Add New Project Category', 'brlliance'),
		'new_item_name' => __('New Project Category Name', 'brlliance'),
		'menu_name'     => __('Project Categories', 'brlliance'),
	];
	$brilliance_taxonomyArguments['rewrite']['slug'] = 'project_category';
	$brilliance_taxonomyArguments['show_in_rest'] = true;

	return $brilliance_taxonomyArguments;
		  
	}
	
	add_filter('libwp_taxonomy_1_arguments', 'brilliance_modify_libwp_taxonomy_argument');
  }