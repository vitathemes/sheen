<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sheen
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */

	if ( post_password_required() ) {
		return;
	}		
	$sheen_comment_counter = get_comments_number();
?>

<div id="comments" class="c-comments comments-area">

    <?php
			//Custom comment form 
			$sheen_comments_args = array(
			//Define Fields
			'fields' => array(
				//Author field
				'author' => '<h6 class="c-comment__author comment-form-author"><input type="text" id="author" name="author" aria-required="true" placeholder="'. esc_attr__( 'Name*', 'sheen' ) .'" /></h6>',
				//Email Field
				'email' => '<h6 class="c-comment__email comment-form-email"><input type="email" id="email" name="email" placeholder="'.esc_attr__( 'Email*' , 'sheen' ).'" /></h6>',
				//URL Field
				'url' => '<h6 class="c-comment__url comment-form-url"><input type="url" id="url" name="url" placeholder="'.esc_attr__( 'Website' , 'sheen' ).'" /></h6>',
				//Cookies
				'cookies' => '<div class="c-comment__cookie"><input type="checkbox" name="wp-comment-cookies-consent" required><span class="c-comments__cookie">' . __(' Save my name, email, and website in this browser for the next time I comment', 'sheen' ) .'</span></div>',
			),
			// Change the title of send button
			'label_submit' => __( 'Submit', 'sheen'),
			// Change the title replyof comment
			'title_reply' => sprintf( '<span class="h3">%s</span>' , esc_html__( 'Leave a Reply', 'sheen' ) ),
			// Change the title of the reply section
			'title_reply_to' =>  __( 'Reply' , 'sheen'),
			//Cancel Reply Text
			'cancel_reply_link' =>  __( 'Cancel Reply', 'sheen' ),
			// Redefine your own textarea (the comment body).
			'comment_field' => '<h6 class="c-comment__comment comment-form-comment "><textarea id="comment" name="comment" aria-required="true" placeholder="'.esc_attr__( 'Your Comment*', 'sheen' ).'" ></textarea></h6>',
			//Message Before Comment
			'comment_notes_before' =>'<h6 class="c-comments__desc">'. __( 'Required fields are marked *' , 'sheen') .'</h6>',
			// Remove "Text or HTML to be displayed after the set of comment fields".
			'comment_notes_after' => '',
			//Submit Button ID
			'id_submit' =>  __( 'comment-submit' , 'sheen'),
			// Submit class
			'class_submit' =>  __( 'c-comment_submit sheen-comment-submit' , 'sheen'),
		);
		comment_form( $sheen_comments_args );

		// You can start editing here -- including this comment!
		if ( have_comments() ) :
	?>

    <h3 class="c-comment__title comments-title h3--regular">
        <?php
			$sheen_comment_count = get_comments_number();
			if( true == get_theme_mod( 'single_comments_count', false ) ) { 
				echo sprintf( '<span>%s %s</span>' , esc_html( $sheen_comment_count ) , esc_html__( 'Comments', 'sheen' ) ); // Sanitized Values
			}
			else { 
				if ( '1' === $sheen_comment_count ) {
					printf(
						/* translators: 1: title. */
						esc_html__( 'Comments', 'sheen' ),
						'<span>' . wp_kses_post( get_the_title() ) . '</span>'
					);
				} else {
					printf( 
						/* translators: 1: comment count number, 2: title. */
						esc_html( _nx( 'Comments', 'Comments', $sheen_comment_count, 'comments title', 'sheen' ) ),
						number_format_i18n( $sheen_comment_count ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<span>' . wp_kses_post( get_the_title() ) . '</span>'
					);
				}
			}
			
		?>
    </h3><!-- .comments-title -->

    <?php the_comments_navigation(); ?>

    <ol class="comment-list">
        <?php
			wp_list_comments(
				array(
					'walker'      => new Sheen_walker_comment(),
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 144,
				)
			);
			?>
    </ol><!-- .comment-list -->

    <?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
    <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'sheen' ); ?></p>
    <?php
		endif;
	endif; // Check for have_comments().
	?>

</div><!-- #comments -->