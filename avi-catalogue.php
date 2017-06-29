<?php
/*
Plugin Name: Avi Catalogue
Plugin URI:  http://aviitsolutions.in/open-source/avi-catalogue
Description: This is the plugin for the catalogue to show on your website. This has categories, sub-categories, product and enquiry form.
Version:     1.0.0
Author:      Avinash Singh Rathi
Author URI:  https://www.linkedin.com/in/avinashrathi
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: avi-catalogue
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

function avi_create_product_categories() {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => _x( 'Product Categories', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Product Category', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Product Categories', 'textdomain' ),
        'all_items'         => __( 'All Product Categories', 'textdomain' ),
        'parent_item'       => __( 'Parent Category', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Category:', 'textdomain' ),
        'edit_item'         => __( 'Edit Product Category', 'textdomain' ),
        'update_item'       => __( 'Update Product Category', 'textdomain' ),
        'add_new_item'      => __( 'Add New Product Category', 'textdomain' ),
        'new_item_name'     => __( 'Add New Product Category', 'textdomain' ),
        'menu_name'         => __( 'Product Category', 'textdomain' ),
    );
 
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'sort'              => true,
        'args' => array( 'orderby' => 'term_order' ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'product-category' ),
    );
 
    register_taxonomy( 'product-category', array( 'product' ), $args );
}

/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function avi_product_init() {
    $labels = array(
        'name'                  => _x( 'Products', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Product', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'Product Catalogue', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Product', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New Product', 'textdomain' ),
        'add_new_item'          => __( 'Add New Product', 'textdomain' ),
        'new_item'              => __( 'New Product', 'textdomain' ),
        'edit_item'             => __( 'Edit Product', 'textdomain' ),
        'view_item'             => __( 'View Product', 'textdomain' ),
        'all_items'             => __( 'All Products', 'textdomain' ),
        'search_items'          => __( 'Search Products', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Products:', 'textdomain' ),
        'not_found'             => __( 'No products found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No products found in Trash.', 'textdomain' ),
        'featured_image'        => _x( 'Product Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'archives'              => _x( 'Product archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
        'insert_into_item'      => _x( 'Insert into Product', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this Product', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
        'filter_items_list'     => _x( 'Filter products list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
        'items_list_navigation' => _x( 'Products list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
        'items_list'            => _x( 'Products list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'product' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
    );
 
    register_post_type( 'product', $args );
}
add_action( 'init', 'avi_product_init' );
add_action( 'init', 'avi_create_product_categories' );

//add_menu_page("Products","Products", "manage_options", "avi-catalogue","?edit.php?post_type=product", '', 50 );
add_filter( 'template_include', 'wpa3396_page_template' );
function wpa3396_page_template( $template )
{
    if( is_tax('product-category')){
        $template = dirname( __FILE__ ) . '/views/category.php';
    }
    return $template;
}
function add_custom_rewrite_rule() {

    // First, try to load up the rewrite rules. We do this just in case
    // the default permalink structure is being used.
    if( ($current_rules = get_option('rewrite_rules')) ) {

        // Next, iterate through each custom rule adding a new rule
        // that replaces 'movies' with 'films' and give it a higher
        // priority than the existing rule.
        foreach($current_rules as $key => $val) {
            if(strpos($key, 'movies') !== false) {
                add_rewrite_rule(str_ireplace('movies', 'films', $key), $val, 'top');   
            } // end if
        } // end foreach

    } // end if/else

    // ...and we flush the rules
    flush_rewrite_rules();

}
add_action('init', 'add_custom_rewrite_rule');
