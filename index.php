<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package brilliance
 */
get_header();
?>
<main id="primary" class="c-main">
    <div class="c-main__content">
        <div class="c-main__header">
            <h2 class="c-main__title">
                <?php brilliance_get_index_title(); ?>
            </h2>
        </div>
        <div class="c-main__body js-main__body-has-masonry">
            <?php
			
				if ( have_posts() ) :
					/* Start the Loop */
					while ( have_posts() ) :
						
						the_post();
						
						get_template_part( 'template-parts/content' , get_post_type() );

					endwhile;
					
					brilliance_get_default_pagination(true);

				else :

					get_template_part( 'template-parts/content', 'none' );
					
				endif;
				
			?>
        </div>
    </div>
</main><!-- #main -->
<?php
get_footer();