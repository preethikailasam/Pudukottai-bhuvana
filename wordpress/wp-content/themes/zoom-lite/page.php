<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Zoom
 * @since 1.0
 */

get_header();
zoom_set_layout( 'page', 'left' ); ?>

		<div id="primary" class="site-content<?php zoom_set_layout_class( 'page' ); ?>">
        <?php if ( zoom_get_array_opt( 'post_meta', 'breadcrumb' ) && ! is_front_page() || 'page' != get_option( 'show_on_front' ) ) zoom_breadcrumb(); ?>
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'contents/content', 'page' ); ?>

					<?php
                        // If comments are open or we have at least one comment, load up the comment template
						if ( ! is_front_page() || 'page' != get_option( 'show_on_front' ) ) {
							if ( comments_open() || '0' != get_comments_number() ) {
								comments_template( '', true );
							} elseif ( ! comments_open() ) { ?>
								<p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'zoom-lite' ); ?></p>
							<?php } 
						}
                    ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary .site-content -->

<?php zoom_set_layout( 'page', 'right' ); ?>
<?php get_footer(); ?>