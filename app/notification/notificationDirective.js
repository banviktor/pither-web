angular.module('PiTher').directive('notification', function () {
  return {
    restrict: 'E',
    templateUrl: 'app/notification/notificationView.html',
    controller: 'NotificationController'
  };
});
