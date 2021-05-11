<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Zoom
 * @since 1.0
 */

get_header();
zoom_set_layout( 'blog', 'left' ); ?>

		<div id="primary" class="site-content<?php zoom_set_layout_class( 'blog' ); ?>">
        <?php if ( zoom_get_array_opt( 'post_meta', 'breadcrumb' ) ) zoom_breadcrumb(); ?>
			<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'contents/content', 'single' ); ?>

				<?php zoom_theme_content_nav( 'nav-below' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) {
						comments_template( '', true );
					} elseif ( ! comments_open() ) { ?>
						<p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'zoom-lite' ); ?></p>
					<?php } 
				?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary .site-content -->

<?php zoom_set_layout( 'blog', 'right' ); ?>
<?php get_footer(); ?>