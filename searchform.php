<?php
/**
 * Display Search Form 
 *
 * @package sheen
 */
?>
<form role="search" method="get" class="c-header__search-form search-form" action="<?php echo esc_url(home_url( '/' )); ?>">
    <label>
        <span class="screen-reader-text"><?php echo esc_html_e( 'Search for:', 'sheen' ) ?></span>
        <input type="search" class="c-header__search-field search-field" placeholder="<?php echo esc_attr__( 'Search…', 'sheen' ) ?>" value="<?php echo get_search_query() ?>"
            name="s" title="<?php echo esc_attr__( 'Search for:', 'sheen' ) ?>" />
    </label>
    <button class="c-search-form__submit c-btn-seacrh c-btn-seacrh--big search-submit" aria-label="<?php esc_attr_e('Search', 'sheen'); ?>" type="submit">
        <span class="c-header__search-icon c-header__search-icon--search iconify" data-icon="akar-icons:search"></span>
    </button>
</form>