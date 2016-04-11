angular.module('PiTher').controller('LoginController', ['$http', '$location', 'user', function ($http, $location, user) {
  var controller = this;
  this.formData = {};
  this.formError = '';

  this.login = function () {
    $http.post('api/login', this.formData).then(
      function successCallback(response) {
        if (response.data.success == true) {
          $location.path('/');
          user.reload();
        }
        else {
          controller.formData = {};
          controller.formError = response.data.errors[0];
        }
      });
  };
}]);
