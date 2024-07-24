<?php

defined( 'ABSPATH' ) ?: exit;

use NyxitSoft\Singleton;

/**
 * Class to interact with CPT Book.
 */

class NyxitBook
{
    use Singleton;

    private $id = 'book';
    public array $labels = array(
        'singular' => 'Book',
        'plural' => 'Books',
    );

    public function __construct()
    {
        add_action( 'init', [ $this, 'register_post_type' ] );

        add_action( 'init' , [ $this, 'register_category' ] );
    }

    /**
     * Register the custom post type
     * 
     * https://developer.wordpress.org/reference/functions/register_post_type/
     */
    public function register_post_type()
    {
        $labels = array(
            'name' => $this->labels['plural'],
            'singular_name' => $this->labels['singular'],
            'add_new' => 'Add ' . $this->labels['singular'],
            'all_items' => 'All ' . $this->labels['plural'],
            'add_new_item' => 'Add ' . $this->labels['singular'],
            'edit_item' => 'Edit ' . $this->labels['singular'],
            'new_item' => 'New ' . $this->labels['singular'],
            'view_item' => 'View ' . $this->labels['singular'],
            'search_items' => 'Search ' . $this->labels['plural'],
            'not_found' => 'No ' . $this->labels['plural'] . ' found',
            'not_found_in_trash' => 'No ' . $this->labels['plural'] . ' found in trash',
            'parent_item_colon' => 'Parent ' . $this->labels['singular'],
        );

        register_post_type(
           $this->id,
            array(
                'labels' => $labels,
                'public' => true,
                'show_in_rest' => true,
                'has_archive' => true,
                'capability_type' => 'post',
                'supports' => array(
                    'title',
                    'editor',
                    'excerpt',
                    'thumbnail',
                ),
                'rewrite' => array(
                    'slug' => 'books',
                    'with_front' => false,
                ),
                'taxonomies' => array(
                    'book_category'
                ),
            )
        );
    }

    /**
     * Register custom taxonomy
     * 
     * https://developer.wordpress.org/reference/functions/register_taxonomy/
     */
    public function register_category()
    {
        register_taxonomy(
            'book_category',
            array(
                $this->id,
            ),
            array(
                'label' => 'Book Categories',
                'public' => true,
                'hierarchical' => true,
                'show_in_rest' => true,
                'rewrite' => array(
                    'slug' => 'books/category',
                    'with_front' => false,
                )
            )
        );
    }

    /**
     * Get a number of posts
     */
    public static function get_posts( int $count = -1 ) : array
    {
        $query = new WP_Query( array(
            'post_type' => 'book',
            'posts_per_page' => $count,
        ) );
                
        return self::fetch_data( $query );
    }

    /**
     * Fetch data from WP Query
     * 
     * @param WP_Query
     */
    protected static function fetch_data( object $query ) : array
    {
        $data = array();

        while ( $query->have_posts() ) {
            $query->the_post();

            $data[] = array(
                'name' => get_the_title(),
                // add more data here
            );
        }
        
        wp_reset_postdata();
        
        return $data;
    }
}

$nyxit_books = NyxitBook::getInstance();