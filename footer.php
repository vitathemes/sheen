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

    <div class="c-footer__container">

        <?php 
            if( get_theme_mod( 'footer_custom_text' , esc_html('Brilliance, a creative portfolio theme') ) ) {
                /** Translator %s 1 : Footer custom Text */
                echo sprintf('<h4 class="c-footer__text u-margin-none">%s</h4>' , get_theme_mod( 'footer_custom_text' , esc_html('Brilliance, a creative portfolio theme') ) );
			}


            if( get_theme_mod( 'footer_copy_text' , '© 2022.Brilliance, made by VitaThemes' ) )
        ?>

        <div class="c-footer__copy">
            <?php 
                /** Translator %s 1: current year. Translator %s 2: copyright text. */
                echo sprintf('<h5 class="c-footer__copy-text u-margin-none">%s %s</h5>' , esc_html( date("Y") ) , esc_html__('Brilliance, made by' , 'brilliance') ); 

                /** Translator %s 1:Vitathems website, Translator %s 2: Vitathemes name text */
                echo sprintf( '<a class="c-footer__copy-link u-link--nav" href="%s">%s</a>' , esc_url('http://vitathemes.com/') , esc_html('VitaThemes') );
            ?>
        </div>

        <?php brilliance_socials_links(); ?>

    </div>

</footer><!-- #colophon -->

</div><!-- o-page__container -->
</div><!-- o-page -->

<?php wp_footer(); ?>

</body>

</html>