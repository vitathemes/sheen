<?php
/**
 * Template part for displaying posts in single pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package brilliance
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('c-single c-single--centered'); ?>>
    <div class="c-single_wrapper">
        <div class="c-single__header">
            <div class="c-single__header-title">
                <?php the_title( '<h2 class="c-single__title">', '</h2>' ); ?>
            </div>
            <div class="c-single__meta">
                <?php
                    brilliance_posted_on( false , "u-link--tertiary" );
                    brilliance_get_seprator();
                    brilliance_post_categories( ", " , "u-link--meta" );
                ?>
            </div>
            <?php 
                if( has_post_thumbnail() ) { 
                    echo wp_kses_post( '<div class="c-single__thumbnail">' );
                    (get_theme_mod( 'single_thumbnail_size' , 'normal' ) === 'wide') ?  $brilliance_is_thumbnail_wide = esc_attr( 'c-single__thumbnail__img wide' ) : $brilliance_is_thumbnail_wide = esc_attr( 'c-single__thumbnail__img normal' ) ;
                    brilliance_get_thumbnail( "large" , esc_attr( $brilliance_is_thumbnail_wide ) );
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
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'brilliance' ),
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
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'brilliance' ),
                        'after'  => '</div>',
                    )
                );
		    ?>
        </div>
    </div>

    <?php if( true == get_theme_mod( 'single_gallery', true ) ) : ?>
    <div class="c-single__slider">
        <div class="c-single__wrapper c-single__wrapper-has-border">
            <?php brilliance_get_gallery_theme_option( 'single_gallery_title' , 'Image galleries' , 'c-single__slider-title h3--bold' , 'h3' ); // Function echo sanitized ?>
            <?php brilliance_get_gallery_theme_option( 'single_gallery_description' , 'Here’s a really neat custom feature we added – galleries:' , 'c-single__slider-desc ' , 'p'); // Function echo sanitized ?>
        </div>
        <?php brilliance_get_gallery(get_the_ID()); ?>
    </div>
    <?php endif; ?>

    <div class="c-single__slider c-single__slider--carousel">
        <div class="c-single__wrapper c-single__wrapper-has-border c-single__wrapper--carousel">
            <?php brilliance_get_gallery_theme_option( 'single_carousel_title' , 'Image carousels' , 'c-single__slider-title h3--bold' , 'h3' ); // Function echo sanitized ?>
            <?php brilliance_get_gallery_theme_option( 'single_carousel_description' , 'Here’s another gallery with only one column, which creates a carousel slide-show instead.
		    A nice little feature: the carousel only advances when it is in view, so your visitors won’t scroll down to find it half way through your images.' , 'c-single__slider-desc ' , 'p'); // Function echo sanitized ?>
        </div>

        <?php brilliance_get_carousel(get_the_ID()); ?>
    </div>
</article>
<!-- #post-<?php the_ID(); ?> -->