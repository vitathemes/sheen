<div class="c-projects">
    <?php
        if( get_query_var( 'paged' ) )
            $sheen_paged = get_query_var( 'paged' );
        else {
        if( get_query_var( 'page' ) )
            $sheen_paged = get_query_var( 'page' );
        else
            $sheen_paged = 1;
            set_query_var( 'paged', $sheen_paged );
            $sheen_paged_post = $sheen_paged;
        }

        $sheen_paged_post = (get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

        $sheen_args = array (
            "post_status"            => "publish",
            "post_type"              => "projects",
            "paged"                  => $sheen_paged_post,
            "posts_per_page"         => get_option("posts_per_page"),
        );
        
        global $sheen_query;
        $sheen_query = $wp_query;

        $sheen_query->query($sheen_args);
    ?>

    <div class="c-main__filter">
        <div class="c-main__filter-wrapper js-main__filter-wrapper">
            <button class="c-main__filter-items js-main__filter-items" aria-label="<?php esc_attr_e( 'Filter button', 'sheen' ) ?>">
                <div class="c-main__filter-item"></div>
                <div class="c-main__filter-item"></div>
                <div class="c-main__filter-item"></div>
            </button>
        </div>
    </div>

    <?php get_template_part('template-parts/components/common/filter'); ?>

    <div class="c-main__body c-main__body--projects js-main__body-has-masonry" id="response">
        <?php         
            if ( $sheen_query->have_posts() ) :
                
                while ( $sheen_query->have_posts() ) : $sheen_query->the_post();
                    get_template_part( 'template-parts/content', get_post_type() );
                endwhile;
            
                sheen_get_loadmore( $sheen_query , true );
                
                wp_reset_postdata();
                
            endif;
        ?>
    </div>
</div>