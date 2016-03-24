angular.module('PiTher')
  .directive('compareTo', function () {
    return {
      require: "ngModel",
      scope: {
        otherModelValue: "=compareTo"
      },
      link: function (scope, element, attributes, ngModel) {

        ngModel.$validators.compareTo = function (modelValue) {
          return modelValue == scope.otherModelValue;
        };

        scope.$watch("otherModelValue", function () {
          ngModel.$validate();
        });
      }
    };
  })
  .directive('ngConfirmMessage', function () {
    return {
      priority: 1,
      restrict: 'A',
      link: function (scope, element, attr) {
        var msg = attr.ngConfirmMessage || "Are you sure?";
        var clickAction = attr.ngConfirmAction;
        element.bind('click', function (event) {
          if (window.confirm(msg)) {
            scope.$eval(clickAction)
          }
        });
      }
    };
  })
;
