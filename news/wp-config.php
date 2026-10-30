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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', '0qpn1_as4d3mxb' );

/** Database username */
define( 'DB_USER', '0qpn1_n7e8t643' );

/** Database password */
define( 'DB_PASSWORD', '=Sy7MT[Vm53' );

/** Database hostname */
define( 'DB_HOST', 'mysql1027.onamae.ne.jp' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'p#jq|:(oZj{-j!wWrp8.A~+ab9hG+{uwl8o]*Z/`>7/38M[OZ]xf~Za3ml?8%!6s' );
define( 'SECURE_AUTH_KEY',   '-i[OX2?/l{tO{Fq) EL~tF84rI.z}Y<s~/h/vY[}T-?}#QG@dlmTt9K}P0TrXd[~' );
define( 'LOGGED_IN_KEY',     'IGx!8Ih|a3CPonMP*`#kQ;m9@_cbfHfZh.w:X&u|6M,63KcFg$X;|Ho!:D_`+ONC' );
define( 'NONCE_KEY',         'Cc0?xK~Q,~E~q>vsB[45a*3gQ*)@QW0pR=}5VDy+1XJwMkBdrYbBDzV>WTf0;zIs' );
define( 'AUTH_SALT',         '?qZyR/x2MW=ogB+M[|or+yzV9d@*OB<d]xV@ZDt7{|dD8EyM>-n`u6_yzSM@MGFC' );
define( 'SECURE_AUTH_SALT',  'LgcIsZsv%H~Ye,k6U,+ELD-E,lQ_-vW]TIaux(@R4=EPhSw~Lt28g[r8vx4`_hfM' );
define( 'LOGGED_IN_SALT',    'i`3{Ru[s#v:T5^wx.Q-<ZKOv4DaktOXnqJ Kh2{ C<ndrU9Q~4!Q5p-gC<8<.PRu' );
define( 'NONCE_SALT',        'J7YF>jBi;_jdMfd!tPrjN2Z!MvB3dM`p%wJWR}bc=`N@5j&z4[0$BpCUS{e~glp~' );
define( 'WP_CACHE_KEY_SALT', 'c[GAlgg;AUF9HJ&5w]$l?P*wF[~jizmN ;O4Lms0Ortol_tt4/icr_*|*$^Y]yA?' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === "https") {
    $_SERVER['HTTPS'] = 'on';
    define('FORCE_SSL_LOGIN', true);
    define('FORCE_SSL_ADMIN', true);
}


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'RS_DASHBOARD_PLUGIN_SID', 'wASrTTl_9nymWXJV2NKLpH-7MYuVJJuR1TKaSEIC_081EOU7DL8ALPAhpZZFrJqg1htU2vkN8maJFspE9o_b2qcIiTeDZmmUHgDjs9MtFP4.' );
define( 'RS_DASHBOARD_PLUGIN_DID', 'jGan9J5wolST6_lE8pOINxbXW1UfOrAujSYcc6Gt1JrTlrIwYHmnu7oANXZO6Oab7B4TFoAu7OD48hp8ybzdduy8guDgy0gt491TUiDbPYE.' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
