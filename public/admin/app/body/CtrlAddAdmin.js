define([
    'app',
    '../_directive/bodyDirective',
    '../_services/Admin'
], function (app) {

  app.registerController('CtrlAddAdmin', [
        '$scope',
        'Admin',
        function ($scope, Admin) {
      $scope.submit = function () {
        Admin.postRegisterAdminRequest($scope.regist).success(function (data) {
          if (data.status === 200) {
            alert('注册成功!');
          } else {
            alert('注册失败，' + data.status);
          }
        }).error(function (data, status) {
          alert('由于网络原因注册失败!status:' + status);
        })
      };
        }
    ]);
});