<?php

/**
 * Theme functions and definitions
 *
 * @package boilerplate
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
add_action(
    'after_setup_theme',
    function () {
        /**
         * Enable plugins to manage the document title
         * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
         */
        add_theme_support('title-tag');

        /**
         * Enable post thumbnails
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        /**
         * Enable HTML5 markup support
         * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
         */
        add_theme_support(
            'html5',
            [
                'search-form',
                'gallery',
                'caption',
            ]
        );

        /**
         * Hide Advanced Custom Fields UI in live and staging environments for avoiding conflicts.
         * Read more at http://awesomeacf.com/how-to-avoid-conflicts-when-using-the-acf-local-json-feature/
         */
        add_filter(
            'acf/settings/show_admin',
            function () {
                $site_url = get_bloginfo('url');

                // An array of site urls where ACF is visible.
                $visible = array(
                    'http://boilerplate.local',
                    'https://boilerplate.local'
                );

                if (in_array($site_url, $visible, true)) {
                    return true;
                } else {
                    return false;
                }
            }
        );

        /**
         * Cleanup head
         */
        add_theme_support('automatic-feed-links');
        add_filter('feed_links_show_comments_feed', '__return_false');
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');

        /**
         * Register menus
         */
        register_nav_menus(
            [
              'primary' => __('Primary Menu', 'boilerplate')
            ]
        );
    }
);

/**
 * Auto update core (minor and major), plugins and themes.
 */
add_filter('auto_update_plugin', '__return_true');
add_filter('auto_update_theme', '__return_true');
add_filter('allow_minor_auto_core_updates', '__return_true');
add_filter('allow_major_auto_core_updates', '__return_true');

/**
 * Disable XML-RPC
 */
add_filter('xmlrpc_enabled', '__return_false');

add_filter(
    'wp_headers',
    function ($headers) {
        unset($headers['X-Pingback']);
        return $headers;
    }
);

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 * Function from Twenty Sixteen.
 */
add_action(
    'wp_head',
    function () {
        echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
    },
    0
);

/**
 * Enqueues scripts and styles.
 */
add_action(
    'wp_enqueue_scripts',
    function () {
        wp_enqueue_style('main-style', get_theme_file_uri('dist/main.css'), array(), filemtime(get_theme_file_path('dist/main.css')), 'screen');
        wp_enqueue_script('main-script', get_theme_file_uri('dist/main.js'), array(), filemtime(get_theme_file_path('dist/main.js')), true);
    }
);

/**
 * Remove text color option from tinymce
 *
 * @param  array $buttons All the buttons.
 * @return array          New array
 */
add_filter(
    'mce_buttons_2',
    function ($buttons) {
        $remove = array('forecolor');
        return array_diff($buttons, $remove);
    }
);

/**
 * ACF - Add options page
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page('Options');
}

/**
 *  Remove the H1 tag from the WordPress editor.
 *
 *  @param   array $settings  The array of editor settings.
 *  @return  array            The modified edit settings
 */
add_filter(
    'tiny_mce_before_init',
    function ($settings) {
        $settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;';
        return $settings;
    }
);

/**
 * Disable users api endpoint.
 * &endpoints.
 */

function Disable_Custom_Rest_endpoints($endpoints)
{
    $routes = array( '/wp/v2/users', '/wp/v2/users/(?P<id>[\d]+)' );

    foreach ($routes as $route) {
        if (empty($endpoints[ $route ])) {
            continue;
        }

        foreach ($endpoints[ $route ] as $i => $handlers) {
            if (
                is_array($handlers) && isset($handlers['methods']) &&
                'GET' === $handlers['methods']
            ) {
                unset($endpoints[ $route ][ $i ]);
            }
        }
    }

    return $endpoints;
}
add_filter('rest_endpoints', 'Disable_Custom_Rest_endpoints');
