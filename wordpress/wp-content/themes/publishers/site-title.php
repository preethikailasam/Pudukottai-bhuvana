<div id="site-title" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
<?php
if ( is_front_page() || is_home() || is_front_page() && is_home() ) {
echo '<h1>';
}
if ( has_custom_logo() ) {
$custom_logo_id = get_theme_mod( 'custom_logo' );
$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
$nologo = '';
} elseif ( has_site_icon() ) {
$logo = get_site_icon_url();
$nologo = '';
} else {
$logo = '';
$nologo = 'no-logo';
}
echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home" itemprop="url"><span class="screen-reader-text" itemprop="name">' . esc_html( get_bloginfo( 'name' ) ) . '</span><span id="logo-container" itemprop="logo" itemscope itemtype="https://schema.org/ImageObject"><img src="';
if ( has_custom_logo() ) {
echo esc_url( $logo[0] );
} else {
echo esc_url( $logo );
}
echo '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" id="logo" class="' . esc_attr( $nologo ) . '" itemprop="url" /></span></a>';
if ( is_front_page() || is_home() || is_front_page() && is_home() ) {
echo '</h1>';
}
?>
</div>