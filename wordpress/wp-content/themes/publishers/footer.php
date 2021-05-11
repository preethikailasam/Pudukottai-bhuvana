<footer id="footer" role="contentinfo">
<nav id="footer-menu">
<?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'fallback_cb' => false ) ); ?>
</nav>
<?php
$menu_name = 'social-menu';
$locations = get_nav_menu_locations();
if ( isset( $locations[$menu_name] ) ) {
$menu = wp_get_nav_menu_object( $locations[$menu_name] );
$menu_items = $menu ? wp_get_nav_menu_items( $menu->term_id ) : array();
echo '<div id="social-menu">';
foreach ( ( array ) $menu_items as $key => $menu_item ) {
if ( !is_object( $menu_item ) ) {
continue;
}
$title = $menu_item->title;
$url = $menu_item->url;
if ( strpos( $url, 'facebook' ) !== false ) {
$social = esc_url( get_stylesheet_directory_uri() ) . '/images/fb.svg';
} elseif ( strpos( $url, 'twitter' ) !== false ) {
$social = esc_url( get_stylesheet_directory_uri() ) . '/images/tw.svg';
} elseif ( strpos( $url, 'instagram' ) !== false ) {
$social = esc_url( get_stylesheet_directory_uri() ) . '/images/ig.svg';
} elseif ( strpos( $url, 'pinterest' ) !== false ) {
$social = esc_url( get_stylesheet_directory_uri() ) . '/images/pn.svg';
} elseif ( strpos( $url, 'youtube' ) !== false ) {
$social = esc_url( get_stylesheet_directory_uri() ) . '/images/yt.svg';
} else {
$social = '';
}
echo '<a href="' . $url . '" title="' . $title . '" rel="me" target="_blank"><img src="' . $social . '" alt="' . $title . '" class="svg" /></a>';
}
echo '</div>';
}
?>
<div id="copyright">
&copy; <?php echo esc_html( date_i18n( __( 'Y', 'publishers' ) ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
</div>
</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>