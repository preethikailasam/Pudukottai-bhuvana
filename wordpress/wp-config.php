<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bhuvana_amman' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'R .S2[qQn,fd(a-*Uyh}8hijb]6o5xc8&+T=N;^<MGhlzzCk)d68[P+Y,aeGK1^2' );
define( 'SECURE_AUTH_KEY',  'g!IVC;zlY+Wt&9Bm[FmjJOR60e#l:eD}g,nKg%tfsSVhn_[qcHJ)%^=YBg+6mKT8' );
define( 'LOGGED_IN_KEY',    'jOufa.+$w+c}T]~IpA6$5mcKcD3fAg>_Q]b 4CVCqpF@4D]-(r9gpM1tFV,Tqh~a' );
define( 'NONCE_KEY',        ';@|5BY0_$%QO|G.}CO>q9Uv>J*t}L+.eVtjcxb0KC}zJ}h)t=zA:Q4CVQu11={?z' );
define( 'AUTH_SALT',        'MMRQN|m+TaNAw#^&qd(c5FAzLm8Uu6#4HP@,hI`(@#[^Pw.%@/Q-R./K7$P#ye{5' );
define( 'SECURE_AUTH_SALT', 'O/Iybpn:@U)?=N_{OWY}#2iWLR9%Fhakc{x0+LDvzr!kr*rcGS$!;O-HtgymZbYp' );
define( 'LOGGED_IN_SALT',   '[FWbrhNQWQe` ;{[.g$.xelNndmMxFu^VVK@ 2xWE|_Ix#G@t_ -N!:*d4^I7!bN' );
define( 'NONCE_SALT',       'nw7Ieq8-T,=[*O7IYjm7?0JZ|DU.$/=6}u,rU!HckhvF.a4,&?Ia0[%~!K=qS)n-' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
