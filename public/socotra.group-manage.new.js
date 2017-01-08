(function($) {
  'use strict';


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
      input.closest('.field').removeClass('error');
    }

    google.maps.event.addListener(autocomplete, 'place_changed', onChanged);
  });


  $('input[name="currency_id"]').parent('.dropdown').dropdown();


  $('input[name="single_fee"]').on('input', function() {
    var input = $(this);
    input.val(input.val().trim().replace(/,/g, '.'));
  });


  $('form').form({
    creativeField1: {
      identifier: 'creative_field1',
      rules: [{
        type: 'empty'
      }]
    },
    location: {
      identifier: 'location',
      rules: [{
        type: 'empty'
      }]
    }
  }, {
    onSuccess: onFormSucceed
  });


}(jQuery));
