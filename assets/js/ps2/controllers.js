var ps2App = angular.module('ps2App', ['chart.js']);

ps2App.config(['$locationProvider', 'ChartJsProvider', function ($locationProvider, ChartJsProvider) {

    $locationProvider.html5Mode({
        enabled: true,
        requireBase: false
    });

    ChartJsProvider.setOptions({
      //  colours: ['#97BBCD', '#DCDCDC', '#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'],
        //  colours: ['#db1515', '#4286f4', '#af41f4', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'],
        responsive: true,
        animationSteps: 7,
        animationEasing: 'easeOutBounce'

    });

}]);

ps2App.controller('profileCtrl', [

    '$scope', '$http', '$filter', '$location', '$window',

    function ($scope, $http, $filter, $location, $window) {
      $scope.colors=['#db1515', '#4286f4', '#af41f4'];
        $scope.loading = true;
        $scope.faction = 0;
        $scope.chartoptions = {
            scaleShowVerticalLines: false,
            tooltipTemplate: "<%= value %>"
        };

        $scope.getprofile = function (name) {
            $http.get("/Division-Tracker/ps2activity/?chars=" + name)
                .success(function (data, status) {

                    if (!data.error) {
                      $scope.characters=angular.copy(data);
                      updateData(0);

                    }
                    $scope.loading = false;
                    console.log($scope.profile);
                });

        };
        function updateData(faction){
          $scope.colors=[];
          if(faction == 1){
            if(angular.isDefined($scope.characters.tr)&& $scope.characters.tr.length>0){

            $scope.profile=angular.copy($scope.characters.tr[0]);
            $scope.colors.push('#db1515');
          }
          }
          else if(faction ==2){
            if(angular.isDefined($scope.characters.nc)&& $scope.characters.nc.length>0){

            $scope.profile=angular.copy($scope.characters.nc[0]);
            $scope.colors.push('#4286f4');
          }
          }
          else if(faction == 3){
            if(angular.isDefined($scope.characters.vs)&& $scope.characters.vs.length>0){

            $scope.profile=angular.copy($scope.characters.vs[0]);
            $scope.colors.push('#af41f4');
          }
          }
          else{
          $scope.colors.push('#db1515');
            $scope.colors.push('#4286f4');
            $scope.colors.push('#af41f4');

            $scope.profile = angular.copy($scope.characters.tr[0]);
            $scope.profile.playtime.month.chart.data=[];
              $scope.profile.playtime.day.chart.data=[];
              $scope.profile.platoonleading.data=[];
              $scope.profile.squadleading.data=[];
              if(angular.isDefined($scope.characters.tr)&& $scope.characters.tr.length>0){
                $scope.profile.platoonleading.data.push($scope.characters.tr[0].platoonleading.data[0]);
                $scope.profile.squadleading.data.push($scope.characters.tr[0].squadleading.data[0]);
                $scope.profile.playtime.month.chart.data.push($scope.characters.tr[0].playtime.month.chart.data[0]);
                $scope.profile.playtime.day.chart.data.push($scope.characters.tr[0].playtime.day.chart.data[0]);

              }
              if(angular.isDefined($scope.characters.nc)&& $scope.characters.nc.length>0){
                $scope.profile.platoonleading.data.push($scope.characters.nc[0].platoonleading.data[0]);
                $scope.profile.squadleading.data.push($scope.characters.nc[0].squadleading.data[0]);
                $scope.profile.playtime.month.chart.data.push($scope.characters.nc[0].playtime.month.chart.data[0]);
                $scope.profile.playtime.day.chart.data.push($scope.characters.nc[0].playtime.day.chart.data[0]);

              }
              if(angular.isDefined($scope.characters.vs)&& $scope.characters.vs.length>0){
                $scope.profile.platoonleading.data.push($scope.characters.vs[0].platoonleading.data[0]);
                $scope.profile.squadleading.data.push($scope.characters.vs[0].squadleading.data[0]);
                $scope.profile.playtime.month.chart.data.push($scope.characters.vs[0].playtime.month.chart.data[0]);
                $scope.profile.playtime.day.chart.data.push($scope.characters.vs[0].playtime.day.chart.data[0]);

              }


          }
        }
        $scope.getprofile($("#ps2_character_name").val());
        $scope.$watch('faction',function(newValue, oldValue) {
          if(angular.isDefined($scope.characters)){
          updateData(newValue);
        }
        });
    }
]);
