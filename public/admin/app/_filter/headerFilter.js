define([
    'app'
], function (app) {

  app.registerFilter('isSuperAdmin', function () {
    return function (value) {
      return value ? '超级管理员' : '管理员';
    }
  });

});