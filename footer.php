<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package brilliance
 */

?>

<footer id="colophon" class="c-footer">
    <div class="site-info">
        <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'brilliance' ) ); ?>">
            <?php
                    /* translators: %s: CMS name, i.e. WordPress. */
                    printf( esc_html__( 'Proudly powered by %s', 'brilliance' ), 'WordPress' );
                ?>
        </a>
        <span class="sep"> | </span>
        <?php
                /* translators: 1: Theme name, 2: Theme author. */
                printf( esc_html__( 'Theme: %1$s by %2$s.', 'brilliance' ), 'brilliance', '<a href="http://underscores.me/">Underscores.me</a>' );
            ?>
    </div><!-- .site-info -->
</footer><!-- #colophon -->

</div><!-- o-page__container -->
</div><!-- o-page -->

<?php wp_footer(); ?>

</body>

</html>