<?php

// Shortoce logic

if (!defined('ABSPATH')) {
    exit;
}

?>

<?php
function article_shortcode_callback($atts) {

  // Get the availability attribute

  $atts = shortcode_atts(array(
    'availability' => ''
  ), $atts);

  // Fetch articles

  $args = array(
    'post_type'      => 'article',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
  );

  // Apply filter only if attribute is passed

  if (!empty($atts['availability'])) {
    $availability_value = strtolower($atts['availability']) === 'available' ? 'Available' : 'Unavailable';
    $args['meta_key'] = '_article_availability';
    $args['meta_value'] = $availability_value;
    $args['meta_compare'] = '=';
  }

  $query = new WP_Query($args);

  // Display articles

  if ($query->have_posts()) {?>
    <table>
    <?php while ($query->have_posts()) { ?>

      <?php
      $query->the_post();
    
      $price = get_post_meta(get_the_ID(), '_article_price', true);
      $availability = get_post_meta(get_the_ID(), '_article_availability', true);
      $title = get_the_title();
      ?>

      <tr>
        <td><?php echo esc_html($title); ?></td>
        <td><?php echo esc_html($price); ?></td>
        <td><?php echo esc_html($availability); ?></td>
      </tr>
      <?php } ?>
    </table>
  
  <?php } else {
    echo '<h4>No articles found.</h4>';
  }
}

// Register articles shortcode

function register_article_shortcode() {
  add_shortcode('show_articles', 'article_shortcode_callback');
}
add_action('init', 'register_article_shortcode');

