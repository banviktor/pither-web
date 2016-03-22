angular.module('PiTher').controller('UserController', ['$scope', '$routeParams', '$http', '$location', function($scope, $routeParams, $http, $location) {
  var controller = this;

  this.user = {
    id: 0,
    name: '',
    email: '',
    pass: '',
    unit: 'c'
  };
  this.error = '';
  this.action = $routeParams.action;
  if ((this.action != 'add' && !$routeParams.hasOwnProperty('id')) || (['add', 'view', 'edit'].indexOf(this.action) < 0)) {
    $location.path('/');
    return;
  }
  if (this.action != 'add') {
    $http.get('api/users/' + $routeParams.id).then(
        function successCallback(response) {
          controller.user = response.data;
        },
        function errorCallback(response) {
          controller.error = response.statusText;
        }
    );
  }

  this.addUser = function(user) {
    delete user.id;
    $http.post('api/users', user).then(
      function successCallback(response) {
        if (response.data != false && response.data > 0) {
          $location.path('/users/view/' + response.data);
        }
      }
    );
  };
  this.editUser = function(user) {
    if (user.pass == '') {
      delete user.pass;
    }
    $http.put('api/users/' + user.id, user).then(
      function successCallback(response) {
        if (response.data == true) {
          $location.path('/users/view/' + user.id);
        }
        else {
          alert('fak');
        }
      }
    );
  };
  this.submit = function() {
    if (this.action == 'add') {
      this.addUser(this.user);
    }
    else if (this.action == 'edit') {
      this.editUser(this.user);
    }
  };
  this.canEditRoles = function() {
    return this.action != 'view' && $scope.hasPermission('manage_users');
  };
}]);
