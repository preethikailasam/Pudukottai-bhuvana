<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


// Author Box ::ref : /helpers/helper-post.php
add_action( 'zoom_after_single_post', 'zoom_about_the_author_box' );

// Disable All Comment Form in Posts / Pages
function zoom_comments_and_pings_closed( $open, $post_id ) {
	
	if ( get_theme_mod( 'post_disable_all_comment_form', false ) == true ) {
		
		return false;
			
	}
	
	return $open;

}
add_filter( 'pings_open', 'zoom_comments_and_pings_closed', 10, 2 );
add_filter( 'comments_open', 'zoom_comments_and_pings_closed', 10, 2 );
