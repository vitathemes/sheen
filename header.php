<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package brilliance
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="o-page">

        <div class="o-page__container">

            <a class="skip-link screen-reader-text" href="#primary">
                <?php esc_html_e( 'Skip to content', 'brilliance' ); ?>
            </a>

            <header id="masthead" class="c-header">
                <div class="c-header__menu">

                    <div class="c-header__branding">
                        <?php brilliance_branding(); ?>
                    </div><!-- .site-branding -->

                    <nav class="c-header__nav" id="site-navigation">
                        <button class="c-nav__toggle c-nav__toggle--burger" aria-controls="primary-menu"
                            aria-expanded="false">
                            <span></span>
                        </button>

                        <?php
                            if ( has_nav_menu( 'primary' ) ) {
                                wp_nav_menu(
                                    array(
                                        'walker'          => new Brilliance_walker_nav_menu(),
                                        'theme_location'  => 'primary',
                                        'menu_id'         => 'primary',
                                        'menu_class'      => 's-nav nav-menu',
                                        'container_class' => 'c-nav',
                                    )
                                );
                            }
                        ?>

                    </nav><!-- #site-navigation -->

                </div><!-- .c-header__menu -->
            </header><!-- #masthead -->