angular.module('PiTher').filter('ucfirst', function () {
  return function (input) {
    return input.charAt(0).toUpperCase() + input.substr(1);
  }
});
