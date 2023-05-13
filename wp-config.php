<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'imharris_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '`eL!tqj7k2w_i<y(h>(`s0fC]^q|ZDl0JlcI?f>YuB:F#vW/Hmh;</nRQ_oLPAD3' );
define( 'SECURE_AUTH_KEY',  '!~v{n_[%S`PgtEe> [z~)hL_8R7T*t9aruB JuYbEqlZW1e3]xXD_D;vAs,%;FK+' );
define( 'LOGGED_IN_KEY',    'kA7|HOreB^13XIOSrEDl(J@9K+5UG80%O^:PzQW:ygb^LZA We93o+[HPVhDQh$M' );
define( 'NONCE_KEY',        'PO~Z$HLS>CDf!cZ>Ys ,$zQ,M$aZr:^p{?:f2nxIbL7*tw6pprsIQFe IFtMsYDx' );
define( 'AUTH_SALT',        '5vb:8,=8C}rE>p bt}=Y?luC#Y3N@>C~4NOfHUD7x#6Gu26#wKOhNIG@f/()frnp' );
define( 'SECURE_AUTH_SALT', '_DrrpcId.|F4/r.0vVZ$V~v+rk1yNbd(u-ERdP~HoDnmm=S[S>HSczR#7Lt)mlG#' );
define( 'LOGGED_IN_SALT',   'w?C|k2z-yzHD,}cg.ZvNczG4|.VE+8^X`C;a^6Wnr&i{k^U*j>N8sgv i1><[Y7X' );
define( 'NONCE_SALT',       '^`@a+e-YGSv;/~%`$=|PX&({Lu2BHi8UF00AGhX/c~J2;C%RKuQ1m/pixKEN-_!Z' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
