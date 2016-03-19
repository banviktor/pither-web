angular.module('PiTher').config(['$routeProvider', function ($routeProvider) {
  $routeProvider
  .when('/', {

  })
  .when('/hello/:who', {
    templateUrl: 'app/hello/helloView.html',
    controller: 'HelloController',
    controllerAs: 'helloCtrl'
  })
  .otherwise({
    redirectTo: '/'
  });
}]);
