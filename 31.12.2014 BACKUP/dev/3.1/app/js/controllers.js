define([
	'angular',
	'angularFoundation',
	'facebook-research-ctrl',
	'facebook-analytics-ctrl',
	'twitter-analytics-ctrl',
	'instagram-analytics-ctrl'
], function (angular) {

	'use strict';
	/*
	|--------------------------------------------------------------------------
	| OWLOO
	|--------------------------------------------------------------------------
	*/
	function HomeCtrl ($rootScope, $scope, data) {

		$scope.totalOwlooUsers = data.totalOwlooUsers;
		$scope.facebookGrowRank = data.facebookGrowRank;
		$scope.twitterGrowRank = data.twitterGrowRank;
		$scope.instagramGrowRank = data.instagramGrowRank;
		$scope.countryGrowRank = data.countryGrowRank;

		$scope.slide = _.random(1, 2);

	}
	function FeaturesCtrl ($rootScope, $scope) {
		// Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_FEATURES', link: $rootScope.url.features},
		];
	}
	/*
	|--------------------------------------------------------------------------
	| FACEBOOK
	|--------------------------------------------------------------------------
	*/
	function FacebookStatisticsLandingCtrl ($scope, $rootScope)
	{
		// --- Breadcrumbs
		$scope.breadcrumbs = [
			{text: 'BREADCRUMBS_FACEBOOK_STATISTICS', link: $rootScope.url.facebook.root}
		];
	}

	return angular.module('owloo.controllers', ['mm.foundation'])

	// OWLOO
	.controller('HomeCtrl', HomeCtrl)
	.controller('FeaturesCtrl', FeaturesCtrl)

	// FACEBOOK
	.controller('FacebookStatisticsLandingCtrl', FacebookStatisticsLandingCtrl)

	// FACEBOOK ANALYTICS
	.controller('FacebookAnalyticsLandingCtrl', FacebookAnalyticsLandingCtrl)
	.controller('FacebookPagesRankingCtrl', FacebookPagesRankingCtrl)
	.controller('FacebookPagesInternalCtrl', FacebookPagesInternalCtrl)

	// FACEBOOK RESEARCH
	.controller('FacebookResearchLandingCtrl', FacebookResearchLandingCtrl)
	.controller('FacebookContinentsCtrl', FacebookContinentsCtrl)
	.controller('FacebookCountriesCtrl', FacebookCountriesCtrl)
	.controller('FacebookCountryCtrl', FacebookCountryCtrl)
	.controller('FacebookRegionsCtrl', FacebookRegionsCtrl)
	.controller('FacebookCitiesCtrl', FacebookCitiesCtrl)

	// TWITTER
	.controller('TwitterLandingCtrl', TwitterLandingCtrl)
	.controller('TwitterProfilesRankingCtrl', TwitterProfilesRankingCtrl)
	.controller('TwitterProfilesInternalCtrl', TwitterProfilesInternalCtrl)

	// INSTAGRAM
	.controller('InstagramLandingCtrl', InstagramLandingCtrl)
	.controller('InstagramAccountsRankingCtrl', InstagramAccountsRankingCtrl)
	.controller('InstagramAccountsInternalCtrl', InstagramAccountsInternalCtrl);

});