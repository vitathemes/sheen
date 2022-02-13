<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package sheen
 * 
 */
get_header();
?>
<main id="primary" class="c-main">
    <div class="c-main__content">
        <section class="c-main__error error-404 not-found">
            <h1 class="c-main__error-title u-margin-none"><?php esc_html_e( '404', 'sheen' ) ?></h1>
            <span class="c-main__error-desc">
                <h2 class="c-main__oops"><?php esc_html_e( 'Oops!', 'sheen' ) ?> </h2>
                <h4 class="c-main__desc"><?php esc_html_e( 'we are sorry, but the page you requested was not found.', 'sheen' ); ?></h4>
            </span>
            <div class="c-main__error-back-button">
                <a class="c-btn" href="<?php echo esc_url( home_url() ); ?>">
                    <?php esc_html_e( 'Back to Home', 'sheen' ); ?>
                </a>
            </div>
        </section><!-- .error-404 -->
    </div><!-- .error-404 -->
</main><!-- .c-main__content -->
<?php
get_footer();