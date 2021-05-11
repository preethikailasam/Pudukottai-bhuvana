<?php
/**
 * The template for displaying Search Results pages.
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
					<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'zoom-lite' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</header>
				<div id="zoom-masonry-mode"><!-- #zoom-masonry-mode START -->
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'contents/content', get_post_format() ); ?>

				<?php endwhile; ?>
				</div><!-- #zoom-masonry-mode END -->
				<?php zoom_theme_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<?php get_template_part( 'contents/content-no-results', 'search' ); ?>

			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary .site-content -->
<?php zoom_set_layout( 'blog', 'right' ); ?>
<?php get_footer(); ?>