angular.module('PiTher').controller('LoginController', ['$scope', '$http', '$location', function ($scope, $http, $location) {
  var controller = this;
  this.formData = {};
  this.formError = '';

  this.login = function () {
    $http.post('api/login', this.formData).then(
      function successCallback(response) {
        if (response.data == true) {
          $location.path('/');
          $scope.refreshCurrentUser();
        }
        else {
          controller.formData = {};
          controller.formError = "Invalid credentials. Try again!";
        }
      });
  };
}]);
