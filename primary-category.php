<?php
namespace Robbie_Cahill\Primary_Category;
/*
 * Plugin Name: Primary Category For Posts
 * Description: Set a Primary Category for your posts. Whats that? WordPress doesn't come with Primary Cateogries? No worries!
 * License: GPLv3+
 * Text Domain: primary-category-for-posts
 */

require __DIR__ . '/php/class-primary-category.php';

$primary_category = new Primary_Category();
add_action( 'admin_init', [ $primary_category, 'admin_init' ] );
add_action( 'add_meta_boxes_post', [ $primary_category, 'adding_custom_meta_boxes' ] );
