<?php

// Admin functionality

// The fileds are separated in different in order to organize the layout a bit better

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register article details metabox
function article_details_add_meta_box() {
  add_meta_box(
    'article_details_meta_box', // ID
    __('Details', 'textdomain'), // Title
    'article_details_meta_box_callback', // Callback function
    'article', // Post type
    'normal', // Context
    'default' // Priority
  );
}
add_action('add_meta_boxes', 'article_details_add_meta_box');

// Register article availabiliity metabox
function article_availability_add_meta_box() {
  add_meta_box(
    'article_availability_meta_box', // ID
    __('Availability', 'textdomain'), // Title
    'article_availability_meta_box_callback', // Callback function
    'article', // Post type
    'side', // Context
    'default' // Priority
  );
}
add_action('add_meta_boxes', 'article_availability_add_meta_box');
?>

<?php

// Display the fields in the admin dashboard

// Display price

function article_details_meta_box_callback($post) {
  $price = get_post_meta($post->ID, '_article_price', true);
  ?>
  <p>
    <label for="article_price"><?php _e('Price', 'textdomain'); ?></label><br>
    <input type="number" step="0.01" name="article_price" id="article_price" value="<?php echo esc_attr($price); ?>">
  </p>
  <?php
}

// Display availability

function article_availability_meta_box_callback($post) {
  $availability = get_post_meta($post->ID, '_article_availability', true);
  ?>
  <p>
    <label for="article_availability"><?php _e('Availability', 'textdomain'); ?></label><br>
    <select name="article_availability" id="article_availability">
      <option value="Available" <?php selected($availability, 'Available'); ?>>Available</option>
      <option value="Unavailable" <?php selected($availability, 'Unavailable'); ?>>Unavailable</option>
    </select>
  </p>
  <?php
}

function article_save_meta_fields($post_id) {

  // Save price
  if (isset($_POST['article_price'])) {
      update_post_meta($post_id, '_article_price', floatval($_POST['article_price']));
  }

  // Save availability
  if (isset($_POST['article_availability'])) {
      update_post_meta($post_id, '_article_availability', sanitize_text_field($_POST['article_availability']));
  }
}
add_action('save_post', 'article_save_meta_fields');

// Register admin menu page

function article_admin_menu() {
  add_menu_page('Add new article', 'Add article', 'manage_options', 'article_admin', 'article_admin_page', 'dashicons-cart', 6);
}
add_action('admin_menu', 'article_admin_menu');

// Render page contents

function article_admin_page() {
  ?>
  <div>
    <h2>Add New Articles</h2>
    <p><i>Fields with * are required.</i></p>
    <form id="article-form">
      <div>
        <label for="article_title">Name*:</label><br>
        <input type="text" id="article_title" name="article_title" required>
      </div>
      <br>
      <div>
        <label for="article_content">Details:</label><br>
        <?php
        wp_editor('', 'article_content', array(
            'textarea_name' => 'article_content',
            'media_buttons' => false,
            'textarea_rows' => 5,
            'teeny'         => true
        ));
        ?>
      </div>
      <div>
        <label for="article_price">Price*:</label><br>
        <input type="number" id="article_price" name="article_price" required>
      </div>
      <div>
        <label for="article_availability">Availability:</label><br>
        <select id="article_availability" name="article_availability">
          <option value="Available">Available</option>
          <option value="Unavailable">Unavailable</option>
        </select>
      </div>
      <br>
      <button type="submit">Add Article</button>
    </form>
    <div id="noticeHolder"></div>
  </div>

  <script type="text/javascript">
  jQuery(document).ready(function($) {
    $('#article-form').on('submit', function(e) {
      e.preventDefault();

      var formData = {
        action: 'add_article',
        title: $('#article_title').val(),
        price: $('#article_price').val(),
        availability: $('#article_availability').val(),
        content: tinyMCE.get('article_content') ? tinyMCE.get('article_content').getContent() : $('#article_content').val(),
      };

      $.post(ajaxurl, formData, function(response) {
        $('#noticeHolder').html(response);

        // Clear inputs

        $('#article-form')[0].reset();
        tinyMCE.get('article_content').setContent('');
      });
    });
  });
  </script>
  <?php
}
?>

<?php

// AJAX logic

function add_article_ajax() {

  // Get the data

  $title = sanitize_text_field($_POST['title']);
  $price = sanitize_text_field($_POST['price']);
  $availability = sanitize_text_field($_POST['availability']);
  $content = sanitize_text_field($_POST['content']);

  $post_id = wp_insert_post(array(
    'post_title'   => $title,
    'post_content' => $content,
    'post_type'    => 'article',
    'post_status'  => 'publish',
  ));

  // Adding new article

  if ($post_id) {

    // Meta fields handling inside because wp_insert_post() equals 0

    update_post_meta($post_id, '_article_price', $price);
    update_post_meta($post_id, '_article_availability', $availability);
    echo '<p style="color: green;">article added successfully!</p>';
  } else {
    echo '<p style="color: red;">Failed to add article.</p>';
  }

  wp_die();
}
add_action('wp_ajax_add_article', 'add_article_ajax');