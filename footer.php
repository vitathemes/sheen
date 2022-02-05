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
<?php if( !is_404() ) : ?>
<footer id="colophon" class="c-footer">
    <button class="c-footer__to-top js-footer__to-top" aria-label="<?php esc_attr_e( 'To top button' , 'brilliance') ?>">
        <span class="c-footer__to-top__icon iconify" data-icon="fe:arrow-up"></span>
    </button>
    <div class="c-footer__container">
        <?php 
            $brilliance_footer_custom_text = get_theme_mod( 'footer_custom_text' , esc_html('Brilliance, a creative portfolio theme') );
            if( $brilliance_footer_custom_text ) {
                /** Translator %s 1 : Footer custom Text */
                echo sprintf('<h4 class="c-footer__text u-margin-none">%s</h4>' , esc_html($brilliance_footer_custom_text) );
			}
            if( get_theme_mod( 'footer_copy_text' , '© 2022.Brilliance, made by VitaThemes' ) )
        ?>
        <div class="c-footer__copy">
            <?php 
                /** Translator %s 1: current year. Translator %s 2: copyright text. */
                echo sprintf('<h5 class="c-footer__copy-text u-margin-none">%s %s</h5>' , esc_html( date("Y") ) , esc_html__('Brilliance, made by' , 'brilliance') ); 
                /** Translator %s 1:Vitathems website, Translator %s 2: Vitathemes name text */
                echo sprintf( '<a class="c-footer__copy-link u-link--nav" href="%s"><span class="u-link--tertiary" >%s</span></a>' , esc_url('http://vitathemes.com/') , esc_html__( 'VitaThemes', 'brilliance' ) );  
            ?>
        </div>
        <?php brilliance_socials_links(); ?>
    </div>
</footer><!-- #colophon -->
<?php endif; ?>
</div><!-- o-page__container -->
</div><!-- o-page -->

<?php wp_footer(); ?>

</body>

</html>