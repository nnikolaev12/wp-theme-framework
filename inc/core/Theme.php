<?php

namespace Nyxit;

defined( 'ABSPATH' ) ?: exit;

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
        add_action( 'enqueue_block_editor_assets', [ $this, "include_editor_styles" ]);
        add_action( 'init', [ $this, "register_navs" ] );
        add_action( 'widgets_init', [ $this, "register_sidebars" ] );
        $this->clean();
        $this->regiter_plugin_hooks();
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
     * Include stylesheets in the editor to style the content
     */
    public function include_editor_styles()
    {
        wp_enqueue_style('tailwind-editor-styles', get_template_directory_uri() . '/assets/css/core.min.css', array(), '1.0', 'all');
        wp_enqueue_style('custom-editor-styles', get_template_directory_uri() . '/assets/css/style.min.css', array(), '1.0', 'all');
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

    /**
     * Register hooks for common plugins
     */
    public function regiter_plugin_hooks()
    {
        // ACF Pro
        if ( Helper::is_plugin_active('advanced-custom-fields-pro/acf.php') ) {
            $this->add_options_page();
            add_action('init', [ $this, 'register_blocks' ] );
        }

        // All in One WP Migration
        if ( Helper::is_plugin_active('all-in-one-wp-migration/all-in-one-wp-migration.php') ) {
            add_filter( 'ai1wm_exclude_themes_from_export', [ $this, 'exclude_dev_folders' ] );
        }
        
    }

    /**
     * Exclude theme folders from exports and backups
     */
    public function exclude_dev_folders( $exclude_filters )
    {
        $theme = wp_get_theme();
        $exclude_filters[] = $theme->get_template() . '/node_modules';
        $exclude_filters[] = $theme->get_template() . '/vendor';
        $exclude_filters[] = $theme->get_template() . '/.git';
        
        return $exclude_filters;
    }

    /**
     * Include an theme options page in the main admin menu
     */
    protected function add_options_page()
    {
        if ( function_exists('acf_add_options_page') ) {
            $theme = wp_get_theme();

            acf_add_options_page( [
                'page_title' => 'Theme Options',
                'menu_title' => 'Theme Options',
                'slug' => $theme->get_template() . '_options',
            ] );
        }
    }

    /**
     * Register Gutenberg blocks
     */
    public function register_blocks()
    {
        $blocks_path = get_stylesheet_directory() . '/template-parts/blocks/';

        // Get all directories inside $blocks_path
        $dirs = glob($blocks_path . '*', GLOB_ONLYDIR);

        foreach ($dirs as $dir) {
            // Get the block name by removing the $blocks_path part from $dir
            $block_name = str_replace($blocks_path, '', $dir);
    
            register_block_type($blocks_path . $block_name);
        }
    }
}