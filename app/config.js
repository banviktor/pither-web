angular.module('PiTher').config(['$routeProvider', function ($routeProvider) {
  $routeProvider
    .when('/', {})
    .when('/login', {
      templateUrl: 'app/login/loginView.html',
      controller: 'LoginController',
      controllerAs: 'loginCtrl'
    })
    .when('/users/:id', {
      templateUrl: 'app/user/userView.html',
      controller: 'UserController',
      controllerAs: 'userCtrl'
    })
    .otherwise({
      redirectTo: '/'
    });
}]);
