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

    // load redux framework for theme options
    require_once "inc/vendor/redux.php";
    
    /**
     * Start of custom functionality
     */
    
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