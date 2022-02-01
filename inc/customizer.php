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

	add_action( 'init', function () {

	/*------------------------------------*\
	  ############# Config ###############
	\*------------------------------------*/
	
	// Config 
	Kirki::add_config( 'brilliance_theme', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	));

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
	
	/* Typography Options */
	Kirki::add_section( 'typography', array(
		'title'          => esc_html__( 'Typography', 'brilliance' ),
		'panel'          => '',
		'priority'       => 50,
	) );

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

	Kirki::add_section( 'home_options', array(
		'title'          => esc_html__( 'Home Page Options', 'brilliance' ),
		'panel'          => 'elements',
		'priority'       => 120,
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
		#Single Options Start
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
	]);

	Kirki::add_field( 'brilliance', [
		'type'        => 'toggle',
		'settings'    => 'single_tags',
		'label'       => esc_html__( 'Display Single Tags', 'brilliance' ),
		'section'     => 'single_options',
		'default'     => "1",
		'priority'    => 20,
	]);

	Kirki::add_field( 'brilliance', [
		'type'        => 'toggle',
		'settings'    => 'single_shares',
		'label'       => esc_html__( 'Display Share Options', 'brilliance' ),
		'section'     => 'single_options',
		'default'     => "1",
		'priority'    => 30,
	]);

	Kirki::add_field( 'brilliance', [
		'type'        => 'toggle',
		'settings'    => 'single_meta_wrapper',
		'label'       => esc_html__( 'Post Meta Wrapper', 'brilliance' ),
		'section'     => 'single_options',
		'default'     => 0,
		'priority'    => 40,
	]);


	Kirki::add_field( 'brilliance', [
		'type'        => 'toggle',
		'settings'    => 'single_comments_count',
		'label'       => esc_html__( 'Display Comments Count', 'brilliance' ),
		'section'     => 'single_options',
		'default'     => 0,
		'priority'    => 50,
	]);

	Kirki::add_field( 'brilliance', [
		'type'	   => 'sortable',
		'settings' => 'single_sliders',
		'label'    => __( 'Sortable Sliders Option', 'brilliance' ),
		'section'  => 'single_options',
		'default'  => [ 'gallery', 'carousel' ],
		'priority' => 60,
		'choices'  => [
			'gallery'  => esc_html__( 'Gallery', 'brilliance' ),
			'carousel' => esc_html__( 'Carousel', 'brilliance' ),
		],
	]);

	/*------------------------------------*\
		#Single Options End
	\*------------------------------------*/


	/*------------------------------------*\
	  #Home Page Options start
	\*------------------------------------*/

	Kirki::add_field( 'brilliance', [
		'type'        => 'image',
		'settings'    => 'profile_image',
		'label'       => esc_html__( 'Profile Image', 'brilliance' ),
		'description' => esc_html__( 'Add Profile Image here', 'brilliance' ),
		'section'     => 'home_options',
		'priority'    => 10,
		'choices' => array(
			'save_as' => 'array',
		),
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'text',
		'settings' => 'profile_name',
		'label'    => __( 'Name', 'brilliance' ),
		'section'  => 'home_options',
		'priority' => 20,
	] );

	Kirki::add_field( 'brilliance', [
		'type'     => 'textarea',
		'settings' => 'profile_description',
		'label'    => __( 'Description', 'brilliance' ),
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
	Kirki::add_field( 'brilliance', [
		'type'     => 'toggle',
		'settings' => 'use_google_fonts',
		'label'    => esc_html__( 'Use Google Fonts', 'brilliance' ),
		'section'  => 'typography',
		'default'  => 1,
		'priority' => 5,
	] );

	/* Base Fonts */
	Kirki::add_field( 'brilliance', [
		'active_callback' => [
			[
				'setting'  => 'use_google_fonts',
				'operator' => '==',
				'value'    => true,
			],
		],
		'type'        => 'typography',
		'settings'    => 'typography_base_font',
		'label'       => esc_html__( 'Base Font', 'brilliance' ),
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
	Kirki::add_field( 'brilliance',
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
		'label'           => esc_html__( 'H1', 'brilliance' ),
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
	Kirki::add_field( 'brilliance',
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
		'label'           => esc_html__( 'H2', 'brilliance' ),
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
	Kirki::add_field( 'brilliance',
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
		'label'           => esc_html__( 'H3', 'brilliance' ),
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
	Kirki::add_field( 'brilliance',
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
		'label'           => esc_html__( 'H4', 'brilliance' ),
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
	Kirki::add_field( 'brilliance',
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
		'label'           => esc_html__( 'H5', 'brilliance' ),
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
	Kirki::add_field( 'brilliance',
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
		'label'           => esc_html__( 'H6', 'brilliance' ),
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

	});

}