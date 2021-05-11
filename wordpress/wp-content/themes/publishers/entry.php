<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php if ( is_singular() ) { ?>
<header>
<h1 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
<?php if ( $publishers_custom_subtitle = get_post_meta( get_the_ID(), 'publishers_custom', true ) ) { echo '<h2 class="entry-subtitle">' . esc_html( $publishers_custom_subtitle ) . '</h2>'; } ?>
<?php edit_post_link(); ?>
</header>
<?php } ?>
<?php if ( is_singular() ) { get_template_part( 'entry', 'meta' ); } ?>
<?php get_template_part( 'entry', ( is_front_page() || is_home() || is_front_page() && is_home() || is_archive() || is_search() ? 'summary' : 'content' ) ); ?>
<?php if ( is_singular() ) { get_template_part( 'entry-footer' ); } ?>
</article>