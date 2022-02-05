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
        <a class="c-post__thumbnail__link" href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
            <?php brilliance_get_thumbnail(); ?>
        </a>
    </div>
    <div class="c-post__entry-header">
        <?php 
			the_title( '<h3 class="c-post__entry-title h3--bold"><a class="u-link--secondary" href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );

			echo wp_kses_post( '<div class="c-post__entry-meta">' );

				brilliance_posted_on( false , "u-link--tertiary" );
				
				if( has_term( '', 'project_category' ) || has_category('',$post->ID) ) { 
					brilliance_get_seprator();
				}
				
				if( 'projects' === get_post_type() ) { 
					brilliance_get_taxonomy('project_category' , 'c-post__taxonomy u-link--meta' , 'a'); // Will be Escaped in function 
				}
				else { 
					brilliance_post_categories(" " , "u-link--meta");
				}
				
			echo wp_kses_post( '</div>' );
	
			wp_link_pages(
				array(
					'before' => '<div class="c-post__page-links">' . esc_html__( 'Pages:', 'brilliance' ),
					'after'  => '</div>',
				)
			);
		?>
    </div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->