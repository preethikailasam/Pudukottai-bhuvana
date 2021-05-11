<?php get_header(); ?>
<main id="content" role="main">
<header class="header layer-inner">
<?php $author = get_queried_object(); ?>
<?php the_post(); ?>
<div class="author-avatar"><a href="<?php echo get_avatar_url( $author->user_email, array( 'size'=>1200 ) ); ?>" class="swipebox" target="_self" onclick="javascript:void(0)"><?php echo get_avatar( $author->user_email, 600 ); ?></a></div>
<div class="author-social">
<?php if ( $author->facebook == '' ) {} else { echo '<a href="' . $author->facebook . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/fb.svg" alt="Facebook" class="svg" /></a>'; } ?>
<?php if ( $author->twitter == '' ) {} else { echo '<a href="' . $author->twitter . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/tw.svg" alt="Twitter" class="svg" /></a>'; } ?>
<?php if ( $author->instagram == '' ) {} else { echo '<a href="' . $author->instagram . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/ig.svg" alt="Instagram" class="svg" /></a>'; } ?>
<?php if ( $author->pinterest == '' ) {} else { echo '<a href="' . $author->pinterest . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/pn.svg" alt="Pinterest" class="svg" /></a>'; } ?>
<?php if ( $author->twitch == '' ) {} else { echo '<a href="' . $author->twitch . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/tt.svg" alt="Twitch" class="svg" /></a>'; } ?>
<?php if ( $author->youtube == '' ) {} else { echo '<a href="' . $author->youtube . '" target="_blank"><img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/yt.svg" alt="YouTube" class="svg" /></a>'; } ?>
</div>
<h1 class="entry-title author-name" itemprop="name"><?php echo $author->display_name ?></h1>
<p class="author-contact clear"><?php if ( $author->user_url == '' ) {} else { echo '<a href="' . $author->user_url . '" target="_blank">' . parse_url( $author->user_url, PHP_URL_HOST ) . '</a>'; } ?><?php if ( $author->publicemail == '' ) {} else { echo '<a href="mailto:' . $author->publicemail . '" target="_blank">' . $author->publicemail . '</a>'; } ?></p>
<p class="author-description clear" itemprop="description"><?php echo $author->user_description ?></p>
<?php rewind_posts(); ?>
</header>
<?php while ( have_posts() ) : the_post(); ?>
<?php get_template_part( 'entry' ); ?>
<?php endwhile; ?>
<?php get_template_part( 'nav', 'below' ); ?>
</main>
<?php get_footer(); ?>