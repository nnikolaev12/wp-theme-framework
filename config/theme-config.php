<?php

defined( 'ABSPATH' ) ?: exit;

/**
 * Main theme config file
 * 
 * This config file holds data about custom dependencies,
 * registered menus, sidebars, and custom posts which will
 * be loaded into the theme.
 * 
 * @author Nikolay Nikolaev <me@nikolaynikolaev.com>
 * 
 * @since 1.0.0
 * 
 * @return array Array that holds the theme config
 */

return array(
    /**
     * Theme support
     * 
     * Adds theme support for various WordPress features. Supported values
     * are already included and the required ones are enabled by default.
     * More info:  https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/
     * More info on WooCommerce support: https://woocommerce.com/document/woocommerce-theme-developer-handbook/#section-5
     */
    "support" => [
        [
            "name" => "title-tag",
        ],
        [
            "name" => "post-thumbnails",
            //"args" => array(),
        ],
        [
            "name" => "custom-logo",
            //"args" => array(),
        ],
        // [
        //     "name" => "html5",
        //     "args" => array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ),
        // ]
        // [
        //     "name" => "post-formats",
        //     "args" => array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'),
        // ]
        /**
         * WooCommerce Support
         */
        // [
        //     "name" => "woocommerce",
        //     "args" => array(),
        // ],
        // [
        //     "name" => "wc-product-gallery-zoom",
        // ],
        // [
        //     "name" => "wc-product-gallery-lightbox",
        // ],
        // [
        //     "name" => "wc-product-gallery-slider",
        // ]
    ],
    /**
     * Theme stylesheet files
     * 
     * Theme stylesheets listed here should be included as key => value
     * entries where IDs are keys and paths to the theme /assets/css
     * directory are values. Alternatively, stylesheets can be included as
     * URLs pointing to the resource. Can be also used for fonts.
     * (Example: style.css or https://example.com/style.css)
     */
    "styles" => [
        "nyx-core" => "core.min.css",
        "nyx-style" => "style.min.css",
    ],
    /**
     * Theme JS files
     * 
     * Theme js libraries or collections should be included as key => value
     * entries where IDs are keys and paths relative to the theme /assets/js directory
     * are values. Alternatively, values can be URLs pointing to the resource. By
     * default scripts are included in the footer. Append ":head" to the value to
     * include the script in the head. Provide  script handle "build-in" as key and the
     * build-in handle itself as value to include WP build-in libs.
     * 
     * Examples:
     * "unique-handle" => "script.js"
     * "unique-handle" => "https://example.com/script.js"
     * "unique-handle" => "script.js:head"
     * "unique-handle" => "https://example.com/script.js:head"
     * "build-in" => "jquery"
     */
    "scripts" => [
        "build-in" => "jquery",
        "nyx-js" => "main.min.js",
    ],
    /**
     * Theme registered navigations
     * 
     * Navigation theme locations registered with the theme.
     */
    "navs" => [
        'primary' => 'Primary Nav',
    ],
    /**
     * Theme registered sidebars
     * 
     * Sidebars registered with the theme.
     */
    "sidebars" => [
        /*
        'primary' => [
            'name' => 'Primary Sidebar',
        ],
        */
    ],
);