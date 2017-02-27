<?php
// ===================================================
// Load database info and local development parameters
// ===================================================
if ( file_exists( dirname( __FILE__ ) . '/local-config.php' ) ) {
	defined('WP_LOCAL_DEV') or define( 'WP_LOCAL_DEV', true );
	include( dirname( __FILE__ ) . '/local-config.php' );
} else {
	defined('WP_LOCAL_DEV') or define( 'WP_LOCAL_DEV', false );
	define( 'DB_NAME', '%%DB_NAME%%' );
	define( 'DB_USER', '%%DB_USER%%' );
	define( 'DB_PASSWORD', '%%DB_PASSWORD%%' );
	define( 'DB_HOST', '%%DB_HOST%%' ); // Probably 'localhost'
}

// =======================================
// Check that we actually have a DB config
// =======================================
if ( ! defined( 'DB_HOST' ) || strpos( DB_HOST, '%%' ) !== false ) {
	header('X-WP-Error: dbconf', true, 500);
	echo '<h1>Database configuration is incomplete.</h1>';
	echo "<p>If you're developing locally, ensure you have a local-config.php.
	If this is in production, deployment is broken.</p>";
	die();
}

// ========================
// Custom Content Directory
// ========================
defined('WP_CONTENT_DIR') or define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content' );
defined('WP_CONTENT_URL') or define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content' );

// =============================
// Configuration for the Content
// =============================
if ( file_exists( dirname( __FILE__ ) . '/wp-content/config.php' ) ) {
	include( dirname( __FILE__ ) . '/wp-content/config.php' );
}

// ==========================================
// URL hacks for proper wp-admin side loading
// ==========================================
if ( ! defined('WP_SITEURL') ) {
	define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp');

	if ( ! defined( 'WP_HOME' ) ) {
		define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);
	}
}

// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
if ( empty( $table_prefix ) )
	$table_prefix  = 'wp_';

// =====================================
// Errors
// Show/hide errors for local/production
// =====================================
if ( WP_LOCAL_DEV ) {
	// Open your eyes!
	defined( 'WP_DEBUG' ) or define( 'WP_DEBUG', true );
	define('WP_DEBUG_LOG', true);
	define('WP_DEBUG_DISPLAY', false);
	define('SCRIPT_DEBUG', true);
	define('SAVEQUERIES', true);
}
// Only override if not already set
elseif ( ! defined( 'WP_DEBUG_DISPLAY' ) ) {
	ini_set( 'display_errors', 0 );
	define( 'WP_DEBUG_DISPLAY', false );
}

// ===================
// Bootstrap WordPress
// ===================
if ( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );

if ( ! file_exists( ABSPATH . 'wp-settings.php' ) ) {
	header('X-WP-Error: wpmissing', true, 500);
	echo '<h1>WordPress is missing.</h1>';
	echo "<p>Did you forget to clone recursively? Try <code>git submodule update --init</code>.</p>";
	die();
}
require_once( ABSPATH . 'wp-settings.php' );


/** Override default file permissions for installing plugins */
if(is_admin()) {
	add_filter('filesystem_method', create_function('$a', 'return "direct";' ));
	define( 'FS_CHMOD_DIR', 0751 );
} else {
	// This will disallow WordPress core updates from /wp-admin
	define( 'DISALLOW_FILE_MODS', true );
}

// Disable WP-CRON default
define( 'DISABLE_WP_CRON', true );
// define( 'ALTERNATE_WP_CRON', true );
