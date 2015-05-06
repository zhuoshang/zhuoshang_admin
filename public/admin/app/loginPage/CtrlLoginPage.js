define([
    'app',
    '../_directive/bodyDirective',
    '../_services/Admin',
    '../_directive/ngFocusDirective'
], function (app) {

  app.registerController('CtrlLoginPage', [
        '$scope',
        'Admin',
        '$state',
        function ($scope, Admin, $state) {
      $scope.login = {};
      $scope.alertMsg = {
        'required': '未填写',
        'minlength': '小于规定长度',
        'maxlength': '大于规定长度',
        'name': '用户名',
        'password': '密码'
      };

      $scope.submit = function (form) {
        if (form.$valid) {
          Admin.postLoginRequest($scope.login).success(function (data, status) {
            // body...
            data = angular.fromJson(data);
            if (data.status === 200) {
              $state.go('home', {}, {
                reload: true
              });
            } else {
              alert('登陆失败，' + data.msg);
            }
          }).error(function (data, status) {
            /* Act on the event */
            alert('由于其他原因登陆失败!status:' + status);
          });
        } else {
          var err = [];
          var msg = [];
          for (var i in form.name.$error) {
            err.push({
              name: 'name',
              msg: i
            });
          }
          for (var j in form.password.$error) {
            err.push({
              name: 'password',
              msg: j
            });
          }

          for (var k = 0; k < err.length; k++) {
            if (err[k].msg !== '') {
              msg.push($scope.alertMsg[err[k].name] + $scope.alertMsg[err[k].msg]);
            }
          }

          alert(msg.join('\n'));
        }
      }
        }
    ]);
});