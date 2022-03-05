<div class="c-filter js-filter <?php echo esc_attr( sheen_get_filter_class(false) ) ?>">
    <span class="c-filter__title"><?php echo esc_html__( 'Filters' , 'sheen' ) ?></span>
    <form action="<?php echo esc_url(admin_url('admin-ajax.php')) ?>" method="POST" id="filter">
        <?php
            if( $sheen_terms = get_terms( array( 'taxonomy' => 'project_category', 'orderby' => 'name' ) ) ) : 
                echo sprintf( '<a class="c-filter__cat u-link--primary js-filter__cat active" href="%s">%s</a>' , esc_url( home_url() ) , esc_html__( 'All', 'sheen' ) );
                foreach ( $sheen_terms as $sheen_term ) :
                    echo sprintf( '<input class="c-filter__list js-filter__list" type="radio" id="c-%s" name="categoryfilter" value="%s">' , esc_attr($sheen_term->term_id) , esc_attr($sheen_term->term_id));
                    echo sprintf( '<label aria-label="%s" tabindex="0" class="c-filter__cat js-filter__cat" for="c-%s">%s</label>' , esc_attr($sheen_term->name) ,esc_attr($sheen_term->term_id) , esc_html($sheen_term->name));
                endforeach;
            endif;
        ?>
        <input type="hidden" name="action" value="myfilter" />
    </form>
</div>