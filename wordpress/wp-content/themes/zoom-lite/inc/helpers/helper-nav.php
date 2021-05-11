<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'zoom_theme_before_header_function' ) ) :
/**
 * Generate the navigation based on settings
 * @since 1.0
 */
add_action( 'zoom_theme_before_header', 'zoom_theme_before_header_function', 5 );
function zoom_theme_before_header_function() {
	
	if ( 'nav-before-header' == get_theme_mod( 'menu_pos', 'nav-after-header' ) ) :
		zoom_theme_generate_navigation();
	endif;
	
}
endif;


if ( ! function_exists( 'zoom_theme_after_header_function' ) ) :
/**
 * Generate the navigation based on settings
 * @since 1.0
 */
add_action( 'zoom_theme_after_header', 'zoom_theme_after_header_function', 5 );
function zoom_theme_after_header_function() {
	
	if ( 'nav-after-header' == get_theme_mod( 'menu_pos', 'nav-after-header' ) ) :
		zoom_theme_generate_navigation();
	endif;
	
}
endif;


if ( ! function_exists( 'generate_navigation_position' ) ) :
/**
 *
 * Build the navigation
 * @since 1.0
 *
 */
function zoom_theme_generate_navigation() {
	
	$allowed_tags = zoom_wp_kses_allowed_html();
	$menuPos = zoom_menu_logo_position();

	ob_start(); ?>
     <!--Mobile nav START-->
    <div class="zoom-menu-<?php echo esc_attr( get_theme_mod( 'menu_align', 'left' ) ); ?> menu-box-mobile">
    	<a id="nav-toggle">
		<?php if ( get_theme_mod( 'main_logo_on_mobile_nav', false ) == true ) echo $menuPos['right']; ?>
		<span class="zoom_mobile_menu_icon">&nbsp;</span>
		<?php if ( get_theme_mod( 'main_logo_on_mobile_nav', false ) == true ) echo $menuPos['left']; ?>
		<div style="clear:both;"></div>
		</a>
    		<nav id="zoom-mobile-nav" role="navigation">
                	<?php
		$menu_mobile = wp_nav_menu( array( 'echo' => FALSE, 'container_class' => 'menu-mobile', 'menu_id' =>'zoom_mobile_nav', 'theme_location' => 'primary', 'link_before' => '<span>', 'link_after' => '</span>', 'fallback_cb' => '__return_false' ) );
		
		if ( ! empty ( $menu_mobile ) ){echo wp_kses_post( $menu_mobile );} else { echo wp_kses( zoom_no_primary_menu( 'menu-mobile', 'zoom_mobile_nav' ), $allowed_tags ); }
		
		?>
    		</nav>
    </div>
    <!--Mobile nav END-->
    <div class="zoom-menu-<?php echo esc_attr( get_theme_mod( 'menu_align', 'left' ) ); ?> menu-box main-menu-padding<?php echo zoom_menu_logo_position( true );?>">
    	<div class="nav-holder">
        	<?php echo $menuPos['left']; ?>
    		<div class="zoom-menu-nav">
    			<nav class="zoom-main-menu" id="zoomnav" role="navigation">
                	<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'zoom-lite' ); ?>"><?php esc_html_e( 'Skip to content', 'zoom-lite' ); ?>
                </a>
            </div><?php
		$menu = wp_nav_menu( array( 'echo' => FALSE, 'container_class' => 'menu', 'menu_id' =>'zoom_nav', 'theme_location' => 'primary', 'link_before' => '<span>', 'link_after' => '</span>', 'fallback_cb' => '__return_false' ) );
		
		if ( ! empty ( $menu ) ){ echo wp_kses_post( $menu ); } else { echo wp_kses( zoom_no_primary_menu( 'menu', 'zoom_nav' ), $allowed_tags ); }
		
		?>
    			</nav>
    		</div>
			<?php echo $menuPos['right']; ?>
       <?php do_action( 'zoom_after_main_menu' ); ?>
    	</div>
    </div>
    <?php
	$zoom_menu = ob_get_clean();
	echo wp_kses( $zoom_menu, $allowed_tags );
	
}
endif;

function zoom_no_primary_menu( $mnclass, $mnid ) {

	if ( is_customize_preview() ) {
		return '<h2 class="no-menu">'.__( 'Please set your theme Primary Menu first', 'zoom-lite' ).'<a class="goto goto-menu" href="#" data-target="menu_locations" data-type="section">&nbsp;'.__( 'from here', 'zoom-lite' ).'</a></h2>';
	}
	
	if ( zoom_is_wprepo_demo() ) {
		return '<div class="'.$mnclass.'"><ul id="'.$mnid.'" class="'.$mnclass.'"><li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1712"><a href="https://wp-themes.com/?page_id=2"><span>About</span></a></li><li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-1713"><a href="https://wp-themes.com/?page_id=46" aria-haspopup="true"><span>Parent Page</span></a><ul class="sub-menu" style="display: none; opacity: 1; margin-left: -50px; visibility: visible; overflow: visible;"><li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1714"><a href="https://wp-themes.com/?page_id=49"><span>Sub Page</span></a></li><li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1714"><a href="https://wp-themes.com/?page_id=49"><span>Sub Page</span></a></li><li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1714"><a href="https://wp-themes.com/?page_id=49"><span>Sub Page</span></a></li></ul></li><li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1712"><a href="https://wp-themes.com/?p=6"><span>Links</span></a></li><li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1712"><a href="https://wp-themes.com/?p=8"><span>HTML</span></a></li><li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1712"><a href="https://wp-themes.com/?p=36"><span>Elements</span></a></li></ul></div>';
	}
	
}

// Add Search Form to Main Menu
function zoom_search_form_on_main_menu() {
	
	if ( ! get_theme_mod( 'menu_search', false ) ) return;
	
	$allowed_tags = zoom_wp_kses_allowed_html();

	ob_start(); ?>
       <div class="menu-search-cont"> 
        <form method="get" class="searchform search-on-menu" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
            <label for="s" class="assistive-text"><?php esc_html_e( 'Search', 'zoom-lite' ); ?></label>
            <input type="text" class="field" name="s" id="s" placeholder="<?php echo esc_attr_e( 'Search', 'zoom-lite' ); ?>&hellip;" />
            <input type="submit" class="submit searchsubmit" name="submit" value="<?php echo esc_attr_e( 'Search', 'zoom-lite' ); ?>" />
        </form>
        </div>
    <?php   
	$zoom_menu = ob_get_clean();
	
	echo wp_kses( $zoom_menu, $allowed_tags );
	
}
add_action( 'zoom_after_main_menu', 'zoom_search_form_on_main_menu' );

// Add Home Button to Main Menu
function zoom_add_home_in_menu( $items, $args ) {
	
	if ( ! get_theme_mod( 'menu_home_btn' ) ) return $items;
	
    //If menu primary menu is set.
    if ( $args->theme_location == 'primary' ) {        

        $home = '<li class="menu-item custom-home-button"><a href="' . esc_url( home_url() ) . '"><span><i class="fa fa-home" aria-hidden="true"></i> '. esc_html( get_theme_mod( 'menu_home_btn_txt', 'Home' ) ).'</span></a></li>';
        $items = $home . $items;
		
    }

    return $items;
	
}
add_filter( 'wp_nav_menu_items', 'zoom_add_home_in_menu', 10, 2 );

function zoom_menu_logo_position( $getStatus = false ) {

	if ( $getStatus ) {
		
		if ( get_theme_mod( 'main_logo_on_nav', false ) == true ) return ' menu_is_show_logo';
		else
		return '';

	}

	$pos = array( 'left' => '', 'right' => '' );

	if ( get_theme_mod( 'main_logo_on_nav', false ) == true && get_theme_mod( 'menu_align', 'left' ) != 'center' ) {


		if ( get_theme_mod( 'menu_align', 'left' ) == 'left' ) {

			$pos['left'] = '<div class="menu-logo">'.wp_kses_post( zoom_theme_brand_logo( 'main', 'menu-logo-img' ) ).'</div>';
			$pos['right'] = '';

		}
		else {

			$pos['right'] = '<div class="menu-logo">'.wp_kses_post( zoom_theme_brand_logo( 'main', 'menu-logo-img' ) ).'</div>';
			$pos['left'] = '';

		}

		return $pos;

	}
	else {

		return $pos;
		
	}

}