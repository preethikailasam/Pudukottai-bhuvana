<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Check if Theme use Structured Data Markup for your posts 
 */
function zoom_is_struct_data( $pos, $other = null ) {
	
	if ( get_theme_mod( 'misc_struct_data', true ) ) {
		
		switch ( $pos ) {
			
			// Content
			case 'article-before':
			
			$markup = 'role="article" itemprop="hasPart" itemscope="" itemtype="http://schema.org/Article" ';
			
			break;
			
			// Headline
			case 'headline':
			
			$markup = 'itemprop="headline" ';
			
			break;
			
			// Permalink
			case 'permalink':

			$markup = 'itemprop="url" ';
			
			break;


			default:
			return;

		}

		$allowed_tags = zoom_wp_kses_allowed_html();
		echo wp_kses( $markup, $allowed_tags );
		
	} else {
		
		return;
		
	}
	
}


// Add span tags before > after the content
function zoom_content_struct_data( $content ) {
	
	if ( ! get_theme_mod( 'misc_struct_data', true ) ) return $content;
	
	$beforecontent = '<span itemprop="description">';
	$aftercontent = '<span>';
	
	$fullcontent = $beforecontent . $content . $aftercontent;
	
	return $fullcontent;

}

add_filter( 'the_content', 'zoom_content_struct_data' );


// Add Publisher information
function zoom_struct_publisher_information() {
	
	if ( ! get_theme_mod( 'misc_struct_data', true ) ) return;

	if ( has_post_thumbnail() ) {
		
		$thumb_url = zoom_get_post_thumbnail_url( get_post_thumbnail_id() );
		
	} else {
		
		$thumb_url = get_template_directory_uri().'/assets/images/misc/placeholder/thumb-medium.png';
		
	}
	
	ob_start(); ?>
    
    <div class="pub-information" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
        <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
            <img src="<?php echo esc_url( $thumb_url ); ?>" width="396" height="91"/>
            <meta itemprop="url" content="<?php echo esc_url( $thumb_url ); ?>">
            <meta itemprop="width" content="232">
            <meta itemprop="height" content="90">
        </div>
    	<meta itemprop="name" content="<?php echo get_the_author(); ?>">
    </div>
    <meta itemprop="dateModified" content="<?php echo the_time( get_option( 'date_format' ) ); ?>"/>
    
	<?php
    
    $pub_info = ob_get_clean();
    
	$allowed_tags = zoom_wp_kses_allowed_html();
	
    echo wp_kses( $pub_info, $allowed_tags );
	
}
add_action( 'zoom_after_article', 'zoom_struct_publisher_information' );


// Structure Data Author & Type
function zoom_struct_data_author_type_func() {
	
	if ( ! get_theme_mod( 'misc_struct_data', true ) ) return;
	
	echo '<meta itemscope="itemscope" itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage"/>';
	echo '<span class="meta-author" class="post-author vcard" itemprop="author" itemscope itemtype="https://schema.org/Person">
<span itemprop="name">'.esc_html( get_the_author() ).'</span></span>';
	
}
add_action( 'zoom_struct_data_author', 'zoom_struct_data_author_type_func' );


// Structure Data Image Object Markup
function zoom_struct_data_image_object_func( $pos = null ) {
	
	if ( ! get_theme_mod( 'misc_struct_data', true ) ) return;
	
	if ( $pos == 'before' ) {
		
		echo '<span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
		
	}
	elseif ( $pos == 'after' ) {
		
		echo '<meta itemprop="url" content="'.esc_url( zoom_get_post_thumbnail_url( get_post_thumbnail_id() ) ).'">';
		echo '<meta itemprop="width" content="569"/>';
		echo '<meta itemprop="height" content="309"/>';
		echo '</span>';
		
	} else {
	
		return;
			
	}
	
}
add_action( 'zoom_struct_data_image_object', 'zoom_struct_data_image_object_func', 10, 1 );