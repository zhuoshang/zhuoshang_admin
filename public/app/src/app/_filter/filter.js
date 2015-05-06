define([
    'app'
], function (app) {

  app.registerFilter('formatParams', function () {
    return function (data) {
      var arr = [];
      for (var name in data) {
        if (data.hasOwnProperty(name)) {
          arr.push(name + '=' + data[name]);
        }
      }
      return '?' + arr.join('&');
    }
  });

});