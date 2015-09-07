define(['angular'], function (angular) {

	'use strict';

	return angular.module('owloo.directives', [])

	.directive('owlooLogo', function() {
		return {
			restrict: 'E',
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/owloo-logo.html'
		}
	})

	.directive('owlooFooter', function() {
		return {
			restrict: 'E',
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/footer.html'
		};
	})

	.directive('breadcrumbs', function() {
		return {
			restrict: 'E',
			scope: {
				links: '=',
			},
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/breadcrumbs.html',
			link: function (scope, element, attrs) {
				scope.$root.$on('breadcrumbs', function (event, args) {
					console.log('LOL');
					console.log(args);
				});
			}
		};
	})

	.directive('widgetAddAccountInput', function() {
		return {
			restrict: 'E',
			scope: {
				placeholder: '@',
			},
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/add-account-input.html'
		};
	})

	.directive('ranking', function() {
		return {
			restrict: 'E',
			scope: {
				data: '='
			},
			controller: function ($scope, $rootScope) {
				$scope.imagesFolder = $rootScope.imagesFolder;
				$scope.url = $rootScope.url;
			},
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/ranking.html'
		};
	})

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

	.directive('widgetBubbleAccount', function() {
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
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/bubble-account.html'
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
			controller: function ($scope, $rootScope) {
				var icon_explode = $scope.icon.split("_");
				$scope.imagesFolder = $rootScope.imagesFolder;
				$scope.icon_text = icon_explode[0];

				if ($scope.icon_text == 'flag') {
					$scope.code = icon_explode[1];
				} else {
					$scope.link = '';
				}
			},
			templateUrl: 'http://www.owloo.com/dev/3.1/app/partials/directives/box-horizontal.html'
		};
	});

});