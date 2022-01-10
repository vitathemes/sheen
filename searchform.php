<?php
/**
 * Display Search Form 
 *
 * @package brilliance
 */
?>
<form role="search" method="get" class="c-header__search-form search-form" action="<?php echo esc_url(home_url( '/' )); ?>">
    <label>
        <span class="screen-reader-text"><?php echo esc_html_e( 'Search for:', 'brilliance' ) ?></span>
        <input type="search" class="c-header__search-field search-field" placeholder="<?php echo esc_attr__( 'Search…', 'brilliance' ) ?>" value="<?php echo get_search_query() ?>"
            name="s" title="<?php echo esc_attr__( 'Search for:', 'brilliance' ) ?>" />
    </label>

    <button class="c-search-form__submit c-btn-seacrh c-btn-seacrh--big search-submit" aria-label="<?php esc_attr_e('Search', 'brilliance'); ?>" type="submit">

        <span class="c-header__search-icon c-header__search-icon--search iconify" data-icon="bx:bx-search-alt-2"></span>

    </button>
</form>