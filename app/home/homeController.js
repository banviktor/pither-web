angular.module('PiTher').controller('HomeController', ['$scope', '$http', 'uiCalendarConfig', function ($scope, $http, uiCalendarConfig) {
  var controller = this;
  this.calendar = function() {
    return uiCalendarConfig.calendars.calendar;
  };
  $scope.rules = {
    className: 'event-rule',
    events: [],
    color: 'orange',
    textColor: 'white',
    editable: false
  };
  $scope.overrides = {
    className: 'event-override',
    events: [],
    color: 'red',
    textColor: 'white',
    editable: false
  };
  $scope.calendarConfig = {
    editable: true,
    header: {
      left: '',
      center: '',
      right: ''
    },
    defaultDate: '2016-02-01',
    defaultView: 'agendaWeek',
    timeFormat: 'H:mm',
    firstDay: 1,
    views: {
      agenda: {
        allDaySlot: false,
        slotDuration: '01:00:00',
        slotLabelFormat: 'H:mm',
        slotLabelInterval: '02:00:00',
        snapDuration: '00:15:00',
        columnFormat: 'ddd'
      }
    },
    eventConstraint: {
      start: '00:00',
      end: '24:00'
    },
    eventRender: function (event, element) {
      element.dblclick(function () {
        if (event.id !== undefined) {
          controller.calendar().fullCalendar('removeEvents', event._id);
          controller.deleteOverride(event.id);
        }
      });
    }
  };
  $scope.eventSources = [$scope.rules, $scope.overrides];
  $scope.quickOverride = {
    temp: 21.5,
    after: '0',
    duration: 3
  };
  var now = new Date();
  now.setUTCSeconds(0);
  now.setUTCMilliseconds(0);
  $scope.plannedOverride = {
    temp: 21.5,
    start: new Date(Math.round(now.getTime())),
    end: new Date(Math.round(now.getTime() + 3 * 3600 * 1000))
  };

  controller.ruleToEvent = function (model) {
    var start_orig = model.start.split(':');
    var start = moment('2016-02-01 01:00:00')
      .add(model.day - 1, 'days')
      .add(start_orig[0], 'hours')
      .add(start_orig[1], 'minutes');

    var end_orig = model.end.split(':');
    var end = moment('2016-02-01 01:00:00')
      .add(model.day - 1, 'days')
      .add(end_orig[0], 'hours')
      .add(end_orig[1], 'minutes');

    return {
      id: undefined,
      start: start.toISOString(),
      end: end.toISOString(),
      title: model.temp
    };
  };
  controller.overrideToEvent = function(model) {
    var offset = moment().startOf('isoweek').add(2, 'hours').unix() - moment('2016-02-01 00:00:00').unix();
    return {
      id: model.id,
      start: moment.unix(model.start - offset),
      end: moment.unix(model.end - offset),
      title: model.temp
    }
  };
  controller.loadRules = function () {
    $http.get('api/rules').then(
      function successCallback(response) {
        if (response.data.success) {
          for (var i = 0; i < response.data.data.length; ++i) {
            $scope.rules.events.push(controller.ruleToEvent(response.data.data[i]));
          }
        } else {
          $scope.clearNotifications();
          $scope.notifications.danger = response.data.errors;
        }
      },
      function errorCallback(response) {
        $scope.clearNotifications();
        $scope.notifications.danger = response.data.errors;
      }
    );
  };
  controller.loadOverrides = function () {
    $http.get('api/overrides').then(
      function successCallback(response) {
        if (response.data.success) {
          for (var i = 0; i < response.data.data.length; ++i) {
            $scope.overrides.events.push(controller.overrideToEvent(response.data.data[i]));
          }
        } else {
          $scope.clearNotifications();
          $scope.notifications.danger = response.data.errors;
        }
      },
      function errorCallback(response) {
        $scope.clearNotifications();
        $scope.notifications.danger = response.data.errors;
      }
    );
  };
  controller.refresh = function () {
    $scope.rules.events = [];
    $scope.overrides.events = [];
    $scope.eventSources = [$scope.rules, $scope.overrides];
    controller.loadOverrides();
    controller.loadRules();
  };
  controller.deleteOverride = function (id) {
    $http({
      method: 'delete',
      url: 'api/overrides/' + id
    }).then(
        function successCallback(response) {
          if (!response.data.success) {
            $scope.clearNotifications();
            $scope.notifications.danger = response.data.errors;
          }
        },
        function errorCallback(response) {
          $scope.clearNotifications();
          $scope.notifications.danger = response.data.errors;
        }
    );
  };
  $scope.addQuickOverride = function () {
    var offset = 2*60*60;
    var start = moment().unix() + $scope.quickOverride.after * 60;
    var end = start + $scope.quickOverride.duration * 60 * 60;
    $http.post('api/overrides', {
      start: start + offset,
      end: end + offset,
      temp: $scope.quickOverride.temp
    }).then(
      function successCallback(response) {
        if (response.data.success) {
          controller.refresh();
        } else {
          $scope.clearNotifications();
          $scope.notifications.danger = response.data.errors;
        }
      },
      function errorCallback(response) {
        $scope.clearNotifications();
        $scope.notifications.danger = response.data.errors;
      }
    );
  };
  $scope.addPlannedOverride = function() {
    var offset = 2*60*60;
    var start = Math.round($scope.plannedOverride.start.getTime() / 1000);
    var end = Math.round($scope.plannedOverride.end.getTime() / 1000);
    $http.post('api/overrides', {
      start: start + offset,
      end: end + offset,
      temp: $scope.plannedOverride.temp
    }).then(
        function successCallback(response) {
          if (response.data.success) {
            controller.refresh();
          } else {
            $scope.clearNotifications();
            $scope.notifications.danger = response.data.errors;
          }
        },
        function errorCallback(response) {
          $scope.clearNotifications();
          $scope.notifications.danger = response.data.errors;
        }
    );
  };
  $scope.clear = function() {
    $http({
      method: 'DELETE',
      url: 'api/overrides'
    }).then(
        function successCallback(response) {
          $scope.clearNotifications();
          if (response.data.success) {
            controller.refresh();
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

  controller.loadRules();
  controller.loadOverrides();
  $('#new-event').data('event', {
    title: '',
    stick: true
  }).draggable({
    zIndex: 999,
    revert: true,
    revertDuration: 0
  });
}]);
