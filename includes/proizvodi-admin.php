<?php

// Admin functionality

// The fileds are separated in different in order to organize the layout a bit better

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register product details metabox
function proizvod_details_add_meta_box() {
  add_meta_box(
      'proizvod_details_meta_box', // ID
      __('Detalji', 'textdomain'), // Title
      'proizvod_details_meta_box_callback', // Callback function
      'proizvod', // Post type
      'normal', // Context
      'default' // Priority
  );
}
add_action('add_meta_boxes', 'proizvod_details_add_meta_box');

// Register product availabiliity metabox
function proizvod_availability_add_meta_box() {
  add_meta_box(
      'proizvod_availability_meta_box', // ID
      __('Dostupnost', 'textdomain'), // Title
      'proizvod_availability_meta_box_callback', // Callback function
      'proizvod', // Post type
      'side', // Context
      'default' // Priority
  );
}
add_action('add_meta_boxes', 'proizvod_availability_add_meta_box');
?>

<?php

// Display the fields in the admin dashboard

// Display price

function proizvod_details_meta_box_callback($post) {
  $price = get_post_meta($post->ID, '_proizvod_cena', true);
  ?>
  <p>
      <label for="proizvod_cena"><?php _e('Price', 'textdomain'); ?></label><br>
      <input type="number" step="0.01" name="proizvod_cena" id="proizvod_cena" value="<?php echo esc_attr($price); ?>">
  </p>
  <?php
}

// Display availability

function proizvod_availability_meta_box_callback($post) {
  $availability = get_post_meta($post->ID, '_proizvod_availability', true);
  ?>
  <p>
      <label for="proizvod_availability"><?php _e('Dostupnost', 'textdomain'); ?></label><br>
      <select name="proizvod_availability" id="proizvod_availability">
          <option value="Dostupno" <?php selected($availability, 'Dostupno'); ?>>Dostupno</option>
          <option value="Nije dostupno" <?php selected($availability, 'Nije dostupno'); ?>>Nije dostupno</option>
      </select>
  </p>
  <?php
}

function proizvod_save_meta_fields($post_id) {

  // Save price
  if (isset($_POST['proizvod_cena'])) {
      update_post_meta($post_id, '_proizvod_cena', floatval($_POST['proizvod_cena']));
  }

  // Save availability
  if (isset($_POST['proizvod_availability'])) {
      update_post_meta($post_id, '_proizvod_availability', sanitize_text_field($_POST['proizvod_availability']));
  }
}
add_action('save_post', 'proizvod_save_meta_fields');

// Register admin menu page

function proizvod_admin_menu() {
  add_menu_page('Dodaj novi proizvod', 'Dodaj proizvod', 'manage_options', 'proizvod_admin', 'proizvod_admin_page', 'dashicons-cart', 6);
}
add_action('admin_menu', 'proizvod_admin_menu');

// Render page contents

function proizvod_admin_page() {
  ?>
  <div>
    <h2>Dodaj Proizvod</h2>
    <p><i>Polja sa zvezdicom su obavezna.</i></p>
    <form id="proizvod-form">
      <div>
        <label for="proizvod_title">Naziv*:</label><br>
        <input type="text" id="proizvod_title" name="proizvod_title" required>
      </div>
      <br>
      <div>
        <label for="proizvod_content">Opis:</label><br>
        <?php
        wp_editor('', 'proizvod_content', array(
            'textarea_name' => 'proizvod_content',
            'media_buttons' => false,
            'textarea_rows' => 5,
            'teeny'         => true
        ));
        ?>
      </div>
      <div>
        <label for="proizvod_cena">Cena*:</label><br>
        <input type="number" id="proizvod_cena" name="proizvod_cena" required>
      </div>
      <div>
        <label for="proizvod_availability">Dostupnost:</label><br>
        <select id="proizvod_availability" name="proizvod_availability">
          <option value="Dostupno">Dostupno</option>
          <option value="Nije dostupno">Nije dostupno</option>
        </select>
      </div>
      <br>
      <button type="submit">Dodaj proizvod</button>
    </form>
    <div id="noticeHolder"></div>
  </div>

  <script type="text/javascript">
  jQuery(document).ready(function($) {
    $('#proizvod-form').on('submit', function(e) {
      e.preventDefault();

      var formData = {
        action: 'add_proizvod',
        title: $('#proizvod_title').val(),
        cena: $('#proizvod_cena').val(),
        availability: $('#proizvod_availability').val(),
        content: tinyMCE.get('proizvod_content') ? tinyMCE.get('proizvod_content').getContent() : $('#proizvod_content').val(),
      };

      $.post(ajaxurl, formData, function(response) {
        $('#noticeHolder').html(response);

        // Clear inputs

        $('#proizvod-form')[0].reset();
        tinyMCE.get('proizvod_content').setContent('');
      });
    });
  });
  </script>
  <?php
}
?>

<?php

// AJAX logic

function add_proizvod_ajax() {

  // Get the data

  $title = sanitize_text_field($_POST['title']);
  $cena = sanitize_text_field($_POST['cena']);
  $availability = sanitize_text_field($_POST['availability']);
  $content = sanitize_text_field($_POST['content']);

  $post_id = wp_insert_post(array(
    'post_title'   => $title,
    'post_content' => $content,
    'post_type'    => 'proizvod',
    'post_status'  => 'publish',
  ));

  // Adding new proizvod

  if ($post_id) {

    // Meta fields handling inside because wp_insert_post() equals 0

    update_post_meta($post_id, '_proizvod_cena', $cena);
    update_post_meta($post_id, '_proizvod_availability', $availability);
    echo '<p style="color: green;">Product added successfully!</p>';
  } else {
    echo '<p style="color: red;">Failed to add product.</p>';
  }

  wp_die();
}
add_action('wp_ajax_add_proizvod', 'add_proizvod_ajax');