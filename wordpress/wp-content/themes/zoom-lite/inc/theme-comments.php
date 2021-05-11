<?php
/*
 * Theme Comments
 *
 * @package zoom
 * @subpackage Functions
*/

/**
 * Generate comment HTML
 * Based on the P2 theme by Automattic
 * http://wordpress.org/extend/themes/p2
 *
 * @since 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'zoom_theme_comment' ) ) :
function zoom_theme_comment( $comment, $args, $depth ) {
	
	global $post;
	
	if ( !is_single() && get_comment_type() != 'comment' )
		return;
	$can_edit_post  = current_user_can( 'edit_post', $comment->comment_post_ID );
	$content_class  = 'comment-content';
	if ( $can_edit_post )
		$content_class .= ' comment-edit';
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
    <?php if( $comment->user_id === $post->post_author ) { echo '<div class="ribbon-wrapper-blue"><div class="ribbon-blue">'.esc_html__( 'Author', 'zoom-lite' ).'</div></div>'; } ?>
		<article id="comment-<?php comment_ID(); ?>" class="comment-item <?php if( $comment->user_id === $post->post_author ) { echo ' is-author'; }?>">
<div class="comment-avatar"><?php echo get_avatar( $comment, 80 ); ?></div>
		<div class="comment-meta">
			<h4><?php echo get_comment_author_link(); ?></h4>
			<i class="fa fa-calendar zoom-comment-date" aria-hidden="true"></i><?php echo esc_html( zoom_theme_comment_time() ); ?><br /><hr />
		</div><!-- .comment-meta -->
		<div id="comment-content-<?php comment_ID(); ?>" class="<?php echo esc_attr( $content_class ); ?>">
			<?php if ( $comment->comment_approved == '0' ): ?>
					<p class="comment-awaiting"><?php esc_html_e( 'Your comment is awaiting moderation.', 'zoom-lite' ); ?></p>
			<?php endif; ?>
			<?php echo wp_kses_post( apply_filters( 'comment_text', $comment->comment_content ) ); ?>	
		</div>
        			<div class="perma-reply-edit">
				<a href="<?php echo esc_url( get_comment_link() ); ?>"><?php esc_html_e( 'Permalink', 'zoom-lite' ); ?></a>
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => '&nbsp;&sdot;&nbsp;' ) ) );
				if ( $can_edit_post ) { edit_comment_link( esc_html__( 'Edit', 'zoom-lite' ), '&nbsp;&sdot;&nbsp;' ); } ?>
			</div><!-- .perma-reply-edit -->
		</article>
<?php }
endif;


/**
 * Change HTML for comment form fields
 *
 * @since 1.0
 */
function zoom_theme_comment_form_args( $args ) {
	
	$args[ 'fields' ] = array(
		'author' => '<div class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'zoom-lite' ) . '</label><input type="text" class="field" name="author" id="author" aria-required="true" placeholder="' . esc_attr__( 'Name', 'zoom-lite' ) . '" required="required"/></div><!-- .comment-form-author -->',
		'email' => '<div class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'zoom-lite' ) . '</label><input type="text" class="field" name="email" id="email" aria-required="true" placeholder="' . esc_attr__( 'Email', 'zoom-lite' ) . '" required="required"/></div><!-- .comment-form-email -->',
		'url' => '<div class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'zoom-lite' ) . '</label><input type="text" class="field" name="url" id="url" placeholder="' . esc_attr__( 'Website', 'zoom-lite' ) . '" /></div><!-- .comment-form-url -->'
	);
	$args[ 'comment_field' ] = '<div class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'zoom-lite' ) . '</label><textarea id="comment" name="comment" rows="8" aria-required="true" placeholder="' . esc_attr__( 'Comment', 'zoom-lite' ) . '" required="required"></textarea></div><!-- .comment-form-comment -->';
	$args[ 'comment_notes_before' ] = '<p class="comment-notes"><i class="fa fa-bullhorn" aria-hidden="true"></i>' . esc_html( get_theme_mod( 'misc_txt_comment_note', esc_html__( 'Your email will not be published. Name and Email fields are required', 'zoom-lite' ) ) ) . '</p>';
	return $args;
	
}
add_filter( 'comment_form_defaults', 'zoom_theme_comment_form_args' );