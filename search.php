<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package sheen
 */

get_header();
?>

<main id="primary" class="c-main">
    <div class="c-main__content">
        <div class="c-main__header">
            <h2 class="c-main__title">
                <?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'sheen' ), '<span>' . get_search_query() . '</span>' );
				?>
            </h2>
        </div>
        <div class="c-main__body js-main__body-has-masonry">
            <?php
			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) :
					
					the_post();
					
					get_template_part( 'template-parts/content' );
					
				endwhile;

				sheen_get_default_pagination(true);

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
		?>
        </div>
    </div>
</main><!-- #main -->
<?php
get_footer();