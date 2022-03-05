<?php 
/**
 * 
 * Template Name: Home
 * 
 * The main template file for home page
 *
 * If this page doesn't exists index.php recommend to show ( Recommended for using as home page )
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 */
get_header();
?>
<main id="primary" class="c-main">
    <div class="c-main__content">
        <div class="c-profile">
            <?php sheen_get_profile_image(); ?>
            <div class="c-profile__context">
                <?php
                    $sheen_profile_name = get_theme_mod( 'profile_name', '' );
                    if( $sheen_profile_name ) { 
                        echo sprintf( '<h1 class="c-profile__name">%s</h1>', esc_html($sheen_profile_name));
                    }
                    
                    $sheen_profile_desc = get_theme_mod( 'profile_description', '' );
                    if($sheen_profile_desc) { 
                        echo sprintf('<p class="c-profile__description h4">%s</p>' , esc_html($sheen_profile_desc) );
                    }
                ?>
            </div>
        </div>
        <?php get_template_part( 'template-parts/components/projects' ); ?>
    </div>
</main>
<?php
get_footer();