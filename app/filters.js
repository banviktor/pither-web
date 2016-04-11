angular.module('PiTher')
  .filter('ucfirst', function () {
    return function (input) {
      return input.charAt(0).toUpperCase() + input.substr(1);
    }
  })
  .filter('kelvin2', function () {
    return function(input, params) {
      var userUnit = params || 'c';

      kelvin = 0;
      switch (userUnit.toLowerCase()) {
        case 'k':
          kelvin = input;
          break;

        case 'c':
          kelvin = input - 273.15;
          break;

        case 'f':
          kelvin = input * 9 / 5 - 459.67;
          break;
      }
      return Math.round(kelvin * 10) / 10;
    }
  })
;
