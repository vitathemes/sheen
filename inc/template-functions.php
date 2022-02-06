<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package sheen
 */

/**
 * Enqueue scripts and styles. (Unplugable function. Required for loading theme css/js assets)
 */
function sheen_scripts() {

	wp_enqueue_script('jquery');

	// WordPress default enqueue
	wp_enqueue_style( 'sheen-style', get_stylesheet_uri(), array(), SHEEN_VERSION );
	wp_style_add_data( 'sheen-style', 'rtl', 'replace' );

	// enqueue css
	wp_enqueue_style( 'sheen-main-style', get_template_directory_uri() . '/assets/css/style.css', array(), SHEEN_VERSION );

	// enqueue js
	wp_enqueue_script( 'sheen-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), SHEEN_VERSION, true );
	wp_enqueue_script( 'sheen-vendors-script', get_template_directory_uri() . '/assets/js/vendors.js', array(), SHEEN_VERSION, true );
	wp_enqueue_script( 'sheen-main-script', get_template_directory_uri() . '/assets/js/main.js', array(), SHEEN_VERSION, true );

	// Dash icons
	wp_enqueue_style('dashicons');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sheen_scripts' );


if( !function_exists('sheen_enqueue_editor_scripts') ) : 
	/**
	 * 
	 * Enqueue Editor scripts 
	 * 
	 * @since v1.0.0
	 * 
	 */
	function sheen_enqueue_editor_scripts() {
		wp_enqueue_script( 'sheen-editor-scripts', get_template_directory_uri() . '/assets/js/block.js' );
	}
	add_action( 'admin_enqueue_scripts', 'sheen_enqueue_editor_scripts' );
endif;


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function sheen_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'sheen_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function sheen_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'sheen_pingback_header' );


if ( ! function_exists( 'sheen_branding' ) ) {
	function sheen_branding() { 
		/**
		 * Get Custom Logo if exist
		 */
		if ( has_custom_logo() ) {
			the_custom_logo();
		} 
		else {	

			// Display the Text title with link 
			/* translator %s : link of main page. translator %s 2: Site title  */
			echo sprintf('<h1 class="c-header__title site-title"><a class="c-header__title__anchor h2" href="%s" rel="home">%s</a></h1>',
			esc_attr(esc_url( home_url( '/' ))),
			esc_html(get_bloginfo( 'name' )));
		}
	}
}


if ( ! function_exists( 'sheen_typography' )) {
	
	// Kirki color variables
	function sheen_typography() {

		(get_theme_mod( 'theme_primary_color' ) == "" ) ? $sheen_theme_primary_color = "#000000" : $sheen_theme_primary_color = get_theme_mod( 'theme_primary_color' ); 

		(get_theme_mod( 'theme_primary_accent_color' ) == "" ) ? $sheen_theme_primary_accent_color = "#FF00B8" : $sheen_theme_primary_accent_color = get_theme_mod( 'theme_primary_accent_color' ); 

		(get_theme_mod( 'theme_headings_color' ) == "" ) ? $sheen_theme_headings_color = "#060606" : $sheen_theme_headings_color = get_theme_mod( 'theme_headings_color' ); 

		(get_theme_mod( 'theme_primary_text_color' ) == "" ) ? $sheen_theme_primary_text_color = "#060606" : $sheen_theme_primary_text_color = get_theme_mod( 'theme_primary_text_color' ); 

		(get_theme_mod( 'theme_primary_text_white_color' ) == "" ) ? $sheen_theme_primary_text_white_color = "#FBFBFB" : $sheen_theme_primary_text_white_color = get_theme_mod( 'theme_primary_text_white_color' ); 

		(get_theme_mod( 'theme_secondary_text_color' ) == "" ) ? $sheen_theme_secondary_text_color = "#767676" : $sheen_theme_secondary_text_color = get_theme_mod( 'theme_secondary_text_color' ); 

		(get_theme_mod( 'theme_tertiary_text_color' ) == "" ) ? $sheen_theme_tertiary_text_color = "#E1E1E1" : $sheen_theme_tertiary_text_color = get_theme_mod( 'theme_tertiary_text_color' ); 

		(get_theme_mod( 'theme_error_color' ) == "" ) ? $sheen_theme_error_color = "#FF3636" : $sheen_theme_error_color = get_theme_mod( 'theme_error_color' ); 

		(get_theme_mod( 'theme_border_color' ) == "" ) ? $sheen_theme_border_color = "#333333" : $sheen_theme_border_color = get_theme_mod( 'theme_border_color' ); 

		(get_theme_mod( 'theme_border_secondary_color' ) == "" ) ? $sheen_theme_border_secondary_color = "#333333" : $sheen_theme_border_secondary_color = get_theme_mod( 'theme_border_secondary_color' ); 

		$html = ':root {	
					--sheen_theme_primary_color: 			    ' . $sheen_theme_primary_color . ';
					--sheen_theme_primary_accent_color: 	    ' . $sheen_theme_primary_accent_color . ';
					--sheen_theme_headings_color:   		    ' . $sheen_theme_headings_color . ';
					--sheen_theme_primary_text_color:     		' . $sheen_theme_primary_text_color . ';
					--sheen_theme_primary_text_white_color:    ' . $sheen_theme_primary_text_white_color . ';
					--sheen_theme_secondary_text_color:   		' . $sheen_theme_secondary_text_color . ';
					--sheen_theme_tertiary_text_color:   		' . $sheen_theme_tertiary_text_color . ';
					--sheen_theme_error_color:   		        ' . $sheen_theme_error_color . ';
					--sheen_theme_border_color:   		        ' . $sheen_theme_border_color . ';
					--sheen_theme_border_secondary_color:   	' . $sheen_theme_border_secondary_color . ';
				}';		
		return $html;	
	}
}


if ( ! function_exists( 'sheen_theme_settings' )) : 
	function sheen_theme_settings() {
		$sheen_theme_typography = sheen_typography();

		/* Translator %s : Sanitized typography function  */
		echo sprintf('<style>%s</style>' , esc_html($sheen_theme_typography) );
	}
endif;

add_action( 'admin_head', 'sheen_theme_settings' );
add_action( 'wp_head', 'sheen_theme_settings' );


if ( ! function_exists( 'sheen_modify_libwp_post_type' ) ) {
	function sheen_modify_libwp_post_type( $sheen_postTypeName ) {
		/**
		 * Modify LibWP post type name (If libwp plugin exist)
		 */
		$sheen_postTypeName = 'projects';
		return $sheen_postTypeName;
	} 
	add_filter('libwp_post_type_1_name', 'sheen_modify_libwp_post_type');
}
  

if ( ! function_exists('sheen_modify_libwp_post_type_argument') ) {	  
	function sheen_modify_libwp_post_type_argument ( $sheen_postTypeArguments ) {
		/**
		 * Modify LibWP post type arguments (If libwp plugin exist)
		 */
		$sheen_postTypeArguments['labels'] = [
			'name'          => _x('Projects', 'Post type general name', 'sheen'),
			'singular_name' => _x('Project', 'Post type singular name', 'sheen'),
			'menu_name'     => _x('Projects', 'Admin Menu text', 'sheen'),
			'add_new'       => __('Add New', 'sheen'),
			'edit_item'     => __('Edit Project', 'sheen'),
			'view_item'     => __('View Project', 'sheen'),
			'all_items'     => __('All Projects', 'sheen'),
		];
		
		$sheen_postTypeArguments['rewrite']['slug'] 		= 'projects';
		$sheen_postTypeArguments['public'] 				= true;
		$sheen_postTypeArguments['show_ui'] 				= true;
		$sheen_postTypeArguments['menu_position'] 			= 5;
		$sheen_postTypeArguments['show_in_nav_menus']  	= true;
		$sheen_postTypeArguments['show_in_admin_bar']  	= true;
		$sheen_postTypeArguments['hierarchical'] 			= true;
		$sheen_postTypeArguments['can_export'] 			= true;
		$sheen_postTypeArguments['has_archive'] 			= true;
		$sheen_postTypeArguments['exclude_from_search'] 	= false;
		$sheen_postTypeArguments['publicly_queryable'] 	= true;
		$sheen_postTypeArguments['capability_type'] 		= 'post';
		$sheen_postTypeArguments['show_in_rest'] 			= true;
		$sheen_postTypeArguments['supports'] 				= array( 'title', 'editor' , 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields' , 'comments' );
	
		return $sheen_postTypeArguments;
	}  
	add_filter('libwp_post_type_1_arguments', 'sheen_modify_libwp_post_type_argument');
}


if ( ! function_exists('sheen_modify_libwp_taxonomy_name')) {
	function sheen_modify_libwp_taxonomy_name($sheen_taxonomyName) {
		/**
		* Modify LibWP taxonomy name (If libwp plugin exist)
		*/
		$sheen_taxonomyName = 'project_category';
		return $sheen_taxonomyName;
	}
	add_filter('libwp_taxonomy_1_name', 'sheen_modify_libwp_taxonomy_name');
}

  
if ( ! function_exists('sheen_modify_libwp_taxonomy_post_type_name')) {
	function sheen_modify_libwp_taxonomy_post_type_name($sheen_taxonomyPostTypeName) {
		/**
	    * Modify LibWP taxonomy post type name (If libwp plugin exist)
		*/
		$sheen_taxonomyPostTypeName = 'projects';
		return $sheen_taxonomyPostTypeName;
	}
	add_filter('libwp_taxonomy_1_post_type', 'sheen_modify_libwp_taxonomy_post_type_name');
}
	

if ( ! function_exists('sheen_modify_libwp_taxonomy_argument') ) {
function sheen_modify_libwp_taxonomy_argument($sheen_taxonomyArguments) {
	/**
	* Modify LibWP taxonomy name (If libwp plugin exist)
	*/
	$sheen_taxonomyArguments['labels'] = [
		'name'          => _x('Project Categories', 'taxonomy general name', 'sheen'),
		'singular_name' => _x('Project Category', 'taxonomy singular name', 'sheen'),
		'search_items'  => __('Search Project Categories', 'sheen'),
		'all_items'     => __('All Project Categories', 'sheen'),
		'edit_item'     => __('Edit Project Category', 'sheen'),
		'add_new_item'  => __('Add New Project Category', 'sheen'),
		'new_item_name' => __('New Project Category Name', 'sheen'),
		'menu_name'     => __('Project Categories', 'sheen'),
	];
	$sheen_taxonomyArguments['rewrite']['slug'] = 'project_category';
	$sheen_taxonomyArguments['show_in_rest'] = true;

	return $sheen_taxonomyArguments;
		  
	}
	
	add_filter('libwp_taxonomy_1_arguments', 'sheen_modify_libwp_taxonomy_argument');
}


if( !function_exists('sheen_load_more_script') ) : 
	/**
	 * 
	 * Load More button 
	 * 
	 * @since v1.0.0
	 * 
	 */
	function sheen_load_more_script() {
		global $wp_query;
		wp_localize_script( 'sheen-main-script', 'loadmore_params', array(
			'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
			'posts' => json_encode( $wp_query->query_vars ),
			'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
			'max_page' => $wp_query->max_num_pages,
			'post_type' => 'projects'
		) );
		wp_enqueue_script( 'sheen-main-script' );
	}
	add_action( 'wp_enqueue_scripts', 'sheen_load_more_script' );
endif;


if( !function_exists('sheen_loadmore_ajax_handler') ) : 
	/**
	 * 
	 * Handle Load More Loop 
	 * 
	 * @since v1.0.0
	 * 
	 */
	function sheen_loadmore_ajax_handler( $sheen_post_type = "projects" ) {

		if ( !empty( $_POST['query'] ||  $_POST['page'] )) {
			
				$sheen_custom_args = [
					"paged"            => sanitize_text_field( wp_unslash( $_POST['page'] )) + 1 ,
					"posts_per_page"   => get_option("posts_per_page"),
					"post_status"      => "publish",
					"post_type"		   => "projects"
				];
				
				query_posts( $sheen_custom_args );
				
				if( have_posts() ) :
					while( have_posts() ) : the_post();
					get_template_part( 'template-parts/content', get_post_type() );
					endwhile;
				endif;
				
			die; 
		}
	}
	add_action('wp_ajax_loadmore', 'sheen_loadmore_ajax_handler'); // wp_ajax_{action}
	add_action('wp_ajax_nopriv_loadmore', 'sheen_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}
endif;



if( !function_exists('sheen_cats_filter') ) : 
	/**
	 * 
	 * Filter Categories with ajax
	 * 
	 * @since v1.0.0
	 * 
	 */
	function sheen_cats_filter() {
		$sheen_filter_args = array(
			'orderby' 		 => 'date', 
			'posts_per_page' => get_option("posts_per_page"),
			'post_type'		 => 'projects'
		);
	
		if( isset( $_POST['categoryfilter'] ) )
			$sheen_filter_args['tax_query'] = array(
				array(
					'taxonomy' => 'project_category',
					'field' => 'id',
					'terms' => sanitize_text_field(wp_unslash($_POST['categoryfilter'])),
				)
			);
	 
		$sheen_filter_query = new WP_Query( $sheen_filter_args );
		
		if( $sheen_filter_query->have_posts() ) :
			while( $sheen_filter_query->have_posts() ): $sheen_filter_query->the_post();
				get_template_part( 'template-parts/content', get_post_type() );
			endwhile;
			
			wp_reset_postdata();
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;
		
		die();
	}
	add_action('wp_ajax_myfilter', 'sheen_cats_filter'); // wp_ajax_{ACTION HERE} 
	add_action('wp_ajax_nopriv_myfilter', 'sheen_cats_filter');
endif;


if ( !function_exists('sheen_modify_archive_title') ) {
	function sheen_modify_archive_title( $sheen_title ) {
		/**
		 * 
		 * Modify Archive title 
		 * 
		 * @since v1.0.0
		 * 
		 */
		if(is_post_type_archive('projects')){
			if(get_theme_mod( 'archives_title' , 'projects')){
				return get_theme_mod( 'archives_title' , 'projects');
			}
			else{
				return esc_html__( 'projects' , 'sheen' ); // Also Available to change From Kirki
			}
		}
		return wp_kses_post( $sheen_title );
	}
	add_filter( 'wp_title', 'sheen_modify_archive_title' );
	add_filter( 'get_the_archive_title', 'sheen_modify_archive_title' );
}