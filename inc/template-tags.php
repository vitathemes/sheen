<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package brilliance
 */

if ( ! function_exists( 'brilliance_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function brilliance_posted_on(  $brilliance_has_modified_time = false , $brilliance_date_class = "" ) {
		$time_string = '<time class="c-post__entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( $brilliance_has_modified_time ) {
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
			esc_html( '%s', 'brilliance' ),
			'<a class="c-post__date '.esc_attr( $brilliance_date_class ).'" href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="c-post__posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;


if ( ! function_exists( 'brilliance_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function brilliance_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by: %s', 'post author', 'brilliance' ),
			'<span class="author vcard"><a class="c-single__author__link url fn n u-link--tertiary" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="c-single__author"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'brilliance_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function brilliance_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'brilliance' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'brilliance' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'brilliance' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'brilliance' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'brilliance' ),
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
					__( 'Edit <span class="screen-reader-text">%s</span>', 'brilliance' ),
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


if ( ! function_exists( 'brilliance_get_index_title' ) ) :
	/**
	  * Get index.php Title 
	  */
	function brilliance_get_index_title() {
		if (is_home()) {
			if (get_option('page_for_posts')) {
				echo esc_html(get_the_title(get_option('page_for_posts')));
			}
			else{
				echo esc_html__( "Blog" , "brilliance" );
			}
		} 
	}
endif;


if ( ! function_exists('brilliance_get_thumbnail')) :
	/**
	 * Return thumbnail if exist
	 */
	function brilliance_get_thumbnail( $brilliance_image_size = "large" , $brilliance_custom_class = "c-post__thumbnail__image" ) {
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				$brilliance_image_size,
				array(
					'alt' => the_title_attribute(
						array(
							'echo' => false,
						)
					),
					'class' => esc_attr( $brilliance_custom_class )
				)
			);
		}
		else{
			echo '<img class="'.esc_attr( $brilliance_custom_class ).' " alt="'.esc_attr__( 'no thumbnail', 'brilliance' ).'" src="' . esc_url(get_template_directory_uri()). '/assets/images/no-thumbnail.png" />';
		}
}
endif;


if ( ! function_exists( 'brilliance_post_categories' ) ) : 
	/**
	 * Show post categories
	 */
	function brilliance_post_categories( $brilliance_separator = ", " , $brilliance_cat_class = "" ) {
		$brilliance_categories = get_the_category();
		$brilliance_output = '';
		
		echo "<div class='c-category'>";
			foreach ( $brilliance_categories as $brilliance_category ) {
					/** Translator %s 1: category link function. Translator %s 2: class name. Translator %s 3: category name. Translator %s 4: The separator */
					$brilliance_output .= sprintf( '<a href="%s" class="c-category__item__link %s">%s</a>%s',
					esc_url( get_category_link( $brilliance_category->term_id ) ),
					esc_html( $brilliance_cat_class ),
					esc_html( $brilliance_category->name ),
					esc_html( $brilliance_separator ) );
			}

			echo  wp_kses_post(trim( $brilliance_output , $brilliance_separator ));
		echo "</div>";
	}
endif;


if (! function_exists('brilliance_get_category')) :
	/**
	  * Return Post category
	  */
	function brilliance_get_category( $brilliance_have_seprator = false ) {
		($brilliance_have_seprator) ? $brilliance_have_seprator = "<span class='seprator h5 u-link--secondary'> ".esc_html( " | " )." </span>" : $brilliance_have_seprator = "";
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'brilliance' ) );
		if ( $categories_list ) {
			/* $categories_list list of categories. Rendered from category section that client set in categories.*/
			echo '<h5 class="c-episode__category u-font--regular">'.  wp_kses_post($categories_list) .'</h5>' . wp_kses_post($brilliance_have_seprator) ;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
endif;


if ( ! function_exists( 'brilliance_get_seprator' ) ) : 
	/**
	 * Display A simple Seprator
	 */

	function brilliance_get_seprator() {
		
		/* translators: %s: Simple seprator */
		echo sprintf('<span class="u-seprator">%s</span>' , esc_html( '/' ) );
	}
endif;


if ( ! function_exists( 'brilliance_get_footer_copy' ) ) : 
	/**
	 * Display A simple Seprator
	 */

	function brilliance_get_footer_copy( $brilliance_has_custom_option = false ) {
		
		if( get_theme_mod('footer_copy_text' , esc_html('© 2022.Brilliance, made by')) ) { 

			/** Translator %s 1: The Footer copyright text */
			echo sprintf('<h5>%s</h5>' , get_theme_mod('footer_copy_text' , esc_html('© 2022.Brilliance, made by')));
		}

		else { 
			echo sprintf('<h5>%s</h5>' , get_theme_mod('footer_copy_text' , esc_html('© 2022.Brilliance, made by')));
		}

	}
endif;



if ( ! function_exists( 'brilliance_socials_links' ) ) :
	/**
	  * Display Social Networks
	  *
	  * @since v1.0.0 
	  *
	  */
	function brilliance_socials_links() {

		$brilliance_facebook  		=  get_theme_mod( 'facebook', '' );
		$brilliance_twitter   		=  get_theme_mod( 'twitter', '' );
		$brilliance_instagram 		=  get_theme_mod( 'instagram', '' );
		$brilliance_linkedin  		=  get_theme_mod( 'linkedin', '' );
		$brilliance_github    		=  get_theme_mod( 'github', '' );
		$brilliance_mail   			=  get_theme_mod( 'mail', '' );
		$brilliance_pinterest    	=  get_theme_mod( 'pinterest', '' );
		$brilliance_youtube    		=  get_theme_mod( 'youtube', '' );
		$brilliance_spotify    		=  get_theme_mod( 'spotify', '' );
		$brilliance_gitlab    		=  get_theme_mod( 'gitlab', '' );
		$brilliance_lastfm    		=  get_theme_mod( 'lastfm', '' );
		$brilliance_stackoverflow   =  get_theme_mod( 'stackoverflow', '' );
		$brilliance_quora    		=  get_theme_mod( 'quora', '' );
		$brilliance_reddit    		=  get_theme_mod( 'reddit', '' );
		$brilliance_medium    		=  get_theme_mod( 'medium', '' );
		$brilliance_vimeo    		=  get_theme_mod( 'vimeo', '' );
		$brilliance_lanyrd    		=  get_theme_mod( 'lanyrd', '' );
		$brilliance_dribbble    	=  get_theme_mod( 'dribbble', '' );
		$brilliance_behance    		=  get_theme_mod( 'behance', '' );
		$brilliance_telegram    	=  get_theme_mod( 'telegram', '' );
		$brilliance_codepen    		=  get_theme_mod( 'codepen', '' );


		// If variable was not empty will display the icons
		$brilliance_social_variables  = array($brilliance_facebook,$brilliance_twitter,$brilliance_instagram,$brilliance_linkedin,$brilliance_github,
		$brilliance_mail, $brilliance_pinterest ,$brilliance_youtube ,$brilliance_spotify , $brilliance_gitlab,$brilliance_lastfm ,$brilliance_stackoverflow ,$brilliance_quora ,$brilliance_reddit ,$brilliance_medium ,
		$brilliance_vimeo, $brilliance_lanyrd,$brilliance_dribbble ,$brilliance_behance,$brilliance_telegram,$brilliance_codepen
		) ;

		// Check if one of the variables are not empty
		$brilliance_social_variable_flag = 0;
		foreach($brilliance_social_variables as $brilliance_social){
			if( !empty($brilliance_social)){
				$brilliance_social_variable_flag = 1;
				break;
			}
		}

		// Display the icons here
		if( $brilliance_social_variable_flag === 1 ) {

			echo '<div class="c-social-share c-social-share--footer">';

			if ( $brilliance_linkedin ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon dashicons dashicons-linkedin"></span></a>', esc_url( $brilliance_linkedin ), esc_html__( 'Linkedin', 'brilliance' ) );
			}
			
			if ( $brilliance_facebook ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon dashicons dashicons-facebook-alt"></span></a>', esc_url( $brilliance_facebook ), esc_html__( 'Facebook', 'brilliance' ) );
			}

			if ( $brilliance_instagram ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon dashicons dashicons-instagram"></span></a>', esc_url( $brilliance_instagram ), esc_html__( 'Instagram', 'brilliance' ) );
			}

			if ( $brilliance_twitter ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon dashicons dashicons-twitter"></span></a>', esc_url( $brilliance_twitter ), esc_html__( 'Twitter', 'brilliance' ) );
			}

			if ( $brilliance_github ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="ant-design:github-filled" data-inline="false"></span></a>', esc_url( $brilliance_github ), esc_html__( 'Github', 'brilliance' ) );
			}

			if ( $brilliance_mail ) {
				echo sprintf( '<a href="mailto:%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="ant-design:mail-outlined" data-inline="false"></span></a>', esc_attr(sanitize_email( $brilliance_mail)), esc_html__( 'Mail', 'brilliance' ) );
			}

			if ( $brilliance_pinterest ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="bx:bxl-pinterest" data-inline="false"></span></a>', esc_url( $brilliance_pinterest ), esc_html__( 'pinterest', 'brilliance' ) );
			}

			if ( $brilliance_youtube ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="akar-icons:youtube-fill" data-inline="false"></span></a>', esc_url( $brilliance_youtube ), esc_html__( 'youtube', 'brilliance' ) );
			}

			if ( $brilliance_spotify ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="bx:bxl-spotify" data-inline="false"></span></a>', esc_url( $brilliance_spotify ), esc_html__( 'spotify', 'brilliance' ) );
			}

			if ( $brilliance_lastfm ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="brandico:lastfm-rect" data-inline="false"></span></a>', esc_url( $brilliance_lastfm ), esc_html__( 'lastfm', 'brilliance' ) );
			}

			if ( $brilliance_gitlab ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="ion:logo-gitlab" data-inline="false"></span></a>', esc_url( $brilliance_gitlab ), esc_html__( 'gitlab', 'brilliance' ) );
			}

			if ( $brilliance_stackoverflow ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="cib:stackoverflow" data-inline="false"></span></a>', esc_url( $brilliance_stackoverflow ), esc_html__( 'stackoverflow', 'brilliance' ) );
			}

			if ( $brilliance_reddit ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="akar-icons:reddit-fill" data-inline="false"></span></a>', esc_url( $brilliance_reddit ), esc_html__( 'reddit', 'brilliance' ) );
			}

			if ( $brilliance_quora ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="bx:bxl-quora" data-inline="false"></span></a>', esc_url( $brilliance_quora ), esc_html__( 'quora', 'brilliance' ) );
			}

			if ( $brilliance_medium ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="ant-design:medium-circle-filled" data-inline="false"></span></a>', esc_url( $brilliance_medium ), esc_html__( 'medium', 'brilliance' ) );
			}

			if ( $brilliance_vimeo ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="brandico:vimeo-rect" data-inline="false"></span></a>', esc_url( $brilliance_vimeo ), esc_html__( 'vimeo', 'brilliance' ) );
			}

			if ( $brilliance_dribbble ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="akar-icons:dribbble-fill" data-inline="false"></span></a>', esc_url( $brilliance_dribbble ), esc_html__( 'dribbble', 'brilliance' ) );
			}

			if ( $brilliance_behance ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="ant-design:behance-outlined" data-inline="false"></span></a>', esc_url( $brilliance_behance ), esc_html__( 'behance', 'brilliance' ) );
			}

			if ( $brilliance_lanyrd ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="cib:lanyrd" data-inline="false"></span></a>', esc_url( $brilliance_lanyrd ), esc_html__( 'lanyrd', 'brilliance' ) );
			}

			if ( $brilliance_telegram ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="bx:bxl-telegram"  data-inline="false"></span></a>', esc_url( $brilliance_telegram ), esc_html__( 'Telegram', 'brilliance' ) );
			}

			if ( $brilliance_codepen ) {
				echo sprintf( '<a href="%s" aria-label="%s" class="c-social-share__item" target="_blank"><span class=" c-social-share__icon iconify" data-icon="akar-icons:codepen-fill" data-inline="false"></span></a>', esc_url( $brilliance_codepen ), esc_html__( 'Codepen', 'brilliance' ) );
			}

			echo '</div>';
		}
	}
endif;


if ( !function_exists('brilliance_get_loadmore') ) : 
	function brilliance_get_loadmore( $query  , $brilliance_has_masonry = false ) { 
		/**
		 * 
		 * Render load more button
		 * 
		 * @since v1.0.0
		 * 
		 */
		if ( $query->max_num_pages > 1 ) {
			($brilliance_has_masonry) ? $brilliance_pagination_class_name = 'js-post-has-masonry' : $brilliance_pagination_class_name = '';
			echo sprintf( '<div class="c-pagination js-pagination__load-more js-pagination--load-more %s ">
			<button class="button--small js-pagination__load-more__btn">%s</button>
			</div>' , esc_attr( $brilliance_pagination_class_name ) , esc_html( 'Load More' , 'brilliance' ) );
		}
	}
endif;


if (! function_exists('brilliance_get_default_pagination')) :
	/**
	  * Show numeric pagination
	  *
	  * @since v1.0.0
	  *
	  */
	function brilliance_get_default_pagination( $brilliance_has_masonry = false ) {
		($brilliance_has_masonry) ? $brilliance_pagination_class_name = 'js-post-has-masonry' : $brilliance_pagination_class_name = '';
		if(paginate_links()) {
			echo wp_kses_post( '<div class="c-pagination '. esc_attr( $brilliance_pagination_class_name ) .'">' ) . wp_kses_post(
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


if( ! function_exists('brilliance_get_gallery') && function_exists( 'acf' ) ) :
	/**
	 * 
	 * Get Gaallery fronm ACF ( IF Acf & ACF Photo Gallery Exist )
	 * 
	 * @since v1.0.0
	 * 
	 */
	function brilliance_get_gallery( $brilliance_post_id ) { 
		$brilliance_images = acf_photo_gallery( 'image_gallery', $brilliance_post_id );
		if( count( $brilliance_images ) > 0 ) {
			echo wp_kses_post( '<div class="c-single__masonry js-single__masonry">' );
				foreach( $brilliance_images as $brilliance_image ) {
					$brilliance_id                  = $brilliance_image['id'];
					$brilliance_title               = $brilliance_image['title'];
					$brilliance_caption             = $brilliance_image['caption'];
					$brilliance_thumbnail_image_url = $brilliance_image['thumbnail_image_url'];
					$brilliance_url                 = $brilliance_image['url'];
					$brilliance_target              = $brilliance_image['target'];
					$brilliance_full_image_url      = $brilliance_image['full_image_url'];
					$brilliance_alt                 = get_field( 'photo_gallery_alt', $brilliance_post_id );

					echo sprintf( 
						'<div class="c-single__masonry-image__wrapper js-single__masonry-image__wrapper">
						<img class="c-single__masonry-img js-single__masonry-img" src="%s" data-src="" alt="%s" />
						</div>', 
						esc_url($brilliance_full_image_url),
						esc_attr($brilliance_alt) 
					);
				}
			echo wp_kses_post('</div>');
		}
	}
endif;


if(!function_exists('brilliance_get_carousel') && function_exists( 'acf' ) ):
	/**
	 * 
	 * Get Carousel Images ( If Acf & ACF Photo Gallery Exist )
	 * 
	 * @since v1.0.0
	 * 
	 */
	function brilliance_get_carousel( $brilliance_post_id ) { 
		$brilliance_carousel_images = acf_photo_gallery( 'image_carousel', $brilliance_post_id );
		if( count( $brilliance_carousel_images ) > 0 ) {
			echo wp_kses_post( '<div class="c-single__carousel js-single__carousel-slider">' );
				foreach( $brilliance_carousel_images as $brilliance_carousel_image ) {
					$brilliance_full_image_url      = $brilliance_carousel_image['full_image_url'];
					$brilliance_alt                 = get_field( 'photo_gallery_alt', $brilliance_post_id );
					echo sprintf( 
						'<div class="c-single__carousel-wrapper"><img class="c-single__carousel-img js-single__carousel-img" src="%s" data-src="" alt="%s" /></div>', 
						esc_url($brilliance_full_image_url),
						esc_attr($brilliance_alt) 
					);
				}
			echo wp_kses_post('</div>');
		}
	}
endif;


if(!function_exists('brilliance_get_acf_text') && function_exists( 'acf' )):
	/**
	 * 
	 * Return text of given field from ACF (IF ACF Existed)
	 * 
	 * @since v1.0.0
	 * 
	 */
	function brilliance_get_acf_text( $brilliance_acf_field_name ) { 
		echo esc_html( get_field($brilliance_acf_field_name) ); // Will Echo acf text field with Sanitization
	}
endif;


if (! function_exists('brilliance_get_tags')) :
	/**
	 * 
	 * Return post tags
	 * 
	 * @since v1.0.0
	 * 
	 */
	function brilliance_get_tags( $brilliance_className = 'c-single__tag' ) {
		$brilliance_post_tags = get_the_tags();
		if ($brilliance_post_tags) {
			$brilliance_tags = "";
			foreach($brilliance_post_tags as $post_tag) {
				$brilliance_tags .= '<a class="'.esc_attr( $brilliance_className ).' " href="'.  esc_url( get_tag_link( $post_tag->term_id ) ) .'" title="'.  esc_attr( $post_tag->name ) .'">'. esc_html( $post_tag->name ). '</a>';
			}
			echo wp_kses_post(sprintf('<div class="c-single__tags">%s %s</div>' , esc_html__( 'tags: ' , 'brilliance' ) , $brilliance_tags));
		}
	}
endif;


if ( ! function_exists( 'brilliance_share_links' ) ) {
	/**
	 * 
	 * Display Share icons 
	 * 
	 * @since v1.0.0
	 * 
	 */
	function brilliance_share_links() {
		$brilliance_linkedin_url = "https://www.linkedin.com/shareArticle?mini=true&url=" .  esc_url( get_permalink() ) . "&title=" . esc_attr( get_the_title() );
		$brilliance_twitter_url  = "https://twitter.com/intent/tweet?url=" . esc_url( get_permalink() ) . "&title=" . esc_attr( get_the_title() );
		$brilliance_facebook_url = "https://www.facebook.com/sharer.php?u=" . esc_url( get_permalink() );

		echo sprintf( '<h5 class="c-social-share__title u-margin-none">%s</h5>', esc_html__( 'Share:', 'brilliance' ) );
		echo sprintf( '<a  class="c-social-share__item" target="_blank" href="%s" aria-label="%s" ><span class="dashicons dashicons-facebook-alt c-social-share__item__icon"></span></a>', esc_url( $brilliance_facebook_url ), esc_attr__( "facebook" , "brilliance" ) );
		echo sprintf( '<a  class="c-social-share__item" target="_blank" href="%s" aria-label="%s" ><span class="dashicons dashicons-twitter c-social-share__item__icon"></span></a>', esc_url( $brilliance_twitter_url ), esc_attr__( "twitter" , "brilliance" ) );
		echo sprintf( '<a  class="c-social-share__item" target="_blank" href="%s" aria-label="%s" ><span class="dashicons dashicons-linkedin c-social-share__item__icon"></span></a>', esc_url( $brilliance_linkedin_url ), esc_attr__( "linkedin" , "brilliance" ) );
	}
}


if ( ! function_exists('brilliance_get_profile_image')) :
	/**
	 * 
	 * Get Profile Image
	 * 
	 * @since v1.0.0
	 * 
	 */
	function brilliance_get_profile_image() {
		$brilliance_image = wp_get_attachment_image_src(get_theme_mod( 'profile_image' )["id"] , 'thumbnail');
		if($brilliance_image) { 
			/** translator %s: image src, translator %s 2: image srcset */
			echo sprintf('<div class="c-profile__thumbnail"><img class="c-profile__img" alt="%s" src="%s" loading="lazy" /></div>', esc_attr__( 'Profile image' , 'brilliance' ) , esc_attr( esc_url($brilliance_image[0])) );
		}
	}
endif;


if ( ! function_exists( 'brilliance_get_taxonomy' ) ) :
    /**
	 *
	 * Get Custom Taxonomies
	 * 
	 * @since v1.0.0 
	 * 
	 */
    function brilliance_get_taxonomy( $brilliance_taxonomy_name = "" , $brilliance_class_name = "" , $brilliance_tag_name = "span" ) {
        $brilliance_custom_taxs = get_the_terms( get_the_ID(), $brilliance_taxonomy_name );

		$brilliance_output = "";

        if (is_array($brilliance_custom_taxs) && !empty($brilliance_custom_taxs)) {
            if( !empty( $brilliance_taxonomy_name ) ){
                foreach ( $brilliance_custom_taxs as $brilliance_custom_tax ) {
                    $brilliance_output .=  '<'. esc_html($brilliance_tag_name) .' class="'.esc_attr(  $brilliance_class_name  ).' " href="'.esc_url( get_tag_link( $brilliance_custom_tax->term_id ) ).'">' . esc_html( $brilliance_custom_tax->name ). '</'. esc_html($brilliance_tag_name) .'> ';
                }
				echo wp_kses_post($brilliance_output);
            }
        }
    }
endif;