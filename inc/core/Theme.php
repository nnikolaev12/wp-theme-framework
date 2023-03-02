<?php

namespace NyxitSoft;

defined( 'ABSPATH' ) ?: exit;

use NyxitSoft\CustomPosts;

/**
 * Core theme class.
 */
class Theme
{
    public $theme_assets_uri;
    protected $config;

    /**
     * Bootstrap the theme.
     * 
     * Initialize all core classes and configs
     * used by the theme.
     */
    public function __construct()
    {
        $this->theme_assets_uri = get_stylesheet_directory_uri() . "/assets/";
        $this->config = require( get_template_directory() . "/config/theme-config.php" );

        add_action( 'after_setup_theme', [ $this, "support" ] );
        add_action( 'wp_enqueue_scripts', [ $this, "include_stylesheets" ] );
        add_action( 'wp_enqueue_scripts', [ $this, "include_scripts" ] );
        add_action( 'init', [ $this, "register_navs" ] );
        add_action( 'widgets_init', [ $this, "register_sidebars" ] );
        $this->clean();
        $this->load_custom_posts();
    }

    /**
     * Load WP related theme support. More info at:
     * https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/
     */
    public function support()
    {
        foreach ( $this->config["support"] as $feature ) {
            if ( isset( $feature['args']) ) {
                add_theme_support( $feature['name'], $feature['args'] );
            } else {
                add_theme_support( $feature['name'] );
            }   
        }
    }

    /**
     * Register navigations from the config.php file
     */
    public function register_navs()
    {
        register_nav_menus( $this->config['navs'] );
    }

    /**
     * Register sidebars from the config.php file
     */
    public function register_sidebars()
    {
        foreach ( $this->config["sidebars"] as $id => $args ) {
            $args['id'] = $id;
            register_sidebar( $args );
        }
    }

    /**
     * Enqueue styles from the config.php file
     */
    public function include_stylesheets()
    {
        foreach ($this->config['styles'] as $handle => $src)
        {    
            $src = substr($src, 0, 4) == "http" ? $src : $this->theme_assets_uri . 'css/' . $src;
            wp_enqueue_style($handle, $src, array(), null, 'all');
        }
    }

    /**
     * Enqueue scripts from the config.php file
     */
    public function include_scripts()
    {
        foreach ($this->config['scripts'] as $handle => $src)
        {
            if ( $handle === "build-in" ) {
                wp_enqueue_script($src);
                continue;
            }

            if ( ! empty($src) ) {
                $src = substr($src, 0, 4) == "http" ? $src : $this->theme_assets_uri . 'js/' . $src;
                $in_footer = true;
                
                if (substr($handle, -5) === ":head") {
                     $in_footer = false;
                }

                wp_enqueue_script($handle, $src, array(), null, $in_footer);
            }
        }
    }

    /**
     * Load the custom posts
     */
    public function load_custom_posts()
    {
        $custom_posts = [];

        foreach ($this->config['custom_posts'] as $id => $options) {
            $custom_posts[] = new CustomPosts( $id, $options );
        }
    }

    /**
     * Clean the default WP clutter
     */
    public function clean()
    {
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'feed_links_extra', 3 );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
        remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        add_filter( 'tiny_mce_plugins', function($plugins) {
            return is_array($plugins) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
        } );
        add_filter( 'wp_resource_hints', function($urls, $relation_type) {
            if ( 'dns-prefetch' == $relation_type ) {
                // This filter is documented in wp-includes/formatting.php
                $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
                $urls = array_diff( $urls, array( $emoji_svg_url ) );
            }
               
            return $urls;
        }, 10, 2 );
        add_filter( 'xmlrpc_enabled', '__return_false' );
    }
}