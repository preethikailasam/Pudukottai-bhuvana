<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Zoom
 * @since 1.0
 */

?>

<div id="secondary" class="widget-area rightside" role="complementary">
	<div class="secondary-content">
    	<div class="before-sidebar-holder">
			<?php do_action( 'before_sidebar' ); ?>
        </div>
		<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) { ?>
		<aside data-section="sidebar-widgets-sidebar-1" class="widget">
        <?php if ( is_customize_preview() ): ?>
			<p class="zoom-no-sidebar"><?php echo esc_html__( 'You currently have no widgets set in the right sidebar. To add widgets simply hover your mouse on this text and click EDIT button.', 'zoom-lite' ); ?></p>
        <?php endif; ?>
		</aside>
		<?php } // end sidebar widget area ?>
	</div><!-- #secondary-content -->
</div><!-- #secondary .widget-area -->