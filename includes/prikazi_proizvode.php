<?php

// Shortoce logic

if (!defined('ABSPATH')) {
    exit;
}

?>

<?php
function proizvod_shortcode_callback($atts) {

  // Get the availiablity attribute

  $atts = shortcode_atts(array(
    'dostupnost' => ''
  ), $atts);

  // Fetch proizvodi

  $args = array(
    'post_type'      => 'proizvod',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
  );

  // Apply filter only if attribute is passed

  if (!empty($atts['dostupnost'])) {
    $availability_value = strtolower($atts['dostupnost']) === 'dostupno' ? 'Dostupno' : 'Nije dostupno';
    $args['meta_key'] = '_proizvod_availability';
    $args['meta_value'] = $availability_value;
    $args['meta_compare'] = '=';
  }

  $query = new WP_Query($args);

  // Display proizvodi 

  if ($query->have_posts()) {?>
    <table>
    <?php while ($query->have_posts()) { ?>

      <?php
      $query->the_post();
    
      $price = get_post_meta(get_the_ID(), '_proizvod_cena', true);
      $availability = get_post_meta(get_the_ID(), '_proizvod_availability', true);
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
    echo '<h4>No products found.</h4>';
  }
}

// Register prozivodi shortcode

function register_proizvod_shortcode() {
  add_shortcode('prikazi_proizvode', 'proizvod_shortcode_callback');
}
add_action('init', 'register_proizvod_shortcode');

