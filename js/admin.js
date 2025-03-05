  console.log('radi')

jQuery(document).ready(function ($) {

  $('#proizvod-form').on('submit', function (e) {
    e.preventDefault();

    var formData = {
      action: 'add_proizvod',
      title: $('#proizvod_title').val(),
      cena: $('#proizvod_cena').val(),
      availability: $('#proizvod_availability').val(),
      content: tinyMCE.get('proizvod_content') ? tinyMCE.get('proizvod_content').getContent() : $('#proizvod_content').val(),
    };

    $.post(proizvodAjax.ajaxurl, formData, function (response) {
      $('#noticeHolder').html(response);

      // Clear inputs
      $('#proizvod-form')[0].reset();
      tinyMCE.get('proizvod_content').setContent('');
    });
  });
});