<?php

// Register "Proizvodi" custom post type

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Regsiter post type.

function proizvod_post_type() {
	register_post_type('proizvod',
		array(
			'labels'      => array(
				'name'          => __('Proizvodi', 'textdomain'),
				'singular_name' => __('Proizvod', 'textdomain'),
			),
				'public'      => true,
				'has_archive' => true,
        'menu_icon'   => 'dashicons-products',
		)
	);
}
add_action('init', 'proizvod_post_type');