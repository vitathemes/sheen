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
                <?php esc_html_e( 'Skip to content', 'sheen' ); ?>
            </a>
            <header id="masthead" class="c-header">
                <div class="c-header__menu">
                    <div class="c-header__branding js-header__branding">
                        <?php brilliance_branding(); ?>
                    </div><!-- .site-branding -->
                    <nav class="c-nav" id="site-navigation">

                        <button class="c-nav__toggle c-nav__toggle--burger js-nav__toggle" aria-controls="primary-menu" aria-expanded="false"
                            aria-label="<?php esc_attr_e('Primary menu', 'sheen'); ?>">
                            <span></span>
                        </button>
                        <div class="c-nav__list js-nav__list">
                            <?php
                                if( !is_404() ) { 
                                    if ( has_nav_menu( 'primary' ) ) {
                                        wp_nav_menu(
                                            array(
                                                'walker'          => new Brilliance_walker_nav_menu(),
                                                'theme_location'  => 'primary',
                                                'menu_id'         => 'primary-menu primary',
                                                'menu_class'      => 's-nav__list nav-menu',
                                                'container'       => 'ul',
                                            )
                                        );
                                    }
                                }
                            ?>
                            <?php if( !is_404() ) : ?>
                            <div class="c-nav__search">
                                <button class="c-nav__search-button js-nav__search-button" aria-label="<?php esc_attr_e('Toggle Search open', 'sheen'); ?>"
                                    aria-controls="primary-menu" aria-expanded="false">
                                    <span class="c-header__search-icon iconify" data-icon="akar-icons:search"></span>
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="c-header__search-overlay js-header__search-overlay">
                            <div class="c-header__search-overlay__container">
                                <div class="c-header__search-close">
                                    <button class="c-btn-seacrh c-btn-seacrh--close js-btn-seacrh-close" aria-label="<?php esc_attr_e('Toggle Search close', 'sheen'); ?>"
                                        aria-controls="primary-menu" aria-expanded="false">
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