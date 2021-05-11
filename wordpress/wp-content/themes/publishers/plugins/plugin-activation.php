<?php
require_once get_template_directory() . '/plugins/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'publishers_register_required_plugins' );
function publishers_register_required_plugins() {
$plugins = array(
array(
'name'      => 'Publishers',
'slug'      => 'publishers',
'required'  => false,
),
array(
'name'      => 'Classic Editor',
'slug'      => 'classic-editor',
'required'  => false,
),
array(
'name'      => 'Require Featured Image',
'slug'      => 'require-featured-image',
'required'  => false,
),
array(
'name'      => 'Easy SwipeBox',
'slug'      => 'easy-swipebox',
'required'  => false,
),
array(
'name'      => 'Theme My Login',
'slug'      => 'theme-my-login',
'source'    => 'https://downloads.wordpress.org/plugin/theme-my-login.6.4.17.zip',
'required'  => false,
),
);
$config = array(
'id'           => 'publishers',
'default_path' => '',
'menu'         => 'install-plugins',
'has_notices'  => true,
'dismissable'  => true,
'dismiss_msg'  => '',
'is_automatic' => false,
'message'      => '',
);
tgmpa( $plugins, $config );
}