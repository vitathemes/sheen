<div class="c-filter js-filter">
    <span class="c-filter__title"><?php echo esc_html__( 'FILTERS' , 'brilliance' ) ?></span>
    <form action="<?php echo esc_url(admin_url('admin-ajax.php')) ?>" method="POST" id="filter">
        <?php
            if( $brilliance_terms = get_terms( array( 'taxonomy' => 'projects_category', 'orderby' => 'name' ) ) ) : 
                echo sprintf( '<a class="c-filter__cat js-filter__cat active" href="%s">%s</a>' , esc_url(site_url()) , esc_html__( 'All', 'brilliance' ) );
                foreach ( $brilliance_terms as $brilliance_term ) :
                    echo sprintf( '<input class="c-filter__list js-filter__list" type="radio" id="c-%s" name="categoryfilter" value="%s">' , esc_attr($brilliance_term->term_id) , esc_attr($brilliance_term->term_id));
                    echo sprintf( '<label class="c-filter__cat js-filter__cat" for="c-%s">%s</label>' , esc_attr($brilliance_term->term_id) , esc_html($brilliance_term->name));
                endforeach;
            endif;
        ?>
        <input type="hidden" name="action" value="myfilter" />
    </form>
</div>