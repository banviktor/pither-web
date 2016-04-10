angular.module('PiTher').controller('RulesController', ['$scope', '$http', 'uiCalendarConfig', function ($scope, $http, uiCalendarConfig) {
  var calendar = uiCalendarConfig.calendars.calendar;
  $scope.rules = {
    className: 'event-rule',
    events: [],
    color: 'orange',
    textColor: 'white',
    overlap: false,
    editable: true
  };
  $scope.calendarConfig = {
    editable: true,
    droppable: true,
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
    eventReceive: function (event) {
      event.source.events = [];
      event.source = $scope.rules;
      event.id = undefined;
      event.title = $scope.newEvent.temp;
      $scope.rules.events.push(event);
    },
    eventDrop: function (event) {
      if (event.id != undefined) {
        for (var i = 0; i < $scope.rules.events.length; ++i) {
          var currEvent = $scope.rules.events[i];
          if (currEvent.id == event.id) {
            currEvent.start = event.start;
            currEvent.end = event.end;
          }
        }
        if ($scope.modified.indexOf(event.id) == -1) {
          $scope.modified.push(event.id);
        }
      }
    },
    eventResize: function (event) {
      if (event.id != undefined) {
        for (var i = 0; i < $scope.rules.events.length; ++i) {
          var currEvent = $scope.rules.events[i];
          if (currEvent.id == event.id) {
            currEvent.start = event.start;
            currEvent.end = event.end;
          }
        }
        if ($scope.modified.indexOf(event.id) == -1) {
          $scope.modified.push(event.id);
        }
      }
    }
  };
  $scope.eventSources = [$scope.rules];
  $scope.newEvent = {
    temp: 21.5
  };
  $scope.modified = [];

  $scope.loadRules = function () {
    $http.get('api/rules').then(
      function successCallback(response) {
        if (response.data.success) {
          for (var i = 0; i < response.data.data.length; ++i) {
            $scope.rules.events.push($scope.modelToEvent(response.data.data[i]));
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
  $scope.loadRules();
  $('#new-event').each(function () {
    $(this).data('event', {
      title: '',
      stick: true
    });
    $(this).draggable({
      zIndex: 999,
      revert: true,
      revertDuration: 0
    });
  });

  $scope.clear = function () {
    $http({
      method: 'DELETE',
      url: 'api/rules'
    }).then(
      function successCallback(response) {
        $scope.clearNotifications();
        if (response.data.success) {
          $scope.rules.events = [];
          calendar.fullCalendar('refetchEvents');
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
  $scope.reset = function () {
    $scope.rules.events = [];
    $scope.eventSources = [$scope.rules];
    $scope.loadRules();
  };
  $scope.eventToModel = function (event) {
    var start = moment(event.start);
    var end = moment(event.end);
    return {
      id: event.id,
      day: start.date(),
      start: start.format('HH:mm:00'),
      end: end.format('HH:mm:00'),
      temp: event.title
    };
  };
  $scope.modelToEvent = function (model) {
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
      id: model.id,
      start: start.toISOString(),
      end: end.toISOString(),
      title: model.temp
    };
  };
  $scope.save = function () {
    $scope.clearNotifications();
    var fail = false;
    for (var i = 0; i < $scope.rules.events.length; ++i) {
      var event = $scope.eventToModel($scope.rules.events[i]);
      if (event.id == undefined) {
        delete event.id;
        $http.post('api/rules', event, {i: i}).then(
          function successCallback(response) {
            if (response.data.success) {
              $scope.rules.events[response.config.i].id = response.data.data;
            }
            else {
              fail = true;
              $scope.notifications.danger = response.data.errors;
            }
          },
          function errorCallback(response) {
            fail = true;
            $scope.notifications.danger = response.data.errors;
          }
        );
      }
      else if ($scope.modified.indexOf(event.id) != -1) {
        $http.put('api/rules/' + event.id, event).then(
          function successCallback(response) {
            if (!response.data.success) {
              fail = true;
              $scope.notifications.danger = response.data.errors;
            }
          },
          function errorCallback(response) {
            fail = true;
            $scope.notifications.danger = response.data.errors;
          }
        );
      }
    }
    if (!fail) {
      $scope.notifications.success = ['Changes saved successfully.'];
    }
    $scope.modified = [];
  };
}]);
