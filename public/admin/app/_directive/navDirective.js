define([
  'app'
], function (app) {

  app.directive('navLoad', function () {
    return {
      restrict: 'A',
      link: function (scope, element, attrs) {
        console.log(element);
      }
    }
  });

});