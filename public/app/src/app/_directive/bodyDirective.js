define([
    'app',
    '../_services/User'
], function (app) {
  var list = {
    'removeUser': '删除用户',
    'addUser': '添加用户',
    'allUser': '所有用户',
    'addAdmin': '添加管理员',
    'addActivity': '添加活动'
  };
  var moduleName = {
    'removeUser': '用户中心',
    'addUser': '用户中心',
    'allUser': '用户中心',
    'addAdmin': '管理中心',
    'addActivity': '活动中心'
  };
  app.registerDirective('bodyHeader', ['$location', '$rootScope', function ($location, $rootScope) {
    return {
      restrict: 'A',
      templateUrl: baseUrl + '/body/bodyHeader.tpl.html',
      link: function ($scope, ele, att) {

        $rootScope.path = $location.path().match(/\/(\w+)/)[1];
        $rootScope.bodyName = list[$rootScope.path];
        $scope.moduleName = moduleName[$rootScope.path];
        if ($rootScope.path !== 'home' || $rootScope.path !== undefined) {
          var $sideMenu = $('#side-menu');
          var $ul = $sideMenu.find('[ui-sref="' + $rootScope.path + '"]').parents('.nav-second-level');
          var $a = $ul.prev();

          $a.click();

        }

      }
    };
    }]);

  app.registerDirective('sideSearch', ['$rootScope', function ($rootScope) {
    return {
      restrict: 'A',
      templateUrl: baseUrl + '/body/sideSearch.tpl.html',
      scope: {
        sideSearch: '='
      },
      link: function ($scope, $ele, $att) {
        $scope.searchList = [];
        $scope.path = $rootScope.path;
        $scope.$watch('sideSearch', function (nval, oval) {
          if (nval === oval) {
            return;
          }
          $scope.searchList = nval;
        });
      }
    }
    }]);

  app.registerDirective('paging', ['$location', '$rootScope', 'User', function ($location, $rootScope, User) {
    return {
      restrict: 'A',
      templateUrl: baseUrl + '/body/bodyPagination.tpl.html',
      link: function ($scope, $ele, $att) {
        User.getUserList({
          currentPage: 1
        }).success(function (data, status) {
          if (data.status === 200) {
            $scope.userList = data.data;
            $scope.pageCount = data.data.pageCount;
            $scope.currentPage = data.data.currentPage;
            $('#page').page({
              total: $scope.pageCount * 10,
              pageBtnCount: 7,
              prevBtnText: 'Previous',
              nextBtnText: 'Next',
              showJump: true,
              jumpBtnText: 'Go'
            })
          }
        }).error(function (data, status) {});

        var $pagination = $ele.find('#page');

        var $goBtn = $pagination.find('.btn');

        var $input = $pagination.find('.form-control');

        // var $previous = $pagination.find('.page-previous');

        // var pages = 1;

        $pagination.on('click', 'a', function (event) {
          /* Act on the event */
          // var pageNum = $(this).data('id');
          // $pagination.find('.active').removeClass('active');
          // if (pageNum !== undefined) {
          //     if (pageNum === 0) {
          //         pages -= 4;
          //         render(pages);
          //     }
          //     if (pageNum == -1) {
          //         pages += 4;
          //         render(pages);
          //     }
          //     if (0 < pageNum <= $scope.pageCount) {
          //         $(this).parent().addClass('active');
          //         $scope.currentPage = pageNum;
          //     }
          //     $scope.$apply();
          // }

          var pageNum = $(this).data('page-index');

          getData(pageNum + 1);

        });

        $pagination.on('click', '.btn', function (event) {
          /* Act on the event */
          var pageNum = $(this).parent().parent().find('input').val();
          if (pageNum > $scope.pageCount) {
            return;
          }
          getData(pageNum);
        });

        // function render(num) {
        //     var $pagination = $ele.find('.pagination');
        //     var $previous = $pagination.find('.page-previous');
        //     var $next = $pagination.find('.page-next');
        //     var $paginationBtn = $pagination.find('.pagination-btn');
        //     var $par = $pagination.find('.par');
        //     for (var i = 0; i < 5; i++) {
        //         var a = $paginationBtn.eq(i).find('a');
        //         a.data('id', num);
        //         a.text(num);
        //         if (i === 4) {
        //           a.text($scope.pageCount);
        //           a.data('id', $scope.pageCount);
        //         }
        //         num++;
        //         $paginationBtn.eq(i).show();
        //     }
        //     $next.show();
        //     $previous.show();
        //     if ($scope.pageCount == 5) {
        //         $par.hide();
        //         $previous.hide();
        //         $next.hide();
        //     } else if ($scope.pageCount < 5) {
        //         $previous.hide();
        //         $next.hide();
        //         $par.hide();
        //         for (var i = $scope.pageCount; i < 5; i++) {
        //             $paginationBtn.eq(i).hide();
        //         }
        //     } else {
        //         var pageCount = $scope.pageCount - $paginationBtn.first().data('id') + 1;
        //         if (pageCount < 5) {
        //             $par.hide();
        //             $next.hide();
        //             for (var i = pageCount; i < i + 5; i++) {
        //                 $paginationBtn.eq(i).hide();
        //             }
        //         } else if (pageCount == 5) {
        //             $par.hide();
        //             $next.hide();
        //         } else {
        //             $next.show();
        //             $previous.show();
        //             $par.show();
        //         }
        //     }
        // }


        function getData(currentPage) {
          User.getUserList({
            currentPage: currentPage || 1
          }).success(function (data, status) {
            if (data.status == 200) {
              $scope.userList = data.data;
              $scope.pageCount = $scope.pageCount || data.data.pageCount;
              $scope.currentPage = data.data.currentPage;
              // $scope.$apply();
            }
          });
        }

        $scope.$watch('currentPage', function (newVal, oldVal, $scope) {
          if (newVal === undefined) {
            return;
          }
          if (newVal == oldVal) {
            return;
          }
          getData(newVal);

        });
      }
    };
    }]);


});