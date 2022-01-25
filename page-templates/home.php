<?php 
/**
 * 
 * Template Name: Home
 * 
 * The main template file for home page
 *
 * If this page doesn't exists index.php recommend show ( Recommended for using as home page )
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */
get_header(); 
?>
<main id="primary" class="c-main">
    <div class="c-main__content c-main__content--big-space">
        <div class="c-profile">
            <?php brilliance_get_profile_image(); ?>
            <div class="c-profile__context">
                <?php
                    $brilliance_profile_name = get_theme_mod( 'profile_name', '' );
                    if( $brilliance_profile_name ) { 
                        echo sprintf( '<h1 class="c-profile__name">%s</h1>', esc_html($brilliance_profile_name));
                    }

                    $brilliance_profile_desc = get_theme_mod( 'profile_description', '' );
                    if($brilliance_profile_desc) { 
                        echo sprintf('<p class="c-profile__description h4">%s</p>' , esc_html($brilliance_profile_desc) );
                    }
                ?>
            </div>
        </div>

        <?php get_template_part( 'template-parts/components/projects' ); ?>

    </div>
</main>
<?php
get_footer();