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
      location: 'no',
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


  if (!window.isMobile) {
    $('a[href*="/socialize/facebook"]').on('click', function(e) {
      var button = $(this);
      window.popup = popup(button.attr('href'), button.attr('title'), 530, 500);

      e.preventDefault();
      return false;
    });
  }


  $('form').form({
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
    }
  }, {
    templates: {
      error: function(errors) {
        return $('form .error').find('ul').html($.map(errors, function(error) {
          return '<li>' + error + '</li>';

        }).join('')).closest('form .error').html();
      }
    }
  });

}(jQuery));
