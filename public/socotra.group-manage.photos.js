(function($) {
  'use strict';


  var groupId = parseInt($('input[name="group_id"]').val(), 10);
  var maxPhotos = parseInt($('input[name="max_photos"]').val(), 10);
  var errorsBlock = $('#errors');
  var successBlock = $('#success');
  var menuBlock = $('#menu');
  var statusBlock = menuBlock.next('#menu-status');
  var barBlock = $('#status-bar');
  var counterBlock = $('#counter');
  var uploadButton = $('#upload-button');


  function sprintf(text){
    var i=1, args=arguments;
    return text.replace(/%s/g, function(pattern){
      return (i < args.length) ? args[i++] : "";
    });
  }


  function disableUpload(opt_disable) {

    if (opt_disable === false) {
      return uploadButton.removeClass('disabled');
    }

    uploadButton.addClass('disabled');
  }


  function updateStatusBar(steps) {
    var templateIndex = (steps > 0) ? 0 : 1;
    var template = barBlock.children('span:eq(' + templateIndex + ')')[0].firstChild.nodeValue;
    var output = template;

    if (steps > 0) {
      output = sprintf(template, steps);
      barBlock.addClass('warning').removeClass('success');

    } else {
      barBlock.addClass('success').removeClass('warning');
    }

    barBlock.children('p').html(output).find('strong[data-label-plural]').each(function() {
      this.innerHTML = steps + ' ' + this.getAttribute('data-label' + ((steps > 1) ? '-plural' : ''));
    });
  }


  function updateMenu(data) {
    var active = menuBlock.find('.item.active');
    var href = active.attr('href');
    var slug = href.substring(href.lastIndexOf('/') + 1);

    if (data[slug] === true) {
      active.children('i').removeClass('plus red').addClass('checkmark green');

    } else if (data[slug] === false) {
      active.children('i').removeClass('checkmark green').addClass('plus red');
    }

    var steps = data['steps_to_complete'];
    var templateIndex = (steps > 0) ? 0 : 1;
    var template = statusBlock.children('span:eq(' + templateIndex + ')')[0].firstChild.nodeValue;
    var output = (steps > 0) ? sprintf(template, steps) : template;

    statusBlock.children('p').html(output).find('strong[data-label-plural]').each(function() {
      this.innerHTML = steps + ' ' + this.getAttribute('data-label' + ((steps > 1) ? '-plural' : ''));
    });
  }


  function countPhotos() {
    return $('input[name="photo_id"]').length;
  }


  function updateCounter() {
    var length = countPhotos();

    counterBlock.text(sprintf('%s of %s photos', length, maxPhotos));
  }


  function findPhotoId(container) {
    return parseInt(container.children('input[name="photo_id"]').val(), 10);
  }


  function deletePhoto(photoId, container) {
    container.dimmer('show');

    $.ajax({
      url: sprintf('/api/v1/classes/%s/photos/%s', groupId, photoId),
      method: 'DELETE'
    }).then(function() {
      var buttons = container.find('.button');

      if (buttons.filter('.yellow').length) {
        var sibling = container.siblings().first();
        sibling.length && setCoverPhoto(findPhotoId(sibling), sibling);
      }

      buttons.off('click');
      container.remove();
      errorsBlock.addClass('hidden');
      successBlock.text('Photo is removed.').removeClass('hidden');
      updateCounter();
      disableUpload(countPhotos() >= maxPhotos);

      return $.getJSON(sprintf('/api/v1/classes/%s/menu', groupId));
    }).done(function(menu) {
      updateMenu(menu);
      updateStatusBar(menu['steps_to_complete']);


    }).fail(function(jqXHR) {
      container.dimmer('hide');
      errorsBlock.find('.list').html('<li>Problem deleting your photo, please try again.</li>');
      errorsBlock.removeClass('hidden');
    });
  }

  function setCoverPhoto(photoId, container) {
    var prev = container.siblings().find('.button.yellow');
    var button = container.find('.button:eq(1)');

    prev.removeClass('yellow');
    button.addClass('yellow');
    successBlock.text('Page photo cover is updated.').removeClass('hidden');

    $.ajax({
      url: sprintf('/api/v1/classes/%s/cover_photo', groupId),
      data: {
        photo_id: photoId
      },
      method: 'PUT',
      error: function() {
        button.removeClass('yellow');
        prev.addClass('yellow');
        errorsBlock.find('.list').html('<li>Problem setting page cover photo, please try again.</li>');
        errorsBlock.removeClass('hidden');
      }
    });
  }


  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });


  $('input[type="file"]').on('change', function() {
    var form = $(this).closest('form').hide();
    var loading = form.next('.loading').show();

    successBlock.addClass('hidden');
    form.trigger('submit');
  });


  $('.hoverable').dimmer({
    closable: false

  }).find('.button').on('click', function() {
    var button = $(this);
    var container = button.closest('.hoverable');
    var photoId = findPhotoId(container);

    if (button.children('.trash').length) {
      deletePhoto(photoId, container);

    } else if (button.children('.star').length && !button.is('.yellow')) {
      setCoverPhoto(photoId, container);
    }
  });


  updateCounter();


}(jQuery));
