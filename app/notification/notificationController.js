angular.module('PiTher').controller('NotificationController', ['$scope', function ($scope) {
  $scope.notifications = {
    success: [],
    info: [],
    warning: [],
    danger: []
  };

  $scope.clearNotifications = function () {
    $scope.notifications = {
      success: [],
      info: [],
      warning: [],
      danger: []
    };
  }
}]);
