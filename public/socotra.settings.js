(function($) {
  'use strict';


  var userId = parseInt($('input[name="user_id"]').val(), 10);
  var avatarModal = $('#avatar-modal');

  var AVATAR_CROPPER_SIDE = 300;


  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });


  function sprintf(text){
    var i=1, args=arguments;
    return text.replace(/%s/g, function(pattern){
      return (i < args.length) ? args[i++] : "";
    });
  }


  function mapToStr(map) {
    return Object.keys(map).map(function(key) {
      return key + '=' + map[key];

    }).join(',');
  }


  function popup(url, title, width, height) {
    return window.open(url, title, mapToStr({
      toolbar: 'no',
      location: 'yes',
      directories: 'no',
      status: 'no',
      menubar: 'no',
      scrollbars: 'yes',
      resizable: 'no',
      copyhistory: 'no',
      width: width,
      height: height,
      left: ($(window).width() / 2) - (width / 2),
      top: ($(window).height() / 2) - (height / 2)
    }));
  }


  !window.isMobile && $('a[href*="/socialize/facebook"]').on('click', function(e) {
    var button = $(this);
    window.popup = popup(button.attr('href'), button.attr('title'), 530, 500);

    e.preventDefault();
    return false;
  });


  function formatLocation(place) {
    var countries = $.grep(place.address_components, function(component) {
      return component.types && component.types[0] === 'country';
    });

    var cities = $.grep(place.address_components, function(component) {
      return component.types && component.types[0] === 'locality';
    });

    var states = $.grep(place.address_components, function(component) {
      return component.types && /[A-Z]{2,3}/.test(component.short_name) &&
          component.types[0] === 'administrative_area_level_1';
    });

    var country_name = countries.length && countries[0].long_name || '';
    var country_alpha2 = country_name && countries[0].short_name || '';
    var country = country_alpha2 && country_name ? country_alpha2 + '|' + country_name : '';
    var city = cities.length && cities[0].long_name || '';
    var state = states.length && states[0].short_name || '';
    var geometry = place.geometry && place.geometry.location;
    var latLng = geometry && [geometry.lat(), geometry.lng()].join() || '';

    return {
      city: city,
      state: state,
      country: country,
      latlng: latLng
    };
  }


  function onFormSucceed() {
    var pac = $('body > .pac-container');
    if (pac.length && pac.is(':visible')) {
      return false;
    }
  }


  window.google && google.maps && $('input[name="location"]').each(function () {
    var input = $(this).next('input[type="text"]');
    var autocomplete = new google.maps.places.Autocomplete(input[0], {
      types: [input.data('types')],
      language: 'en'
    });

    function onChanged() {
      var place = formatLocation(autocomplete.getPlace());
      input.prev().val(JSON.stringify(place));
    }

    google.maps.event.addListener(autocomplete, 'place_changed', onChanged);
  });


  function onAvatarUploadFailed(errorResponse) {
    var img = $('#avatar');
    var segment = img.closest('.segment');
    var container = segment.children('.ui.error');

    enableAvatarLoading(false);

    if (!errorResponse || !errorResponse.errors) {
      return;
    }

    if (!container.length) {
      container = $('<div />', {
        'class': 'ui error message visible',
        'html': $('<div />', {
          'class': 'content',
          'html': $('<ul />', {
            'class': 'list'
          })
        })
      }).appendTo(segment);
    }

    container.find('ul').html($.map(errorResponse.errors, function(errors) {
      return $.map(errors, function(error) {
        return $('<li />', {
          'text': error
        })
      });
    }));
  }

  function onAvatarUploadDone(url) {
    setAvatarSrc(url);

    if (window.isMobile) {
      destroyCache();
      enableAvatarLoading(false);

    } else {
      showAvatarModal(url);
    }
  }


  function onAvatarCropDone(url) {
    setAvatarSrc(url);

    window.setTimeout(function() {
      enableAvatarLoading(false);
    }, 1000);
  }


  function setAvatarSrc(src) {
    $('#avatar').children('img').attr('src', src);
  }


  function isAvatarLoading() {
    return $('#avatar').is('.loading');
  }


  function enableAvatarLoading(opt_enable) {
    var img = $('#avatar');

    if (opt_enable === false) {
      return img.removeClass('loading');
    }

    img.addClass('loading');
  }


  function destroyCache() {
    $.ajax(sprintf('/api/v1/users/%s/avatar', userId), {
      type: 'DELETE',
      cache: false
    });
  }


  function destroyCropper() {
    $(document).unbind('mousemove touchmove mouseup touchend');

    avatarModal.removeProp('cropper');
    avatarModal.find('#avatar-modal-cropper').parent().remove();
    avatarModal.children('.content').append($('<div />', {
      'class': 'cropper',
      'html': $('<div />', {
        'id': 'avatar-modal-cropper',
        'class': 'cropper-container'
      })
    }));
  }


  function showAvatarModal(src) {
    var cropper = avatarModal.prop('cropper');

    if (!cropper) {
      cropper = new window.CROP();

      cropper.init({
        container: '#avatar-modal-cropper',
        image: src,
        width: AVATAR_CROPPER_SIDE,
        height: AVATAR_CROPPER_SIDE,
        zoom: {
          steps: 0.1,
          min: 1,
          max: 5
        }
      });

      avatarModal.prop('cropper', cropper);
    }

    avatarModal.modal('show');
  }


  if (location.search.substring(1)) {
    var urlParams = JSON.parse('{"' + decodeURI(location.search.substring(1)).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');

    if (urlParams.hasOwnProperty('focus')) {
      $('input[name="' + urlParams.focus + '"]').focus();
    }
  }


  $('#avatar-change-link').on('click', function(e) {
    e.preventDefault();
    $('#avatar').trigger('click');
  });


  $('#avatar').on('click', function() {
    if (!isAvatarLoading()) {
      $('form#avatar-form').find('input[type="file"]').trigger('click');
    }
  });


  $('form#avatar-form').on('submit', function(e) {
    var form = $(this);

    e.preventDefault();

    enableAvatarLoading();

    $.ajax({
      url: sprintf('/api/v1/users/%s/avatar', userId),
      type: 'POST',
      data: new FormData(form[0]),
      cache: false,
      contentType: false,
      processData: false
    }).done(onAvatarUploadDone).fail(function(jqXhr) {
      onAvatarUploadFailed(jqXhr.responseJSON || {
        errors: [form.data('labelError')]
      });
    });

  }).children('input[type="file"]').on('change', function() {
    $(this).parent('form').trigger('submit');
  });


  $('form:not(#avatar-form)').form({
    firstName: {
      identifier: 'first_name',
      rules: [{
        type: 'empty',
        prompt : 'First name shan\'t be empty.'
      }]
    },
    lastName: {
      identifier: 'last_name',
      rules: [{
        type: 'empty',
        prompt : 'Last name shan\'t be empty.'
      }]
    },
    email: {
      identifier: 'email',
      rules: [{
        type: 'email',
        prompt : 'Please fix your email address.'
      }]
    },
    password: {
      identifier: 'new_password',
      optional: true,
      rules: [{
        type: 'length[6]',
        prompt : 'New password must be at least 6 characters.'
      }]
    },
    password_confirmation: {
      identifier: 'new_password_confirmation',
      rules: [{
        type: 'match[new_password]',
        prompt : 'New password and its confirmation doesn\'t match.'
      }]
    }
  }, {
    templates: {
      error: function(errors) {
        return $('form:not(#avatar-form) .error').find('ul').html($.map(errors, function(error) {
          return '<li>' + error + '</li>';

        }).join('')).closest('form:not(#avatar-form) .error').html();
      }
    },
    onSuccess: onFormSucceed
  });


  avatarModal.modal({
    closable: false,
    onApprove: function() {
      var cropper = avatarModal.prop('cropper');

      $.ajax({
        url: sprintf('/api/v1/users/%s/avatar', userId),
        type: 'PUT',
        data: cropper.crop(AVATAR_CROPPER_SIDE, AVATAR_CROPPER_SIDE, 'jpeg'),
        cache: false

      }).done(onAvatarCropDone).fail(function(jqXhr) {
        onAvatarUploadFailed(jqXhr.responseJSON || {
          errors: [$('#avatar-form').data('labelError')]
        });
      });
    },
    onDeny: function() {
      enableAvatarLoading(false);
      destroyCache();
    },
    onHidden: destroyCropper
  });

}(jQuery));
