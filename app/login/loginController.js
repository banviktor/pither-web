angular.module('PiTher').controller('LoginController', ['$scope', '$http', '$location', function($scope, $http, $location) {
    $scope.formData = {};
    $scope.errorMsg = "";
    $scope.login = function() {
        $http.post('api/login', $scope.formData).success(function(data) {
            if (data == true) {
                $location.path('/');
            }
            else {
                $scope.formData = {};
                $scope.errorMsg = "Invalid credentials. Try again!";
            }
        });
    };
    $scope.clearError = function() {
        $scope.errorMsg = "";
        alert ("wat");
    };
}]);
