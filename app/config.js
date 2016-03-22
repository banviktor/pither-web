angular.module('PiTher').config(['$routeProvider', function ($routeProvider) {
  $routeProvider
    .when('/', {})
    .when('/login', {
      templateUrl: 'app/login/loginView.html',
      controller: 'LoginController',
      controllerAs: 'loginCtrl'
    })
    .when('/users/:action', {
      templateUrl: 'app/user/userView.html',
      controller: 'UserController',
      controllerAs: 'userCtrl'
    })
    .when('/users/:action/:id', {
      templateUrl: 'app/user/userView.html',
      controller: 'UserController',
      controllerAs: 'userCtrl'
    })
    .otherwise({
      redirectTo: '/'
    });
}]);
