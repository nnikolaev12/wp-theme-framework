<?php

namespace NyxitSoft;

defined( 'ABSPATH' ) ?: exit;

/**
 * Class that handles initialization of custom posts
 * 
 * Registers the custom post types defined in the config.php
 * file.
 */

class CustomPosts
{
    protected $id;
    protected $args;
    protected $labels;

    public function __construct( string $id, array $options )
    {
        $this->id = $id;
        
        if ( ! empty( $options['args'] ) ) {
            $this->set_args( $options['args'] );
        }

        if ( ! empty( $options['labels'] ) ) {
            $this->set_labels( $options['labels'] );
        }

        add_action( 'init', [ $this, 'create_post_type' ] );
    }

    protected function set_labels( array $labels )
    {
        $this->labels = $labels;

        $this->args['labels'] = 
            [
                'name' => $labels['singular'],
                'singular_name' => $labels['singular'],
                'add_new' => 'Add ' . $labels['singular'],
                'all_items' => 'All ' . $labels['plural'],
                'add_new_item' => 'Add ' . $labels['singular'],
                'edit_item' => 'Edit ' . $labels['singular'],
                'new_item' => 'New ' . $labels['singular'],
                'view_item' => 'View ' . $labels['singular'],
                'search_items' => 'Search ' . $labels['plural'],
                'not_found' => 'No ' . $labels['plural'] . ' found',
                'not_found_in_trash' => 'No ' . $labels['plural'] . ' found in trash',
                'parent_item_colon' => 'Parent ' . $labels['singular'],
            ];
    }

    protected function set_args( array $args )
    {
        $this->args = $args;
    }

    public function create_post_type()
    {          
        register_post_type($this->id, $this->args);
    }
}

endif;