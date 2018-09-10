<?php
/**
 * @package Primary Category
 * Plugin Name: Primary Category For Posts
 * Description: Set a Primary Category for your posts. Whats that? WordPress doesn't come with Primary Cateogries? No worries!
 * Summary: Primary Category.
 * License: GPLv3+
 * Text Domain: primary-category-for-posts
 **/

namespace Robbie_Cahill\Primary_Category;

require __DIR__ . '/php/class-primary-category.php';

/**
 * Register hooks. The Primary_Category class has no knowledge of the WordPress hooks system
 */
$primary_category = new Primary_Category();
add_action( 'admin_enqueue_scripts', [ $primary_category, 'admin_enqueue_scripts' ] );
add_action( 'add_meta_boxes_post', [ $primary_category, 'add_meta_box' ] );
add_action( 'wp_ajax_primary_category_query', [ $primary_category, 'admin_ajax_primary_category_query' ] );
add_action( 'save_post', [ $primary_category, 'process_primary_category' ] );
