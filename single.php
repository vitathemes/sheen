<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package sheen
 */
get_header();
?>
<main id="primary" class="c-main">
    <div class="c-main__content">
        <?php
			while ( have_posts() ) :
				the_post();
				
				get_template_part( 'template-parts/content', 'single' );
				
				if( get_theme_mod( 'single_navigation' , false ) === true ) { 
					the_post_navigation(
						array(
							'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'sheen' ) . '</span> <span class="nav-title">%title</span>',
							'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'sheen' ) . '</span> <span class="nav-title">%title</span>',
						)
					);
				}
			endwhile;
			// End of the loop.
		?>
    </div>
</main><!-- #main -->
<?php
get_footer();