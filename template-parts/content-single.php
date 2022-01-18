<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package brilliance
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('c-single'); ?>>

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

                (get_theme_mod( 'single_thumbnail_size' , 'normal' ) === 'normal') ?  $brilliance_is_thumbnail_wide = esc_attr( 'c-single__thumbnail__img normal' ) : $brilliance_is_thumbnail_wide = esc_attr( 'c-single__thumbnail__img wide' ) ;

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

</article>
<!-- #post-<?php the_ID(); ?> -->