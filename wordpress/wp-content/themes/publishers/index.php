<?php get_header(); ?>
<?php if ( !is_paged() ) {
$args = array(
'posts_per_page' => 3,
'meta_key' => 'meta-checkbox',
'meta_value' => 'yes'
);
$featured = new WP_Query( $args );
echo '<div id="home-featured" class="clear">';
if ( $featured -> have_posts() ) : while( $featured -> have_posts() ) : $featured -> the_post();
get_template_part( 'entry-featured' );
endwhile; endif;
echo '</div>';
} ?>
<?php if ( !is_paged() ) {
$posts = get_posts( array( 'posts_per_page' => 1, 'category__not_in' => array( 0 ) ) );
foreach ( $posts as $_post ) {
echo '<div id="home-hero"><div class="entry-image"><a href="' . esc_url( get_permalink( $_post->ID ) ) . '" title="' . esc_attr( $_post->post_title ) . '">';
if ( has_post_thumbnail( $_post->ID ) ) {
echo get_the_post_thumbnail( $_post->ID, 'full' );
} else {
echo '<img src="' . esc_url( get_stylesheet_directory_uri() ) . '/images/featured-image.png" class="attachment-full size-full wp-post-image" alt="' . esc_attr__( 'Featured Image', 'publishers' ) . '" width="1920" height="1080" />';
}
echo '<h2 class="entry-title">' . esc_attr( $_post->post_title ) . '</h2></a></div></div>';
}
} ?>
<main id="content" role="main">
<?php if ( is_home() ) {
if ( !is_paged() ) { echo '<div id="home-posts">'; }
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
query_posts( $query_string . '&cat=-0&paged=' . $paged );
} ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_template_part( 'entry' ); ?>
<?php comments_template(); ?>
<?php endwhile; endif; ?>
<?php get_template_part( 'nav', 'below' ); ?>
<?php if ( !is_paged() ) {
echo '<div id="home-content" class="clear">';
$recent = new WP_Query( array( 'pagename' => 'home' ) ); while( $recent->have_posts() ) : $recent->the_post();
the_content();
endwhile;
echo '</div>';
if ( is_home() ) { echo '</div>'; }
} ?>
</main>
<?php get_footer(); ?>