<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package brilliance
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('c-post js-post-has-masonry'); ?>>

    <div class="c-post__thumbnail">
        <?php brilliance_get_thumbnail(); ?>
    </div>

    <div class="c-post__entry-meta">

        <?php 
			the_title( '<h2 class="c-post__entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

			brilliance_posted_on();

			brilliance_get_seprator();

			brilliance_post_categories();
		?>

        <?php
			wp_link_pages(
				array(
					'before' => '<div class="c-post__page-links">' . esc_html__( 'Pages:', 'brilliance' ),
					'after'  => '</div>',
				)
			);
		?>
    </div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->