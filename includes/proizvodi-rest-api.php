<?php

// REST API Endpoint

if (!defined('ABSPATH')) {
    exit;
}

// Register an Endpoint

function register_proizvod_api_routes() {
  register_rest_route('mojplugin', '/proizvodi/', array(
      'methods'  => 'GET',
      'callback' => 'get_available_proizvodi',
      'permission_callback' => '__return_true', // Sets permission acordingly
  ));
}
add_action('rest_api_init', 'register_proizvod_api_routes');

// Callback to fetch all available proizvodi

function get_available_proizvodi($request) {
  $args = array(
      'post_type'      => 'proizvod',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
  );

  $query = new WP_Query($args);
  $products = [];

  if ($query->have_posts()) {
      while ($query->have_posts()) {
          $query->the_post();
          $products[] = array(
              'id'     => get_the_ID(),
              'title'  => get_the_title(),
              'price'  => get_post_meta(get_the_ID(), '_proizvod_cena', true),
              'availability' => 'Dostupno',
          );
      }
      wp_reset_postdata();
  }

  return rest_ensure_response($products);
}