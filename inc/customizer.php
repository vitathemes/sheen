<?php
/**
 * brilliance Theme Customizer
 *
 * @package brilliance
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function brilliance_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'brilliance_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'brilliance_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'brilliance_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function brilliance_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function brilliance_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function brilliance_customize_preview_js() {
	wp_enqueue_script( 'brilliance-customizer', get_template_directory_uri() . './assets/js/customizer.js', array( 'customize-preview' ), BRILLIANCE_VERSION, true );
}
add_action( 'customize_preview_init', 'brilliance_customize_preview_js' );


if( function_exists( 'kirki' ) ) {
	
/*------------------------------------*\
  ############# Config ###############
\*------------------------------------*/
	
	// Config 
	Kirki::add_config( 'brilliance_theme', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	) );

/*------------------------------------*\
  ############# Panels ###############
\*------------------------------------*/

	// Footer
	Kirki::add_panel( 'footer', array(
		'priority' => 200,
		'title'    => esc_html__( 'Footer', 'brilliance' ),
	) );

	// Elements
	Kirki::add_panel( 'elements', array(
		'priority' => 90,
		'title'    => esc_html__( 'Elements', 'brilliance' ),
	) );
	

/*------------------------------------*\
  ############# Sections #############
\*------------------------------------*/

	/* Social Options  */
	Kirki::add_section( 'socials', array(
		'title'          => esc_html__( 'Social Networks', 'brilliance' ),
		'description'    => esc_html__( 'Add or Change Social Networks', 'brilliance' ),
		'panel'          => '',
		'priority'       => 90,
	) );

	/* Typography Colors Section */
	Kirki::add_section( 'colors', array(
		'title'          => esc_html__( 'Theme Colors', 'brilliance' ),
		'description'    => esc_html__( 'Change Theme color and customize them.', 'brilliance' ),
		'panel'          => '',
		'priority'       => 100,
	) );
	

	/* Footer Normal Options */
	Kirki::add_section( 'footer_context', array(
		'title'          => esc_html__( 'Footer Context', 'brilliance' ),
		'description'    => esc_html__( 'Change Theme footer Texts', 'brilliance' ),
		'panel'          => 'footer',
		'priority'       => 150,
	) );
	
	/* Footer Pro Options */
	Kirki::add_section( 'footer_copy', array(
		'title'          => esc_html__( 'Footer Copyright Option', 'brilliance' ),
		'description'    => esc_html__( 'Change Theme footer Copyright', 'brilliance' ),
		'panel'          => 'footer',
		'priority'       => 160,
	) );

	/*------------------------------------*\
  		#Elements
	\*------------------------------------*/
	Kirki::add_section( 'single_options', array(
		'title'          => esc_html__( 'Single Options', 'brilliance' ),
		'panel'          => 'elements',
		'priority'       => 100,
	) );
	
/*------------------------------------*\
  ############## Fields ##############
\*------------------------------------*/

	/*------------------------------------*\
  		#Start Colors
	\*------------------------------------*/
	Kirki::add_field( 'brilliance', [
		'type'     => 'color',
		'settings' => 'theme_primary_color',
		'label'    => __( 'Primary Color', 'brilliance' ),
		'section'  => 'colors',
		'default'  => '#000000',
		'priority' => 10,
		
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'color',
		'settings' => 'theme_primary_accent_color',
		'label'    => __( 'Primary Accent Color', 'brilliance' ),
		'section'  => 'colors',
		'default'  => '#FF00B8',
		'priority' => 20,
		
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'color',
		'settings' => 'theme_headings_color',
		'label'    => __( 'Headings Color', 'brilliance' ),
		'section'  => 'colors',
		'default'  => '#060606',
		'priority' => 30,
		
	] );
	
	Kirki::add_field( 'brilliance', [
		'type'     => 'color',
		'settings' => 'theme_primary_text_color',
		'label'    => __( 'Primary Text Color', 'brilliance' ),
		'section'  => 'colors',
		'default'  => '#060606',
		'priority' => 40,
		
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'color',
		'settings' => 'theme_primary_text_white_color',
		'label'    => __( 'Primary White Text Color', 'brilliance' ),
		'section'  => 'colors',
		'default'  => '#FBFBFB',
		'priority' => 45,
		
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'color',
		'settings' => 'theme_secondary_text_color',
		'label'    => __( 'Secondary Text Color', 'brilliance' ),
		'section'  => 'colors',
		'default'  => '#767676',
		'priority' => 50,
		
	] );
	
	Kirki::add_field( 'brilliance', [
		'type'     => 'color',
		'settings' => 'theme_tertiary_text_color',
		'label'    => __( 'Tertiary Text Color', 'brilliance' ),
		'section'  => 'colors',
		'default'  => '#E1E1E1',
		'priority' => 60,
		
	] );
	
	Kirki::add_field( 'brilliance', [
		'type'     => 'color',
		'settings' => 'theme_error_color',
		'label'    => __( 'Error Color', 'brilliance' ),
		'section'  => 'colors',
		'default'  => '#FF3636',
		'priority' => 70,
		
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'color',
		'settings' => 'theme_border_color',
		'label'    => __( 'Border Color', 'brilliance' ),
		'section'  => 'colors',
		'default'  => '#333333',
		'priority' => 80,
		
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'color',
		'settings' => 'theme_border_secondary_color',
		'label'    => __( 'Secondary Border Color', 'brilliance' ),
		'section'  => 'colors',
		'default'  => '#060606',
		'priority' => 90,
		
	] );

	/*------------------------------------*\
  		#End Colors
	\*------------------------------------*/

	/*------------------------------------*\
  		#Start Footer Options
	\*------------------------------------*/

	Kirki::add_field( 'brilliance', [
		'type'     => 'text',
		'settings' => 'footer_custom_text',
		'label'    => esc_html__( 'Footer Cusom Text', 'brilliance' ),
		'section'  => 'footer_context',
		'default'  => esc_html__( 'Brilliance, a creative portfolio theme', 'brilliance' ),
		'priority' => 10,
	] );
	
	/*------------------------------------*\
		#End Footer Options
	\*------------------------------------*/
	

	/*------------------------------------*\
		#Start Social Networks
	\*------------------------------------*/
	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'facebook',
		'label'    => esc_html__( 'Facebook', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 10,
	]);

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'twitter',
		'label'    => esc_html__( 'Twitter', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 20,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'instagram',
		'label'    => esc_html__( 'Instagram', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 30,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'linkedin',
		'label'    => esc_html__( 'Linkedin', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 40,
	] );
	
	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'github',
		'label'    => esc_html__( 'Github', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 50,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'text',
		'settings' => 'mail',
		'label'    => __( 'Email', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 60,
	] );


	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'pinterest',
		'label'    => __( 'Pinterest', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 70,
	] );


	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'youtube',
		'label'    => __( 'Youtube', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 80,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'spotify',
		'label'    => __( 'Spotify', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 90,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'gitlab',
		'label'    => __( 'Gitlab', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 100,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'lastfm',
		'label'    => __( 'Lastfm', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 110,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'stackoverflow',
		'label'    => __( 'Stackoverflow', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 120,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'quora',
		'label'    => __( 'Quora', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 130,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'reddit',
		'label'    => __( 'Reddit', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 140,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'medium',
		'label'    => __( 'Medium', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 150,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'vimeo',
		'label'    => __( 'Vimeo', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 160,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'lanyrd',
		'label'    => __( 'Lanyrd', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 170,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'dribbble',
		'label'    => __( 'Dribbble', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 180,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'behance',
		'label'    => __( 'Behance', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 190,
	] );

	
	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'codepen',
		'label'    => __( 'Codepen', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 200,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'link',
		'settings' => 'telegram',
		'label'    => __( 'Telegram', 'brilliance' ),
		'section'  => 'socials',
		'priority' => 210,
	] );

	/*------------------------------------*\
		#End Social Networks
	\*------------------------------------*/


	/*------------------------------------*\
		#Single Options
	\*------------------------------------*/
	Kirki::add_field( 'brilliance', [
		'type'        => 'radio-image',
		'settings'    => 'single_thumbnail_size',
		'label'       => esc_html__( 'Thumbnail Size', 'brilliance' ),
		'section'     => 'single_options',
		'default'     => 'normal',
		'priority'    => 10,
		'choices'     => [
			'normal' => get_template_directory_uri() . '/assets/images/normal.jpg',
			'wide'   => get_template_directory_uri() . '/assets/images/wide.jpg',
		],
	] );

	Kirki::add_field( 'brilliance', [
		'type'        => 'toggle',
		'settings'    => 'single_gallery',
		'label'       => esc_html__( 'Display Single Gallery', 'brilliance' ),
		'section'     => 'single_options',
		'default'     => 1,
		'priority'    => 20,
	] );
	
}