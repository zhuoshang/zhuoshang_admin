define([
    'app',
    '../_services/User',
    '../_directive/bodyDirective'
], function (app) {

  app.registerController('CtrlRemoveUser', [
        '$scope',
        'User',
        function ($scope, User) {
      $scope.searchList = [];

      User.getHistory().success(function (data, status) {
        // body...
        if (data.status === 200) {
          $scope.searchList = data.data.lock_list;
        }
      }).error(function (data, status) {
        /* Act on the event */
      });
        }
    ]);
});