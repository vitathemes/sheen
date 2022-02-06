<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package sheen
 */

if ( ! function_exists( 'sheen_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function sheen_posted_on(  $sheen_has_modified_time = false , $sheen_date_class = "" ) {
		$time_string = '<time class="c-post__entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( $sheen_has_modified_time ) {
			$time_string = '<time class="c-post__entry-date published" datetime="%1$s">%2$s</time><time class="c-post__entry-date__updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html( '%s', 'sheen' ),
			'<a class="c-post__date '.esc_attr( $sheen_date_class ).'" href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="c-post__posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;


if ( ! function_exists( 'sheen_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function sheen_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by: %s', 'post author', 'sheen' ),
			'<span class="author vcard"><a class="c-single__author__link url fn n u-link--tertiary" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="c-single__author"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'sheen_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function sheen_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'sheen' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'sheen' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'sheen' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'sheen' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'sheen' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'sheen' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;


if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;


if ( ! function_exists( 'sheen_get_index_title' ) ) :
	/**
	  * Get index.php Title 
	  */
	function sheen_get_index_title() {
		if (is_home()) {
			if (get_option('page_for_posts')) {
				echo esc_html(get_the_title(get_option('page_for_posts')));
			}
			else{
				echo esc_html__( "Blog" , "sheen" );
			}
		} 
	}
endif;


if ( ! function_exists('sheen_get_thumbnail')) :
	/**
	 * Return thumbnail if exist
	 */
	function sheen_get_thumbnail( $sheen_image_size = "large" , $sheen_custom_class = "c-post__thumbnail__image" ) {
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				$sheen_image_size,
				array(
					'alt' => the_title_attribute(
						array(
							'echo' => false,
						)
					),
					'class' => esc_attr( $sheen_custom_class )
				)
			);
		}
		else{
			echo '<img class="'.esc_attr( $sheen_custom_class ).' " alt="'.esc_attr__( 'no thumbnail', 'sheen' ).'" src="' . esc_url(get_template_directory_uri()). '/assets/images/no-thumbnail.png" />';
		}
}
endif;


if ( ! function_exists( 'sheen_post_categories' ) ) : 
	/**
	 * Show post categories
	 */
	function sheen_post_categories( $sheen_separator = ", " , $sheen_cat_class = "" ) {
		$sheen_categories = get_the_category();
		$sheen_output = '';
		
		echo "<div class='c-category'>";
			foreach ( $sheen_categories as $sheen_category ) {
					/** Translator %s 1: category link function. Translator %s 2: class name. Translator %s 3: category name. Translator %s 4: The separator */
					$sheen_output .= sprintf( '<a href="%s" class="c-category__item__link %s">%s</a>%s',
					esc_url( get_category_link( $sheen_category->term_id ) ),
					esc_html( $sheen_cat_class ),
					esc_html( $sheen_category->name ),
					esc_html( $sheen_separator ) );
			}
			echo  wp_kses_post(trim( $sheen_output , $sheen_separator ));
		echo "</div>";
	}
endif;


if (! function_exists('sheen_get_category')) :
	/**
	  * Return Post category
	  */
	function sheen_get_category( $sheen_have_seprator = false ) {
		($sheen_have_seprator) ? $sheen_have_seprator = "<span class='seprator h5 u-link--secondary'> ".esc_html( " | " )." </span>" : $sheen_have_seprator = "";
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'sheen' ) );
		if ( $categories_list ) {
			/* $categories_list list of categories. Rendered from category section that client set in categories.*/
			echo '<h5 class="c-episode__category u-font--regular">'.  wp_kses_post($categories_list) .'</h5>' . wp_kses_post($sheen_have_seprator) ;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
endif;


if ( ! function_exists( 'sheen_get_seprator' ) ) : 
	/**
	 * Display A simple Seprator
	 */

	function sheen_get_seprator() {
		/* translators: %s: Simple separator */
		echo sprintf('<span class="u-seprator">%s</span>' , esc_html( '/' ) );
	}
endif;


if ( ! function_exists( 'sheen_get_footer_copy' ) ) : 
	/**
	 * Display A simple Seprator
	 */

	function sheen_get_footer_copy( $sheen_has_custom_option = false ) {
		
		if( get_theme_mod('footer_copy_text' , esc_html('© 2022.Sheen, made by')) ) { 

			/** Translator %s 1: The Footer copyright text */
			echo wp_kses_post( sprintf('<h5>%s</h5>' , get_theme_mod('footer_copy_text' , esc_html('© 2022.Sheen, made by'))) );
		}

		else { 
			echo wp_kses_post( sprintf('<h5>%s</h5>' , get_theme_mod('footer_copy_text' , esc_html('© 2022.Sheen, made by'))) );
		}

	}
endif;


if ( ! function_exists( 'sheen_socials_links' ) ) :
	/**
	  * Display Social Networks
	  *
	  * @since v1.0.0 
	  *
	  */
	function sheen_socials_links() {

		$sheen_facebook  		=  get_theme_mod( 'facebook', '' );
		$sheen_twitter   		=  get_theme_mod( 'twitter', '' );
		$sheen_instagram 		=  get_theme_mod( 'instagram', '' );
		$sheen_linkedin  		=  get_theme_mod( 'linkedin', '' );
		$sheen_github    		=  get_theme_mod( 'github', '' );
		$sheen_mail   			=  get_theme_mod( 'mail', '' );
		$sheen_pinterest    	=  get_theme_mod( 'pinterest', '' );
		$sheen_youtube    		=  get_theme_mod( 'youtube', '' );
		$sheen_spotify    		=  get_theme_mod( 'spotify', '' );
		$sheen_gitlab    		=  get_theme_mod( 'gitlab', '' );
		$sheen_lastfm    		=  get_theme_mod( 'lastfm', '' );
		$sheen_stackoverflow   =  get_theme_mod( 'stackoverflow', '' );
		$sheen_quora    		=  get_theme_mod( 'quora', '' );
		$sheen_reddit    		=  get_theme_mod( 'reddit', '' );
		$sheen_medium    		=  get_theme_mod( 'medium', '' );
		$sheen_vimeo    		=  get_theme_mod( 'vimeo', '' );
		$sheen_lanyrd    		=  get_theme_mod( 'lanyrd', '' );
		$sheen_dribbble    	=  get_theme_mod( 'dribbble', '' );
		$sheen_behance    		=  get_theme_mod( 'behance', '' );
		$sheen_telegram    	=  get_theme_mod( 'telegram', '' );
		$sheen_codepen    		=  get_theme_mod( 'codepen', '' );

		// If variable was not empty will display the icons
		$sheen_social_variables  = array($sheen_facebook,$sheen_twitter,$sheen_instagram,$sheen_linkedin,$sheen_github,
		$sheen_mail, $sheen_pinterest ,$sheen_youtube ,$sheen_spotify , $sheen_gitlab,$sheen_lastfm ,$sheen_stackoverflow ,$sheen_quora ,$sheen_reddit ,$sheen_medium ,
		$sheen_vimeo, $sheen_lanyrd,$sheen_dribbble ,$sheen_behance,$sheen_telegram,$sheen_codepen
		) ;

		// Check if one of the variables are not empty
		$sheen_social_variable_flag = 0;
		foreach($sheen_social_variables as $sheen_social){
			if( !empty($sheen_social)){
				$sheen_social_variable_flag = 1;
				break;
			}
		}

		// Display the icons here
		if( $sheen_social_variable_flag === 1 ) {

			echo '<div class="c-social-share c-social-share--footer">';

			if ( $sheen_linkedin ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon dashicons dashicons-linkedin"></span></a>', esc_url( $sheen_linkedin ), esc_html__( 'Linkedin', 'sheen' ) );
			}
			
			if ( $sheen_facebook ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon dashicons dashicons-facebook-alt"></span></a>', esc_url( $sheen_facebook ), esc_html__( 'Facebook', 'sheen' ) );
			}

			if ( $sheen_instagram ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon dashicons dashicons-instagram"></span></a>', esc_url( $sheen_instagram ), esc_html__( 'Instagram', 'sheen' ) );
			}

			if ( $sheen_twitter ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon dashicons dashicons-twitter"></span></a>', esc_url( $sheen_twitter ), esc_html__( 'Twitter', 'sheen' ) );
			}

			if ( $sheen_github ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="ant-design:github-filled" data-inline="false"></span></a>', esc_url( $sheen_github ), esc_html__( 'Github', 'sheen' ) );
			}

			if ( $sheen_mail ) {
				echo sprintf( '<a href="mailto:%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="ant-design:mail-outlined" data-inline="false"></span></a>', esc_attr(sanitize_email( $sheen_mail)), esc_html__( 'Mail', 'sheen' ) );
			}

			if ( $sheen_pinterest ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="bx:bxl-pinterest" data-inline="false"></span></a>', esc_url( $sheen_pinterest ), esc_html__( 'pinterest', 'sheen' ) );
			}

			if ( $sheen_youtube ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="akar-icons:youtube-fill" data-inline="false"></span></a>', esc_url( $sheen_youtube ), esc_html__( 'youtube', 'sheen' ) );
			}

			if ( $sheen_spotify ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="bx:bxl-spotify" data-inline="false"></span></a>', esc_url( $sheen_spotify ), esc_html__( 'spotify', 'sheen' ) );
			}

			if ( $sheen_lastfm ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="brandico:lastfm-rect" data-inline="false"></span></a>', esc_url( $sheen_lastfm ), esc_html__( 'lastfm', 'sheen' ) );
			}

			if ( $sheen_gitlab ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="ion:logo-gitlab" data-inline="false"></span></a>', esc_url( $sheen_gitlab ), esc_html__( 'gitlab', 'sheen' ) );
			}

			if ( $sheen_stackoverflow ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="cib:stackoverflow" data-inline="false"></span></a>', esc_url( $sheen_stackoverflow ), esc_html__( 'stackoverflow', 'sheen' ) );
			}

			if ( $sheen_reddit ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="akar-icons:reddit-fill" data-inline="false"></span></a>', esc_url( $sheen_reddit ), esc_html__( 'reddit', 'sheen' ) );
			}

			if ( $sheen_quora ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="bx:bxl-quora" data-inline="false"></span></a>', esc_url( $sheen_quora ), esc_html__( 'quora', 'sheen' ) );
			}

			if ( $sheen_medium ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="ant-design:medium-circle-filled" data-inline="false"></span></a>', esc_url( $sheen_medium ), esc_html__( 'medium', 'sheen' ) );
			}

			if ( $sheen_vimeo ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="brandico:vimeo-rect" data-inline="false"></span></a>', esc_url( $sheen_vimeo ), esc_html__( 'vimeo', 'sheen' ) );
			}

			if ( $sheen_dribbble ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="akar-icons:dribbble-fill" data-inline="false"></span></a>', esc_url( $sheen_dribbble ), esc_html__( 'dribbble', 'sheen' ) );
			}

			if ( $sheen_behance ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="ant-design:behance-outlined" data-inline="false"></span></a>', esc_url( $sheen_behance ), esc_html__( 'behance', 'sheen' ) );
			}

			if ( $sheen_lanyrd ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="cib:lanyrd" data-inline="false"></span></a>', esc_url( $sheen_lanyrd ), esc_html__( 'lanyrd', 'sheen' ) );
			}

			if ( $sheen_telegram ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="bx:bxl-telegram"  data-inline="false"></span></a>', esc_url( $sheen_telegram ), esc_html__( 'Telegram', 'sheen' ) );
			}

			if ( $sheen_codepen ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="akar-icons:codepen-fill" data-inline="false"></span></a>', esc_url( $sheen_codepen ), esc_html__( 'Codepen', 'sheen' ) );
			}

			echo '</div>';
		}
	}
endif;


if ( !function_exists('sheen_get_loadmore') ) : 
	function sheen_get_loadmore( $query  , $sheen_has_masonry = false ) { 
		/**
		 * 
		 * Render load more button
		 * 
		 * @since v1.0.0
		 * 
		 */
		if ( $query->max_num_pages > 1 ) {
			($sheen_has_masonry) ? $sheen_pagination_class_name = 'js-post-has-masonry' : $sheen_pagination_class_name = '';
			echo sprintf( '<div class="c-pagination js-pagination__load-more js-pagination--load-more %s ">
			<button class="button--small js-pagination__load-more__btn">%s</button>
			</div>' , esc_attr( $sheen_pagination_class_name ) , esc_html( 'Load More' , 'sheen' ) );
		}
	}
endif;


if (! function_exists('sheen_get_default_pagination')) :
	/**
	  * Show numeric pagination
	  *
	  * @since v1.0.0
	  *
	  */
	function sheen_get_default_pagination( $sheen_has_masonry = false ) {
		($sheen_has_masonry) ? $sheen_pagination_class_name = 'js-post-has-masonry' : $sheen_pagination_class_name = '';
		if(paginate_links()) {
			echo wp_kses_post( '<div class="c-pagination '. esc_attr( $sheen_pagination_class_name ) .'">' ) . wp_kses_post(
				paginate_links(
					array(
						'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span>',
						'next_text' => '<span class="dashicons dashicons-arrow-right-alt2"></span>',
						'mid_size' => 1,
						'end_size' => 1

					)
			)) . wp_kses_post( '</div>' );
		}
	}
endif;


if (! function_exists('sheen_get_tags')) :
	/**
	 * 
	 * Return post tags
	 * 
	 * @since v1.0.0
	 * 
	 */
	function sheen_get_tags( $sheen_className = 'c-single__tag' ) {
		$sheen_post_tags = get_the_tags();
		if ($sheen_post_tags) {
			$sheen_tags = "";
			foreach($sheen_post_tags as $post_tag) {
				$sheen_tags .= '<a class="'.esc_attr( $sheen_className ).' " href="'.  esc_url( get_tag_link( $post_tag->term_id ) ) .'" title="'.  esc_attr( $post_tag->name ) .'">'. esc_html( $post_tag->name ). '</a>';
			}
			echo wp_kses_post(sprintf('<div class="c-single__tags">%s %s</div>' , esc_html__( 'tags: ' , 'sheen' ) , $sheen_tags));
		}
	}
endif;


if ( ! function_exists( 'sheen_share_links' ) ) {
	/**
	 * 
	 * Display Share icons 
	 * 
	 * @since v1.0.0
	 * 
	 */
	function sheen_share_links() {
		$sheen_linkedin_url = "https://www.linkedin.com/shareArticle?mini=true&url=" .  esc_url( get_permalink() ) . "&title=" . esc_attr( get_the_title() );
		$sheen_twitter_url  = "https://twitter.com/intent/tweet?url=" . esc_url( get_permalink() ) . "&title=" . esc_attr( get_the_title() );
		$sheen_facebook_url = "https://www.facebook.com/sharer.php?u=" . esc_url( get_permalink() );

		echo sprintf( '<h5 class="c-social-share__title u-margin-none">%s</h5>', esc_html__( 'Share:', 'sheen' ) );
		echo sprintf( '<a  class="c-social-share__item" target="_blank" href="%s" aria-label="%s" ><span class="dashicons dashicons-facebook-alt c-social-share__item__icon"></span></a>', esc_url( $sheen_facebook_url ), esc_attr__( "facebook" , "sheen" ) );
		echo sprintf( '<a  class="c-social-share__item" target="_blank" href="%s" aria-label="%s" ><span class="dashicons dashicons-twitter c-social-share__item__icon"></span></a>', esc_url( $sheen_twitter_url ), esc_attr__( "twitter" , "sheen" ) );
		echo sprintf( '<a  class="c-social-share__item" target="_blank" href="%s" aria-label="%s" ><span class="dashicons dashicons-linkedin c-social-share__item__icon"></span></a>', esc_url( $sheen_linkedin_url ), esc_attr__( "linkedin" , "sheen" ) );
	}
}


if ( ! function_exists('sheen_get_profile_image')) :
	/**
	 * 
	 * Get Profile Image
	 * 
	 * @since v1.0.0
	 * 
	 */
	function sheen_get_profile_image() {
		$sheen_image = wp_get_attachment_image_src(get_theme_mod( 'profile_image' )["id"] , 'thumbnail');
		if($sheen_image) { 
			/** translator %s: image src, translator %s 2: image srcset */
			echo sprintf('<div class="c-profile__thumbnail"><img class="c-profile__img" alt="%s" src="%s" loading="lazy" /></div>', esc_attr__( 'Profile image' , 'sheen' ) , esc_attr( esc_url($sheen_image[0])) );
		}
	}
endif;


if ( ! function_exists( 'sheen_get_taxonomy' ) ) :
    /**
	 *
	 * Get Custom Taxonomies
	 * 
	 * @since v1.0.0 
	 * 
	 */
    function sheen_get_taxonomy( $sheen_taxonomy_name = "" , $sheen_class_name = "" , $sheen_tag_name = "span" ) {
        $sheen_custom_taxs = get_the_terms( get_the_ID(), $sheen_taxonomy_name );

		$sheen_output = "";

        if (is_array($sheen_custom_taxs) && !empty($sheen_custom_taxs)) {
            if( !empty( $sheen_taxonomy_name ) ){
                foreach ( $sheen_custom_taxs as $sheen_custom_tax ) {
                    $sheen_output .=  '<'. esc_html($sheen_tag_name) .' class="'.esc_attr(  $sheen_class_name  ).' " href="'.esc_url( get_tag_link( $sheen_custom_tax->term_id ) ).'">' . esc_html( $sheen_custom_tax->name ). '</'. esc_html($sheen_tag_name) .'> ';
                }
				echo wp_kses_post($sheen_output);
            }
        }
    }
endif;