define([
    'app',
    '../_services/User',
    '../_directive/bodyDirective'
], function (app) {

  app.registerController('CtrlAllUser', [
        '$scope',
        'User',
        function ($scope, User) {
      $scope.searchList = [];

      User.getHistory().success(function (data, status) {
        // body...
        if (data.status === 200) {
          $scope.searchList = data.data.update;
        }
      }).error(function (data, status) {
        /* Act on the event */
      });
        }
    ]);
});