<?php
/**
 * sheen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package sheen
 */

if ( ! defined( 'SHEEN_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'SHEEN_VERSION', '1.0.2' );
}

if( ! function_exists('sheen_content_width')) : 
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function sheen_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'sheen_content_width', 640 );
	}
endif;
add_action( 'after_setup_theme', 'sheen_content_width', 0 );


/**
 * Theme Setup file
 */
require get_template_directory() . '/inc/setup.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
* Comment walker
*/
require get_template_directory() . '/classes/class_sheen_walker_comment.php';

/**
* Nav menu walker
*/
require get_template_directory() . '/classes/class_sheen_walker_nav_menu.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


/**
* Load TGMPA file
*/
require_once get_template_directory() . '/inc/tgmpa/class-tgm-plugin-activation.php';
require_once get_template_directory() . '/inc/tgmpa/tgmpa-config.php';