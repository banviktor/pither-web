angular.module('PiTher').controller('UserListController', ['$scope', '$routeParams', '$http', '$location', function ($scope, $routeParams, $http) {
  var controller = this;
  this.users = [];
  this.selected = {};
  this.allSelected = false;
  this.action = 'list';
  this.newUser = {
    unit: 'c'
  };

  this.deleteSelected = function () {
    var delUsers = [];
    for (var i = 0; i < controller.users.length; ++i) {
      var id = controller.users[i].id;
      if (controller.selected.hasOwnProperty(id) && controller.selected[id] == true) {
        delUsers.push(id);
      }
    }

    $http({
      method: 'DELETE',
      url: 'api/users',
      headers: {
        'Content-Type': 'application/json'
      },
      data: {ids: delUsers}
    }).then(
      function successCallback(response) {
        $scope.clearNotifications();
        if (response.data.success) {
          controller.refreshList();
        }
        else {
          $scope.notifications.danger = response.data.errors;
        }
      },
      function errorCallback(response) {
        $scope.notifications.danger = response.data.errors;
      }
    );
  };
  this.selectAll = function () {
    controller.selected = {};
    for (var i = 0; i < controller.users.length; ++i) {
      var id = controller.users[i].id;
      controller.selected[id] = controller.allSelected;
    }
  };
  this.refreshList = function () {
    $http.get('api/users').then(
      function successCallback(response) {
        $scope.clearNotifications();
        if (response.data.success) {
          controller.users = response.data.data;
          for (var i = 0; i < controller.users.length; ++i) {
            var id = controller.users[i].id;
            if (!controller.selected.hasOwnProperty(id)) {
              controller.selected[id] = false;
            }
          }
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
