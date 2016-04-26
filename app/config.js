angular.module('PiTher').config(['$routeProvider', function ($routeProvider) {
  $routeProvider
    .when('/', {
      templateUrl: 'app/home/homeView.html',
      controller: 'HomeController',
      controllerAs: 'homeCtrl'
    })
    .when('/login', {
      templateUrl: 'app/login/loginView.html',
      controller: 'LoginController',
      controllerAs: 'loginCtrl'
    })
    .when('/users', {
      templateUrl: 'app/user/userListView.html',
      controller: 'UserListController',
      controllerAs: 'userListCtrl'
    })
    .when('/users/:id', {
      templateUrl: 'app/user/userView.html',
      controller: 'UserController',
      controllerAs: 'userCtrl'
    })
    .when('/settings', {
      templateUrl: 'app/settings/settingsView.html',
      controller: 'SettingsController',
      controllerAs: 'settingsCtrl'
    })
    .when('/rules', {
      templateUrl: 'app/rules/rulesView.html',
      controller: 'RulesController',
      controllerAs: 'rulesCtrl'
    })
    .when('/charts', {
      templateUrl: 'app/chart/chartView.html',
      controller: 'ChartController',
      controllerAs: 'chartCtrl'
    })
    .otherwise({
      redirectTo: '/'
    });
}]);
