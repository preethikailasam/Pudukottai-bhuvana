<?php
add_action( 'after_setup_theme', 'publishers_setup' );
function publishers_setup() {
load_theme_textdomain( 'publishers', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'custom-logo' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', array( 'search-form' ) );
global $content_width;
if ( !isset( $content_width ) ) { $content_width = 1920; }
register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'publishers' ), 'account-menu' => esc_html__( 'Account Menu', 'publishers' ), 'footer-menu' => esc_html__( 'Footer Menu', 'publishers' ), 'social-menu' => esc_html__( 'Social Menu', 'publishers' ) ) );
}
add_filter( 'wp_nav_menu_objects', 'publishers_profile_link', 20, 2 );
function publishers_profile_link( $items, $args ) {
if ( !is_user_logged_in() || $args->theme_location != 'account-menu' )
return $items;
$parent = false;
$current_user = wp_get_current_user();
$url = '/writer/' . strtolower( str_replace( ' ', '-', $current_user->user_login ) ) . '/';
$item_profile = [
'title' => __( 'Profile', 'publishers' ),
'url' => home_url( $url ),
'classes' => 'menu-item profile-link',
];
$item_profile = ( object )$item_profile;
$item_profile = new WP_Post( $item_profile );
$i = -1;
foreach ( $items as $item ) {
++$i;
if ( $item->menu_item_parent == 0 )
continue;
$item_profile->menu_item_parent = $item->menu_item_parent;
$new_items = [ $item_profile ];
array_splice( $items, $i, 0, $new_items );
break;
}
return $items;
}
add_action( 'wp_enqueue_scripts', 'publishers_enqueue' );
function publishers_enqueue() {
wp_enqueue_style( 'publishers-style', get_stylesheet_uri() );
wp_enqueue_script( 'jquery' );
wp_register_script( 'publishers-videos', get_template_directory_uri() . '/js/videos.js' );
wp_enqueue_script( 'publishers-videos' );
wp_add_inline_script( 'publishers-videos', 'jQuery(document).ready(function($){$("#wrapper").vids();});' );
}
add_action( 'wp_footer', 'publishers_footer' );
function publishers_footer() {
?>
<script>
jQuery(document).ready(function($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
$(":checkbox").on("click", function() {
$(this).parent().toggleClass("checked");
});
$(".before").on("focus", function() {
$(".last").focus();
});
$(".after").on("focus", function() {
$(".first").focus();
});
$(".menu-toggle").on("keypress click", function(e) {
if (e.which == 13 || e.type === "click") {
e.preventDefault();
$("#menu").toggleClass("toggled");
$(".looper").toggle();
}
});
$(document).keyup(function(e) {
if (e.keyCode == 27) {
if ($("#menu").hasClass("toggled")) {
$("#menu").toggleClass("toggled");
}
}
});
$("#account li.menu-item-has-children a").on("keypress click", function(e) {
if (e.which == 13 || e.type === "click") {
e.preventDefault();
$(this).next("#account .sub-menu").toggle();
}
});
$(document).click(function(e) {
var target = e.target;
if (!$(target).is("#account li.menu-item-has-children a") && !$(target).parents().is("#account li.menu-item-has-children a")) {
$("#account .sub-menu").hide();
}
});
$("img.no-logo").each(function() {
var alt = $(this).attr("alt");
$(this).replaceWith(alt);
});
$("img.svg").each(function() {
var $img = $(this);
var imgURL = $img.attr("src");
var attributes = $img.prop("attributes");
$.get(imgURL, function(data) {
var $svg = $(data).find("svg");
$svg = $svg.removeAttr("xmlns:a");
$.each(attributes, function() {
$svg.attr(this.name, this.value);
});
$img.replaceWith($svg);
}, "xml");
});
});
</script>
<?php
}
add_filter( 'document_title_separator', 'publishers_document_title_separator' );
function publishers_document_title_separator( $sep ) {
$sep = '|';
return $sep;
}
add_filter( 'the_title', 'publishers_title' );
function publishers_title( $title ) {
if ( $title == '' ) {
return '...';
} else {
return $title;
}
}
function publishers_schema_type() {
$schema = 'https://schema.org/';
if ( is_single() ) {
$type = "Article";
} elseif ( is_author() ) {
$type = 'ProfilePage';
} elseif ( is_search() ) {
$type = 'SearchResultsPage';
} else {
$type = 'WebPage';
}
echo 'itemscope itemtype="' . $schema . $type . '"';
}
add_filter( 'nav_menu_link_attributes', 'publishers_schema_url', 10 );
function publishers_schema_url( $atts ) {
$atts['itemprop'] = 'url';
return $atts;
}
if ( !function_exists( 'publishers_wp_body_open' ) ) {
function publishers_wp_body_open() {
do_action( 'wp_body_open' );
}
}
add_action( 'wp_body_open', 'publishers_skip_link', 5 );
function publishers_skip_link() {
echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'publishers' ) . '</a>';
}
add_filter( 'the_content_more_link', 'publishers_read_more_link' );
function publishers_read_more_link() {
if ( !is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">...</a>';
}
}
add_filter( 'excerpt_more', 'publishers_excerpt_read_more_link' );
function publishers_excerpt_read_more_link( $more ) {
if ( !is_admin() ) {
global $post;
return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">...</a>';
}
}
function publishers_reading_time() {
global $post;
$content = get_post_field( 'post_content', $post->ID );
$word_count = str_word_count( strip_tags( $content ) );
$readingtime = ceil( $word_count / 200 );
$totalreadingtime = $readingtime;
return $totalreadingtime;
}
add_action( 'wp_head', 'publishers_pingback_header' );
function publishers_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'publishers_enqueue_comment_reply_script' );
function publishers_enqueue_comment_reply_script() {
if ( get_option( 'thread_comments' ) ) {
wp_enqueue_script( 'comment-reply' );
}
}
function publishers_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php
}
add_filter( 'get_comments_number', 'publishers_comment_count', 0 );
function publishers_comment_count( $count ) {
if ( !is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}
require_once( get_stylesheet_directory() . '/plugins/plugin-activation.php' );