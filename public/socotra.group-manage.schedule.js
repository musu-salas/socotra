(function($) {
  'use strict';

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
  });


  $.fn.form.settings.rules.isTime = function(value) {
    return /^(\d{1,2}:\d{2})$/.test(value);
  };


  var groupId = parseInt($('input[name="group_id"]').val(), 10);
  var locationId = parseInt($('input[name="location_id"]').val(), 10);


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
            window.location = sprintf('/home/classes/%s/schedule/%s', groupId, locationId);
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


  $('.ui.dropdown[data-scoped="time"]').dropdown({
    onChange: function(text, value) {
      $(this).dropdown('clear').siblings('input').val(value);
    }

  }).next('input').on('focus', function() {
    var input = $(this);
    var value = input.val().trim();
    var dropdown = input.prev('.dropdown');
    var menu = dropdown.find('.menu');
    var items = menu.children('.item');
    var selected;

    items.each(function() {
      if ((this.innerText || this.innerHTML) > value) {
        selected = $(this).prev('.item');
        return false;
      }
    });

    dropdown.dropdown('show');

    if (selected.length) {
      var index = items.index(selected);
      var height = selected.outerHeight();
      var scrollTop = (index - 2) * height;

      menu.scrollTop(scrollTop >= 0 ? scrollTop : 0);
      selected.addClass('selected');

    } else {
      menu.scrollTop(items.first().outerHeight() * 20);
    }

  }).on('keyup', function() {
    $(this).prev('.dropdown').dropdown('hide').dropdown('clear').find('.menu').scrollTop(0);

  }).next('.icon').on('click', function() {
    $(this).prev('input').trigger('focus');
  });


  $('form').form({
    title: {
      identifier: 'title',
      rules: [{
        type: 'empty',
        prompt: 'Title shan\'t be empty.'
      }, {
        type: 'maxLength[50]',
        prompt: 'Title shan\'t exceed 50 characters.'
      }]
    },
    description: {
      identifier: 'description',
      rules: [{
        type: 'maxLength[255]',
        prompt: 'Description shan\'t exceed 255 characters.'
      }]
    },
    starts: {
      identifier: 'starts',
      rules: [{
        type: 'isTime',
        prompt: 'Session start time shall be in HH:MM format (e.g. 18:30).'
      }]
    },
    ends: {
      identifier: 'ends',
      rules: [{
        type: 'isTime',
        prompt: 'Session end time shall be in HH:MM format (e.g. 20:00).'
      }]
    }
  });


  $('.ui.dropdown[data-scoped="location"]').dropdown({
    onChange: function(value, text, $item) {

      if (value) {
        var path = window.location.pathname;

        path = path.substring(0, path.lastIndexOf('/'));
        window.location = path + '/' + value;
      }
    }
  });


}(jQuery));
