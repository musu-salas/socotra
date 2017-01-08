(function($) {

  var pioneerModal = $('#pioneer-modal');


  var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
        'August', 'September', 'October', 'November', 'December'];


  function sprintf(text){
    var i=1, args=arguments;
    return text.replace(/%s/g, function(pattern){
      return (i < args.length) ? args[i++] : "";
    });
  }


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


  /**
   * @param {false=} opt_unset
   */
  function setFormLoading(opt_unset) {

    if (opt_unset === false) {
      $('form').
          prop('loading', false).
          find('input[name="email"] + .icon').
          removeClass('asterisk loading').
          addClass('share');

    } else {
      $('form').
          prop('loading', true).
          find('input[name="email"] + .icon').
          removeClass('share').
          addClass('asterisk loading');
    }
  }


  function savePioneer() {
    var form = $(this);

    if (form.prop('loading') === true) {
      return false;
    }

    setFormLoading();

    $.ajax({
      url: '/api/v1/pioneer',
      method: 'POST',
      data: form.form('get values'),
      complete: function() {
        setFormLoading(false);
      },
      error: function(jqXHR) {
        var data = jqXHR.responseJSON;
        var errors = ['There was an error signing you in. Please try again later.'];

        if (data && data.errors) {
          var fields = Object.keys(data.errors);

          errors = [].concat($.map(fields, function(field) {
            return data.errors[field];
          }));

          $.each(fields, function(index, field) {
            form.find(sprintf('input[name="%s"]', field)).
                closest('.field').addClass('error');
          });
        }

        form.form('add errors', errors).addClass('error');
      },
      success: function(data, textStatus, jqXHR) {
        var standardText = 'We are going to keep you updated with cool things we make here preparing to launch in your country.';
        var pioneerName = data.first_name.substring(0, 1).toUpperCase() + data.first_name.substring(1);

        if (jqXHR.status === 208) {
          var date = new Date(data.created_at);
          var month = MONTHS[date.getMonth()];
          var day = date.getDate();
          var year = date.getFullYear();
          var text = '<p>You have already signed up on %s %s, %s</p><p>%s</p>';

          pioneerModal.
              children('.header').
              html(sprintf('%s, thanks for stopping by again<em>!</em><br />' +
                'And no worries, we remember you.', pioneerName)).

              next('.content').
              html(sprintf(text, month, day, year, standardText));

        } else {
          pioneerModal.
              children('.header').
              html(sprintf('%s, you are in. Thank you<em>!</em>', pioneerName)).
              next('.content').
              html(sprintf('<p>%s</p>', standardText));
        }

        pioneerModal.modal('show');
      }
    });

    return false;
  }


  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });


  $('form').form({
    location: {
      identifier: 'location',
      rules: [{
        type: 'empty',
        prompt : 'City shan\'t be empty.'
      }]
    },
    creativeField1: {
      identifier: 'creative_field1',
      rules: [{
        type: 'empty',
        prompt : 'Creative field shan\'t be empty.'
      }]
    },
    first_name: {
      identifier: 'first_name',
      rules: [{
        type: 'empty',
        prompt: 'First name shan\'t be empty.'
      }]
    },
    last_name: {
      identifier: 'last_name',
      rules: [{
        type: 'empty',
        prompt: 'Last name shan\'t be empty.'
      }]
    },
    email: {
      identifier: 'email',
      rules: [{
        type: 'email',
        prompt: 'Incorrect email format.'
      }]
    }
  }, {
    onSuccess: savePioneer
  });


  window.google && google.maps && $('input[name="location"]').each(function () {
    var input = $(this).next('input[type="text"]');
    var autocomplete = new google.maps.places.Autocomplete(input[0], {
      types: [input.data('types')],
      language: 'en'
    });

    function onChanged() {
      var place = formatLocation(autocomplete.getPlace());
      input.prev().val(JSON.stringify(place));
      input.closest('.field').removeClass('error');
    }

    google.maps.event.addListener(autocomplete, 'place_changed', onChanged);
  });


  pioneerModal.modal({
    closable: false
  });

}(jQuery));
