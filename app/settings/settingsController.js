angular.module('PiTher').controller('SettingsController', ['$scope', '$routeParams', '$http', '$location', function ($scope, $routeParams, $http) {
  var controller = this;
  this.settings = {};

  this.load = function() {
    $http.get('api/settings').then(
      function successCallback(response) {
        $scope.clearNotifications();
        if (response.data.success) {
          controller.settings = response.data.data;
        }
        else {
          $scope.notifications.danger = response.data.errors;
        }
      },
      function errorCallback(response) {
        $scope.clearNotifications();
        $scope.notifications.danger = response.data.errors;
      }
    );
  };
  this.submit = function() {
    $http.put('api/settings', {settings: this.settings}).then(
      function successCallback(response) {
        $scope.clearNotifications();
        if (response.data.success) {
          $scope.notifications.success = ['Settings successfully saved.'];
        }
        else {
          $scope.notifications.danger = response.data.errors;
        }
      },
      function errorCallback(response) {
        $scope.clearNotifications();
        $scope.notifications.danger = response.data.errors;
      }
    );
  };
  this.load();
}]);
