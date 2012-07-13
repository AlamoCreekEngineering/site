<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

$url=parse_url(getenv('CLEARDB_DATABASE_URL'));

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', substr($url["path"],1));

/** MySQL database username */
define('DB_USER', $url["user"]);

/** MySQL database password */
define('DB_PASSWORD', $url["pass"]);

/** MySQL hostname */
define('DB_HOST', $url["host"]);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

define('AUTH_KEY',         '[rdWtIxKbAH},`Q7Xfk5oMr@{|~(gtS[C!~-1.;KSD]${}KR%BBB<#^jnTuG^{Ws');
define('SECURE_AUTH_KEY',  'RN<9d1^da$-YTnBC,Qj~f+x,Zj?={HqU3}G99sj&>p2z*]K>D-6{Q_v~-wfJq(>>');
define('LOGGED_IN_KEY',    'u!{:%VYY}t5].EqN{-,5aw,e,3S]|GlSDU7}-q_b8FVXSUGhmpz(8=Za-OIg:>(&');
define('NONCE_KEY',        '`!mCjo%gl!uEEl _8o7 o:(Dt}>gX<tLyE&j1CoWF0VSFEsuGb3Y/<B~({e#Bu~]');
define('AUTH_SALT',        '2Q^nF!_Jfg9O_$U(T,:ml]A01H1a;8Llw.Y-r699LW&T(ogW[ Jj)w!fGXEDfbI3');
define('SECURE_AUTH_SALT', 'tj&0;4+t_H,VXM-v<YSyavU)U:a;UDQ-2P1Tv]|XP3.;D2H_W)Lzoa)UM9{}7tPC');
define('LOGGED_IN_SALT',   'z6=tv,k_6|,R(_/-SEL&M;Ab~~+e.+p8]f<j1gEHN@K48Hj3I stA?Bb+|8d68IM');
define('NONCE_SALT',       '=#bcpP{~8H~mZH<vSz^klw=l29b1nt!zEx=MQS!Dsw@utd`p9+Rsa@K]^Ld:_g#b');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
