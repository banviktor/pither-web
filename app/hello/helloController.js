angular.module('PiTher')
.controller('HelloController', ['$http', '$routeParams', function($http, $routeParams) {
  this.who = "World";
  if ($routeParams.who !== null) {
    this.who = $routeParams.who;
  }
}]);
