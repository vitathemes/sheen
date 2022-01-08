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
	function brilliance_posted_on(  $brilliance_has_modified_time = false ) {
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
			esc_html_x( '%s', 'post date', 'brilliance' ),
			'<a class="c-post__date" href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
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
			esc_html_x( 'by %s', 'post author', 'brilliance' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

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
	function brilliance_get_thumbnail( $brilliance_image_size = "large" ) {
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				$brilliance_image_size,
				array(
					'alt' => the_title_attribute(
						array(
							'echo' => false,
						)
					),
					'class' => "c-post__thumbnail__image"
				)
			);
		}
		else{
			echo '<img alt="'.esc_attr__( 'no thumbnail', 'brilliance' ).'" src="' . esc_url(get_template_directory_uri()). '/assets/images/no-thumbnail.png" />';
		}
}
endif;


if ( ! function_exists( 'brilliance_post_categories' ) ) : 
	/**
	 * Show post categories
	 */
	function brilliance_post_categories() {
		$brilliance_categories = get_the_category();

		echo "<div class='c-category'>";
			foreach ( $brilliance_categories as $brilliance_category ) {
				echo sprintf( '<a href="%s" class="c-category__item__link">%s</a>',
					esc_url( get_category_link( $brilliance_category->term_id ) ),
					esc_html( $brilliance_category->name ) );
			}
		echo "</div>";
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