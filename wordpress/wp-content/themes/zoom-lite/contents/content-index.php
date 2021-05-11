<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Zoom
 * @since 1.0
 */

get_header();
zoom_set_layout( 'blog', 'left' ); ?>

		<div id="primary" class="site-content<?php zoom_set_layout_class( 'blog' ); ?>">
        <?php do_action( 'zoom_home_before_content' ); ?>
			<div <?php if ( zoom_jetpack_active_module( 'infinite-scroll' ) ) { echo 'class="infinite-scroll-area" '; } ?>id="content" role="main">
			<?php if ( have_posts() ) : ?>
            
				<div id="zoom-masonry-mode"><!-- #zoom-masonry-mode START -->
                
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'contents/content', get_post_format() );
					?>

				<?php endwhile; ?>
                
				</div><!-- #zoom-masonry-mode END -->
                
				<?php zoom_theme_content_nav( 'nav-below' ); ?>

			<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>

				<?php get_template_part( 'contents/content-no-results', 'index' ); ?>

			<?php endif; ?>
            
			</div><!-- #content -->
        <?php do_action( 'zoom_home_after_content' ); ?>
		</div><!-- #primary .site-content -->

<?php zoom_set_layout( 'blog', 'right' ); ?>
<?php get_footer(); ?>