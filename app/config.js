angular.module('PiTher').config(['$routeProvider', function ($routeProvider) {
  $routeProvider
    .when('/', {})
    .when('/login', {
      templateUrl: 'app/login/loginView.html',
      controller: 'LoginController',
      controllerAs: 'loginCtrl'
    })
    .otherwise({
      redirectTo: '/'
    });
}]);
