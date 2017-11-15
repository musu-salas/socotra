(function($) {
  'use strict';


  window.isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));


  $().popup && $('[data-content]').popup();

  $().accordion && $('.ui.accordion').accordion();

  $().checkbox && $('.ui.checkbox').checkbox();


  $().dropdown && $('.ui.dropdown:not([data-scoped])').dropdown({
    onChange: function() {
      $(this).closest('.field').removeClass('error');
    }
  });


  $('.message .close').on('click', function() {
    $(this).closest('.message').remove();
  });


  $('#country-switcher').on('change', function() {
    var $country = $(this);
    var $locale = $('#locale-switcher');
    var country = $(this).val();
    var currentLocale = $locale.data('current');
    var currentDefaultLocale = $locale.data('default');
    var hasLocaleSegment = currentLocale !== currentDefaultLocale;
    var href = window.location.href;
    var segments = window.location.host.split('.');

    segments.splice(0, 1, country);

    var host = segments.join('.');
    var nextHref = href.replace(new RegExp(window.location.host), host);

    window.location = nextHref;
  });

  $('#locale-switcher').on('change', function() {
    var $locale = $(this);
    var nextLocale = $locale.val();
    var currentLocale = $locale.data('current');
    var currentDefaultLocale = $locale.data('default');
    var hasLocaleSegment = currentLocale !== currentDefaultLocale;
    var href = window.location.href;
    var host = window.location.host;
    var currentHost = host;
    var nextHost = host + '/' + nextLocale;
    var nextHref = href.replace(new RegExp(currentHost), nextHost);

    if (hasLocaleSegment) {
      currentHost = host + '/' + currentLocale;
      nextHref = href.replace(new RegExp(currentHost), nextHost);
    }

    window.location = nextHref;
  });

}(jQuery));
