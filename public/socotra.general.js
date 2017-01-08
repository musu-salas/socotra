(function($) {
  'use strict';


  window.isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));


  $('[data-content]').popup();


  $('.ui.accordion').accordion();


  $('.ui.checkbox').checkbox();


  $('.ui.dropdown:not([data-scoped])').dropdown({
    onChange: function() {
      $(this).closest('.field').removeClass('error');
    }
  });


  $('.message .close').on('click', function() {
    $(this).closest('.message').remove();
  });

}(jQuery));
