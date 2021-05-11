<?php
/**
 * The template used for displaying page content in page.php
 * @package Zoom
 * @since 1.0
 */
?>

<article <?php esc_attr( zoom_is_struct_data( 'article-before' ) ); ?>id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php do_action( 'zoom_struct_data_author' ); ?>
<?php if ( ! is_front_page() || 'page' != get_option( 'show_on_front' ) ) { ?>
	<header class="entry-header"><?php
		do_action( 'zoom_before_single_post' ); ?>
		<h1 <?php esc_attr( zoom_is_struct_data( 'headline' ) ); ?>title="<?php the_title_attribute(); ?>" class="entry-title"><?php the_title(); ?></h1>
        <?php
    	do_action( 'zoom_struct_data_image_object', 'before' );
		zoom_theme_thumbs( 'featured_image_on_single', false, esc_html( get_theme_mod( 'post_single_thumb_size' ) ) );
        do_action( 'zoom_struct_data_image_object', 'after' );
		?>
        <div class="entry-meta<?php if ( has_post_thumbnail() ) echo esc_attr(' add-margin-top'); ?>">
			<?php zoom_theme_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
<?php } ?>
	<div class="entry-content clearfixafter">
		<?php the_content(); ?>
		<?php zoom_custom_wp_link_pages(); ?>
	</div><!-- .entry-content -->
    <footer class="entry-meta page-bottom-meta">
    <?php if ( ! is_admin_bar_showing() ): edit_post_link( esc_html__( 'Edit', 'zoom-lite' ), '<span class="edit-link">', '</span>' ); endif; ?>
    </footer><!-- .entry-meta -->
    <?php do_action( 'zoom_after_single_post' ); ?>
    <?php do_action( 'zoom_after_article' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->
