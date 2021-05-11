<?php
/**
 * The template for displaying the footer.
 *
 * (Feel free to remove all links if you choose.)
 *
 * @package Zoom 
 * @since 1.0 
 */
 
?>
	</div><!-- #main -->
	<footer id="colophon" class="site-footer" role="contentinfo">
    	<?php if ( zoom_get_array_opt( 'footer_layout', 'widget_left' ) || zoom_get_array_opt( 'footer_layout', 'widget_center' ) || zoom_get_array_opt( 'footer_layout', 'widget_right' ) ): $allowed_tags = zoom_wp_kses_allowed_html(); ?>
    	<div id="footer-container">
            <div class="widget-area footer-left" role="complementary">
        	<?php if ( zoom_get_array_opt( 'footer_layout', 'widget_left' ) ): ?>
                <?php if ( ! dynamic_sidebar( 'footer-left' ) ) :
				echo wp_kses( zoom_default_footer_widget( 'left' ), $allowed_tags );
				endif; ?>
            <?php endif; ?>
            </div>
            <div class="widget-area footer-center" role="complementary">
            <?php if ( zoom_get_array_opt( 'footer_layout', 'widget_center' ) ): ?>
                <?php if ( ! dynamic_sidebar( 'footer-center' ) ) :
				echo wp_kses( zoom_default_footer_widget( 'center' ), $allowed_tags );
				endif; ?>
            <?php endif; ?>
            </div>
            <div class="widget-area footer-right" role="complementary">
            <?php if ( zoom_get_array_opt( 'footer_layout', 'widget_right' ) ): ?>
                <?php if ( ! dynamic_sidebar( 'footer-right' ) ) :
				echo wp_kses( zoom_default_footer_widget( 'right' ), $allowed_tags );
				endif; ?>
            <?php endif; ?>
            </div>
         </div><!-- #footer-container -->
         <?php endif; ?>
		 <?php do_action( 'zoom_theme_bottom_bar' ); ?>
	</footer><!-- #colophon .site-footer -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>