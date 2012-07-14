<?php
/**
 * Simple Catch functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, simplecatch_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * @package WordPress
 * @subpackage Simple_Catch
 * @since Simple Catch 1.0
 */


/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 642;


/**
 * Tell WordPress to run simplecatch_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'simplecatch_setup' );

if ( ! function_exists( 'simplecatch_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @uses load_theme_textdomain() For localization support.
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menu() To add support for navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Simple Catch 1.0
 */
function simplecatch_setup() {

	// Loading textdomain simplecatch
	load_theme_textdomain( 'simplecatch' );
	
	// Load up our theme options page and related code.
	require( get_template_directory() . '/functions/panel/theme_options.php' );
	
	// Grab Simple Catch's Custom Tags widgets.
	require( get_template_directory() . '/functions/widgets.php' );
	
	// Load up our Simple Catch's Functions
	require( get_template_directory() . '/functions/simplecatch_functions.php' );
	
	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page.
	add_theme_support( 'post-thumbnails' );
	
	/* We'll be using post thumbnails for custom features images on posts under blog category.
	 * Larger images will be auto-cropped to fit.
	 */
	set_post_thumbnail_size( 210, 210 );
	
	// Add Simple Catch's custom image sizes
	add_image_size( 'featured', 210, 210, true); // uses on homepage featured image
	add_image_size( 'slider', 976, 313, true); // uses on Featured Slider on Homepage Header
	
	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' ); 
		
	// remove wordpress version from header for security concern
	remove_action( 'wp_head', 'wp_generator' );
 
	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'simplecatch' ) );
	
} // simplecatch_setup
endif;


/**
 * Register our sidebars and widgetized areas.
 *
 * uses register_sidebar
 * returns the id
 */
if ( function_exists( 'register_sidebar' ) ) {
	register_sidebar( array( 
		'name'          => __( 'sidebar', 'simplecatch' ),
		'id'            => 'sidebar',
		'description'   => __( 'sidebar', 'simplecatch' ),
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3><hr/>' 
	) ); 	
}
?>