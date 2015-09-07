define(['angular', 'angularFoundation'], function (angular) {

	'use strict';

	/*
	|--------------------------------------------------------------------------
	| OWLOO
	|--------------------------------------------------------------------------
	*/

	function HomeCtrl ($scope, data) {

		$scope.totalOwlooUsers = data.totalOwlooUsers;
		$scope.facebookGrowRank = data.facebookGrowRank;
		$scope.twitterGrowRank = data.twitterGrowRank;
		$scope.instagramGrowRank = data.instagramGrowRank;
		$scope.countryGrowRank = data.countryGrowRank;

		$scope.slide = _.random(1, 2);

	}

	function FeaturesCtrl ($scope, data) {

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_FEATURES', link: 'features'},
		];

		$scope.totalFacebookPages = data.totalFacebookPages;
		$scope.totalTwitterProfiles = data.totalTwitterProfiles;
		$scope.totalInstagramProfiles = data.totalInstagramProfiles;
		$scope.totalOwlooUsers = data.totalOwlooUsers;

	}

	function TestCtrl ($scope, data) {

		$scope.seriesData = _.map(data.page.likes_history_30.series_data, function(likes, key){ return {'date': data.page.likes_history_30.x_axis[key], 'likes': likes} });

	}

	/*
	|--------------------------------------------------------------------------
	| FACEBOOK
	|--------------------------------------------------------------------------
	*/

	function FacebookLandingCtrl ($scope, data) {

		$scope.facebookAllUsers = data.facebookAllUsers;
		$scope.facebookWomenUsers = data.facebookWomenUsers;
		$scope.facebookMenUsers = data.facebookMenUsers;
		$scope.facebookAverageCPC = data.facebookAverageCPC;

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_FACEBOOK_STATS', link: 'facebook-statistics'}
		];
	}

	function FacebookPagesRankCtrl ($scope, $location, data) {

		// Pagination
		// -- conf
		$scope.maxSize = 5;
		$scope.perPage = 20;
		$scope.total = data.total;
		// -- current
		$scope.where = data.where;
		$scope.category = data.category;
		$scope.page = data.page;

		// Content
		$scope.rank = data.rank;
		$scope.categories = data.categories;
		$scope.countries = data.countries;

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_FACEBOOK_STATS', link: 'facebook-statistics'},
			{text: 'BREADCRUMBS_FACEBOOK_STATS_PAGES', link: 'facebook-stats/pages/' + $scope.where}
		];

		$scope.pageChanged = function (page) {
			$location.path('/facebook-stats/pages/' + $scope.where + '/' + $scope.category + '/page-' + page);
		};

	}

	function FacebookPagesRankByCountryCtrl ($scope, $location, data) {

		// Pagination
		// -- conf
		$scope.maxSize = 5;
		$scope.perPage = 20;
		$scope.total = data.total;
		// -- current
		$scope.category = data.category;
		$scope.page = data.page;
		$scope.country = data.countries[data.where];
		$scope.country.code = data.where;

		// Filters
		$scope.categories = data.categories;
		$scope.countries = data.countries;

		// Rank
		$scope.rank = data.rank;

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_FACEBOOK_STATS', link: 'facebook-statistics'},
			{text: 'BREADCRUMBS_FACEBOOK_STATS_PAGES', link: 'facebook-stats/pages'},
			{text: $scope.country.name, link:  'facebook-stats/pages/country/' + $scope.country.code}
		];

		$scope.pageChanged = function (page) {
			$location.path('/facebook-stats/pages/country/' + $scope.country.code + '/' + $scope.category + '/page-' + page);
		};

	}

	function FacebookPagesFanpageCtrl ($scope, data) {

		$scope.page = data.page;

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_FACEBOOK_STATS', link: 'facebook-statistics'},
			{text: 'BREADCRUMBS_FACEBOOK_STATS_PAGES', link: 'facebook-stats/pages'},
			{text: $scope.page.name, link: 'facebook-stats/pages/' + $scope.page.username}
		];

	}

	function FacebookCitiesRankCtrl ($scope, $location, $translate, data) {

		//Pagination
		// -- conf
		$scope.maxSize = 5;
		$scope.perPage = 20;
		$scope.total = data.total;
		// -- current
		$scope.page = data.page;

		// Filters
		$scope.countries = data.countries;

		// Rank
		$scope.rank = data.rank;

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_FACEBOOK_STATS', link: 'facebook-statistics'},
			{text: 'BREADCRUMBS_FACEBOOK_STATS_CITIES', link: 'facebook-stats/cities'}
		];

		if (data.where != 'world') {
			$scope.country = data.countries[data.where];
			$scope.breadcrumbs.push({text: $scope.country.name, link: 'facebook-stats/cities/' + data.where});
		}

		$scope.pageChanged = function (page) {
			$location.path('/facebook-stats/cities/' + data.where + '/page-' + page);
		};
	}

	function FacebookCountriesRankCtrl ($scope, $location, data) {

		//Pagination
		// -- conf
		$scope.maxSize = 5;
		$scope.perPage = 20;
		$scope.total = data.total;
		// -- current
		$scope.page = data.page;
		$scope.where = data.where;

		// Rank
		$scope.rank = data.rank;

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_FACEBOOK_STATS', link: 'facebook-statistics'},
			{text: 'BREADCRUMBS_FACEBOOK_STATS_COUNTRIES', link: 'facebook-stats/countries'}
		];

		$scope.pageChanged = function (page) {
			$location.path('/facebook-stats/countries/' + $scope.where + '/page-' + page);
		};

	}

	function FacebookCountryCtrl ($scope, data) {

		// Rank
		$scope.country = data.country;

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_FACEBOOK_STATS', link: 'facebook-statistics'},
			{text: 'BREADCRUMBS_FACEBOOK_STATS_COUNTRIES', link: 'facebook-stats/countries'},
			{text: $scope.country.name, link: 'facebook-stats/' + $scope.country.link}
		];

	}

	/*
	|--------------------------------------------------------------------------
	| TWITTER
	|--------------------------------------------------------------------------
	*/

	function TwitterLandingCtrl ($scope) {

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_TWITTER_STATS', link: 'twitter-statistics'}
		];

	}

	function TwitterRankCtrl ($scope, $location, data) {

		//Pagination
		// -- conf
		$scope.maxSize = 5;
		$scope.perPage = 20;
		$scope.total = data.total;
		// -- current
		$scope.page = data.page;
		$scope.where = data.where;

		// Rank
		$scope.rank = data.rank;

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_TWITTER_STATS', link: 'twitter-statistics'},
			{text: 'BREADCRUMBS_TWITTER_STATS_PROFILES', link: 'twitter-stats/profiles/' + $scope.where}
		];

		$scope.pageChanged = function (page) {
			$location.path('/twitter-stats/profiles/' + $scope.where + '/page-' + page);
		};
	}

	/*
	|--------------------------------------------------------------------------
	| INSTAGRAM
	|--------------------------------------------------------------------------
	*/

	function InstagramLandingCtrl ($scope) {

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_INSTAGRAM_STATS', link: 'instagram-statistics'}
		];

	}

	function InstagramRankCtrl ($scope, $location, data) {

		//Pagination
		// -- conf
		$scope.maxSize = 5;
		$scope.perPage = 20;
		$scope.total = data.total;
		// -- current
		$scope.page = data.page;

		// Rank
		$scope.rank = data.rank;

		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_INSTAGRAM_STATS', link: 'instagram-statistics'},
			{text: 'BREADCRUMBS_INSTAGRAM_STATS_ACCOUNTS', link: 'instagram-stats/accounts/'}
		];

		$scope.pageChanged = function (page) {
			$location.path('/instagram-stats/accounts/' + $scope.where + '/page-' + page);
		};

	}

	return angular.module('owloo.controllers', ['mm.foundation'])

	// OWLOO
	.controller('HomeCtrl', HomeCtrl)
	.controller('FeaturesCtrl', FeaturesCtrl)
	.controller('TestCtrl', TestCtrl)

	// FACEBOOK
	.controller('FacebookLandingCtrl', FacebookLandingCtrl)
	.controller('FacebookPagesRankCtrl', FacebookPagesRankCtrl)
	.controller('FacebookPagesRankByCountryCtrl', FacebookPagesRankByCountryCtrl)
	.controller('FacebookPagesFanpageCtrl', FacebookPagesFanpageCtrl)
	.controller('FacebookCitiesRankCtrl', FacebookCitiesRankCtrl)
	.controller('FacebookCountriesRankCtrl', FacebookCountriesRankCtrl)

	// TWITTER
	.controller('TwitterLandingCtrl', TwitterLandingCtrl)
	.controller('TwitterRankCtrl', TwitterRankCtrl)

	// INSTAGRAM
	.controller('InstagramLandingCtrl', InstagramLandingCtrl)
	.controller('InstagramRankCtrl', InstagramRankCtrl);

});