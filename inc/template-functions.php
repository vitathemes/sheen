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


if ( ! function_exists( 'brilliance_theme_settings' ) ) {
	function brilliance_theme_settings() {
		$vars = ':root {	
	            --brilliance-primary-color: ' . get_theme_mod( "knowpress_primary_color", "#0090AD" ) . ';
	            --brilliance-primary-text-color: ' . get_theme_mod( "knowpress_primary_text_color", "#242A31" ) . ';
	            --brilliance-secondary-text-color: ' . get_theme_mod( "knowpress_secondary_text_color", "#4D5A66" ) . ';
	            --brilliance-sidebar-color: ' . get_theme_mod( "knowpress_sidebar_color", "#f6f8fa" ) . ';
	            --brilliance-border-color: ' . get_theme_mod( "knowpress_border_color", "#E2E8EE" ) . ';
	            --brilliance-card-bg-color: ' . get_theme_mod( "knowpress_card_bg_color", "#F5F7F9" ) . ';
	            --brilliance-message-bg-color: ' . get_theme_mod( "knowpress_message_bg", "#EBFCFF" ) . ';
	            --brilliance-message-border-color: ' . get_theme_mod( "knowpress_message_border", "#0090AD" ) . ';
	            --brilliance-warning-bg-color: ' . get_theme_mod( "knowpress_warning_bg", "#FEFAEB" ) . ';
	            --brilliance-warning-border-color: ' . get_theme_mod( "knowpress_warning_border", "#F2BB08" ) . ';
	            --brilliance-danger-bg-color: ' . get_theme_mod( "knowpress_danger_bg", "#FBEEEF" ) . ';
	            --brilliance-danger-border-color: ' . get_theme_mod( "knowpress_danger_border", "#D4303B" ) . ';
	            --brilliance-list-bg-color: ' . get_theme_mod( "knowpress_list_bg", "#F5F7F9" ) . ';
	            --brilliance-list-border-color: ' . get_theme_mod( "knowpress_list_border", "#0090AD" ) . ';
			}';

		$mobile_base_font        = get_theme_mod( 'text_typography_m', false );
		$mobile_base_font_styles = "";
		if ( $mobile_base_font ) {
			$mobile_base_font_styles = "@media (max-width: 576px) { html {font-size:" . $mobile_base_font['font-size'] . " !important; line-height:" . $mobile_base_font['line-height'] . " !important; }}";
		}

		?>
<style>
<?php echo esc_html($vars);
?><?php echo esc_html($mobile_base_font_styles);
?>
</style>
<?php
	}
}
add_action( 'wp_head', 'brilliance_theme_settings' );
add_action( 'enqueue_block_editor_assets', 'brilliance_theme_settings' );