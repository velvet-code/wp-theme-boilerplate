<?php
/**
 * Theme functions and definitions
 *
 * @package boilerplate
 */

if ( ! function_exists( 'boilerplate_setup' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function boilerplate_setup() {
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/**
		 * Hide Advanced Custom Fields UI in live and staging environments
		 * for avoiding conflicts. Read more at http://awesomeacf.com/how-to-avoid-conflicts-when-using-the-acf-local-json-feature/
		 */
		function boilerplate_hide_acf_admin() {
			// Get the current site url.
			$site_url = get_bloginfo( 'url' );

			// An array of protected site urls.
			$protected_urls = array(
				'',
			);
			// Check if the current site url is in the protected urls array.
			if ( in_array( $site_url, $protected_urls, true ) ) {
				// Hide the acf menu item.
				return false;
			} else {
				// Show the acf menu item.
				return true;
			}
		}

		add_filter( 'acf/settings/show_admin', 'boilerplate_hide_acf_admin' );

	}
endif;

add_action( 'after_setup_theme', 'boilerplate_setup' );

/**
 * Auto update core (minor and major), plugins and themes.
 */

add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );
add_filter( 'allow_minor_auto_core_updates', '__return_true' );
add_filter( 'allow_major_auto_core_updates', '__return_true' );

/**
 * Disable XML-RPC
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

function disable_x_pingback( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}
add_filter( 'wp_headers', 'disable_x_pingback' );

/**
 * Cleanup head
 */
add_action( 'after_setup_theme', 'boilerplate_head_cleanup' );
if ( ! function_exists( 'boilerplate_head_cleanup' ) ) {
	function boilerplate_head_cleanup() {
		add_theme_support( 'automatic-feed-links' );
		add_filter( 'feed_links_show_comments_feed', '__return_false' );
		remove_action( 'wp_head', 'feed_links_extra', 3 ); // extra feeds such as category feeds
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}
}

/**
 * Register menus.
 *
 * @return void
 */
function register_menus() {
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'boilerplate' ),
		)
	);
}
add_action( 'after_setup_theme', 'register_menus' );

/**
 * Hide unnecessary admin menu items
 *
 * @return void
 */
function remove_menus() {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'remove_menus' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 * Function from Twenty Sixteen.
 */
function boilerplate_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'boilerplate_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 */
function boilerplate_scripts() {
	wp_enqueue_style( 'boilerplate-style', get_theme_file_uri( 'dist/bundle.css' ), array(), filemtime( get_theme_file_path( 'dist/bundle.css' ) ), 'screen' );
	wp_enqueue_script( 'boilerplate-script', get_theme_file_uri( 'dist/bundle.js' ), array(), filemtime( get_theme_file_path( 'dist/bundle.js' ) ), true );
}
add_action( 'wp_enqueue_scripts', 'boilerplate_scripts' );

/**
 * Remove text color option from tinymce
 *
 * @param  array $buttons All the buttons.
 * @return array          New array
 */
function remove_forecolor_from_tinymce( $buttons ) {
	$remove = array( 'forecolor' );
	return array_diff( $buttons, $remove );
}
add_filter( 'mce_buttons_2', 'remove_forecolor_from_tinymce' );

/**
 *  Remove the H1 tag from the WordPress editor.
 *
 *  @param   array $settings  The array of editor settings.
 *  @return  array            The modified edit settings
 */
function remove_h1_from_tinymce( $settings ) {
	$settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre;';
	return $settings;
}
add_filter( 'tiny_mce_before_init', 'remove_h1_from_tinymce' );
