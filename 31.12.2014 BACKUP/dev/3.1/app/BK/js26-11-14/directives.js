define(['angular'], function (angular) {

	'use strict';

	function rankingCtrl ($scope, $rootScope) {

		$scope.imagesFolder = $rootScope.imagesFolder;

	}

	function widgetBoxHorizontalCtrl ($scope, $rootScope) {

		var icon_explode = $scope.icon.split("_");
		$scope.imagesFolder = $rootScope.imagesFolder;
		$scope.icon_text = icon_explode[0];

		if ($scope.icon_text == 'flag') {
			$scope.code = icon_explode[1];
		} else {
			$scope.link = '';
		}
	}

	return angular.module('owloo.directives', [])

	.directive('owlooFooter', function() {
		return {
			restrict: 'E',
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/footer.html'
		};
	})

	.directive('breadcrumbs', function() {
		return {
			restrict: 'E',
			scope: {
				links: '=',
			},
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/breadcrumbs.html'
		};
	})

	.directive('dropDown', function() {
		return {
			restrict: 'E',
			scope: {
				rank: '=',
			},
			controller: 'rankingCtrl',
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/ranking.html'
		};
	}).controller('rankingCtrl', rankingCtrl)

	.directive('search', function(){
	   return {
			restrict: "E",
			scope: {
				placeholder: "@",
				searchModel: "="
			},
			template: 
			'<div class="search">'+
			'   <div class="left">'+
			'       <div class="search-icon"></div>'+
			'   </div>'+
			'   <div class="right">'+
			'       <input class="search-input" type="text" ng-model="searchModel" placeholder="{{placeholder}}">'+
			'   </div>'+
			'   <div style="clear:both;"></div>'+
			'</div>',
		};
	})

	.directive('ranking', function() {
		return {
			restrict: 'E',
			scope: {
				rank: '=',
			},
			controller: 'rankingCtrl',
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/ranking.html'
		};
	}).controller('rankingCtrl', rankingCtrl)

	.directive('widgetBoxUpright', function() {
		return {
			restrict: 'E',
			scope: {
				picture: '@',
				class: '@',
				grownumber: '@',
				growpercent: '@',
				link: '@',
				name: '@'
			},
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/box-upright.html'
		};
	})

	.directive('widgetBoxHorizontal', function() {
		return {
			restrict: 'E',
			scope: {
				icon: '@',
				class: '@',
				value: '@',
				percent: '@',
				link: '@',
				footer: '@'
			},
			controller: 'widgetBoxHorizontalCtrl',
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/box-horizontal.html'
		};
	}).controller('widgetBoxHorizontalCtrl', widgetBoxHorizontalCtrl);

});