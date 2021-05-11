<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Zoom
 * @since 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content nosidebar">
		<div id="content" role="main">

			<article id="post-0" class="post error404 not-found">
				<header class="entry-header not-found-cnt">
					<span class="not-found-bg"></span><h1 class="entry-title not-found-title"><?php esc_html_e( 'Oops! That page cannot be found.', 'zoom-lite' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'zoom-lite' ); ?></p>

					<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

					<div class="widget">
						<h2 class="widgettitle"><?php esc_html_e( 'Most Used Categories', 'zoom-lite' ); ?></h2>
						<ul>
						<?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10 ) ); ?>
						</ul>
					</div>

					<?php
					/* translators: %1$s: smilie */
					$archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'zoom-lite' ), convert_smilies( ':)' ) ) . '</p>';
					the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );
					?>

					<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>

				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		</div><!-- #content -->
	</div><!-- #primary .site-content -->

<?php get_footer(); ?>