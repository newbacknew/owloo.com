define(['angular', 'angularFoundation'], function (angular) {

	'use strict';

	return angular.module('owloo.controllers', ['mm.foundation'])

	.controller('HomeController', ['$scope', 'data', function ($scope, data) {

		$scope.totalOwlooUsers = data.totalOwlooUsers;
		$scope.facebookGrowRank = data.facebookGrowRank;
		$scope.twitterGrowRank = data.twitterGrowRank;
		$scope.instagramGrowRank = data.instagramGrowRank;
/* */	$scope.countryGrowRank = data.countryGrowRank;

		$scope.slide = _.random(1, 2);

	}])

	/*
	|--------------------------------------------------------------------------
	| FACEBOOK
	|--------------------------------------------------------------------------
	*/

	.controller('FacebookIndexController', ['$scope', function ($scope) {

		$scope.facebookAllUsers = '1.389.786.888';
		$scope.facebookWomenUsers = '455.675.982';
		$scope.facebookMenUsers = '553.115.923';
		$scope.facebookAverageCPC = '0.06-1.20';

	}])

	.controller('FacebookPagesRankController', ['$scope', '$location', 'data', function ($scope, $location, data) {

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

		$scope.pageChanged = function (page) {
			$location.path('/facebook-stats/pages/' + data.currentWhere + '/' + data.currentCategory + '/' + page);
		};

	}])

	.controller('FacebookPagesRankByCountryController', ['$scope', '$location', 'data', function ($scope, $location, data) {

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

	}])

	.controller('FacebookPagesUsernameController', ['$scope', 'data', function ($scope, data) {

		$scope.page = data.page;

	}])

	/*
	|--------------------------------------------------------------------------
	| TWITTER
	|--------------------------------------------------------------------------
	*/

	.controller('TwitterStatsController', ['$scope', function ($scope) {

		//  code 

	}])

	.controller('TwitterStatsRankController', ['$scope', function ($scope) {

		//  code 

	}])


	/*
	|--------------------------------------------------------------------------
	| INSTAGRAM
	|--------------------------------------------------------------------------
	*/


	.controller('FeaturesController', ['$scope', 'data', function ($scope, data) {

		$scope.totalFacebookPages = data.totalFacebookPages;
		$scope.totalTwitterProfiles = data.totalTwitterProfiles;
		$scope.totalInstagramProfiles = data.totalInstagramProfiles;
		$scope.totalOwlooUsers = data.totalOwlooUsers;

	}])

	.controller('TestController', ['$scope', 'data', function ($scope, data) {

		$scope.seriesData = _.map(data.page.likes_history_30.series_data, function(likes, key){ return {'date': data.page.likes_history_30.x_axis[key], 'likes': likes} });

	}]);

});