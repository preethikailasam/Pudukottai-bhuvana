<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Zoom
 * @since 1.0
 */

get_header();
zoom_set_layout( 'blog', 'left' ); ?>

		<section id="primary" class="site-content<?php zoom_set_layout_class( 'blog' ); ?>">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<h1 class="page-title">
						<?php the_archive_title(); ?>
					</h1>
				</header>

				<?php rewind_posts(); ?>
				<div id="zoom-masonry-mode"><!-- #zoom-masonry-mode START -->
				<?php /* Start the Loop */ ?>
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

			<?php else : ?>

				<?php get_template_part( 'contents/content-no-results', 'archive' ); ?>

			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary .site-content -->
        
<?php zoom_set_layout( 'blog', 'right' ); ?>
<?php get_footer(); ?>