var ps2App = angular.module('ps2App', ['chart.js']);
ps2App.config(['$locationProvider','ChartJsProvider',function($locationProvider,ChartJsProvider) {
        $locationProvider.html5Mode({
  enabled: true,
  requireBase: false
});
        ChartJsProvider.setOptions({
            colours: ['#97BBCD', '#DCDCDC', '#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'],
            responsive:  true,
            animationSteps : 7,
            animationEasing : 'easeOutBounce'

          });

    }]);

		ps2App
		.controller('profileCtrl',[
									'$scope',
									'$http','$filter','$location','$window',
									function($scope, $http,$filter,$location,$window) {
										$scope.loading=true;
										$scope.chartoptions={
												scaleShowVerticalLines: false,
												tooltipTemplate: "<%= value %>",
										};

									$scope.profile={};
							$scope.getprofile=function(name){
								$http.get("/ps2activity/"+name).success(function(data,status){
									$scope.profile=data;
									$scope.loading=false;
									console.log($scope.profile);
								});

							}


									$scope.getprofile($("#ps2_character_name").val());

									}]);
