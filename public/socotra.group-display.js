(function($) {
  'use strict';


  var groupId = parseInt($('input[name="group_id"]').val(), 10);
  var locationId = parseInt($('input[name="location_id"]').val(), 10);


  function sprintf(text){
    var i=1, args=arguments;
    return text.replace(/%s/g, function(pattern){
      return (i < args.length) ? args[i++] : "";
    });
  }


  function toggleCoverDragging() {
    if ($(window).width() < 767) {
      $('#cancel-cover').trigger('click');
      $('#cover-controls').addClass('no-edit');

    } else {
      $('#cover-controls').removeClass('no-edit');
    }
  }


  function initCoverDragging(img) {
    $('#reposition-cover').on('click', function(e) {
      var parent = img.parent();
      var offset = parent.offset();

      img.draggable({
        axis: 'y',
        containment: [0, parent.height() - img.height() + offset.top, 0, offset.top],
        cursor: 'grab'
      });

      e.preventDefault();
      return false;
    });

    $('#save-cover').on('click', function(e) {
      var offset = parseInt(img[0].style.top, 10);
      var height = img.parent().height();
      var percentage = Math.round(offset / height * 100);

      img.draggable('destroy').attr('data-offset', percentage + '%');

      $.ajax({
        url: sprintf('/api/v1/classes/%s/cover_photo', groupId),
        data: {
          offset: percentage
        },
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
      });

      e.preventDefault();
      return false;
    });

    $('#cancel-cover').on('click', function(e) {

      if (img.draggable('instance')) {
        img.draggable('destroy').css('top', img.attr('data-offset') || 0);
      }

      e.preventDefault();
      return false;
    });
  }


  function setCover(opt_img) {
    var img = opt_img && opt_img.length ? opt_img : $('#cover');

    if (img.length) {
      var parent = img.parent();
      var imgHeight = img.width() * img.data('height') / img.data('width');
      var parHeight = parent.data('height');
      var imgOffset = parHeight * parseInt(img.attr('data-offset'), 10) / 100;

      if (Math.round(imgHeight + imgOffset) < parHeight) {
        img.css('top', 0);

      } else {
        img.css('top', img.attr('data-offset'));
      }

      if (imgHeight < parent.data('height')) {
        parent.css('height', 'auto');

      } else {
        parent.css('height', parent.data('height') + 'px');
      }
    }
  }


  function getMapModalContainer(opt_modal) {
    return (opt_modal && opt_modal.length ? opt_modal : $('#map-modal')).children('.content');
  }


  function setMapCenter() {
    var container = getMapModalContainer();
    var map = container.data('map');

    if (typeof map !== 'undefined') {
      map.setCenter(buildLatLng(container.data('latlng')));
    }
  }


  function setMapModalHeight() {
    getMapModalContainer().height(Math.round($(window).height() * 0.75));
  }


  function buildLatLng(latlng) {
    if (typeof latlng === 'string') {
      var split = latlng.split(',');
      return new google.maps.LatLng(split[0], split[1]);
    }

    return latlng;
  }


  function renderMap(container) {
    var data = container.data();

    if (typeof data.map !== 'undefined') {
      if (typeof data.info !== 'undefined') {
        data.info.open(data.map, data.marker);
      }

      return;
    }

    var latLng = buildLatLng(data.latlng);
    var options = {
      zoom: 16,
      center: latLng,
      scrollwheel: false,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL
      },
      panControl: false
    };

    if (!data.isFull) {
      $.extend(options, {
        zoom: 14,
        styles: [{
          stylers: [{ saturation: -100 }]
        }],
        disableDefaultUI: true,
        draggable: false,
        zoomControl: false,
        disableDoubleClickZoom: true,
        zoomControlOptions: null
      });
    }

    var map = new google.maps.Map(container[0], options);

    if (data.isFull) {
      var marker = new google.maps.Marker({
        position: latLng,
        map: map,
        title: data.address
      });

      var info = new google.maps.InfoWindow({
        content: $('<div />', {
          'html': [$('<address />', {
            'text':  data.address
          }).css({
            fontStyle: 'normal',
            fontWeight: 'bold'
          }), $('<p />', {
            'text': data.find
          })]
        })[0],
        maxWidth: 200
      });

      marker.addListener('click', function() {
        info.open(map, marker);
      });

      container.data({
        marker: marker,
        info: info
      });

      google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
        var containerData = $(this.getDiv()).data();

        containerData.info.open(this, containerData.marker);
      });
    }

    container.data('map', map);
  }


  $('.ui.dropdown.address').dropdown({
    onChange: function(value, text, $item) {

      if (value) {
        var path = window.location.pathname;

        path = path.substring(0, path.lastIndexOf('/'));
        window.location = path + '/' + value;
      }
    }
  });


  $('#cover').each(function() {
    var cover = $(this);
    var data = cover.data();
    var parent = cover.parent();

    var loaded = false;
    var timer = window.setTimeout(function() {
      if (!loaded) {
        parent.children('.loader').addClass('active');
        window.clearTimeout(timer);
      }
    }, 1000);

    cover.on('load', function() {
      initCoverDragging(cover);
      setCover(cover);
      toggleCoverDragging();
      cover.css('opacity', 1).off('load');
      parent.children('.loader').remove();

      loaded = true;
      window.clearTimeout(timer);

    }).attr('src', data.src);
  });


  $('#dimmer').each(function() {
    $(this).dimmer({
      duration: {
        hide: 0,
        show: 0
      }

    }).dimmer('show');
  });


  if ($('.pswp').length) {
    var galleryEl = $('.pswp');
    var galleryOptions = {
      index: 0,
      hideAnimationDuration: 0,
      showAnimationDuration: 0,
      opacity: 0,
      loop: false,
      pinchToClose: false,
      closeOnScroll: false,
      //closeOnVerticalDrag: false,
      history: false,
      fullscreenEl: false,
      shareEl: false
    };
    var gallery;

    $.getJSON(sprintf('/api/v1/classes/%s/photos', groupId), function(photos) {
      $('[data-gallery]').on('click', function(e) {
        var photoId = parseInt($(this).data('gallery'), 10);
        var index = 0;

        $.each(photos, function(i, photo) {
          if (photo.id === photoId) {
            index = i;
            return false;
          }
        });

        gallery = new PhotoSwipe(galleryEl[0], window.PhotoSwipeUI_Default, photos, galleryOptions);
        gallery.options.index = index;
        gallery.init();

        e.preventDefault();
        return false;
      });
    });
  }


  $('#map').each(function() {
    var container = $(this);
    var latlng = container.data('latlng');
    var width = container.width();
    var height = container.height();

    $.getJSON(sprintf('/api/v1/classes/%s/location/%s/map?width=%s&height=%s', groupId, locationId, width, height), function(location) {

      if (!location.map) {
        return;
      }

      var holder = container.children('#map-image');
      var styles = {
        'width': '100%',
        'min-height': holder.css('min-height') || 'auto'
      };
      
      var img = $('<img />', {
        'src': location.map,
        'alt': ''
      });

      if (container.data('isFull')) {
        styles['cursor'] = 'zoom-in';

        img.on('click', function() {
          setMapModalHeight();
          $('#map-modal').modal('show');
        });
      }

      img.css(styles);
      holder.replaceWith(img);
    });
  });


  $('#map-modal').modal({
    onVisible: function() {
      renderMap(getMapModalContainer($(this)));
    },
    onHidden: function() {}
  });


  $('#description').each(function() {
    var container = $(this);
    var height = container.height();
    var text = container.children('p');

    if (text.height() > height) {
      container.append($('<a />', {
        href: '#',
        text: '+ Read more',
        click: function() {
          $(this).remove();
          container.removeClass('collapsed');
          return false;
        }
      }));

    } else {
      container.removeClass('collapsed');
    }
  });


  $(window).on('resize', function() {
    setMapModalHeight();
    setMapCenter();
    setCover();
    toggleCoverDragging();
  });

}(jQuery));
