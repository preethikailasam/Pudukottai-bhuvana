<?php
/*
 * Main loop related functions
 *
 * @package zoom
 * @subpackage Functions
*/

/**
 * Display navigation to next/previous pages when applicable
 *
 * @since 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

function zoom_theme_content_nav( $nav_id ) {
	
	global $wp_query;

	// Don't print markup below if there's only one page.
	if ( $wp_query->max_num_pages < 2 ) {
		//return;
	}

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo esc_attr( $nav_class ); ?>">
		<h1 class="assistive-text"><?php esc_html_e( 'Post navigation', 'zoom-lite' ); ?></h1>

	<?php if ( is_single() && get_theme_mod( 'post_next_prev', true ) == true ) : // navigation links for single posts ?>
    
		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<i class="fa fa-arrow-left" aria-hidden="true"></i>%title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title<i class="fa fa-arrow-right" aria-hidden="true"></i>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_front_page() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( '<i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;'.esc_html( get_theme_mod( 'misc_txt_op', 'Older posts' ) ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( esc_html( get_theme_mod( 'misc_txt_np', 'Newer posts' ) ).'&nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i>' ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo esc_attr( $nav_id ); ?> -->
	<?php
}


/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 1.0
 */
function zoom_theme_posted_on() {
	
	printf( ( ( zoom_get_array_opt( 'post_meta', 'meta_date' ) ) ? '<i class="fa fa-calendar zoom-meta-date-posted" aria-hidden="true"></i>'.esc_attr__( 'Posted on', 'zoom-lite' ).' <a href="%1$s" title="%2$s" rel="bookmark"><time '.( get_theme_mod( 'misc_struct_data', true ) ? 'itemprop="datePublished"' : '' ).' class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>' : '' ).''.( zoom_get_array_opt( 'post_meta', 'meta_author' ) ? ( is_home() || is_archive() || is_search() || is_front_page() ? '' : '<span class="byline"> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>' ) : '' ).'',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( zoom_theme_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( esc_attr__( 'View all posts by %s', 'zoom-lite' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
	
}


/**
 * Adds custom classes to the array of post classes.
 *
 * @since 1.7
 */
function zoom_theme_post_classes( $classes ) {
	
	if ( has_post_thumbnail() && get_theme_mod( 'featured_image_on_post_list', true ) == true ) {
		$classes[] = esc_attr( 'zoom-theme-has-thumb' );
	}
	
	elseif ( ! has_post_thumbnail() && get_theme_mod( 'featured_image_on_post_list', true ) == true && get_theme_mod( 'featured_image_placeholder', true ) == true ) {
		$classes[] = esc_attr( 'zoom-theme-has-thumb' );
	}

	if ( is_home() || is_archive() || is_search() || is_front_page() && 'page' != get_option( 'show_on_front' ) ) {
		if ( get_theme_mod( 'post_layout', 'grid' ) == 'grid' ) {
			$classes[] = esc_attr( 'post-mode-grid cols-'.esc_attr( get_theme_mod( 'post_layout_cols', 'two' ) ) );
		}
		
		if ( get_theme_mod( 'post_layout', 'grid' ) == 'list' ) {
			$classes[] = esc_attr( 'post-mode-list' );
		}
	}
	
	return $classes;
	
}
add_filter( 'post_class', 'zoom_theme_post_classes' );


/**
 * Output the date with correct formatting per language
 * (currently supports ID only, all other languages will display as EN)
 *
 * @since 1.5
 */
function zoom_theme_date() {
	
    if ( class_exists( 'Sitepress', false ) && 'id' == ICL_LANGUAGE_CODE ) {
        $date = get_the_time( 'j F Y' );
    } else {
        $date = get_the_time( 'F j, Y' );
    }
    return $date;
	
}


/**
 * Output the comment timestamp with correct formatting per language
 * (currently supports ID only, all other languages will display as EN)
 *
 * @since 1.5
 */
function zoom_theme_comment_time() {
	
	if ( class_exists( 'Sitepress', false ) && 'id' == ICL_LANGUAGE_CODE ) {
        $timestamp = comment_time( '\l\e j F Y \&#224; H\hi' );
    } else {
        $timestamp = comment_time( 'F j, Y \a\t g:ia' );
    }
    return $timestamp;
	
}


/**
 * Returns true if a blog has more than one category
 *
 * @since 1.0
 */
function zoom_theme_categorized_blog() {
	
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so zoom_theme_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so zoom_theme_categorized_blog should return false
		return false;
	}
	
}


/**
 * Flush out the transients used in zoom_theme_categorized_blog
 *
 * @since 1.0
 */
function zoom_theme_category_transient_flusher() {
	
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
	
}
add_action( 'edit_category', 'zoom_theme_category_transient_flusher' );
add_action( 'save_post', 'zoom_theme_category_transient_flusher' );


/**
 * Adds a read more link to all excerpts
 *
 * This function is attached to the 'excerpt_more' filter hook.
 *
 * @param	int $more
 *
 * @return	Custom excerpt ending
 *
	 * @since 1.0.0
 */
add_filter( 'excerpt_more', 'zoom_theme_excerpt' );

function zoom_theme_excerpt( $more ) {
	
	return '&hellip;';
	
}


/**
 * Adds a read more link to all excerpts
 *
 * This function is attached to the 'wp_trim_excerpt' filter hook.
 *
 * @param	string $text
 *
 * @return	Custom read more link
 *
 * @since 1.0.0
 */
add_filter( 'wp_trim_excerpt', 'zoom_theme_excerpt_more' );

if ( ! function_exists( 'zoom_theme_excerpt_more' ) ) :
	function zoom_theme_excerpt_more( $text ) {
		
		if ( is_singular() )
		
			return $text;
			
			if ( get_theme_mod( 'misc_struct_data', true ) ) {
				
				$text = '<span itemprop="description">'. $text .'</span>';
			
			}
			
			$string = get_theme_mod( 'excerpts_text', 'Read More' );
		return '<p class="excerpt">' . $text . '</p><p class="more-link-p btn-align-'.esc_attr( get_theme_mod( 'button_readmore_pos', 'left' ) ).'"><a class="'.esc_attr( get_theme_mod( 'button_readmore', 'blue' ) ).' zoom-btn" href="' . esc_url( get_permalink( get_the_ID() ) ) . '">' . esc_html( $string ) . '</a></p>';
		
	}
endif;


/**
 * Customize read more link for content
 *
 * This function is attached to the 'the_content_more_link' filter hook.
 *
 * @param	string $link
 * @param	string $text
 *
 * @return	Custom read more link
 *
 * @since 1.0.0
 */
add_filter( 'the_content_more_link', 'zoom_theme_content_more_link', 10, 2 );

if ( ! function_exists( 'zoom_theme_content_more_link' ) ) :
	function zoom_theme_content_more_link( $link, $text ) {
		
		return '<p class="more-link-p btn-align-'.esc_attr( get_theme_mod( 'button_readmore_pos', 'left' ) ).'"><a class="'.esc_attr( get_theme_mod( 'button_readmore', 'blue' ) ).' zoom-btn" href="' . esc_url( get_permalink( get_the_ID() ) ) . '">' . $text . '</a></p>';
		
	}
endif;
add_filter( 'excerpt_length', 'zoom_theme_excerpt_length', 999 );


/**
 * Custom excerpt length
 *
 * This function is attached to the 'excerpt_length' filter hook.
 *
 * @param	int $length
 *
 * @return	Custom excerpt length
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'zoom_theme_excerpt_length' ) ) :

	function zoom_theme_excerpt_length( $length ) {
		
		global $zoom_theme_custom_excerpt_length;
	
		if ( $zoom_theme_custom_excerpt_length )
			return $zoom_theme_custom_excerpt_length;
	
		return intval ( get_theme_mod( 'excerpt_length', 45 ) );
		
	}
	
endif; // zoom_theme_excerpt_length


/**
 * Related Post 
 *
 */
function zoom_get_related_posts() {
	
    wp_reset_postdata();
	
    global $post;

    // Define shared post arguments
    $args = array(
        'no_found_rows'           => true,
        'update_post_meta_cache'  => false,
        'update_post_term_cache'  => false,
        'ignore_sticky_posts'     => 1,
        'orderby'                 => 'rand',
        'post__not_in'            => array( $post->ID ),
        'posts_per_page'          => ( wp_is_mobile() ? 1 : 3 )
    );

    // Related by categories
    if ( get_theme_mod( 'post_related' ) == 'by_cat' ) {
		
        $cats = get_post_meta( $post->ID, 'related-cat', true );

        if ( ! $cats ) {
            $cats = wp_get_post_categories( $post->ID, array( 'fields'=>'ids' ) );
            $args['category__in'] = $cats;
        } else {
            $args['cat'] = $cats;
        }
		
    }

    // Related by tags
    if ( get_theme_mod( 'post_related' ) == 'by_tags' ) {
		
        $tags = get_post_meta($post->ID, 'related-tag', true);

        if ( ! $tags ) {
            $tags = wp_get_post_tags( $post->ID, array( 'fields'=>'ids') );
            $args['tag__in'] = $tags;
        } else {
            $args['tag_slug__in'] = explode( ',', $tags );
        }
        if ( ! $tags ) { $break = true; }
		
    }
	
    return ! isset( $break ) ? new WP_Query( $args ) : new WP_Query;
	
}


/**
 * Single Post Meta ( Category & Tags )
 *
 */
function zoom_single_post_meta() {
	
			// Thanks to @webreaper for Bug Fix: “This entry was posted in” for custom post types
			// Details see here https://wordpress.org/support/topic/bug-fix-this-entry-was-posted-in-for-custom-post-types/
			global $post, $post_id;

			$post_type = $post->post_type;
    		// get post type taxonomies
    		$taxonomies = get_object_taxonomies( $post_type );

			$taxonomy = $taxonomies[0];
			$category_list = get_the_term_list( $post_id, $taxonomy, '', esc_html__( ', ', 'zoom-lite' ) );
			
			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', ', ' );

			if ( ! zoom_theme_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					$meta_text = ( zoom_get_array_opt( 'post_meta', 'meta_tags' ) ? esc_html__( 'This entry was tagged %2$s.', 'zoom-lite' ) : '' );
				} else {
					$meta_text = '';
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					$meta_text = ( zoom_get_array_opt( 'post_meta', 'meta_cat' ) ? esc_html__( 'This entry was posted in %1$s.', 'zoom-lite' ) : '' ).( zoom_get_array_opt( 'post_meta', 'meta_tags' ) ? esc_html__( ' Tagged %2$s.', 'zoom-lite' ) : '' );
				} else {
					$meta_text = ( zoom_get_array_opt( 'post_meta', 'meta_cat' ) ? esc_html__( 'This entry was posted in %1$s.', 'zoom-lite' ) : '' );
				}

			} // end check for categories on this blog

			ob_start();
			printf(
				wp_kses_post( $meta_text ),
				wp_kses_post( $category_list ),
				wp_kses_post( $tag_list ),
				esc_url( get_permalink() ),
				the_title_attribute( 'echo=0' )
			);
			$meta_content = ob_get_clean();

			echo apply_filters( 'zoom_single_post_meta_content', $meta_content );
			
}


/**
 * Sticky Post Ribbon
 *
 */
function zoom_sticky_post_ribbon() {
	
	if ( is_sticky() && get_theme_mod( 'sticky_ribbon', true ) ) {
		
		$ribbon = '<div class="ribbon-par"><div class="ribbon-container"><span>'. esc_html( get_theme_mod( 'sticky_ribbon_txt', esc_html__( 'Featured', 'zoom-lite' ) ) ) .'</span></div><div class="mobile-ribbon-container"><i class="fa fa-thumb-tack ribbon-mobile"></i></div></div>';
		
		$allowed_tags = zoom_wp_kses_allowed_html();

		echo wp_kses( $ribbon, $allowed_tags );
		
	}
	
	return;
	
}