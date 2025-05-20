<?php

// REST API Endpoint

if (!defined('ABSPATH')) {
    exit;
}

// Register an Endpoint

function register_article_api_routes() {
  register_rest_route('testplugin', '/articles/', array(
      'methods'  => 'GET',
      'callback' => 'get_available_articles',
      'permission_callback' => '__return_true',
  ));
}
add_action('rest_api_init', 'register_article_api_routes');

// Callback to fetch all available articles

function get_available_articles($request) {
  $args = array(
      'post_type'      => 'article',
      'posts_per_page' => -1,
      'post_status'    => 'publish',
  );

  $query = new WP_Query($args);
  $articles = [];

  if ($query->have_posts()) {
      while ($query->have_posts()) {
          $query->the_post();
          $articles[] = array(
              'id'     => get_the_ID(),
              'title'  => get_the_title(),
              'price'  => get_post_meta(get_the_ID(), '_article_price', true),
              'availability' => 'Available',
          );
      }
      wp_reset_postdata();
  }

  return rest_ensure_response($articles);
}