angular.module('PiTher').controller('ChartController', ['$scope', '$http', function ($scope, $http) {
  $scope.series = {
    v24h: ['Temperature', 'Target temperature'],
    vweek: ['Temperature', 'Target temperature'],
    vmonth: ['Temperature', 'Target temperature']
  };
  $scope.data = {
    v24h: [],
    vweek: [],
    vmonth: []
  };
  $scope.labels = {
    v24h: [],
    vweek: [],
    vmonth: []
  };
  $scope.view = '24h';
  $scope.options = {
    scaleOverride: true,
    scaleSteps: 13,
    scaleStepWidth: 1,
    scaleStartValue: 12
  };

  this.loadData = function(view) {
    $http.get('api/log/temp/' + view).then(
      function successCallback(response) {
        $scope.clearNotifications();
        if (response.data.success) {
          var data = response.data.data;
          var values = [];
          var cnt = 3;
          for (var prop in data) {
            if (data.hasOwnProperty(prop)) {
              if (++cnt == 4) {
                cnt = 0;
                $scope.labels['v' + view].push(prop);
              }
              else {
                $scope.labels['v' + view].push('');
              }
              values.push(data[prop]);
            }
          }
          $scope.data['v' + view].push(values);
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
    $http.get('api/log/target/' + view).then(
      function successCallback(response) {
        $scope.clearNotifications();
        if (response.data.success) {
          var data = response.data.data;
          var values = [];
          for (var prop in data) {
            if (data.hasOwnProperty(prop)) {
              values.push(data[prop]);
            }
          }
          $scope.data['v' + view].push(values);
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
  $scope.changeView = function(view) {
    $scope.view = view;
  };

  this.loadData('24h');
  this.loadData('week');
  this.loadData('month');
}]);
