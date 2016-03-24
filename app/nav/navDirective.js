angular.module('PiTher').directive('navigation', function () {
  return {
    restrict: 'E',
    templateUrl: 'app/nav/navView.html',
    controller: 'NavController'
  };
});
