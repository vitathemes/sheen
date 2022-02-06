<?php
/**
 * sheen Theme Customizer
 *
 * @package sheen
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sheen_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'sheen_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'sheen_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'sheen_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function sheen_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function sheen_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sheen_customize_preview_js() {
	wp_enqueue_script( 'sheen-customizer', get_template_directory_uri() . './assets/js/customizer.js', array( 'customize-preview' ), SHEEN_VERSION, true );
}
add_action( 'customize_preview_init', 'sheen_customize_preview_js' );


if( function_exists( 'kirki' ) ) {

	add_action( 'init', function () {

	/*------------------------------------*\
	  ############# Config ###############
	\*------------------------------------*/
	
	// Config 
	Kirki::add_config( 'sheen_theme', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	));

	/*------------------------------------*\
	  ############# Panels ###############
	\*------------------------------------*/

	// Footer
	Kirki::add_panel( 'footer', array(
		'priority' => 200,
		'title'    => esc_html__( 'Footer', 'sheen' ),
	) );

	// Elements
	Kirki::add_panel( 'elements', array(
		'priority' => 90,
		'title'    => esc_html__( 'Elements', 'sheen' ),
	) );
	
	/*------------------------------------*\
	  ############# Sections #############
	\*------------------------------------*/
	
	/* Typography Options */
	Kirki::add_section( 'typography', array(
		'title'          => esc_html__( 'Typography', 'sheen' ),
		'panel'          => '',
		'priority'       => 50,
	) );

	/* Social Options  */
	Kirki::add_section( 'socials', array(
		'title'          => esc_html__( 'Social Networks', 'sheen' ),
		'description'    => esc_html__( 'Add or Change Social Networks', 'sheen' ),
		'panel'          => '',
		'priority'       => 90,
	) );

	/* Typography Colors Section */
	Kirki::add_section( 'colors', array(
		'title'          => esc_html__( 'Theme Colors', 'sheen' ),
		'description'    => esc_html__( 'Change Theme color and customize them.', 'sheen' ),
		'panel'          => '',
		'priority'       => 100,
	) );
	

	/* Footer Normal Options */
	Kirki::add_section( 'footer_context', array(
		'title'          => esc_html__( 'Footer Context', 'sheen' ),
		'description'    => esc_html__( 'Change Theme footer Texts', 'sheen' ),
		'panel'          => 'footer',
		'priority'       => 150,
	) );
	
	/* Footer Pro Options */
	Kirki::add_section( 'footer_copy', array(
		'title'          => esc_html__( 'Footer Copyright Option', 'sheen' ),
		'description'    => esc_html__( 'Change Theme footer Copyright', 'sheen' ),
		'panel'          => 'footer',
		'priority'       => 160,
	) );

	/*------------------------------------*\
  		#Elements
	\*------------------------------------*/
	Kirki::add_section( 'single_options', array(
		'title'          => esc_html__( 'Single Options', 'sheen' ),
		'panel'          => 'elements',
		'priority'       => 100,
	) );

	Kirki::add_section( 'home_options', array(
		'title'          => esc_html__( 'Home Page Options', 'sheen' ),
		'panel'          => 'elements',
		'priority'       => 120,
	) );

	Kirki::add_section( 'projects_options', array(
		'title'          => esc_html__( 'Projects Options', 'sheen' ),
		'panel'          => 'elements',
		'priority'       => 130,
	) );

	Kirki::add_section( 'archive_options', array(
		'title'          => esc_html__( 'Archive Options', 'sheen' ),
		'panel'          => 'elements',
		'priority'       => 140,
	) );
	
	/*------------------------------------*\
	  ############## Fields ##############
	\*------------------------------------*/

	/*------------------------------------*\
  		#Start Colors
	\*------------------------------------*/
	Kirki::add_field( 'sheen', [
		'type'     => 'color',
		'settings' => 'theme_primary_color',
		'label'    => __( 'Primary Color', 'sheen' ),
		'section'  => 'colors',
		'default'  => '#000000',
		'priority' => 10,
		
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'color',
		'settings' => 'theme_primary_accent_color',
		'label'    => __( 'Primary Accent Color', 'sheen' ),
		'section'  => 'colors',
		'default'  => '#FF00B8',
		'priority' => 20,
		
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'color',
		'settings' => 'theme_headings_color',
		'label'    => __( 'Headings Color', 'sheen' ),
		'section'  => 'colors',
		'default'  => '#060606',
		'priority' => 30,
		
	] );
	
	Kirki::add_field( 'sheen', [
		'type'     => 'color',
		'settings' => 'theme_primary_text_color',
		'label'    => __( 'Primary Text Color', 'sheen' ),
		'section'  => 'colors',
		'default'  => '#060606',
		'priority' => 40,
		
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'color',
		'settings' => 'theme_primary_text_white_color',
		'label'    => __( 'Primary White Text Color', 'sheen' ),
		'section'  => 'colors',
		'default'  => '#FBFBFB',
		'priority' => 45,
		
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'color',
		'settings' => 'theme_secondary_text_color',
		'label'    => __( 'Secondary Text Color', 'sheen' ),
		'section'  => 'colors',
		'default'  => '#767676',
		'priority' => 50,
		
	] );
	
	Kirki::add_field( 'sheen', [
		'type'     => 'color',
		'settings' => 'theme_tertiary_text_color',
		'label'    => __( 'Tertiary Text Color', 'sheen' ),
		'section'  => 'colors',
		'default'  => '#E1E1E1',
		'priority' => 60,
		
	] );
	
	Kirki::add_field( 'sheen', [
		'type'     => 'color',
		'settings' => 'theme_error_color',
		'label'    => __( 'Error Color', 'sheen' ),
		'section'  => 'colors',
		'default'  => '#FF3636',
		'priority' => 70,
		
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'color',
		'settings' => 'theme_border_color',
		'label'    => __( 'Border Color', 'sheen' ),
		'section'  => 'colors',
		'default'  => '#333333',
		'priority' => 80,
		
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'color',
		'settings' => 'theme_border_secondary_color',
		'label'    => __( 'Secondary Border Color', 'sheen' ),
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

	Kirki::add_field( 'sheen', [
		'type'     => 'text',
		'settings' => 'footer_custom_text',
		'label'    => esc_html__( 'Footer Cusom Text', 'sheen' ),
		'section'  => 'footer_context',
		'default'  => esc_html__( 'Sheen, a creative portfolio theme', 'sheen' ),
		'priority' => 10,
	] );
	
	/*------------------------------------*\
		#End Footer Options
	\*------------------------------------*/
	
	/*------------------------------------*\
		#Start Social Networks
	\*------------------------------------*/
	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'facebook',
		'label'    => esc_html__( 'Facebook', 'sheen' ),
		'section'  => 'socials',
		'priority' => 10,
	]);

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'twitter',
		'label'    => esc_html__( 'Twitter', 'sheen' ),
		'section'  => 'socials',
		'priority' => 20,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'instagram',
		'label'    => esc_html__( 'Instagram', 'sheen' ),
		'section'  => 'socials',
		'priority' => 30,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'linkedin',
		'label'    => esc_html__( 'Linkedin', 'sheen' ),
		'section'  => 'socials',
		'priority' => 40,
	] );
	
	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'github',
		'label'    => esc_html__( 'Github', 'sheen' ),
		'section'  => 'socials',
		'priority' => 50,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'text',
		'settings' => 'mail',
		'label'    => __( 'Email', 'sheen' ),
		'section'  => 'socials',
		'priority' => 60,
	] );


	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'pinterest',
		'label'    => __( 'Pinterest', 'sheen' ),
		'section'  => 'socials',
		'priority' => 70,
	] );


	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'youtube',
		'label'    => __( 'Youtube', 'sheen' ),
		'section'  => 'socials',
		'priority' => 80,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'spotify',
		'label'    => __( 'Spotify', 'sheen' ),
		'section'  => 'socials',
		'priority' => 90,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'gitlab',
		'label'    => __( 'Gitlab', 'sheen' ),
		'section'  => 'socials',
		'priority' => 100,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'lastfm',
		'label'    => __( 'Lastfm', 'sheen' ),
		'section'  => 'socials',
		'priority' => 110,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'stackoverflow',
		'label'    => __( 'Stackoverflow', 'sheen' ),
		'section'  => 'socials',
		'priority' => 120,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'quora',
		'label'    => __( 'Quora', 'sheen' ),
		'section'  => 'socials',
		'priority' => 130,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'reddit',
		'label'    => __( 'Reddit', 'sheen' ),
		'section'  => 'socials',
		'priority' => 140,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'medium',
		'label'    => __( 'Medium', 'sheen' ),
		'section'  => 'socials',
		'priority' => 150,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'vimeo',
		'label'    => __( 'Vimeo', 'sheen' ),
		'section'  => 'socials',
		'priority' => 160,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'lanyrd',
		'label'    => __( 'Lanyrd', 'sheen' ),
		'section'  => 'socials',
		'priority' => 170,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'dribbble',
		'label'    => __( 'Dribbble', 'sheen' ),
		'section'  => 'socials',
		'priority' => 180,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'behance',
		'label'    => __( 'Behance', 'sheen' ),
		'section'  => 'socials',
		'priority' => 190,
	] );

	
	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'codepen',
		'label'    => __( 'Codepen', 'sheen' ),
		'section'  => 'socials',
		'priority' => 200,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'link',
		'settings' => 'telegram',
		'label'    => __( 'Telegram', 'sheen' ),
		'section'  => 'socials',
		'priority' => 210,
	] );

	/*------------------------------------*\
		#End Social Networks
	\*------------------------------------*/

	
	/*------------------------------------*\
		#Single Options Start
	\*------------------------------------*/
	
	Kirki::add_field( 'sheen', [
		'type'        => 'radio-image',
		'settings'    => 'single_thumbnail_size',
		'label'       => esc_html__( 'Thumbnail Size', 'sheen' ),
		'section'     => 'single_options',
		'default'     => 'normal',
		'priority'    => 10,
		'choices'     => [
			'normal' => get_template_directory_uri() . '/assets/images/normal.jpg',
			'wide'   => get_template_directory_uri() . '/assets/images/wide.jpg',
		],
	]);

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'single_tags',
		'label'       => esc_html__( 'Display Single Tags', 'sheen' ),
		'section'     => 'single_options',
		'default'     => "1",
		'priority'    => 20,
	]);

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'single_shares',
		'label'       => esc_html__( 'Display Share Options', 'sheen' ),
		'section'     => 'single_options',
		'default'     => "1",
		'priority'    => 30,
	]);

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'single_meta_wrapper',
		'label'       => esc_html__( 'Post Meta Wrapper', 'sheen' ),
		'section'     => 'single_options',
		'default'     => 0,
		'priority'    => 40,
	]);

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'single_comments_count',
		'label'       => esc_html__( 'Display Comments Count', 'sheen' ),
		'section'     => 'single_options',
		'default'     => 0,
		'priority'    => 50,
	]);

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'single_navigation',
		'label'       => esc_html__( 'Display Post Navigation', 'sheen' ),
		'section'     => 'single_options',
		'default'     => 0,
		'priority'    => 55,
	]);

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'single_display_date',
		'label'       => esc_html__( 'Display Post Date', 'sheen' ),
		'section'     => 'single_options',
		'default'     => 1,
		'priority'    => 56,
	]);

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'single_display_author',
		'label'       => esc_html__( 'Display Post Author', 'sheen' ),
		'section'     => 'single_options',
		'default'     => 1,
		'priority'    => 57,
	]);

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'single_display_category',
		'label'       => esc_html__( 'Display Post Categories', 'sheen' ),
		'section'     => 'single_options',
		'default'     => 1,
		'priority'    => 58,
	]);

	Kirki::add_field( 'sheen', [
		'type'	   => 'sortable',
		'settings' => 'single_sliders',
		'label'    => __( 'Sortable Sliders Option', 'sheen' ),
		'section'  => 'single_options',
		'default'  => [ 'gallery', 'carousel' ],
		'priority' => 60,
		'choices'  => [
			'gallery'  => esc_html__( 'Gallery', 'sheen' ),
			'carousel' => esc_html__( 'Carousel', 'sheen' ),
		],
	]);

	/*------------------------------------*\
		#Single Options End
	\*------------------------------------*/


	/*------------------------------------*\
	  #Home Page Options start
	\*------------------------------------*/

	Kirki::add_field( 'sheen', [
		'type'        => 'image',
		'settings'    => 'profile_image',
		'label'       => esc_html__( 'Profile Image', 'sheen' ),
		'description' => esc_html__( 'Add Profile Image here', 'sheen' ),
		'section'     => 'home_options',
		'priority'    => 10,
		'choices' => array(
			'save_as' => 'array',
		),
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'text',
		'settings' => 'profile_name',
		'label'    => __( 'Name', 'sheen' ),
		'section'  => 'home_options',
		'priority' => 20,
	] );

	Kirki::add_field( 'sheen', [
		'type'     => 'textarea',
		'settings' => 'profile_description',
		'label'    => __( 'Description', 'sheen' ),
		'section'  => 'home_options',
		'priority' => 30,
	] );

	/*------------------------------------*\
	  #Home Page Options end
	\*------------------------------------*/


	/*------------------------------------*\
	  #Typography Options start
	\*------------------------------------*/
	
	/* Typography */
	Kirki::add_field( 'sheen', [
		'type'     => 'toggle',
		'settings' => 'use_google_fonts',
		'label'    => esc_html__( 'Use Google Fonts', 'sheen' ),
		'section'  => 'typography',
		'default'  => 1,
		'priority' => 5,
	] );

	/* Base Fonts */
	Kirki::add_field( 'sheen', [
		'active_callback' => [
			[
				'setting'  => 'use_google_fonts',
				'operator' => '==',
				'value'    => true,
			],
		],
		'type'        => 'typography',
		'settings'    => 'typography_base_font',
		'label'       => esc_html__( 'Base Font', 'sheen' ),
		'section'     => 'typography',
		'default'     => [
			'font-family'   	 => 'DM Sans',
			'font-size'          => '1rem',
			'variant'         	 => 'regular',
			'line-height'		 => '1.563rem',
			'letter-spacing'     => '0'
		],
		'output'      => array(
			array(
				'element' => 'html',
			),
			array(
				'element' => '.edit-post-visual-editor.editor-styles-wrapper',
				'context' => [ 'editor' ],
			)
		),
		'priority'    => 10,
		'transport'   => 'auto',
	] );

	/* H1 */
	Kirki::add_field( 'sheen',
		[
		'active_callback' => [
			[
				'setting'  => 'use_google_fonts',
				'operator' => '==',
				'value'    => true,
			],
		],
		'type'            => 'typography',
		'settings'        => 'typography_h1',
		'label'           => esc_html__( 'H1', 'sheen' ),
		'section'         => 'typography',
		'default'         => [
			'font-weight'    => '700',
			'font-family'    => 'Josefin Sans',
			'font-size'      => '3.75rem',
			'line-height'    => '3.75rem',
			'letter-spacing' => '0',
		],
		'transport'       => 'auto',
		'priority'        => 20,
		'output'          => array(
			array(
				'element' => array( 'h1', '.h1' ),
			),
		),
	] );

	/* H2 */
	Kirki::add_field( 'sheen',
		[
		'active_callback' => [
			[
				'setting'  => 'use_google_fonts',
				'operator' => '==',
				'value'    => true,
			],
		],
		'type'            => 'typography',
		'settings'        => 'typography_h2',
		'label'           => esc_html__( 'H2', 'sheen' ),
		'section'         => 'typography',
		'default'         => [
			'font-weight'    => '700',
			'font-family'    => 'Josefin Sans',
			'font-size'      => '2rem',
			'line-height'    => '2.188rem',
			'letter-spacing' => '0',
		],
		'transport'       => 'auto',
		'priority'        => 30,
		'output'          => array(
			array(
				'element' => array( 'h2', '.h2' ),
			),
		),
	] );
	
	/* H3 */
	Kirki::add_field( 'sheen',
		[
		'active_callback' => [
			[
				'setting'  => 'use_google_fonts',
				'operator' => '==',
				'value'    => true,
			],
		],
		'type'            => 'typography',
		'settings'        => 'typography_h3',
		'label'           => esc_html__( 'H3', 'sheen' ),
		'section'         => 'typography',
		'default'         => [
			'font-weight'    => '400',
			'font-family'    => 'Josefin Sans',
			'font-size'      => '1.5rem',
			'line-height'    => '1.5rem',
			'letter-spacing' => '0',
		],
		'transport'       => 'auto',
		'priority'        => 40,
		'output'          => array(
			array(
				'element' => array( 'h3', '.h3' ),
			),
		),
	] );

	/* H4 */
	Kirki::add_field( 'sheen',
		[
		'active_callback' => [
			[
				'setting'  => 'use_google_fonts',
				'operator' => '==',
				'value'    => true,
			],
		],
		'type'            => 'typography',
		'settings'        => 'typography_h4',
		'label'           => esc_html__( 'H4', 'sheen' ),
		'section'         => 'typography',
		'default'         => [
			'font-weight'    => '400',
			'font-family'    => 'DM Sans',
			'font-size'      => '1.25rem',
			'line-height'    => '1.625rem',
			'letter-spacing' => '0',
		],
		'transport'       => 'auto',
		'priority'        => 50,
		'output'          => array(
			array(
				'element' => array( 'h4', '.h4' ),
			),
		),
	] );

	/* H5 */
	Kirki::add_field( 'sheen',
		[
		'active_callback' => [
			[
				'setting'  => 'use_google_fonts',
				'operator' => '==',
				'value'    => true,
			],
		],
		'type'            => 'typography',
		'settings'        => 'typography_h5',
		'label'           => esc_html__( 'H5', 'sheen' ),
		'section'         => 'typography',
		'default'         => [
			'font-weight'    => '400',
			'font-family'    => 'DM Sans',
			'font-size'      => '1rem',
			'line-height'    => '1.563rem',
			'letter-spacing' => '0',
		],
		'transport'       => 'auto',
		'priority'        => 60,
		'output'          => array(
			array(
				'element' => array( 'h5', '.h5' ),
			),
		),
	] );

	/* H6 */
	Kirki::add_field( 'sheen',
		[
		'active_callback' => [
			[
				'setting'  => 'use_google_fonts',
				'operator' => '==',
				'value'    => true,
			],
		],
		'type'            => 'typography',
		'settings'        => 'typography_h6',
		'label'           => esc_html__( 'H6', 'sheen' ),
		'section'         => 'typography',
		'default'         => [
			'font-weight'    => '400',
			'font-family'    => 'DM Sans',
			'font-size'      => '0.875rem',
			'line-height'    => '1.125rem',
			'letter-spacing' => '0',
		],
		'transport'       => 'auto',
		'priority'        => 70,
		'output'          => array(
			array(
				'element' => array( 'h6', '.h6' ),
			),
		),
	] );

	/*------------------------------------*\
	  #Typography Options end
	\*------------------------------------*/

	/*------------------------------------*\
	  #Projects Options Start 
	\*------------------------------------*/
	
	/* Custom Arvhives title  */
	Kirki::add_field( 'sheen', [
		'type'     => 'text',
		'settings' => 'archives_title',
		'label'    => esc_html__( 'Post Type Archive title', 'sheen' ),
		'section'  => 'projects_options',
		'priority' => 10,
	] );

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'display_projects_date',
		'label'       => esc_html__( 'Display Projects Date', 'sheen' ),
		'section'     => 'projects_options',
		'default'     => 0,
		'priority'    => 20,
	] );

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'display_projects_author',
		'label'       => esc_html__( 'Display Author', 'sheen' ),
		'section'     => 'projects_options',
		'default'     => 0,
		'priority'    => 30,
	] );

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'projects_display_taxonomy',
		'label'       => esc_html__( 'Display Projects Category', 'sheen' ),
		'section'     => 'projects_options',
		'default'     => 0,
		'priority'    => 40,
	] );

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'projects_display_archives_date',
		'label'       => esc_html__( 'Display Projects Archives Date', 'sheen' ),
		'section'     => 'projects_options',
		'default'     => 0,
		'priority'    => 50,
	] );

	/*------------------------------------*\
	  #Projects Options end 
	\*------------------------------------*/

	/*------------------------------------*\
	  #Archive Options Start 
	\*------------------------------------*/

	Kirki::add_field( 'sheen', [
		'type'        => 'toggle',
		'settings'    => 'archives_display_date',
		'label'       => esc_html__( 'Display Date', 'sheen' ),
		'section'     => 'archive_options',
		'default'     => 1,
		'priority'    => 40,
	] );
	
	/*------------------------------------*\
	  #Archive Options End 
	\*------------------------------------*/

	});

}