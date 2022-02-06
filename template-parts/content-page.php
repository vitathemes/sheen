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
            <div class="c-single__meta">
                <?php
                    sheen_posted_on( false , "u-link--tertiary" );
                    sheen_get_seprator();
                    sheen_post_categories( ", " , "u-link--meta" );
                ?>
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

    <?php 
        // Get sliders ( Carousel & Gallery ) Features ( Editable from customizer )
        $sheen_template_parts = get_theme_mod( 'single_sliders', array( 'gallery', 'carousel' ) );
        foreach ( $sheen_template_parts as $sheen_template_part ) {
            get_template_part( 'template-parts/components/' . $sheen_template_part );
        }
    ?>

    <?php 
        if( true == get_theme_mod( 'single_meta_wrapper', false ) ) : 
            echo wp_kses_post( '<div class="c-single__wrapper">' );
        endif;
    ?>

    <?php
        // Display Single Tags ( Editable from Customizer ) 
        if( true == get_theme_mod( 'single_tags', true )) { 
            sheen_get_tags('c-single__tag u-link--meta');
        }
    ?>

    <?php 
        // Display Single Social Shares ( Editable from Customizer ) 
        if( true == get_theme_mod( 'single_shares', true ) ) : 
            echo wp_kses_post( '<div class="c-social-share c-social-share--lefted">' );
                sheen_share_links(); // Sanitized Function 
            echo wp_kses_post( '</div>' );
        endif; 
    ?>

    <?php 
    if( true == get_theme_mod( 'single_meta_wrapper', false ) ) : 
        echo wp_kses_post( '</div>' );
    endif;
    ?>

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