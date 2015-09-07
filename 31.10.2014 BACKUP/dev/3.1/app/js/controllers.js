define(['angular', 'angularFoundation'], function (app) {

	'use strict';

	var module = app.module('controllers', ['mm.foundation']);

	module.controller('HomeCtrl', ['$scope', 'data', function ($scope, data) {

			$scope.totalOwlooUsers = data.totalOwlooUsers;
			$scope.rankPageGrow = data.rankPageGrow;

	}]);

	module.controller('FacebookStatsCtrl', ['$scope', function ($scope) {

		$scope.facebookAllUsers = '1.389.786.888';
		$scope.facebookWomenUsers = '455.675.982';
		$scope.facebookMenUsers = '553.115.923';
		$scope.facebookAverageCPC = '0.06-1.20';

	}]);

	module.controller('FacebookStatsPagesCtrl', ['$scope', '$location', 'data', function ($scope, $location, data) {

		//Pagination
		$scope.totalItems = data.total;
		$scope.currentWhere = data.currentWhere;
		$scope.currentPage = data.currentPage;
		$scope.currentCategory = data.currentCategory;
		$scope.maxSize = 5;

		//Content
		$scope.rank = data.rank;
		$scope.categories = data.categories;
		$scope.countries = data.countries;

		// if (data.currentWhere == 'hispanic') $scope.hispanic = true;
		// else $scope.world = true;

		$scope.pageChanged = function (page) {
			$location.path('/facebook-stats/pages/' + data.currentWhere + '/' + data.currentCategory + '/' + page);
		};


	}]);

	module.controller('FacebookStatsPagesCountryCtrl', ['$scope', '$location', 'data', function ($scope, $location, data) {

		//Breadcrumb
		$scope.countryName = data.countries[data.countryCode].name;
		$scope.countryCode = data.countryCode;

		//Pagination
		$scope.totalItems = data.total;
		$scope.currentPage = data.currentPage;
		$scope.currentCategory = data.currentCategory;
		$scope.maxSize = 5;

		//Content
		$scope.rank = data.rank;
		$scope.categories = data.categories;
		$scope.countries = data.countries;

		$scope.pageChanged = function (country, category, page) {
			$location.path('/facebook-stats/pages/country/' + country + '/' + category + '/' + page);
		};

	}]);

	module.controller('FeaturesCtrl', ['$scope', 'data', function ($scope, data) {

	    $scope.totalFacebookPages = data.totalFacebookPages;
	    $scope.totalTwitterProfiles = data.totalTwitterProfiles;
	    $scope.totalInstagramProfiles = data.totalInstagramProfiles;
	    $scope.totalOwlooUsers = data.totalOwlooUsers;

	}]);

	return module;

});