<div class="entry-summary">
<?php if ( !is_search() ) : ?>
<div class="entry-image"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium_large' ); } else { echo '<img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/featured-image.png" class="attachment-full size-full wp-post-image" alt="' . esc_attr__( 'Featured Image', 'publishers' ) . '" width="1920" height="1080" />'; } ?></a></div>
<?php endif; ?>
<?php if ( !is_singular() ) { get_template_part( 'entry', 'meta' ); } ?>
<h2 class="entry-title">
<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
</h2>
<span itemprop="description"><?php the_excerpt(); ?></span>
<?php if ( is_search() ) { ?>
<div class="entry-links"><?php wp_link_pages(); ?></div>
<?php } ?>
</div>