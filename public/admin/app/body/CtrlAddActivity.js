define([
  'app',
  '../_directive/bodyDirective',
  '../_services/User'
], function (app) {
  app.registerController('CtrlAddActivity', [
    '$scope',
    'User',
  function ($scope, User) {
      $scope.login = {};
      $scope.alertMsg = {
        'required': '未填写',
        'minlength': '小于规定长度',
        'maxlength': '大于规定长度',
        'title': '活动名',
        'content': '活动内容',
        'time': '时间'
      };

      $scope.submit = function (form) {
        if (form.$valid) {
          User.addActivity($scope.activity).success(function (data, status) {
            // body...
            data = angular.fromJson(data);
            if (data.status === 200) {
              alert('发表成功!');
            } else {
              alert('发表失败，' + data.msg);
            }
          }).error(function (data, status) {
            /* Act on the event */
            alert('由于其他原因发表失败!status:' + status);
          });
        } else {
          console.log(form.time.$error);
          var err = [];
          var msg = [];
          for (var i in form.title.$error) {
            err.push({
              name: 'title',
              msg: i
            });
          }
          for (var j in form.content.$error) {
            err.push({
              name: 'content',
              msg: j
            });
          }
          for (var k in form.time.$error) {
            err.push({
              name: 'time',
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
  }]);
});