angular.module('PiTher').controller('UserController', ['$scope', '$routeParams', '$http', '$location', function ($scope, $routeParams, $http) {
  var controller = this;

  this.user = {
    id: 0,
    name: '',
    email: '',
    pass: '',
    unit: 'c'
  };
  this.error = '';
  this.action = 'view';

  $http.get('api/users/' + $routeParams.id).then(
    function successCallback(response) {
      $scope.clearNotifications();
      if (response.data.success) {
        controller.user = response.data.data;
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
  this.editUser = function () {
    var user = this.user;

    if (user.pass == '') {
      delete user.pass;
    }
    else if (user.pass != user.pass_confirm) {
      $scope.clearNotifications();
      $scope.notifications.danger = ['The two passwords are not the same.'];
      this.user.pass = '';
      this.user.pass_confirm = '';
      return;
    }
    $http.put('api/users/' + user.id, user).then(
      function successCallback(response) {
        if (response.data.success == true) {
          controller.action = 'view';
          $scope.clearNotifications();
          $scope.notifications.success = ['The modifications were successful.'];
          delete controller.user.pass;
        }
        else {
          $scope.notifications.danger = response.data.errors;
        }
      }
    );
  };
  this.canEditRoles = function () {
    return this.action != 'view' && $scope.hasPermission('manage_users');
  };
}]);
