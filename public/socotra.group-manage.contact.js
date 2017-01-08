(function($) {
  'use strict';


  $('form').form().find('input[name="phone"]').on('focus blur', function(e) {
    var inputEl = $(this);

    if (e.type === 'focus') {
      if (!inputEl.data('placeholder')) {
        inputEl.data('placeholder', inputEl.attr('placeholder'));
      }

      inputEl.removeAttr('placeholder');

    } else {
      inputEl.attr('placeholder', inputEl.data('placeholder'));
    }
  });


}(jQuery));
