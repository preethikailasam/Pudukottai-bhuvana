<?php

$related = zoom_get_related_posts();

if ( $related->have_posts() ): ?>

<div class="related-post-cont">
<h4 class="rp-heading"><i class="fa fa-thumbs-up rp-also-like" aria-hidden="true"></i><?php echo esc_html( get_theme_mod( 'misc_txt_rp', 'You may also like...' ) ); ?></h4>
<ul class="related-posts-list">
	<?php while ( $related->have_posts() ) : $related->the_post(); ?>
	<li class="related-list">
		<article <?php post_class(); ?>>
			<div class="rp-post-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php zoom_theme_thumbs(); ?>
				</a>
			</div><!--/.rp-post-thumbnail-->

			<div class="related-inner">
            <h4 class="rp-post-title post-title entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4><!--/.post-title-->
				<div class="rp-post-date entry-meta">
                <?php if ( zoom_get_array_opt( 'post_meta', 'meta_date' ) ) { echo '<i class="fa fa-clock-o" aria-hidden="true"></i>'. esc_html( zoom_theme_date() ); } ?>
				</div><!--/.post-meta-->
			</div><!--/.related-inner-->
		</article>
	</li><!--/.related-->
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
</ul>
</div><!--/.post-related-->
<?php endif; ?>

<?php wp_reset_query(); ?>