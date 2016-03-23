angular.module('PiTher').controller('UserListController', ['$scope', '$routeParams', '$http', '$location', function ($scope, $routeParams, $http) {
  var controller = this;
  this.users = [];
  this.action = 'list';
  this.newUser = {
    unit: 'c'
  };

  this.refreshList = function () {
    $http.get('api/users').then(
      function successCallback(response) {
        controller.users = response.data.data;
      }
    );
  };
  this.addUser = function () {
    var user = this.newUser;
    if (user.pass != user.pass_confirm) {
      $scope.clearNotifications();
      $scope.notifications.danger = ['The two passwords are not the same.'];
      this.newUser.pass = '';
      this.newUser.pass_confirm = '';
      return;
    }
    $http.post('api/users', user).then(
      function successCallback(response) {
        if (response.data.success == true) {
          controller.action = 'list';
          $scope.clearNotifications();
          $scope.notifications.success = ['User successfully created.'];
          controller.newUser = {
            unit: 'c'
          };
          controller.refreshList();
        }
        else {
          $scope.notifications.danger = response.data.errors;
        }
      }
    );
  };

  this.refreshList();
}]);
