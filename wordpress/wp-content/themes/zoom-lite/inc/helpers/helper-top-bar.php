<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'zoom_theme_top_bar_function' ) ) :
/**
 * Generate the Bottom Bar
 * @since 1.0
 */

function zoom_theme_top_bar_function() {
	
	if ( get_theme_mod( 'top_bar_active', true ) == false || ( get_theme_mod( 'top_bar_mobile', true ) == true && wp_is_mobile() ) ) return;
	
	
	if ( ! is_customize_preview() ) {

		$email = ( get_theme_mod( 'top_bar_email' ) ? get_theme_mod( 'top_bar_email' ) : '' );
		$works = ( get_theme_mod( 'top_bar_w_hours' ) ? get_theme_mod( 'top_bar_w_hours' ) : '' );
		$fb = ( get_theme_mod( 'top_bar_sos_facebook' ) ? get_theme_mod( 'top_bar_sos_facebook' ) : '' );
		$twt = ( get_theme_mod( 'top_bar_sos_twitter' ) ? get_theme_mod( 'top_bar_sos_twitter' ) : '' );		
		$gplus = ( get_theme_mod( 'top_bar_sos_googleplus' ) ? get_theme_mod( 'top_bar_sos_googleplus' ) : '' );
		$ytb = ( get_theme_mod( 'top_bar_sos_youtube' ) ? get_theme_mod( 'top_bar_sos_youtube' ) : '' );
		$insta = ( get_theme_mod( 'top_bar_sos_instagram' ) ? get_theme_mod( 'top_bar_sos_instagram' ) : '' );
		$pint = ( get_theme_mod( 'top_bar_sos_pinterest' ) ? get_theme_mod( 'top_bar_sos_pinterest' ) : '' );
	
	}
	

	if ( is_customize_preview() || zoom_is_wprepo_demo() ) {	
	// We add content in TopBar top bar when empty ONLY IN THEME CUSTOMIZE MODE.
		
		$email = ( ! get_theme_mod( 'top_bar_email' ) && ! zoom_if_tolbar_no_items() ? 'mail@your-domain.com' : ( get_theme_mod( 'top_bar_email' ) && zoom_if_tolbar_no_items() ? get_theme_mod( 'top_bar_email' ) : '' ) );
		$works = ( ! get_theme_mod( 'top_bar_w_hours' ) && ! zoom_if_tolbar_no_items() ? 'Monday - Friday 8AM - 5PM' : ( get_theme_mod( 'top_bar_w_hours' ) && zoom_if_tolbar_no_items() ? get_theme_mod( 'top_bar_w_hours' ) : '' ) );
		$fb = ( ! get_theme_mod( 'top_bar_sos_facebook' ) && ! zoom_if_tolbar_no_items() ? 'https://www.facebook.com/' : ( get_theme_mod( 'top_bar_sos_facebook' ) && zoom_if_tolbar_no_items() ? get_theme_mod( 'top_bar_sos_facebook' ) : '' ) );
		$twt = ( ! get_theme_mod( 'top_bar_sos_twitter' ) && ! zoom_if_tolbar_no_items() ? 'https://www.twitter.com/' : ( get_theme_mod( 'top_bar_sos_twitter' ) && zoom_if_tolbar_no_items() ? get_theme_mod( 'top_bar_sos_twitter' ) : '' ) );		
		$gplus = ( ! get_theme_mod( 'top_bar_sos_googleplus' ) && ! zoom_if_tolbar_no_items() ? 'https://plus.google.com/' : ( get_theme_mod( 'top_bar_sos_googleplus' ) && zoom_if_tolbar_no_items() ? get_theme_mod( 'top_bar_sos_googleplus' ) : '' ) );
		$ytb = ( ! get_theme_mod( 'top_bar_sos_youtube' ) && ! zoom_if_tolbar_no_items() ? 'https://www.youtube.com/' : ( get_theme_mod( 'top_bar_sos_youtube' ) && zoom_if_tolbar_no_items() ? get_theme_mod( 'top_bar_sos_youtube' ) : '' ) );
		$insta = ( ! get_theme_mod( 'top_bar_sos_instagram' ) && ! zoom_if_tolbar_no_items() ? 'https://www.instagram.com/' : ( get_theme_mod( 'top_bar_sos_instagram' ) && zoom_if_tolbar_no_items() ? get_theme_mod( 'top_bar_sos_instagram' ) : '' ) );
		$pint = ( ! get_theme_mod( 'top_bar_sos_pinterest' ) && ! zoom_if_tolbar_no_items() ? 'https://www.pinterest.com/' : ( get_theme_mod( 'top_bar_sos_pinterest' ) && zoom_if_tolbar_no_items() ? get_theme_mod( 'top_bar_sos_pinterest' ) : '' ) );
		
	}
	
	ob_start();
	
	?>
    <div id="top-bar">
    	<div class="top-bar-holder">
            	<div class="top-bar-left">
                	<?php if ( $email ) echo '<span class="top-bar-email"><i class="fa fa-envelope-o" aria-hidden="true"></i>'.esc_html( $email ).'</span>'; ?>
                    <?php if ( $works ) echo '<span class="top-bar-works"><i class="fa fa-clock-o" aria-hidden="true"></i>'.esc_html( $works ).'</span>'; ?>
                </div>
            	<div class="top-bar-right">
                	<ul class="sosmed-wrap">
					<?php if ( $fb ) echo '<li><a href="'.esc_url( $fb ).'" target="_blank"></a></li>'."\n"; ?>
                    <?php if ( $twt ) echo '<li><a href="'.esc_url( $twt ).'" target="_blank"></a></li>'."\n"; ?>
                    <?php if ( $gplus ) echo '<li><a href="'.esc_url( $gplus ).'" target="_blank"></a></li>'."\n"; ?>
                    <?php if ( $ytb ) echo '<li><a href="'.esc_url( $ytb ).'" target="_blank"></a></li>'."\n"; ?>
                    <?php if ( $insta ) echo '<li><a href="'.esc_url( $insta ).'" target="_blank"></a></li>'."\n"; ?>
                    <?php if ( $pint ) echo '<li><a href="'.esc_url( $pint ).'" target="_blank"></a></li>'."\n"; ?>
                	</ul>
               </div>
        </div>
    </div>
	<?php
    
	$top_bar = ob_get_clean();
	
	echo wp_kses_post( $top_bar );

}

add_action( 'zoom_theme_top_bar', 'zoom_theme_top_bar_function', 5 );



function zoom_if_tolbar_no_items() {
	
	if ( get_theme_mod( 'top_bar_email' ) || get_theme_mod( 'top_bar_w_hours' ) || get_theme_mod( 'top_bar_sos_facebook' ) || get_theme_mod( 'top_bar_sos_twitter' ) || get_theme_mod( 'top_bar_sos_googleplus' ) || get_theme_mod( 'top_bar_sos_youtube' ) || get_theme_mod( 'top_bar_sos_instagram' ) || get_theme_mod( 'top_bar_sos_pinterest' ) ) {
		
		return true;
		
	}
	
}

endif;