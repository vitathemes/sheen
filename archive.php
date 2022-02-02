<?php
/**
 * The template for displaying archive pages
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
                <?php echo wp_kses_post(get_the_archive_title()); ?>
            </h2>
        </div><!-- .c-main__header -->
        <div class="c-main__body js-main__body-has-masonry">
            <?php
				if ( have_posts() ) :

					/* Start the Loop */
					while ( have_posts() ) :
						
						the_post();
						
						get_template_part( 'template-parts/content' );
						
					endwhile;

					if( is_post_type_archive('projects') ) { 
						brilliance_get_loadmore( $wp_query , true );
					}
					else { 
						brilliance_get_default_pagination(true);
					}

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
			?>
        </div><!-- .c-main__body -->
    </div><!-- .c-main__content -->
</main><!-- #main -->
<?php
get_footer();