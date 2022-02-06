<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sheen
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('c-single c-single--centered'); ?>>
    <div class="c-single__wrapper">
        <div class="c-single__header">
            <div class="c-single__header-title">
                <?php the_title( '<h2 class="c-single__title u-margin-none">', '</h2>' ); ?>
            </div>

            <?php 
                if( has_post_thumbnail() ) { 
                    echo wp_kses_post( '<div class="c-single__thumbnail">' );
                    (get_theme_mod( 'single_thumbnail_size' , 'normal' ) === 'wide') ?  $sheen_is_thumbnail_wide = esc_attr( 'c-single__thumbnail__img wide' ) : $sheen_is_thumbnail_wide = esc_attr( 'c-single__thumbnail__img normal' ) ;
                    sheen_get_thumbnail( "large" , esc_attr( $sheen_is_thumbnail_wide ) );
                    echo wp_kses_post( '</div>' );
                }   
            ?>
        </div>
        <div class="c-single__content">
            <?php
                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'sheen' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );
                
                wp_link_pages(
                    array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sheen' ),
                        'after'  => '</div>',
                    )
                );
		    ?>
        </div>
    </div>

    <div class="c-single__wrapper">
        <?php 
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
        ?>
    </div>

</article>
<!-- #post-<?php the_ID(); ?> -->