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
      identifier: 'country_id',
      rules: [{
        type: 'empty',
        prompt: __('validation.required', { attribute: 'country' })
      }]
    },
    currency_id: {
      identifier: 'currency_id',
      rules: [{
        type: 'empty',
        prompt: __('validation.required', { attribute: 'currency' })
      }]
    },
    address_line_1: {
      identifier: 'address_line_1',
      rules: [{
        type: 'empty',
        prompt: __('validation.required', { attribute: 'address' })
      }, {
        type: 'maxLength[255]',
        prompt: __('validation.max.string', { attribute: 'address line 1', max: 255 })
      }]
    },
    address_line_2: {
      identifier: 'address_line_2',
      rules: [{
        type: 'maxLength[255]',
        prompt: __('validation.max.string', { attribute: 'address line 2', max: 255 })
      }]
    },
    city: {
      identifier: 'city',
      rules: [{
        type: 'empty',
        prompt: __('validation.required', { attribute: 'city' })
      }, {
        type: 'maxLength[255]',
        prompt: __('validation.max.string', { attribute: 'city', max: 255 })
      }]
    },
    state: {
      identifier: 'state',
      rules: [{
        type: 'maxLength[255]',
        prompt: __('validation.max.string', { attribute: 'state', max: 255 })
      }]
    },
    zip: {
      identifier: 'zip',
      rules: [{
        type: 'empty',
        prompt: __('validation.required', { attribute: 'zip' })
      }, {
        type: 'maxLength[15]',
        prompt: __('validation.max.string', { attribute: 'zip', max: 15 })
      }]
    },
    how_to_find: {
      identifier: 'how_to_find',
      rules: [{
        type: 'maxLength[255]',
        prompt: __('validation.max.string', { attribute: 'entrance description', max: 255 })
      }]
    }
  });


}(jQuery));
