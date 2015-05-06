define([
    'app'
], function (app) {

  app.registerService('Admin', [
        '$http',
        function ($http) {
      return {
        getAdminInfo: function (data) {
          return $http.get('data/adminInfo.json');
        },
        postRegisterAdminRequest: function (data) {
          return $.post(interfacePath + '/adminAdd', data);
        },
        postLoginRequest: function (data) {
          return $.post(interfacePath + '/loginAjax', data);
        }
      };
        }
    ]);

});