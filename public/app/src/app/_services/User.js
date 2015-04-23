define([
    'app'
], function (app) {

  app.registerService('User', [
        '$http',
        '$filter',
        function ($http, $filter) {
      return {
        postAddUser: function (data) {
          return $.post('data/userList.json', data);
        },
        getUserList: function (data) {
          // return $http.get('data/userList.json' + $filter('formatParams')(data));
          return $http.get(interfacePath + '/userList', data);
        },
        postDeleteUser: function () {
          return $http.get('data/deleteUser.json');
        },
        getHistory: function () {
          return $http.get(interfacePath + '/lockUserList');
        },
        addActivity: function (data) {
          return $.post(interfacePath + '/activityAdd', data);
        }
      };
        }
    ]);

});