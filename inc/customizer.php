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
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

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
	/*
	 *	Kirki - Config
	 */

	// Config 
	Kirki::add_config( 'castpress_theme', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	) );


	/*
	 *	Kirki -> Panels
	 */

	// Footer
	Kirki::add_panel( 'footer', array(
		'priority' => 180,
		'title'    => esc_html__( 'Footer', 'castpress' ),
	) );


	/*
	 *	Kirki -> Sections
	 */
	
	// Home Components 
	Kirki::add_section( 'home_components', array(
		'title'    => esc_html__( 'Home Components', 'castpress' ),
		'panel'    => '',
		'priority' => 5,
	) );

}