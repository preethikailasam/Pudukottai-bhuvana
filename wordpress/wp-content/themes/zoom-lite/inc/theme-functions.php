<?php
/**
 * Misc functions
 *
 * @package Zoom
 * @since 1.0
 */


/**
 * @return bool
 * Thanks to Hueman Theme https://wordpress.org/themes/hueman/
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

function zoom_is_wprepo_demo() {
	
	global $wp_customize;
	
    $is_dirty = false;
	
    if ( is_object( $wp_customize ) && method_exists( $wp_customize, 'unsanitized_post_values' ) ) {
        $real_cust            = $wp_customize -> unsanitized_post_values( array( 'exclude_changeset' => true ) );
        $_preview_index       = array_key_exists( 'customize_messenger_channel' , $_POST ) ? wp_unslash( $_POST['customize_messenger_channel'] ) : '';
        $_is_first_preview    = false !== strpos( $_preview_index ,'-0' );
        $_doing_ajax_partial  = array_key_exists( 'wp_customize_render_partials', $_POST );
        $is_dirty             = ( ! empty( $real_cust ) && ! $_is_first_preview ) || $_doing_ajax_partial;
    }
	
    return apply_filters( 'zoom_is_wprepo_demo', ! $is_dirty && zoom_get_raw_option( 'template', null, false ) != get_stylesheet() && ! is_child_theme() );
	
}


//@return an array of unfiltered options
function zoom_get_raw_option( $opt_name = null, $opt_group = null, $from_cache = true ) {
	
    $alloptions = wp_cache_get( 'alloptions', 'options' );
    $alloptions = maybe_unserialize( $alloptions );

    if ( ! is_null( $opt_group ) && array_key_exists( $opt_group, $alloptions ) ) {
      $alloptions = maybe_unserialize( $alloptions[ $opt_group ] );
    }

    if ( is_null( $opt_name ) ) {
        return $alloptions;
    } else {
        $opt_value = array_key_exists( $opt_name, $alloptions ) ? maybe_unserialize( $alloptions[ $opt_name ] ) : false;
		
        if ( ! $from_cache ) {
            global $wpdb;

            $row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $opt_name ) );
            if ( is_object( $row ) ) {
                $opt_value = $row->option_value;
            }
        }
        return $opt_value;
    }
}


/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0
 */
function zoom_theme_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = esc_attr( 'group-blog' );
	}
	if ( get_theme_mod( 'effect_screen_preload', false ) == true ) $classes[] = esc_attr( 'use-preload' );
	
	if ( 'page' == get_option('show_on_front') && is_front_page() ) {
	   $classes[] = esc_attr( 'zoom-is-on-static-page' );
	}
	
	return $classes;
	
}
add_filter( 'body_class', 'zoom_theme_body_classes' );


/**
 * Convert Hex color to RGB
 *
 * @since 1.0
 */
function zoom_hex2rgb( $hex ) {
	
   $hex = str_replace( "#", "", $hex );
   if ( preg_match( "/^([a-f0-9]{3}|[a-f0-9]{6})$/i", $hex ) ):
        if ( strlen( $hex ) == 3 ) {
           $r = hexdec( substr( $hex, 0, 1 ).substr( $hex ,0, 1 ) );
           $g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
           $b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
        } else {
           $r = hexdec( substr( $hex, 0, 2 ) );
           $g = hexdec( substr( $hex, 2, 2 ) );
           $b = hexdec( substr( $hex, 4, 2 ) );
        }
        $rgb = array( $r, $g, $b );
        return implode( ",", $rgb );
   else: return "";
   endif;
   
}


function zoom_hexadder( $hex ,$inc ) {
	
   $hex = str_replace( "#", "", $hex );
   
   if ( preg_match( "/^([a-f0-9]{3}|[a-f0-9]{6})$/i", $hex ) ):
        if ( strlen( $hex ) == 3 ) {
           $r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
           $g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
           $b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
        } else {
           $r = hexdec( substr( $hex, 0, 2 ) );
           $g = hexdec( substr( $hex, 2, 2 ) );
           $b = hexdec( substr( $hex, 4, 2 ) );
        }
		
		$rgb_array = array( $r, $g, $b );
		$newhex = "#";
		foreach ( $rgb_array as $el ) {
			$el += $inc;
			if ( $el <= 0 ) { $el = '00'; } 
			elseif ( $el >= 255 ) { $el = 'ff';} 
			else { $el = dechex( $el );}
			if( strlen( $el ) == 1 )  {$el = '0'.$el;}
			$newhex .= $el;
		}
		return esc_attr( $newhex );
   else: return "";
   endif;
   
}


/* Convert hexdec color string to rgb(a) string */

function zoom_hex2rgba( $color, $opacity = false ) {

	$default = 'rgb(0,0,0)';

	//Return default if no color provided
	if( empty( $color ) )
          return $default; 

	//Sanitize $color if "#" is provided 
        if ( $color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if ( strlen( $color ) == 6 ) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map( 'hexdec', $hex );

        //Check if opacity is set(rgba or rgb)
        if( $opacity ){
        	if( abs( $opacity ) > 1 )
        		$opacity = 1.0;
        	$output = esc_attr( 'rgba('.implode(",",$rgb).','.$opacity.')' );
        } else {
        	$output = esc_attr( 'rgb('.implode(",",$rgb).')' );
        }

        //Return rgb(a) color string
        return $output;
}


/**
 * Breadcrumbs
 *
 * @since 1.0
 */

/**
 * Generate breadcrumbs
 * @author CodexWorld
 * @authorURL www.codexworld.com
 */
function zoom_breadcrumb() {
	
	/* Arrow on RTL */
	if ( is_rtl() ) {
		$arrow = 'left';
	} else {
		$arrow = 'right';
	}
	
	echo '<div class="breadcrumb">';
    echo '<a href="'.esc_url( home_url() ).'" rel="nofollow"><i class="fa fa-home" aria-hidden="true"></i></a>';
    if ( is_category() || is_single() ) {
        echo '<i class="fa fa-chevron-'.esc_attr( $arrow ).'" aria-hidden="true"></i>';
        the_category( ' &bull; ' );
            if ( is_single() ) {
                echo '<i class="fa fa-chevron-'.esc_attr( $arrow ).'" aria-hidden="true"></i>';
                the_title();
            }
    } elseif ( is_page() ) {
        echo '<i class="fa fa-chevron-'.esc_attr( $arrow ).'" aria-hidden="true"></i>';
		the_title();
    }
	echo '</div>';
	
}


/**
 * Inline CSS Compressor
 *
 * @since 1.0
 */
function zoom_css_compress( $minify ) {
	
	/* remove comments */
	$minify = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify );
	
	/* remove tabs, spaces, newlines, etc. */
	$minify = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $minify );
	
	return $minify;
		
}


/**
 * Add Custom Class to @ next/prev button for posts, comments and images
 *
 * @since 1.0
 */
function zoom_posts_link_attributes() {
	
	return 'class="'.esc_attr( get_theme_mod( 'button_nav', 'green' ) ).' zoom-btn"';
	
}

function zoom_images_link_attributes( $link ) {
	
    $class = esc_attr( get_theme_mod( 'button_nav', 'green' ).' zoom-btn' );

    return str_replace( '<a ', "<a class='$class'", $link );
	
}

add_filter( 'previous_image_link', 'zoom_images_link_attributes' );
add_filter( 'next_image_link', 'zoom_images_link_attributes' );

add_filter( 'next_comments_link_attributes', 'zoom_posts_link_attributes' );
add_filter( 'previous_comments_link_attributes', 'zoom_posts_link_attributes' );

add_filter( 'next_posts_link_attributes', 'zoom_posts_link_attributes' );
add_filter( 'previous_posts_link_attributes', 'zoom_posts_link_attributes' );


/**
 * Default Footer Widget Content
 *
 * @since 1.0
 */

function zoom_default_footer_widget( $in ) {
	
	// Make sure this content only visible on Customize and when dynamic_sidebar() return FALSE
	if ( is_customize_preview() || zoom_is_wprepo_demo() )
	 printf( '<aside data-section="sidebar-widgets-footer-%4$s" class="widget widget_text" title=""><h1 class="widget-title">%1$s&nbsp;%2$s</h1><div class="textwidget"><p>%3$s<span class="goto gotolink link-to-control" data-type="section" data-target="zoom_site_footer_layout">%5$s</span></p></div></aside>',
	esc_html__( 'Footer', 'zoom-lite' ),
	esc_attr( $in ),
	esc_html__( 'This content only visible in theme customize page. Simply add new widgets to get rid this text. Just hover your mouse on this text and click EDIT button. You also can easily manage to show &#47; hide this footer widget', 'zoom-lite' ),
	esc_attr( $in ),
	( zoom_is_wprepo_demo() ? esc_html__( 'from theme customizer option', 'zoom-lite' ) : esc_html__( 'FROM HERE', 'zoom-lite' ) )
	
	 );
	 
	 return;
	
}


/**
 * Custom Allowed HTML Tags
 *
 * @since 1.3
 */
function zoom_wp_kses_allowed_html() {

	$allowed_tags = array(
		'a' => array(
			'id' => array(),
			'class' => array(),
			'href'  => array(),
			'rel'   => array(),
			'title' => array(),
			'target' => array(),
			'data-type' => array(),
			'data-target' => array(),
			'itemprop' => array(),
		),
		'abbr' => array(
			'title' => array(),
		),
		'aside' => array(
			'id' => array(),
			'data-section' => array(),
			'class' => array(),
			'title' => array(),
		),		
		'b' => array(),
		'blockquote' => array(
			'id' => array(),
			'cite'  => array(),
		),
		'cite' => array(
			'title' => array(),
		),
		'code' => array(),
		'del' => array(
			'datetime' => array(),
			'title' => array(),
		),
		'dd' => array(),
		'div' => array(
			'id' => array(),
			'class' => array(),
			'title' => array(),
			'style' => array(),
			'itemscope' => array(),
			'itemprop' => array(),
			'itemtype' => array(),
		),
		'dl' => array(),
		'dt' => array(),
		'em' => array(),
		'h1' => array(
			'id' => array(),
			'class'  => array(),
			'itemprop'  => array(),
		),
		'h2' => array(
			'id' => array(),
			'class'  => array(),
		),
		'h3' => array(
			'id' => array(),
			'class'  => array(),
		),
		'h4' => array(
			'id' => array(),
			'class'  => array(),
		),
		'h5' => array(
			'id' => array(),
			'class'  => array(),
		),
		'h6' => array(
			'id' => array(),
			'class'  => array(),
		),
		'img' => array(
			'alt'    => array(),
			'class'  => array(),
			'height' => array(),
			'src'    => array(),
			'width'  => array(),
			'data-lazy-src'  => array(),
		),
		'ol' => array(
			'class' => array(),
		),
		'p' => array(
			'id' => array(),
			'class' => array(),
		),
		'q' => array(
			'cite' => array(),
			'title' => array(),
		),
		'span' => array(
			'id' => array(),
			'class' => array(),
			'title' => array(),
			'style' => array(),
			'data-type' => array(),
			'data-target' => array(),
		),
		'strike' => array(),
		'strong' => array(),
		'ul' => array(
			'class' => array(),
			'id' => array(),
		),
		'li' => array(
			'class' => array(),
			'id' => array(),
		),
		'nav' => array(
			'id' => array(),
			'class' => array(),
			'role' => array(),
		),
		'form' => array(
			'method' => array(),
			'class' => array(),
			'action' => array(),
			'role' => array(),
		),		
		'input' => array(
			'type' => array(),
			'class' => array(),
			'id' => array(),
			'value' => array(),
			'name' => array(),
			'placeholder' => array(),
		),
		'label' => array(
			'for' => array(),
			'class' => array(),
		),
		'i' => array(
			'aria-hidden' => array(),
			'class' => array(),
		),
		'meta' => array(
			'itemscope' => array(),
			'itemprop' => array(),
			'itemtype' => array(),
			'itemType' => array(),
			'content' => array(),
		),
		'article' => array(
			'role' => array(),
			'itemprop' => array(),
			'itemscope' => array(),
			'itemtype' => array(),
			'id' => array(),
			'class' => array(),
			'style' => array(),
		),
		
	);
	
	return apply_filters( 'zoom_custom_allowed_tags', $allowed_tags );
	
}


/**
 * Add Custom HTML Tags into wp_kses_allowed_html
 *
 * @since 1.3
 */
function zoom_add_tags_wp_kses_allowed_html( $tags ) {

	$newtgs = array(
			'data-type' => true,
			'data-target' => true,
		);
	
	$tags['a'] = array_merge( $tags['a'], $newtgs );

	return $tags;
	
}
add_filter( 'wp_kses_allowed_html', 'zoom_add_tags_wp_kses_allowed_html' );


/**
 * Custom Password Protected Form
 *
 * @since 1.5
 */
function zoom_custom_password_form() {
	
    $o = '<div class="ppf-markup"><span class="ppf-lock-bg"></span><form class="protected-post-form zoom-custom-ppf" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post"><span class="ppf-text">' . esc_html__( "This content is password protected. To view it please enter your password below:", "zoom-lite" ) . '</span><input placeholder="' . esc_attr__( "Password here...", "zoom-lite" ) . '" name="post_password" type="password" class="ppf-password" size="30" /><input type="submit" name="Submit" class="ppf-button defbtn zoom-btn" value="' . esc_attr__( "Submit", "zoom-lite" ) . '" /></form></div>';
	
    return $o;
	
}
add_filter( 'the_password_form', 'zoom_custom_password_form' );


/**
 * Logos Handle
 *
 * @since 1.0
 */

function zoom_theme_brand_logo( $pos, $cls = null ) {
	
	switch ( $pos ) {
		
		// Default Site Logo using add_theme_support( 'custom-logo' )
		case 'main':

			if ( has_custom_logo() ) {
				
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
				$logo_img = $image[0];
				
			} else {
				
				if ( is_customize_preview() ) {
					
					$logo_img = get_template_directory_uri() . '/assets/images/misc/no-main-logo.png';;
					
				} else {
					
					return;
					
				}
				
			}

			break;
			
		// Custom Logo using WP_Customize_Image_Control	
		case 'bottom':
		case 'menu':
		
			$logo_img = get_theme_mod( ''.$pos.'_logo' );
			
			break;
			
		default:
	} 

	// Final Check
	if ( ! empty( $logo_img ) ) {
		
		$logo_img = $logo_img;
		
	}
	else {
		
		return;
		
	}
	
	$imge = '<img class="'.esc_attr( $cls ).'" src="'.esc_url( $logo_img ).'">';
	return $imge;
	
}


/**
 * Posts Featured Images Handle
 *
 * @since 1.0
 */

function zoom_theme_thumbs( $pos = '', $def = true, $sz = 'medium', $is_blog = false ) {
	
	$allowed_tags = zoom_wp_kses_allowed_html();
	$placeholder = get_template_directory_uri().'/assets/images/misc/placeholder/thumb-medium.png';
	$img_markup = '<img class="wp-post-image no-featured-image" src="'.esc_url( $placeholder ).'">';
	
	// If theme setting set to display the post featured image
	if ( ! empty( $pos ) ) {
		
		if ( get_theme_mod( $pos, $def ) && has_post_thumbnail() ) {
			// Show the image if the post has thumbnail
			the_post_thumbnail( 'zoom-img-'.$sz );
			
		} else {
			
			if ( get_theme_mod( $pos, $def ) && get_theme_mod( 'featured_image_placeholder', true ) && $is_blog ) {
			
				echo wp_kses( $img_markup, $allowed_tags );
				
			}
			
			return;
			
		}
		
	}
	
	else {
		
		if ( has_post_thumbnail() ) {
			// Show the image if the post has thumbnail
			the_post_thumbnail( 'zoom-img-'.$sz );
				
		} else {
			
			echo wp_kses( $img_markup, $allowed_tags );
					
		}
	
		
	}

}


/**
 * Centering the_post_thumbnail
 *
 * @since 1.0
 */

function zoom_centering_the_post_thumbnail( $attr ) {
	
	$attr['class'] .= esc_attr( ' aligncenter' );
	
	return $attr;
	
}
add_filter( 'wp_get_attachment_image_attributes','zoom_centering_the_post_thumbnail' );


/**
 * @param string|array $args Optional. Overwrite the defaults.
 * @return string Formatted output in HTML.
 */
function zoom_custom_wp_link_pages( $args = '' ) {
	
	$defaults = array(
		'before' => '<div class="zoom-page-pag '.esc_attr( get_theme_mod( 'button_nav', 'green' ) ).'"><span class="zoom-pag-note">'.esc_html( get_theme_mod( 'misc_txt_pg', 'Pages:' ) ).'</span>', 
		'after' => '</div>',
		'text_before' => '',
		'text_after' => '',
		'next_or_number' => 'number',
		'pagelink' => '%',
		'echo' => 1
	);

	$r = wp_parse_args( $args, $defaults );
	$r = apply_filters( 'wp_link_pages_args', $r );
	extract( $r, EXTR_SKIP );

	global $page, $numpages, $multipage, $more, $pagenow;

	$output = '';
	if ( $multipage ) {
		if ( 'number' == $next_or_number ) {
			$output .= $before;
			for ( $i = 1; $i < ( $numpages + 1 ); $i = $i + 1 ) {
				$j = str_replace( '%', $i, $pagelink );
				$output .= ' ';
				if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) )
					$output .= _wp_link_page( $i );
				else
					$output .= '<span class="current-post-page">';

				$output .= $text_before . $j . $text_after;
				if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) )
					$output .= '</a>';
				else
					$output .= '</span>';
			}
			$output .= $after;
		} else {
			if ( $more ) {
				$output .= $before;
				$i = $page - 1;
				if ( $i && $more ) {
					$output .= _wp_link_page( $i );
					$output .= $text_before . $text_after . '</a>';
				}
				$i = $page + 1;
				if ( $i <= $numpages && $more ) {
					$output .= _wp_link_page( $i );
					$output .= $text_before . $text_after . '</a>';
				}
				$output .= $after;
			}
		}
	}

	if ( $echo )
	
	$allowed_tags = zoom_wp_kses_allowed_html();

		echo wp_kses( $output, $allowed_tags );

	return $output;
	
}


/**
 * Check JetPack active module and specific options
 */

function zoom_jetpack_active_module( $module, $opt = null ) {

	if ( class_exists( 'Jetpack' ) && in_array( $module, Jetpack::get_active_modules() ) ) {
	    
	    $return = true;
	    
	    if ( $opt && ! get_option( $opt ) ) $return = false;
	    
	}
	
	else {
			
		$return = false;
			
	}
	
	return $return;

}


/**
 * Image lazyLoad
 */

function zoom_add_image_placeholders( $content ) {
	
	if ( ! get_theme_mod( 'misc_image_lazyload', true ) )
		return $content;
	
	// Don't lazyload for feeds, previews, mobile
	if( is_feed() || is_preview() )
		return $content;

	// Don't lazy-load if the content has already been run through previously
	if ( false !== strpos( $content, 'data-lazy-src' ) )
		return $content;
	// Don't lazy-load if the content is Gallery
	if ( false !== strpos( $content, 'data-full-url' ) )
		return $content;

	// This is a pretty simple regex, but it works
	$content = preg_replace_callback( '#<(img)([^>]+?)(>(.*?)</\\1>|[\/]?>)#si', 'zoom_process_image', $content );

	return $content;
	
}

function zoom_process_image( $matches ) {
	
	$plholder = esc_url( get_template_directory_uri() . '/assets/images/misc/placeholder/ajax-loader.gif' );
	
	// In case you want to change the placeholder image
	$placeholder_image = apply_filters( 'zoom_lazyload_images_placeholder_image', $plholder );

	$old_attributes_str = $matches[2];
	$old_attributes = wp_kses_hair( $old_attributes_str, wp_allowed_protocols() );

	if ( empty( $old_attributes['src'] ) ) {
			return $matches[0];
	}

	$image_src = $old_attributes['src']['value'];

	// Remove src and lazy-src since we manually add them
	$new_attributes = $old_attributes;
	unset( $new_attributes['src'], $new_attributes['data-lazy-src'] );

	$new_attributes_str = zoom_build_attributes_string( $new_attributes );

	return sprintf( '<img src="%1$s" data-lazy-src="%2$s" %3$s><noscript>%4$s</noscript>', esc_url( $placeholder_image ), esc_url( $image_src ), $new_attributes_str, $matches[0] );
	
}

function zoom_build_attributes_string( $attributes ) {
	
	$string = array();
	
	foreach ( $attributes as $name => $attribute ) {
		$value = $attribute['value'];
		
		if ( '' === $value ) {
				$string[] = sprintf( '%s', $name );
			} else {
				$string[] = sprintf( '%s="%s"', $name, esc_attr( $value ) );
			}
		}
		
	return implode( ' ', $string );
	
}

add_action( 'wp_head', 'zoom_lazyload_setup_filters', 9999 );

function zoom_lazyload_setup_filters() {
	
	add_filter( 'the_content', 'zoom_add_image_placeholders', 99 );
	add_filter( 'post_thumbnail_html', 'zoom_add_image_placeholders', 11 );
	add_filter( 'get_avatar', 'zoom_add_image_placeholders', 11 );
	
}


/**
 * Get Post Thumbnail URL
 */

function zoom_get_post_thumbnail_url( $id ) {
	
	if ( has_post_thumbnail() ) {
		
		$thumb_url_array = wp_get_attachment_image_src( $id, 'medium', true );
		$thumb_url = $thumb_url_array[0];
		
		return $thumb_url;
		
	} else {
		
		return esc_url( get_template_directory_uri().'/assets/images/misc/placeholder/thumb-medium.png' );
		
	}

}


/**
 * Get Theme Update Info
 */
 
function zoom_get_update_info() {

	$need_update = false;
	$updates = array();
	
	$updates_transient = get_site_transient( 'update_themes' );
	
	if ( isset( $updates_transient->response ) ) {
		
		$updates = $updates_transient->response;
		
		if ( array_key_exists( get_template(), $updates ) ) $need_update = true;
		
	}
	
	return $need_update;
	
}

function zoom_check_if_plugin_installed( $slug, $filenm ) {

	$path = WPMU_PLUGIN_DIR . '/' . $slug . '/' . $filenm . '.php';
	if ( ! file_exists( $path ) ) {
		$path = WP_PLUGIN_DIR . '/' . $slug . '/' . $filenm . '.php';
		if ( ! file_exists( $path ) ) {
			$path = false;
		}
	}

	if ( file_exists( $path ) ) return true;

	return false;
	
}


/**
 * Return Boolean
 */
 
function zoom_slider_display_in() {
	
	if ( get_theme_mod( 'header_slider_hp_only', false ) == true ) {
		
		if ( is_front_page() || is_home() ) {
			
			return true;
			
		} else {
			
			return false;
						
		}
		
	} else {
		
		return true;
				
	}
	
}