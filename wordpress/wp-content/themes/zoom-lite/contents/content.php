<?php
/**
 * @package Zoom
 * @since 1.0
 */
?>

<article <?php esc_attr( zoom_is_struct_data( 'article-before' ) ); ?>id="post-<?php the_ID(); ?>" <?php post_class( 'zoom-blog-default' ); ?>>
<?php zoom_sticky_post_ribbon(); ?>
<?php do_action( 'zoom_struct_data_author' ); ?>
	<div class="entry-header-cont">
        <header class="entry-header">
		<?php
		if ( zoom_is_wprepo_demo() ) { ?>
        	<a href="<?php the_permalink(); ?>"><img class="wp-post-image" src="<?php echo esc_url( zoom_generate_rand_img() ); ?>"></a>
			<?php	
		} else {
			do_action( 'zoom_struct_data_image_object', 'before' );
			?>
			<a rel="bookmark" href="<?php the_permalink(); ?>"><?php
			zoom_theme_thumbs( 'featured_image_on_post_list', true, get_theme_mod( 'post_blog_thumb_size' ), true );
			?></a>
			<?php
			do_action( 'zoom_struct_data_image_object', 'after' );
		} ?>
        </header><!-- .entry-header -->
    </div>
    <div class="zoom-blog-entry-content">
    <div class="entry-title-meta">
		<h2 <?php esc_attr( zoom_is_struct_data( 'headline' ) ); ?>class="entry-title"><a <?php esc_attr( zoom_is_struct_data( 'permalink' ) ); ?>href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'zoom-lite' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php zoom_theme_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</div>

	<div class="entry-content clearfixafter">
		<?php
		if ( 'full' == get_theme_mod( 'post_content', 'excerpts' ) ) :
			the_content( esc_html__( 'Continue reading', 'zoom-lite' ) .'<span class="meta-nav">&rarr;</span>' );
		else :
			the_excerpt();
		endif;
		
		?>
		<?php zoom_custom_wp_link_pages(); ?>
	</div><!-- .entry-content -->
    </div>

	<footer class="entry-meta">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				if ( zoom_get_array_opt( 'post_meta', 'meta_cat' ) ) :
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( esc_html__( ', ', 'zoom-lite' ) );
				if ( $categories_list && zoom_theme_categorized_blog() ) :
			?>
            <i class="fa fa-folder-open" aria-hidden="true"></i>
			<span class="cat-links">
				<?php printf( esc_html__( 'Posted in %1$s', 'zoom-lite' ), wp_kses_post( $categories_list ) ); ?>
			</span>
            
			<?php endif; endif; // End if categories ?>

			<?php
				if ( zoom_get_array_opt( 'post_meta', 'meta_tags' ) ) :
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', esc_html__( ', ', 'zoom-lite' ) );
				if ( $tags_list ) :
			?>
            <i style="margin-left:5px;" class="fa fa-tags" aria-hidden="true"></i>
			<span class="tag-links">
				<?php printf( esc_html__( 'Tagged %1$s', 'zoom-lite' ), wp_kses_post( $tags_list ) ); ?>
			</span>
			<?php endif; endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php 
			if ( zoom_get_array_opt( 'post_meta', 'meta_comments' ) ) :
			if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
            <i style="margin-left:5px;" class="fa fa-comment" aria-hidden="true"></i>
			<span class="comments-link"><?php comments_popup_link( esc_html__( 'Leave a comment', 'zoom-lite' ), esc_html__( '1 Comment', 'zoom-lite' ), esc_html__( '% Comments', 'zoom-lite' ) ); ?></span>
		<?php endif; endif; ?>

		<?php if ( ! is_admin_bar_showing() ): edit_post_link( esc_html__( 'Edit', 'zoom-lite' ), '<span class="edit-link">', '</span>' ); endif; ?>
	</footer><!-- #entry-meta -->
<?php do_action( 'zoom_after_article' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->