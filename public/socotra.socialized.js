(function() {
  'use strict';


  function url(w) {
    var search = window.location.search.substring(1);
    var params = JSON.parse('{"' + decodeURI(search).replace(/"/g, '\\"').
            replace(/&/g, '","').replace(/=/g,'":"') + '"}');

    var query = params.error ? '?error=' + params.error : '';

    if (w.location.pathname === '/socialize/facebook') {
      return '/login' + query;
    }

    return w.location.pathname + query;
  }


  if (window.opener && window.opener.popup) {
    window.opener.location.replace(url(window.opener));
    window.opener.popup.close();

  } else {
    window.location.replace(url(window));
  }

}());
