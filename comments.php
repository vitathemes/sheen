<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package brilliance
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */

	if ( post_password_required() ) {
		return;
	}		
	$brilliance_comment_counter = get_comments_number();
?>

<div id="comments" class="c-comments comments-area">

    <?php
			//Custom comment form 
			$brilliance_comments_args = array(
			//Define Fields
			'fields' => array(
				//Author field
				'author' => '<h6 class="c-comment__author comment-form-author"><input type="text" id="author" name="author" aria-required="true" placeholder="'. esc_attr__( 'Name*', 'brilliance' ) .'" /></h6>',
				//Email Field
				'email' => '<h6 class="c-comment__email comment-form-email"><input type="email" id="email" name="email" placeholder="'.esc_attr__( 'Email*' , 'brilliance' ).'" /></h6>',
				//URL Field
				'url' => '<h6 class="c-comment__url comment-form-url"><input type="url" id="url" name="url" placeholder="'.esc_attr__( 'Website' , 'brilliance' ).'" /></h6>',
				//Cookies
				'cookies' => '<div class="c-comment__cookie"><input type="checkbox" name="wp-comment-cookies-consent" required><span class="c-comments__cookie">' . __(' Save my name, email, and website in this browser for the next time I comment', 'brilliance' ) .'</span></div>',
			),
			// Change the title of send button
			'label_submit' => __( 'Submit', 'brilliance'),
			// Change the title replyof comment
			'title_reply' => sprintf( '<span class="h3 h3--bold">%s</span>' , esc_html__( 'Leave a Reply', 'brilliance' ) ),
			// Change the title of the reply section
			'title_reply_to' =>  __( 'Reply' , 'brilliance'),
			//Cancel Reply Text
			'cancel_reply_link' =>  __( 'Cancel Reply', 'brilliance' ),
			// Redefine your own textarea (the comment body).
			'comment_field' => '<h6 class="c-comment__comment comment-form-comment "><textarea id="comment" name="comment" aria-required="true" placeholder="'.esc_attr__( 'Your Comment*', 'brilliance' ).'" ></textarea></h6>',
			//Message Before Comment
			'comment_notes_before' =>'<h6 class="c-comments__desc">'. __( 'Required fields are marked *' , 'brilliance') .'</h6>',
			// Remove "Text or HTML to be displayed after the set of comment fields".
			'comment_notes_after' => '',
			//Submit Button ID
			'id_submit' =>  __( 'comment-submit' , 'brilliance'),
			// Submit class
			'class_submit' =>  __( 'c-comment_submit brilliance-comment-submit' , 'brilliance'),
		);
		comment_form( $brilliance_comments_args );

		// You can start editing here -- including this comment!
		if ( have_comments() ) :
	?>

    <h3 class="c-comment__title comments-title">
        <?php
			$brilliance_comment_count = get_comments_number();
			if ( '1' === $brilliance_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'Comments', 'brilliance' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf( 
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( 'Comments', 'Comments', $brilliance_comment_count, 'comments title', 'brilliance' ) ),
					number_format_i18n( $brilliance_comment_count ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
		?>
    </h3><!-- .comments-title -->

    <?php the_comments_navigation(); ?>

    <ol class="comment-list">
        <?php
			wp_list_comments(
				array(
					'walker'      => new Brilliance_walker_comment(),
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
    <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'brilliance' ); ?></p>
    <?php
		endif;
	endif; // Check for have_comments().
	?>

</div><!-- #comments -->