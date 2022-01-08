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
	
	/*
	 *	Kirki - Config
	 */
	
	// Config 
	Kirki::add_config( 'brilliance_theme', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	) );

	/*
	 *	Kirki -> Panels
	 */

	// Footer
	Kirki::add_panel( 'footer', array(
		'priority' => 180,
		'title'    => esc_html__( 'Footer', 'brilliance' ),
	) );


	/*
	 *	Kirki -> Sections
	 */

	/* Typography Colors Section */
	Kirki::add_section( 'colors', array(
		'title'          => esc_html__( 'Theme Colors', 'brilliance' ),
		'description'    => esc_html__( 'Change Theme color and customize them.', 'brilliance' ),
		'panel'          => '',
		'priority'       => 100,
	) );

	/* Typography Colors Fields */
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
	/* End Typography Colors Fields */

}