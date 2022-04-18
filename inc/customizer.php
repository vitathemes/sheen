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


if( ! function_exists('sheen_customize_partial_blogname') ) :
	/**
	 * Render the site title for the selective refresh partial.
	 *
	 * @return void
	 */
	function sheen_customize_partial_blogname() {
		bloginfo( 'name' );
	}
endif;

if( ! function_exists('sheen_customize_partial_blogdescription') ) :
	/**
	 * Render the site tagline for the selective refresh partial.
	 *
	 * @return void
	 */
	function sheen_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
endif;

if( ! function_exists('sheen_customize_preview_js') ) :
	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	function sheen_customize_preview_js() {
		wp_enqueue_script( 'sheen-customizer', get_template_directory_uri() . './assets/js/customizer.js', array( 'customize-preview' ), SHEEN_VERSION, true );
	}
endif;
add_action( 'customize_preview_init', 'sheen_customize_preview_js' );


if( function_exists( 'kirki' ) ) {

	if (class_exists('\Kirki\Section')) {

		add_action( 'init', function () {
		/*------------------------------------*\
		############# Config ###############
		\*------------------------------------*/
		
		/**
		 * 
		 * Config is not needed anymore since v4+
		 * 
		 * 
		 * @link https://kirki.org/docs/setup/config/
		 * 
		*/

		/*------------------------------------*\
		############# Panels ###############
		\*------------------------------------*/

		/* Elements */
		new \Kirki\Panel(
			'elements',
			[
				'priority'    => 90,
				'title'    => esc_html__( 'Elements', 'sheen' ),
			]
		);

		/* Footer */
		new \Kirki\Panel(
			'footer',
			[
				'priority'    => 200,
				'title'    => esc_html__( 'Footer', 'sheen' ),
			]
		);
		
		/*------------------------------------*\
		############# Sections #############
		\*------------------------------------*/

		/* Typography Options */
		new \Kirki\Section(
			'typography',
			[
				'title'          => esc_html__( 'Typography', 'sheen' ),
				'panel'          => '',
				'priority'       => 50,
			]
		);
		
		/* Social Options  */
		new \Kirki\Section(
			'socials',
			[
				'title'          => esc_html__( 'Social Networks', 'sheen' ),
				'description'    => esc_html__( 'Add or edit your social networks', 'sheen' ),
				'panel'          => '',
				'priority'       => 100,
			]
		);


		/* Typography Colors Section */
		new \Kirki\Section(
			'colors',
			[
				'title'          => esc_html__( 'Theme Colors', 'sheen' ),
				'description'    => esc_html__( 'Change Theme color and customize them.', 'sheen' ),
				'panel'          => '',
				'priority'       => 100,
			]
		);
		

		/* Footer Normal Options */
		new \Kirki\Section(
			'footer_context',
			[
				'title'          => esc_html__( 'Footer Context', 'sheen' ),
				'description'    => esc_html__( 'Change Theme footer Texts', 'sheen' ),
				'panel'          => 'footer',
				'priority'       => 150,
			]
		);
		

		/*------------------------------------*\
			#Elements
		\*------------------------------------*/
		new \Kirki\Section(
			'single_options',
			[
				'title'          => esc_html__( 'Single Options', 'sheen' ),
				'panel'          => 'elements',
				'priority'       => 100,
			]
		);

		new \Kirki\Section(
			'home_options',
			[
				'title'          => esc_html__( 'Home Page Options', 'sheen' ),
				'panel'          => 'elements',
				'priority'       => 120,
			]
		);

		new \Kirki\Section(
			'projects_options',
			[
				'title'          => esc_html__( 'Projects Options', 'sheen' ),
				'panel'          => 'elements',
				'priority'       => 130,
			]
		);

		new \Kirki\Section(
			'archive_options',
			[
				'title'          => esc_html__( 'Archive Options', 'sheen' ),
				'panel'          => 'elements',
				'priority'       => 140,
			]
		);
		
		/*------------------------------------*\
		############## Fields ##############
		\*------------------------------------*/

		/*------------------------------------*\
			#Start Colors
		\*------------------------------------*/

		new \Kirki\Field\Color(
			[
				'settings'    => 'theme_primary_color',
				'label'       => __( 'Primary Color', 'sheen' ),
				'section'  	  => 'colors',
				'default'     => '#000000',
				'priority'    => 10,
			]
		);

		new \Kirki\Field\Color(
			[
				'settings' => 'theme_primary_accent_color',
				'label'    => __( 'Primary Accent Color', 'sheen' ),
				'section'  => 'colors',
				'default'  => '#FF00B8',
				'priority' => 20,
			]
		);

		new \Kirki\Field\Color(
			[
				'settings' => 'theme_headings_color',
				'label'    => __( 'Headings Color', 'sheen' ),
				'section'  => 'colors',
				'default'  => '#060606',
				'priority' => 30,
			]
		);
		
		new \Kirki\Field\Color(
			[
				'settings' => 'theme_primary_text_color',
				'label'    => __( 'Primary Text Color', 'sheen' ),
				'section'  => 'colors',
				'default'  => '#060606',
				'priority' => 40,
			]
		);

		new \Kirki\Field\Color(
			[
				'settings' => 'theme_primary_text_white_color',
				'label'    => __( 'Primary White Text Color', 'sheen' ),
				'section'  => 'colors',
				'default'  => '#FBFBFB',
				'priority' => 45,
			]
		);

		new \Kirki\Field\Color(
			[
				'settings' => 'theme_secondary_text_color',
				'label'    => __( 'Secondary Text Color', 'sheen' ),
				'section'  => 'colors',
				'default'  => '#767676',
				'priority' => 50,
			]
		);
		
		new \Kirki\Field\Color(
			[
				'settings' => 'theme_tertiary_text_color',
				'label'    => __( 'Tertiary Text Color', 'sheen' ),
				'section'  => 'colors',
				'default'  => '#E1E1E1',
				'priority' => 60,
			]
		);
		
		new \Kirki\Field\Color(
			[
				'settings' => 'theme_error_color',
				'label'    => __( 'Error Color', 'sheen' ),
				'section'  => 'colors',
				'default'  => '#FF3636',
				'priority' => 70,
			]
		);

		new \Kirki\Field\Color(
			[
				'settings' => 'theme_border_color',
				'label'    => __( 'Border Color', 'sheen' ),
				'section'  => 'colors',
				'default'  => '#333333',
				'priority' => 80,
			]
		);

		new \Kirki\Field\Color(
			[
				'settings' => 'theme_border_secondary_color',
				'label'    => __( 'Secondary Border Color', 'sheen' ),
				'section'  => 'colors',
				'default'  => '#060606',
				'priority' => 90,
			]
		);

		/*------------------------------------*\
			#End Colors
		\*------------------------------------*/

		/*------------------------------------*\
			#Start Footer Options
		\*------------------------------------*/

		new \Kirki\Field\Text(
			[
				'settings' => 'footer_custom_text',
				'label'    => esc_html__( 'Footer Cusom Text', 'sheen' ),
				'section'  => 'footer_context',
				'default'  => esc_html__( 'Sheen, a creative portfolio theme', 'sheen' ),
				'priority' => 10,
			]
		);
		
		/*------------------------------------*\
			#End Footer Options
		\*------------------------------------*/
		
		/*------------------------------------*\
			#Start Social Networks
		\*------------------------------------*/

		new \Kirki\Field\URL(
			[
				'settings' => 'facebook',
				'label'    => esc_html__( 'Facebook', 'sheen' ),
				'section'  => 'socials',
				'priority' => 10,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'twitter',
				'label'    => esc_html__( 'Twitter', 'sheen' ),
				'section'  => 'socials',
				'priority' => 20,
			]
		);


		new \Kirki\Field\URL(
			[
				'settings' => 'instagram',
				'label'    => esc_html__( 'Instagram', 'sheen' ),
				'section'  => 'socials',
				'priority' => 30,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'linkedin',
				'label'    => esc_html__( 'Linkedin', 'sheen' ),
				'section'  => 'socials',
				'priority' => 40,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'github',
				'label'    => esc_html__( 'Github', 'sheen' ),
				'section'  => 'socials',
				'priority' => 50,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'mail',
				'label'    => __( 'Email', 'sheen' ),
				'section'  => 'socials',
				'priority' => 60,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'pinterest',
				'label'    => __( 'Pinterest', 'sheen' ),
				'section'  => 'socials',
				'priority' => 70,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'youtube',
				'label'    => __( 'Youtube', 'sheen' ),
				'section'  => 'socials',
				'priority' => 80,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'spotify',
				'label'    => __( 'Spotify', 'sheen' ),
				'section'  => 'socials',
				'priority' => 90,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'gitlab',
				'label'    => __( 'Gitlab', 'sheen' ),
				'section'  => 'socials',
				'priority' => 100,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'lastfm',
				'label'    => __( 'Lastfm', 'sheen' ),
				'section'  => 'socials',
				'priority' => 110,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'stackoverflow',
				'label'    => __( 'Stackoverflow', 'sheen' ),
				'section'  => 'socials',
				'priority' => 120,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'quora',
				'label'    => __( 'Quora', 'sheen' ),
				'section'  => 'socials',
				'priority' => 130,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'reddit',
				'label'    => __( 'Reddit', 'sheen' ),
				'section'  => 'socials',
				'priority' => 140,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'medium',
				'label'    => __( 'Medium', 'sheen' ),
				'section'  => 'socials',
				'priority' => 150,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'vimeo',
				'label'    => __( 'Vimeo', 'sheen' ),
				'section'  => 'socials',
				'priority' => 160,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'lanyrd',
				'label'    => __( 'Lanyrd', 'sheen' ),
				'section'  => 'socials',
				'priority' => 170,
			]
		);
		
		new \Kirki\Field\URL(
			[
				'settings' => 'dribbble',
				'label'    => __( 'Dribbble', 'sheen' ),
				'section'  => 'socials',
				'priority' => 180,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'behance',
				'label'    => __( 'Behance', 'sheen' ),
				'section'  => 'socials',
				'priority' => 190,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'codepen',
				'label'    => __( 'Codepen', 'sheen' ),
				'section'  => 'socials',
				'priority' => 200,
			]
		);

		new \Kirki\Field\URL(
			[
				'settings' => 'telegram',
				'label'    => __( 'Telegram', 'sheen' ),
				'section'  => 'socials',
				'priority' => 210,
			]
		);

		/*------------------------------------*\
			#End Social Networks
		\*------------------------------------*/

		
		/*------------------------------------*\
			#Single Options Start
		\*------------------------------------*/

		new \Kirki\Field\Radio_Image(
			[
				'settings'    => 'single_thumbnail_size',
				'label'       => esc_html__( 'Thumbnail Size', 'sheen' ),
				'section'     => 'single_options',
				'default'     => 'normal',
				'priority'    => 10,
				'choices'     => [
					'normal' => get_template_directory_uri() . '/assets/images/normal.jpg',
					'wide'   => get_template_directory_uri() . '/assets/images/wide.jpg',
				],
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'single_tags',
				'label'       => esc_html__( 'Display Single Tags', 'sheen' ),
				'section'     => 'single_options',
				'default'     => "1",
				'priority'    => 20,
			]
		);
		
		new \Kirki\Field\Toggle(
			[
				'settings'    => 'single_shares',
				'label'       => esc_html__( 'Display Share Options', 'sheen' ),
				'section'     => 'single_options',
				'default'     => "1",
				'priority'    => 30,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'single_comments_count',
				'label'       => esc_html__( 'Display Comments Count', 'sheen' ),
				'section'     => 'single_options',
				'default'     => 0,
				'priority'    => 50,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'single_navigation',
				'label'       => esc_html__( 'Display Post Navigation', 'sheen' ),
				'section'     => 'single_options',
				'default'     => 0,
				'priority'    => 55,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'single_display_date',
				'label'       => esc_html__( 'Display Post Date', 'sheen' ),
				'section'     => 'single_options',
				'default'     => 1,
				'priority'    => 56,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'single_display_author',
				'label'       => esc_html__( 'Display Post Author', 'sheen' ),
				'section'     => 'single_options',
				'default'     => 1,
				'priority'    => 57,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'single_display_category',
				'label'       => esc_html__( 'Display Post Categories', 'sheen' ),
				'section'     => 'single_options',
				'default'     => 1,
				'priority'    => 58,
			]
		);
		/*------------------------------------*\
			#Single Options End
		\*------------------------------------*/


		/*------------------------------------*\
		#Home Page Options start
		\*------------------------------------*/

		new \Kirki\Field\Image(
			[
				'settings'    => 'profile_image',
				'label'       => esc_html__( 'Profile Image', 'sheen' ),
				'description' => esc_html__( 'Add Profile Image here', 'sheen' ),
				'section'     => 'home_options',
				'priority'    => 10,
				'choices' => array(
					'save_as' => 'array',
				),
			]
		);

		new \Kirki\Field\Text(
			[
				'settings' => 'profile_name',
				'label'    => __( 'Name', 'sheen' ),
				'section'  => 'home_options',
				'priority' => 20,
			]
		);

		new \Kirki\Field\Textarea(
			[
				'settings' => 'profile_description',
				'label'    => __( 'Description', 'sheen' ),
				'section'  => 'home_options',
				'priority' => 30,
			]
		);

		new \Kirki\Field\Radio_Buttonset(
			[
				'settings'    => 'home_filter_style',
				'label'       => esc_html__( 'Home Page Filter Style', 'sheen' ),
				'section'     => 'home_options',
				'default'     => 'closed',
				'priority'    => 40,
				'choices'     => [
					'closed'   => esc_html__( 'Closed with toggle', 'sheen' ),
					'opened' => esc_html__( 'Open without toggle', 'sheen' ),
				],
			]
		);

		/*------------------------------------*\
		#Home Page Options end
		\*------------------------------------*/


		/*------------------------------------*\
		#Typography Options start
		\*------------------------------------*/
		
		/* Typography */
		new \Kirki\Field\Toggle(
			[
				'settings' => 'use_google_fonts',
				'label'    => esc_html__( 'Use Google Fonts', 'sheen' ),
				'section'  => 'typography',
				'default'  => 1,
				'priority' => 5,
			]
		);

		/* Base Fonts */
		new \Kirki\Field\Typography(
			[
				'active_callback' => [
					[
						'setting'  => 'use_google_fonts',
						'operator' => '==',
						'value'    => true,
					],
				],
				'settings'    => 'typography_base_font',
				'label'       => esc_html__( 'Base Font', 'sheen' ),
				'section'     => 'typography',
				'priority'    => 10,
				'transport'   => 'auto',
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
			]
		);

		/* H1 */
		new \Kirki\Field\Typography(
			[
				'active_callback' => [
					[
						'setting'  => 'use_google_fonts',
						'operator' => '==',
						'value'    => true,
					],
				],
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
			]
		);

		/* H2 */
		new \Kirki\Field\Typography(
			[
				'active_callback' => [
					[
						'setting'  => 'use_google_fonts',
						'operator' => '==',
						'value'    => true,
					],
				],
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
			]
		);
		
		/* H3 */
		new \Kirki\Field\Typography(
			[
				'active_callback' => [
					[
						'setting'  => 'use_google_fonts',
						'operator' => '==',
						'value'    => true,
					],
				],
				'settings'        => 'typography_h3',
				'label'           => esc_html__( 'H3', 'sheen' ),
				'section'         => 'typography',
				'default'         => [
					'font-weight'    => '700',
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
			]
		);

		/* H4 */
		new \Kirki\Field\Typography(
			[
				'active_callback' => [
					[
						'setting'  => 'use_google_fonts',
						'operator' => '==',
						'value'    => true,
					],
				],
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
			]
		);

		/* H5 */
		new \Kirki\Field\Typography(
			[
				'active_callback' => [
					[
						'setting'  => 'use_google_fonts',
						'operator' => '==',
						'value'    => true,
					],
				],
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
			]
		);

		/* H6 */
		new \Kirki\Field\Typography(
			[
				'active_callback' => [
					[
						'setting'  => 'use_google_fonts',
						'operator' => '==',
						'value'    => true,
					],
				],
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
			]
		);

		/*------------------------------------*\
		#Typography Options end
		\*------------------------------------*/

		/*------------------------------------*\
		#Projects Options Start 
		\*------------------------------------*/
		
		/* Custom Arvhives title  */
		new \Kirki\Field\Text(
			[
				'settings' => 'archives_title',
				'label'    => esc_html__( 'Post Type Archive title', 'sheen' ),
				'section'  => 'projects_options',
				'priority' => 10,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'display_projects_date',
				'label'       => esc_html__( 'Display Projects Date', 'sheen' ),
				'section'     => 'projects_options',
				'default'     => 0,
				'priority'    => 20,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'display_projects_author',
				'label'       => esc_html__( 'Display Author', 'sheen' ),
				'section'     => 'projects_options',
				'default'     => 0,
				'priority'    => 30,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'display_projects_taxonomy',
				'label'       => esc_html__( 'Display Projects Category', 'sheen' ),
				'section'     => 'projects_options',
				'default'     => 1,
				'priority'    => 40,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'projects_display_archives_date',
				'label'       => esc_html__( 'Display Projects Archives Date', 'sheen' ),
				'section'     => 'projects_options',
				'default'     => 0,
				'priority'    => 50,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'projects_display_custom_taxonomy',
				'label'       => esc_html__( 'Display Projects Archives Category', 'sheen' ),
				'section'     => 'projects_options',
				'default'     => 1,
				'priority'    => 60,
			]
		);

		/*------------------------------------*\
		#Projects Options end 
		\*------------------------------------*/

		/*------------------------------------*\
		#Archive Options Start 
		\*------------------------------------*/

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'archives_display_date',
				'label'       => esc_html__( 'Display Date', 'sheen' ),
				'section'     => 'archive_options',
				'default'     => 1,
				'priority'    => 40,
			]
		);

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'archives_display_thumbnail',
				'label'       => esc_html__( 'Display Thumbnail', 'sheen' ),
				'section'     => 'archive_options',
				'default'     => 1,
				'priority'    => 50,
			]
		);
		

		new \Kirki\Field\Toggle(
			[
				'settings'    => 'archives_display_category',
				'label'       => esc_html__( 'Display Category', 'sheen' ),
				'section'     => 'archive_options',
				'default'     => 1,
				'priority'    => 70,
			]
		);
		
		/*------------------------------------*\
		#Archive Options End 
		\*------------------------------------*/

		});

	}

}