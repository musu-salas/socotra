(function(locale) {
  Lang.prototype._getMessage = function(key) {
    return window.i18n[key] || key;
  };

  Lang.prototype.has = function() {
      return true;
  };

  Lang.prototype._isUpperCase = function(text) {
      return text === text.toUpperCase();
  };

  Lang.prototype._isFirstCapital = function(text) {
    return this._isUpperCase(text.charAt(0));
  };

  Lang.prototype._capitalize = function(text) {
    return text.charAt(0).toUpperCase() + text.substr(1);
  };

  Lang.prototype._applyReplacements = function(text, replacements) {
    var self = this;
    var replacements = replacements || {};
    var keys = Object.keys(replacements);

    keys.sort(function(a, b) {
      return b.length - a.length;
    });

    keys.forEach(function(key) {
      text = text.replace(new RegExp(':' + key, 'gi'), function(match) {
        var value = replacements[key];
        var placeholder = match.substr(1);

        if (self._isUpperCase(placeholder)) {
            return value.toUpperCase();
        }

        if (self._isFirstCapital(placeholder)) {
            return self._capitalize(value);
        }

        return value;
      });
    });

    return text;
  };

  var lang = new Lang({ locale: locale });

  window.__ = lang.trans.bind(lang);
  window._n = lang.transChoice.bind(lang);

}(document.documentElement.lang));
