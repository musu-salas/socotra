(function($) {
  'use strict';

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });


  $.fn.form.settings.rules.isPrice = function(value) {
    return !isNaN(value);
  };


  var groupId = parseInt($('input[name="group_id"]').val(), 10);
  var formValidations = {
    title: {
      identifier: 'title',
      rules: [{
        type: 'empty',
        prompt: __('validation.required', { attribute: 'title' })
      }, {
        type: 'maxLength[50]',
        prompt: __('validation.max.string', { attribute: 'title', max: 50 })
      }]
    },
    description: {
      identifier: 'description',
      rules: [{
        type: 'maxLength[255]',
        prompt: __('validation.max.string', { attribute: 'description', max: 255 })
      }]
    }
  };


  function sprintf(text){
    var i=1, args=arguments;
    return text.replace(/%s/g, function(pattern){
      return (i < args.length) ? args[i++] : "";
    });
  }


  $('table .checkbox').checkbox({
    onChecked: function () {
      var input = $(this).closest('tr').addClass('positive').find('.input input');

      input.val(input.val() || '0').removeAttr('disabled', 'disabled');
    },
    onUnchecked: function () {
      $(this).closest('tr').removeClass('positive').find('.input input').val('').attr('disabled', 'disabled');
    }
  });


  $('table tr').on('click', function(e) {
    var target = $(e.target);

    if (!target.closest('.checkbox').length) {
      var checkbox = $(this).find('.checkbox');

      if (!checkbox.checkbox('is checked')) {
        checkbox.checkbox('check');
      }
    }
  });


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
            window.location = sprintf('/home/classes/%s/pricing', groupId);
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


  $('table .input input[type="text"][name^="prices"]').on('input', function() {
    $(this).val(this.value.trim().replace(/,/g, '.'));

  }).each(function() {
    formValidations[this.id] = {
      identifier: this.id,
      optional: true,
      rules: [{
        type: 'isPrice',
        prompt: __('validation.numeric', { attribute: 'price' })
      }]
    };
  });


  $('form').form(formValidations);


}(jQuery));
