angular.module('PiTher').controller('RulesController', ['$scope', '$http', function ($scope, $http) {
  $scope.rules = {
    className: 'event-rule',
    events: [],
    color: 'orange',
    textColor: 'white',
    overlap: false,
    //overlap: function(movingEvent, stillEvent) {
    //  return movingEvent.className == 'event-override';
    //},
    editable: true
  };
  $scope.calendarConfig = {
    editable: true,
    header: {
      left: '',
      center: '',
      right: ''
    },
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
    timeFormat: 'H:mm',
    defaultView: 'agendaWeek',
    firstDay: 1,
    dayClick: $scope.alertEventOnClick,
    eventDrop: $scope.alertOnDrop,
    eventResize: $scope.alertOnResize
  };
  $scope.eventSources = [$scope.rules];

  this.loadRules = function() {
    // TODO
  };

  this.loadRules();
}]);
