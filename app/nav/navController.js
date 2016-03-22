angular.module('PiTher').controller('NavController', ['$scope', '$http', function($scope, $http) {
  $scope.currentUser = {
    id: 0,
    perms: {}
  };

  $scope.logout = function() {
    $http.get('api/logout').then(
      function successCallback(response) {
        $scope.refreshCurrentUser();
      }
    );
  };
  $scope.refreshCurrentUser = function() {
    $http.get('api/self').then(
      function successCallback(response) {
        $scope.currentUser = response.data;
      }
    );
  };
  $scope.hasPermission = function(perm) {
    return $scope.currentUser.perms.hasOwnProperty(perm);
  };
  $scope.refreshCurrentUser();
}]);
