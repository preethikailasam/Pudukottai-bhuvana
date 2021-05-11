<?php


// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

// Check is selected option value available in array
function zoom_get_array_opt( $setname, $meta ) {
	
	$all_meta = array(
				'meta_date',
				'meta_cat',
				'meta_tags',
				'meta_author',
				'meta_comments',
				'breadcrumb',
				'widget_left',
				'widget_center',
				'widget_right'
				);
	
	$metas = get_theme_mod( $setname, $all_meta );
	
	if ( in_array( $meta, $metas ) ) {
		
		return true;
		
		}
}

// Author Box Markup
function zoom_about_the_author_box() {
	
	if ( ! get_theme_mod( 'author_box', true ) ) return;
	
	$allowed_tags = zoom_wp_kses_allowed_html();
	
	ob_start(); ?>
	
	<div id="authorbox">
    <div class="authorimg">
    <?php if ( function_exists( 'get_avatar' ) ) {

		if ( get_theme_mod( 'misc_image_lazyload', true ) ) {
			
			echo get_avatar( get_the_author_meta( 'ID' ), 80, '', '', array( 'extra_attr' => 'data-lazy-src="'.esc_url( get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => 80 ) ) ).'"') ); 
			
		} else {
			
			echo get_avatar( get_the_author_meta( 'ID' ), 80 );
			
		}
		
		} ?>
    </div>
   	<div class="authortext">
       <h4><?php echo esc_attr_e( 'About', 'zoom-lite' ); ?> <?php the_author_posts_link(); ?></h4>
       <p><?php the_author_meta( 'description' ); ?></p>
    </div>
</div>
	
    <?php
	
	$author_box = ob_get_clean();
	
	if ( ! is_front_page() || 'page' != get_option( 'show_on_front' ) ) {
		
		echo wp_kses( $author_box, $allowed_tags );
		
	}
	
}