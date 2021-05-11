<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Zoom
 * @since 1.0
 */
?>

<?php
	/*
	 * If the current post is protected by a password and
	 * the visitor has not yet entered the password we will
	 * return early without loading the comments.
	 */
	if ( post_password_required() )
		return;
?>

	<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title"><i class="fa fa-comments" aria-hidden="true"></i>
			<?php
				printf( esc_attr( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'zoom-lite' ),
					esc_attr( number_format_i18n( get_comments_number() ) ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<ol class="commentlist">
			<?php
				//Loop through and list the comments
				wp_list_comments( array( 'callback' => 'zoom_theme_comment' ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav role="navigation" id="comment-nav-below" class="site-navigation comment-navigation">
			<h1 class="assistive-text"><?php esc_html_e( 'Comment navigation', 'zoom-lite' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( '<i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;'.esc_html__( 'Older Comments', 'zoom-lite' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'zoom-lite' ).'&nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i>' ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'zoom-lite' ); ?></p>
	<?php endif; ?>

	<?php comment_form( array( 'title_reply' => '' ) ); ?>

</div><!-- #comments .comments-area -->
