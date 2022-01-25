<div class="c-projects">
    <?php
        // Show latest episodes in home page 
        if( get_query_var( 'paged' ) )
            $brilliance_paged = get_query_var( 'paged' );
        else {
        if( get_query_var( 'page' ) )
            $brilliance_paged = get_query_var( 'page' );
        else
            $brilliance_paged = 1;
            set_query_var( 'paged', $brilliance_paged );
            $brilliance_paged_post = $brilliance_paged;
        }

        $brilliance_paged_post = ( get_query_var("paged") ) ? get_query_var("paged") : 1;
    

        $brilliance_args = array (
            "post_status"            => "publish",
            "post_type"              => "post",
            "paged"                  => $brilliance_paged_post,
            "posts_per_page"         => get_option("posts_per_page"),
        );
        
        global $brilliance_query;
        $brilliance_query = $wp_query;

        $brilliance_query->query( $brilliance_args );

        if ( $brilliance_query->have_posts() ) : 
    ?>
    <div class="c-main__body js-main__body-has-masonry">
        <?php         
            while ( $brilliance_query->have_posts() ) : $brilliance_query->the_post();

                get_template_part( 'template-parts/content', get_post_type() );
                
            endwhile;
                brilliance_get_default_pagination();

                wp_reset_postdata();
            endif;
        ?>
    </div>
</div>