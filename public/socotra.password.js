(function($) {
  'use strict';


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
    }
  });

}(jQuery));
