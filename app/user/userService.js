angular.module('PiTher').factory('user', ['$rootScope', '$http', function ($rootScope, $http) {
  var service = {};

  service.user = service.anonymous = {
    id: 0,
    roles: {access_rules: true},
    perms: {anon: true}
  };

  service.isAnon = function () {
    return service.user.id == 0;
  };
  service.hasRole = function (role) {
    return service.user.roles.hasOwnProperty(role);
  };
  service.hasPermission = function (permission) {
    return service.user.perms.hasOwnProperty(permission);
  };
  service.logout = function () {
    $http.get('api/logout').then(
      function successCallback(response) {
        service.user = service.anonymous;
      }
    );
  };
  service.reload = function () {
    $http.get('api/self').then(
      function successCallback(response) {
        service.user = response.data.data;
      }
    );
  };

  $rootScope.user = function () {
    return service;
  };

  service.reload();
  return service;
}]);
