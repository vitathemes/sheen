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
                    <div class="c-header__branding js-header__branding">
                        <?php brilliance_branding(); ?>
                    </div><!-- .site-branding -->
                    <nav class="c-header__nav" id="site-navigation">
                        <button class="c-nav__toggle c-nav__toggle--burger js-nav__toggle" aria-controls="primary-menu" aria-expanded="false"
                            aria-label="<?php esc_attr_e('Primary menu', 'brilliance'); ?>">
                            <span></span>
                        </button>
                        <?php
                            if( !is_404() ) { 
                                if ( has_nav_menu( 'primary' ) ) {
                                    wp_nav_menu(
                                        array(
                                            'walker'          => new Brilliance_walker_nav_menu(),
                                            'theme_location'  => 'primary',
                                            'menu_id'         => 'primary-menu primary',
                                            'menu_class'      => 's-nav nav-menu',
                                            'container_class' => 'c-nav',
                                        )
                                    );
                                }
                            }
                        ?>
                        <?php if( !is_404() ) : ?>
                        <div class="c-header__search js-header__search">
                            <button class="c-header__search-button js-header__search-button" aria-label="<?php esc_attr_e('Toggle Search', 'brilliance'); ?>"
                                aria-controls="primary-menu" aria-expanded="false">
                                <span class="c-header__search-icon iconify" data-icon="bx:bx-search-alt-2"></span>
                            </button>
                        </div>
                        <?php endif; ?>
                        <div class="c-header__search-overlay">
                            <div class="c-header__search-overlay__container">
                                <div class="c-header__search-close">
                                    <button class="c-btn-seacrh js-btn-seacrh" aria-label="<?php esc_attr_e('Toggle close', 'brilliance'); ?>" aria-controls="primary-menu"
                                        aria-expanded="false">
                                        <span class="c-header__search-close__icon iconify" data-icon="codicon:chrome-close">
                                        </span>
                                    </button>
                                </div>
                                <div class="c-header__search-wrapper">
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                        </div>
                    </nav><!-- #site-navigation -->
                </div><!-- .c-header__menu -->
            </header><!-- #masthead -->