(function($) {
  'use strict';

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });


  var groupId = parseInt($('input[name="group_id"]').val(), 10);


  function sprintf(text){
    var i=1, args=arguments;
    return text.replace(/%s/g, function(pattern){
      return (i < args.length) ? args[i++] : "";
    });
  }


  $('#delete').on('click', function(e) {
    var target = $(this);
    var href = target.attr('href');

    e.preventDefault();

    if (target.data('deleting')) {
      return false;
    }

    $('#delete-modal').modal({
      closable: false,
      onApprove: function() {
        target.data({
          deleting: true,
          initialLabel: target.text()
        });

        target.text(target.data('deletingLabel'));

        $.ajax({
          url: href,
          method: 'DELETE',
          success: function() {
            window.location = sprintf('/home/classes/%s/location', groupId);
          },
          error: function() {
            target.data('deleting', false);
            target.text(target.data('initialLabel'));

            $('.error.message').addClass('visible').find('.list').html($('<li />', {
              text: target.data('errorLabel')
            }));

            window.setTimeout(function() {
              $(window).scrollTop(0);
            }, 1000);
          }
        });
      }
    }).modal('show');

    return false;
  });


  $('form').form({
    country: {
      identifier  : 'country_id',
      rules: [{
        type: 'empty',
        prompt: 'Country shan\'t be empty.'
      }]
    },
    currency_id: {
      identifier  : 'currency_id',
      rules: [{
        type: 'empty',
        prompt: 'Currency shan\'t be empty.'
      }]
    },
    address_line_1: {
      identifier: 'address_line_1',
      rules: [{
        type: 'empty',
        prompt: 'Address line 1 shan\'t be empty.'
      }, {
        type: 'maxLength[255]',
        prompt: 'Address line 1 shan\'t exceed 255 characters.'
      }]
    },
    address_line_2: {
      identifier: 'address_line_2',
      rules: [{
        type: 'maxLength[255]',
        prompt: 'Address line 2 shan\'t exceed 255 characters.'
      }]
    },
    city: {
      identifier: 'city',
      rules: [{
        type: 'empty',
        prompt: 'City shan\'t be empty.'
      }, {
        type: 'maxLength[255]',
        prompt: 'City shan\'t exceed 255 characters.'
      }]
    },
    state: {
      identifier: 'state',
      rules: [{
        type: 'maxLength[255]',
        prompt: 'State shan\'t exceed 255 characters.'
      }]
    },
    zip: {
      identifier: 'zip',
      rules: [{
        type: 'empty',
        prompt: 'Zip shan\'t be empty.'
      }, {
        type: 'maxLength[15]',
        prompt: 'Zip shan\'t exceed 15 characters.'
      }]
    },
    how_to_find: {
      identifier: 'how_to_find',
      rules: [{
        type: 'maxLength[255]',
        prompt: 'Entrance description shan\'t exceed 255 characters.'
      }]
    }
  });


}(jQuery));
