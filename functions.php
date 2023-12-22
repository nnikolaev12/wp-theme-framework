<?php

/**
 * Start of Nyxit Soft Theme
 * 
 * @author Nikolay Nikolaev <me@nikolaynikolaev.com>
 */

use NyxitSoft\Theme;

try {
    if ( defined( "NYXIT_ENV" ) && NYXIT_ENV === "local" ) {
        // autoload composer libs (dev only)
        require_once "vendor/autoload.php";
        require_once "inc/core/Debug.php";
    }

    // autoload theme
    require_once "inc/core/autoload.php";
    $nyxit_theme = new Theme;
    
    /**
     * Start of custom functionality
     */

    /**
     * ACF handler class
     */
    require_once "inc/classes/class-acf.php";

    /**
     * Custom post type: Book
     */
    require_once "inc/classes/class-book.php";
    
    /**
     * End of custom functionality
     */
} catch (Exception $e) {
    add_action('template_redirect', function($e) {
        echo "Theme failed to initialize\n";
        if (WP_DEBUG) {
            echo "Error: " . $e->getMessage() . "\n";
        }
        die;
    });
}
// End of Nyxit Soft Theme