define([
    'app',
    '../../_filter/headerFilter',
    '../../_services/Admin'
], function (app) {

  app.registerController('CtrlHeader', [
        '$scope',
        '$rootScope',
        'Admin',
        function ($scope, $rootScope, Admin) {
      if ($rootScope.adminInfo === undefined) {
        Admin.getAdminInfo().success(function (data, status) {
            if (data.status === 200) {
              $rootScope.adminInfo = data.data;
            }
          })
          .error(function (data, status) {});
      }

        }
    ]);
});