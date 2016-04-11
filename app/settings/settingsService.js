angular.module('PiTher').factory('settings', ['$rootScope', '$http', 'user', function ($rootScope, $http, user) {
  var service = {};

  service.data = {};

  service.reload = function () {
    $http.get('api/settings').then(
      function successCallback(response) {
        if (response.data.success) {
          service.data = response.data.data;
          user.anonymous.unit = service.data.default_unit;
        }
      }
    );
  };

  $rootScope.setting = service.get = function(setting) {
    if (service.data.hasOwnProperty(setting)) {
      return service.data[setting];
    }
    return undefined;
  };

  service.reload();
  return service;
}]);
