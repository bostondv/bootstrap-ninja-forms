jQuery(document).ready(function($) {

  jQuery(document).on('submitResponse.example', function(event, response) {
    $('.ninja-forms-field-error').addClass('help-block');
    $('.ninja-forms-response-msg').addClass('alert alert-warning');
    $('.ninja-forms-error-msg').removeClass('alert-warning').addClass('alert-danger');
    $('.ninja-forms-success-msg').removeClass('alert-warning').addClass('alert-success');
    $('.ninja-forms-error').addClass('has-error');
  });

});