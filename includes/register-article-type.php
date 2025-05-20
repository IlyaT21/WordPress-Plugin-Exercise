<?php

// Register "Articles" custom post type

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Regsiter post type.

function article_post_type() {
	$labels = array(
		'name'                  => __( 'Articles',        'textdomain' ),
		'singular_name'         => __( 'Article',         'textdomain' ),
		'menu_name'             => __( 'Articles',        'textdomain' ),
		'name_admin_bar'        => __( 'Article',         'textdomain' ),
		'add_new_item'          => __( 'Add New Article', 'textdomain' ),
		'edit_item'             => __( 'Edit Article',    'textdomain' ),
		'new_item'              => __( 'New Article',     'textdomain' ),
		'view_item'             => __( 'View Article',    'textdomain' ),
		'all_items'             => __( 'All Articles',    'textdomain' ),
		'search_items'          => __( 'Search Articles', 'textdomain' ),
		'not_found'             => __( 'No articles found.',   'textdomain' ),
		'not_found_in_trash'    => __( 'No articles in trash.', 'textdomain' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-media-text',
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		'rewrite'            => array( 'slug' => 'articles' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'article', $args );
}
add_action( 'init', 'article_post_type', 0 );