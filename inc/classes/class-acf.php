<?php

defined( 'ABSPATH' ) ?: exit;

use NyxitSoft\Singleton;

/**
 * Class to handle ACF (Advanced Custom Fields) functionality.
 */

class NyxitACF {

    use Singleton;

    public function __construct()
    {        
        if ( ! NyxitSoft\Helper::is_plugin_active('advanced-custom-fields-pro/acf.php') ) {
            return;
        }

        $this->add_options_page();
        add_action('init', [ $this, 'register_blocks' ] );
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

$nyxit_acf = NyxitACF::getInstance();