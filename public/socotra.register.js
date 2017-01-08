(function($) {
  'use strict';


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


  $('form').form({
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
      identifier: 'password',
      rules: [{
        type: 'length[6]',
        prompt : 'Password must be at least 6 characters.'
      }]
    },
    password_confirmation: {
      identifier: 'password_confirmation',
      rules: [{
        type: 'match[password]',
        prompt : 'Password and its confirmation doesn\'t match.'
      }]
    }
  }, {
    templates: {
      error: function(errors) {
        return $('form .error').find('ul').html($.map(errors, function(error) {
          return '<li>' + error + '</li>';

        }).join('')).closest('form .error').html();
      }
    },
    onSuccess: onFormSucceed
  });

}(jQuery));
