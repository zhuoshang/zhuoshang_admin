/**
 * routeDefs.js 路由定义
 * @description 该app为SPA，single page application
 * 路由完全有前端控制，此处配置**路由**
 */
define(['app'], function (app) {
  /**
   * register `routeDefs`
   *
   */
  app.registerProvider('routeDefs', [
        '$stateProvider',
        '$urlRouterProvider',
        '$couchPotatoProvider',
        'STATIC_DIR',
        function (
      $stateProvider,
      $urlRouterProvider,
      $couchPotatoProvider,
      STATIC_DIR
        ) {

      this.$get = function () {
        // this is a config-time-only provider
        // in a future sample it will expose runtime information to the app
        return {};
      };
      // $locationProvider.html5Mode(true);

      $urlRouterProvider.otherwise('loginPage');


      var baseUrl = STATIC_DIR + "app/";
      var headerConfig = {
        templateUrl: baseUrl + '_common/header/header.tpl.html',
        controller: 'CtrlHeader',
        resolve: {
          ctrl: $couchPotatoProvider.resolveDependencies(['_common/header/CtrlHeader'])
        }
      };

      var navConfig = {
        templateUrl: baseUrl + 'nav/nav.tpl.html',
        resolve: {
          ctrl: $couchPotatoProvider.resolveDependencies(['nav/CtrlNav'])
        }
      };
      // loginPage
      $stateProvider.state('loginPage', {
        url: '/loginPage',
        views: {
          body: {
            templateUrl: baseUrl + 'loginPage/loginPage.tpl.html',
            controller: 'CtrlLoginPage',
            resolve: {
              ctrl: $couchPotatoProvider.resolveDependencies(['loginPage/CtrlLoginPage'])
            }
          },
          nav: navConfig
        }
      });
      // Home
      $stateProvider.state('home', {
        url: '/home',
        views: {
          body: {
            templateUrl: baseUrl + 'body/body.tpl.html',
            resolve: {
              ctrl: $couchPotatoProvider.resolveDependencies(['body/CtrlBody'])
            }
          },
          nav: navConfig,
          header: headerConfig
        }
      });
      // addUser
      $stateProvider.state('addUser', {
        url: '/addUser',
        views: {
          body: {
            templateUrl: baseUrl + 'body/addUser.tpl.html',
            controller: 'CtrlAddUser',
            resolve: {
              ctrl: $couchPotatoProvider.resolveDependencies(['body/CtrlAddUser'])
            }
          },
          nav: navConfig,
          header: headerConfig
        }
      });

      // removeUser
      $stateProvider.state('removeUser', {
        url: '/removeUser',
        views: {
          body: {
            templateUrl: baseUrl + 'body/removeUser.tpl.html',
            controller: 'CtrlRemoveUser',
            resolve: {
              ctrl: $couchPotatoProvider.resolveDependencies(['body/CtrlRemoveUser'])
            }
          },
          nav: navConfig,
          header: headerConfig
        }
      });

      // allUser
      $stateProvider.state('allUser', {
        url: '/allUser',
        views: {
          body: {
            templateUrl: baseUrl + 'body/allUser.tpl.html',
            controller: 'CtrlAllUser',
            resolve: {
              ctrl: $couchPotatoProvider.resolveDependencies(['body/CtrlAllUser'])
            }
          },
          nav: navConfig,
          header: headerConfig
        }
      });
      // allUser
      $stateProvider.state('addAdmin', {
        url: '/addAdmin',
        views: {
          body: {
            templateUrl: baseUrl + 'body/addAdmin.tpl.html',
            controller: 'CtrlAddAdmin',
            resolve: {
              ctrl: $couchPotatoProvider.resolveDependencies(['body/CtrlAddAdmin'])
            }
          },
          nav: navConfig,
          header: headerConfig
        }
      });
      // addActivity
      $stateProvider.state('addActivity', {
        url: '/addActivity',
        views: {
          body: {
            templateUrl: baseUrl + 'body/addActivity.tpl.html',
            controller: 'CtrlAddActivity',
            resolve: {
              ctrl: $couchPotatoProvider.resolveDependencies(['body/CtrlAddActivity'])
            }
          },
          nav: navConfig,
          header: headerConfig
        }
      });

      angular.noop(); //do not remove this line,grunt tool use this to do reg match.
        }
    ]);
  //end for define
});