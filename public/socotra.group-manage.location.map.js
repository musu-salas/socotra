(function($) {
  'use strict';


  var groupId = parseInt($('input[name="group_id"]').val(), 10);
  var mapContainer = $('#map');
  var mapLocation = mapContainer.data('location');
  var messageContainer = $('#message');
  var mapOptions = {
    ENABLED: {
      zoom: 17,
      disableDefaultUI: false,
      draggable: true,
      zoomControl: true,
      scrollwheel: false,
      mapTypeControl: true,
      disableDoubleClickZoom: false,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL
      },
      panControl: false,
      streetViewControl: false,
      styles: null
    },
    DISABLED: {
      zoom: 16,
      disableDefaultUI: true,
      draggable: false,
      zoomControl: false,
      scrollwheel: false,
      mapTypeControl: false,
      disableDoubleClickZoom: true,
      zoomControlOptions: null,
      panControl: false,
      streetViewControl: false,
      styles: [{
        stylers: [{ saturation: -100 }]
      }]
    }
  };


  function sprintf(text){
    var i=1, args=arguments;
    return text.replace(/%s/g, function(pattern){
      return (i < args.length) ? args[i++] : "";
    });
  }


  function setMessage(title, description, type) {
    messageContainer.find('.header').text(title).next().html(description);
    messageContainer.removeClass('warning success error hidden').addClass(type + ' visible');
  }


  function centerMap(latlng) {
    var map = mapContainer.prop('map');

    if (!map) {
      map = new google.maps.Map(mapContainer[0], mapOptions.ENABLED);
      mapContainer.prop('map', map);
    }

    map.setCenter(latlng);
    return map;
  }


  function enableMap(opt_enable) {
    var map = mapContainer.prop('map');

    map.setOptions(opt_enable === false ? mapOptions.DISABLED : mapOptions.ENABLED);
  }


  function buildLatLng(latlng) {

    if (typeof latlng === 'string') {
      var split = latlng.split(',');
      return new google.maps.LatLng(split[0], split[1]);
    }

    return latlng;
  }


  function saveMarker(latlng) {
    var position = latlng.latLng || latlng;
    var str = position.lat() + ',' + position.lng();

    if (mapLocation.latlng !== str) {
      $('input[name="latlng"]').val(str);
      setMessage('Your marker exact position is accepted', 'Hit <a id="save-button-trigger" href="./">"Save"</a> to save this position.', 'info');
    }
  }


  function addMarker(latlng) {
    var instance = buildLatLng(latlng);
    var map = centerMap(instance);

    enableMap();

    var marker = mapContainer.prop('marker');

    if (!marker) {
      marker = new google.maps.Marker({
        map: map,
        draggable: true
      });

      google.maps.event.addListener(marker, 'dragend', saveMarker);
      mapContainer.prop('marker', marker);
    }

    marker.setPosition(instance);
    saveMarker(instance);
  }


  function getAddressLatLng(addressStr, success, error) {
    var geocoder = mapContainer.prop('geocoder');

    if (!geocoder) {
      geocoder = new google.maps.Geocoder();
      mapContainer.prop('geocoder', geocoder);
    }

    geocoder.geocode({
      address: addressStr

    }, function(results, status) {

      if (status === google.maps.GeocoderStatus.OK) {
        success(results[0].geometry.location);

      } else {
        error();
      }
    });
  }


  function renderMap() {
    if (!mapContainer.height()) {
      mapContainer.css({
        maxHeight: $(window).height(),
        height: 400,
        borderTop: 'solid 1px #DCE0E0',
        borderBottom: 'solid 1px #DCE0E0'
      });
    }

    mapContainer.data('location', mapLocation);
    mapContainer.removeAttr('data-location');

    if (!mapLocation.latlng) {
      getAddressLatLng(mapLocation.address_line_1 + ', ' + mapLocation.zip + ' ' + mapLocation.city + ', ' + mapLocation.country.name, function(latlng) {
        addMarker(latlng);
        setMessage('Marker was set automatically', 'If it doesn\'t match exact position of your location, feel free to drag it.', 'warning');

      }, function() {
        getAddressLatLng(mapLocation.city + ', ' + mapLocation.country.name, function(latlng) {
          addMarker(latlng);
          setMessage('Automatic marker position is not accurate', 'Please drag the marker on your own to the desired location.', 'warning');

        }, function() {
          getAddressLatLng(mapLocation.country.name, function(latlng) {
            addMarker(latlng);
            setMessage('Automatic marker position is not accurate', 'Please drag the marker on your own to the desired location.', 'warning');

          }, function() {
            $.get('http://ipinfo.io/geo', function(geo) {
              addMarker(geo.loc);
              setMessage('It was not possible to set the marker automatically', 'Please drag the marker on your own to the desired location.', 'warning');
            }, 'jsonp');
          });
        });
      });

    } else if (mapLocation.latlng && mapLocation.address_line_1) {
      addMarker(mapLocation.latlng);

    } else {
      centerMap(buildLatLng(mapLocation.latlng));
      enableMap(false);
      setMessage('People shall be able to find your class location effortless', 'Please <a href="../" title="">add detailed address of the location</a> of your class before reviewing it on the map.', 'error');
    }
  }


  renderMap();


  $('form').on('click', '#save-button-trigger', function(e) {
    e.preventDefault();

    $('form').find('button[type="submit"]').trigger('click');
    return false;
  });


}(jQuery));
