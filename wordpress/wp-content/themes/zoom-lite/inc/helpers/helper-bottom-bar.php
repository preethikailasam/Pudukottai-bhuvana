<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'zoom_theme_bottom_bar_function' ) ) :
/**
 * Generate the Bottom Bar
 * @since 1.0
 */

function zoom_theme_bottom_bar_function() {
	
	if ( ! get_theme_mod( 'bottom_bar_active', true ) ) return;
	
	// Allowed to add default link created from Theme Customize, see here https://wordpress.slack.com/archives/themereview/p1486571977009432
	?>
    <div id="bottom-bar">
    	<div class="bottom-bar-holder">
   		 	<div class="bottom-bar-logo"><?php echo wp_kses_post( zoom_theme_brand_logo( 'bottom' ) ); ?></div>
    	 <div class="bottom-bar-content"><span class="zoom-bottom-copyright"><?php if( esc_attr( get_theme_mod( 'bottom_bar_copyright' ) ) == '' ) { printf( esc_attr__( 'Zoom Lite Theme Powered by %1$s - Theme by %2$s', 'zoom-lite'), '<a href="'.esc_url( 'https://wordpress.org/' ).'" target="_blank">WordPress</a>', '<a href="'. esc_url( 'https://ghozylab.com/' ) .'" target="_blank">GhozyLab</a>' ); } else { echo wp_kses_post( get_theme_mod( 'bottom_bar_copyright' ) ); }; ?></span></div>
        </div>
    </div>
	<?php

}

add_action( 'zoom_theme_bottom_bar', 'zoom_theme_bottom_bar_function', 5 );

endif;