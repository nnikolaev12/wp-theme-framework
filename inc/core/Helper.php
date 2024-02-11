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
     */
    public static function image( array $img )
    {
        $src = isset( $img['src'] ) ? 'images/' . $img['src'] : '';
        $format = isset( $img['format'] ) ? $img['format'] : '';

        // stop execution if no source or format 
        if ( empty( $src ) || empty( $format ) ) {
            return;
        }

        $alt = isset( $img['alt'] ) ? $img['alt'] : '';
        $dimentions = isset( $img['width'] ) && isset( $img['height'] ) ? "width=\"" . $img['width'] . "\" height=\"" . $img['height'] . "\"" : "";
        $class = isset( $img['class'] ) ? "class=\"" . $img['class'] . "\" " : "";
?>
<picture>
    <source srcset="<?php self::asset( $src . ".webp" ); ?>" type="image/webp">
    <source srcset="<?php self::asset( $src . "." . $format ); ?>" type="image/<?php echo $format; ?>">
    <img <?php echo $class; ?><?php echo $dimentions; ?> src="<?php self::asset( $src . "." . $format ); ?>"
        alt="<?php echo $alt; ?>" loading="lazy">
</picture>
<?php
    }

    /**
     * Output svg icon
     */
    public static function icon( string $filename )
    {        
        $icon = file_get_contents( get_template_directory() . "/assets/icons/" . $filename . ".svg" );

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
}