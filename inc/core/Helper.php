<?php

namespace NyxitSoft;

defined( 'ABSPATH' ) ?: exit;

/**
 * Static class with helper functions.
 * 
 * Helper functions to be used staticly around
 * the theme.
 * 
 * @author Nikolay Nikolaev <me@nikolaynikolaev.com>
 * 
 * @since 1.0.0
 */

class Helper
{
    /**
     * Outputs or return a relative url
     */
    public static function url( string $path = "/", bool $echo = true )
    {
        if ( ! $echo ) {
            return home_url( $path );
        }

        echo esc_url( home_url( $path ) );
    }

    /**
     * Output assets from assets dir
     * 
     * Outputs assets (images, css files, js files, etc.) relative
     * to the /assets dir.
     */
    public static function asset( string $path, bool $echo = true )
    {
        $full_path = get_stylesheet_directory_uri() . "/assets/" . $path;
        
        if ( ! $echo ) {
            return $full_path;
        }
        
        echo $full_path;
    }

    /**
     * Outputs the custom logo
     * 
     * Custom logo is a WP theme feature enabled in theme config file.
     */

     public static function logo( bool $echo = true )
     {
        if ( has_custom_logo() ) {
            if ( ! $echo ) {
                $custom_logo_id = get_theme_mod( 'custom_logo' );
                $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                
                return empty( $logo ) ? "" : esc_url( $logo[0] );
            }
            
            the_custom_logo();
        }
     }

    /**
     * Output optimized image with fallback
     * 
     * Outputs an image through the <picture> tag with webp format as main source
     * and older format as a fallback for compatibility with older browsers.
     * 
     * @param array $img
     * @param string $img['name'] The name of the image file
     * @param string $img['alt'] The alt text of the image
     * @param int $img['width'] The width of the image
     * @param int $img['height'] The height of the image
     * @param string $img['class'] The classes of the image
     */
    public static function image( array $img )
    {
        // stop execution if no source
        if ( empty( $img['name'] ) ) {
            return;
        }

        $src = "src=\"" . self::asset( 'images/' . $img['name'] . ".webp", false ) . "\" ";
        $alt = isset( $img['alt'] ) ? "alt=\"" . $img['alt'] . "\" " : "alt=\"" . ucwords(str_replace('-', ' ', $img['name'])) . "\" ";
        $width = isset( $img['width'] ) ? "width=\"" . $img['width'] . "\" " : '';
        $height = isset( $img['height'] ) ? "height=\"" . $img['height'] . "\" " : '';
        $classes = isset( $img['class'] ) ? "class=\"" . $img['class'] . "\" " : '';

        $image = "<img " . $classes . $width . $height . $src .$alt . "loading=\"lazy\" />";

        echo $image;
    }

    /**
     * Output SVG icon inline or as an image
     */
    public static function icon( string $name, bool $is_img = false )
    {
        if ( $is_img ) {
            $alt_text = ucwords(str_replace('-', ' ', $name)) . " icon";
            $icon = "<img src=\"" . self::asset( "icons/" . $name . ".svg", false ) . "\" alt=\"" . $alt_text . "\">";
        } else {
            $icon = file_get_contents( get_template_directory() . "/assets/icons/" . $name . ".svg" );
        }
        
        if ( ! empty( $icon ) ) {
            echo $icon;
        }
    }

    /**
     * Load component template
     */
    public static function component( string $name, array $args = array() )
    {
        if ( empty( $name ) ) {
            return;
        }

        get_template_part( "/template-parts/components/" . $name, null, $args );
    }

    /**
     * Load block template with custom args
     */
    public static function block( string $name, array $args = array() ) {
        if ( empty( $name ) ) {
            return;
        }

        get_template_part( "/template-parts/blocks/" . $name . '/' . $name, null, $args );
    }

    /**
     * Check if a required plugin is active
     */
    public static function is_plugin_active( string $plugin_id )
    {
        $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        
        return in_array( $plugin_id, $active_plugins );
    }
}