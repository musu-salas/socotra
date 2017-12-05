(function(locale) {
  Lang.prototype._getMessage = function(key) {
    return window.i18n[key] || key;
  };

  Lang.prototype.has = function() {
      return true;
  };

  var lang = new Lang({ locale: locale });

  window.__ = lang.trans.bind(lang);
  window._n = lang.transChoice.bind(lang);

}(document.documentElement.lang));
