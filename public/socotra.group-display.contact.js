(function($) {
  'use strict';

  /**
   * Checks whether current field value or at least one of additionally given fields values is not empty, neither blank string.
   * @param {string} value Current field value.
   * @param {string} fieldIdentifiers Comma separated field identifiers.
   * @return {boolean}
   */
  $.fn.form.settings.rules.allEmpty = function(value, fieldIdentifiers) {
    var $form = $(this);

    return !!value || fieldIdentifiers.split(',').some(function(fieldIdentifier) {
      return $form.find('#' + fieldIdentifier).val() ||
          $form.find('[name="' + fieldIdentifier +'"]').val() ||
          $form.find('[data-validate="'+ fieldIdentifier +'"]').val();

    });
  };


  $.fn.form.settings.rules.minLength = function(value, length) {
    return value.length >= length;
  };


  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });


  var groupId = parseInt($('input[name="group_id"]').val(), 10);
  var modal = $('#contact-modal');
  var form = modal.find('form');
  var buttons = form.find('.buttons .button');
  var buttonsHeader = form.find('#buttons-header');
  var submitButt = buttons.children('#submit-button');
  var messageButt = buttons.children('#message-button');
  var closeButt = buttons.children('#close-button');
  var sendButton = form.find('#send-button');
  var messageField = form.find('#message-field');
  var comments = form.find('.threaded.comments');
  var personFields = form.find('#person-fields');


  function sprintf(text){
    var i=1, args=arguments;
    return text.replace(/%s/g, function(pattern){
      return (i < args.length) ? args[i++] : "";
    });
  }


  function error(opt_errors) {

    if (opt_errors && opt_errors.length) {
      return form.addClass('error').find('.message.error .list').html(opt_errors.map(function(text) {
        return $('<li />', {
          text: text
        });
      }));
    }

    form.removeClass('error').find('.message.error .list').html('');
  }


  function alert(opt_html) {
    var block = form.find('#alert');

    if (!block.length) {
      block = $('<div />', {
        'id': 'alert',
        'class': 'ui message success'
      }).insertAfter(messageField);
    }

    if (opt_html) {
      return block.html(opt_html).show();
    }

    block.hide();
  }


  function getData() {
    var data = {};

    $.each(form.serializeArray(), function(i, input) {
      data[input.name] = input.value;
    });

    return data;
  }


  function onModalClosed() {
    alert();
    toggleHeader(buttons.first().closest('.header').next('.header'));
    enablePerson();

    buttons.each(function() {
      enableButton($(this));

    }).filter('[data-action="close"]').hide().next('.button').show();

    messageField.hide();

    form.removeClass('error');
    modal.modal('refresh');
  }


  function onSubmit() {
    var formData = getData(form);
    var isMessage = messageField.is(':visible');

    if (isMessage && !$.fn.form.settings.rules.minLength(formData.message, 10)) {
      error(['Message is too short.']);
      return false;
    }

    var enabledButtons = [];
    var ajaxData = {
      name: formData.name,
      email: formData.email,
      phone: formData.phone,
      location_id: formData.location_id,
      message: formData.message
    };

    enablePerson();

    if (isMessage) {
      enabledButtons.push(enableButton(buttons.filter('[data-action="send"]'), false));

    } else {
      ajaxData.message = '';
      enabledButtons.push(enableButton(buttons.filter('[data-action="notify"]'), false));
      enabledButtons.push(enableButton(buttons.filter('[data-action="mail"]'), false));
    }

    $.ajax({
      url: sprintf('/api/v1/classes/%s/contact', groupId),
      method: 'post',
      dataType: 'json',
      data: ajaxData

    }).done(function(r) {

      if (isMessage) {
        alert(__('Thanks, :Name! Your message is sent directly to the coach.', { name: formData.name }));
        messageField.hide();
        buttons.filter('[data-action="close"]').show().next('.button').hide();

      } else {
        alert('Your notification is sent. Thanks, :Name! You will be contacted by the coach shortly.', { name: formData.name });
        buttons.filter('[data-action="close"]').first().show().next('.button').hide();
      }

    }).fail(function(jqXHR) {
      var data = jqXHR.responseJSON;
      var errors = [__('Error sending a message to the coach. Please try again later.')];

      if (data && data.errors) {
        errors = [].concat($.map(Object.keys(data.errors), function(field) {
          return data.errors[field];
        }));
      }

      error(errors);

    }).always(function() {
      enabledButtons.forEach(enableButton);
      modal.modal('refresh');

    });

    return false;
  }


  function showPersonFields(opt_show) {
    return (opt_show === false) ? personFields.hide() : personFields.show();
  }


  function toggleHeader(header) {
    header.hide().siblings('.header').show();
  }


  function enableButton(button, opt_enable) {

    if (opt_enable === false) {
      return button.text(button.data('labelLoading')).attr('disabled', 'disabled');
    }

    return button.text(button.data('labelDefault')).removeAttr('disabled');
  }


  function enablePerson(opt_enable) {
    var children = comments.children('.comment');
    var comment = children.first();
    var data = getData();

    if (opt_enable === false) {
      comment.children('.comments').hide();
      comment.next('.comment').remove();

    } else if (children.length === 2 || !data.name || !data.phone && !data.email) {
        return;

    } else {
      var clone = comment.clone();

      clone.children('.avatar').replaceWith($('<i />').addClass('icon smile large avatar'));
      clone.find('.author').text(data.name).next('.metadata').text(data.phone || '').next('.text').text(data.email || '');
      clone.children('.comments').replaceWith($('<a />', {
        href: '#',
        style: 'position: absolute; top: 0.5rem; right: 0;',
        html: [$('<i />', {
          'class': 'icon write'
        }), $('<span />', {
          text: 'Edit'
        })],
        click: function() {
          enablePerson(false);
          return false;
        }
      }));

      comment.children('.comments').show();
      comments.append(clone);
    }

    opt_enable = typeof opt_enable !== 'undefined' ? opt_enable : true;
    showPersonFields(!opt_enable);
    modal.modal('refresh');
  }


  modal.children('form').form({
    title: {
      identifier: 'name',
      rules: [{
        type: 'empty',
        prompt: __('validation.required', { attribute: 'name' })
      }]
    },
    phone: {
      identifier: 'phone',
      rules: [{
        type: 'allEmpty[email]',
        prompt: __('validation.required', { attribute: 'phone number or email address' })
      }]
    },
    email: {
      identifier: 'email',
      optional: true,
      rules: [{
        type: 'email',
        prompt: __('validation.email', { attribute: 'email address' })
      }]
    }
  }, {
    onSuccess: onSubmit,
    onFailure: function(formErrors, fields) {
      modal.modal('refresh');
      return false;
    }

  }).find('.buttons .button').filter('[data-action="close"], [data-action="mail"]').on('click', function(e) {
    var button = $(this);
    var action = button.data('action');

    if (action === 'close') {
      modal.modal('hide');

    } else {
      toggleHeader(button.closest('.header'));
      messageField.show();
      enablePerson();
      alert();
    }

    modal.modal('refresh');
    e.preventDefault();
    return false;
  });


  modal.modal({
    onHidden: onModalClosed
  });


  $('[data-toggle="contact-modal"]').on('click', function(e) {
    $('#' + $(this).data('toggle')).modal('show');

    e.preventDefault();
    return false;
  });

}(jQuery));
