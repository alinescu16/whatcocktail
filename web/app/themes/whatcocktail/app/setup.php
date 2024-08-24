<?php

/**
 * Theme setup.
 */

namespace App;

use App\Http\Middleware\HandleUserSession;

use App\Http\Controllers\Cocktail;

use function Roots\bundle;

/**
 * Register HandleUserSession Middleware.
 * 
 * @return \Illuminate\Http\Request
 */

add_action('init', function () {
    $middleware = new HandleUserSession();

    $middleware->handle(request(), function ($request) {
        return $request;
    });
});

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    bundle('app')->enqueue();
}, 100);


/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    bundle('editor')->enqueue();
}, 100);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'footer_navigation' => __('Footer Navigation Menu', 'sage'),
        'footer_legal' => __('Footer Legal Menu', 'sage')
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer Start', 'sage'),
        'id' => 'sidebar-footer-start',
    ] + $config);

    register_sidebar([
        'name' => __('Footer End', 'sage'),
        'id' => 'sidebar-footer-end',
    ] + $config);
});

/**
 * Register Customizer settings.
 * 
 * @return void 
 */
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    $wp_customize->add_section('site_logo', array(
        'title' => __('Site Logo', 'sage'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('site_logo', array(
        'default' => '',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'site_logo', array(
        'label' => __('Upload Logo', 'sage'),
        'section' => 'site_logo',
        'settings' => 'site_logo',
    )));
});


/**
 * Register API endpoints
 * 
 * @return void 
 */
add_action('rest_api_init', function () {

    register_rest_route('whatcocktail/v1', '/cocktail_by_name_or_ingredients', array(
        'methods' => 'POST',
        'callback' => function (\WP_REST_Request $request) {
            return Cocktail::get_cocktail_from_api($request);
        },
        'permission_callback' => function() { return true; }
    ));

    register_rest_route('whatcocktail/v1', '/save_cocktail', array(
        'methods' => 'POST',
        'callback' =>  function (\WP_REST_Request $request) {
            return Cocktail->save_cocktail($request);
        },
        'permission_callback' => function() { return true; }
    ));

    register_rest_route('whatcocktail/v1', '/create_cocktail', array(
        'methods' => 'POST',
        'callback' => function (\WP_REST_Request $request) {
            return Cocktail->create_cocktail($request);
        },
        'permission_callback' => function() { return true; }
    ));
});