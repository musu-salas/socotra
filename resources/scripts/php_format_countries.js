var countries = require('../../countries.json');
var util = require('util');
var fs = require('fs');

countries.forEach(function(country) {
  fs.appendFileSync('countries.txt',
      util.format('["alpha_2" => "%s", "name" => "%s"],\n', country.cca2, country.name.common));
});

