define([
	'angular',
	'angularRoute'
], function (angular) {

	'use strict';

	return angular.module('owloo.routes', ['ngRoute'])

	.config (function ($locationProvider, $httpProvider, $sceDelegateProvider, $routeProvider) {

		var server = 'http://www.owloo.com/dev/3.1';
		var templatesFolder = server + '/app/partials';

		$locationProvider.html5Mode(true);

		$httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
 
		$sceDelegateProvider.resourceUrlWhitelist(['self', server + '/**']);

		$routeProvider
			/*
			|--------------------------------------------------------------------------
			| HOME
			|--------------------------------------------------------------------------
			*/
			.when('/', {
				controller: 'HomeCtrl',
				templateUrl: templatesFolder + '/home.html',
				resolve: { data: function(Resolver) { return Resolver.home(); } }
			})
			/*
			|--------------------------------------------------------------------------
			| FEATURES
			|--------------------------------------------------------------------------
			*/
			.when('/features', {
				controller: 'FeaturesCtrl',
				templateUrl: templatesFolder + '/features.html',
			})
			/*
			|--------------------------------------------------------------------------
			| FACEBOOK STATISTICS
			|--------------------------------------------------------------------------
			*/
			.when('/facebook-statistics', {
				controller: 'FacebookStatisticsLandingCtrl',
				templateUrl: templatesFolder + '/facebook-statistics-landing.html',
			})
			/*
			|--------------------------------------------------------------------------
			| FACEBOOK RESEARCH
			|--------------------------------------------------------------------------
			*/
			// --- LANDING
			.when('/facebook-research', {
				controller: 'FacebookResearchLandingCtrl',
				templateUrl: templatesFolder + '/facebook-research-landing.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.research.landing(); } }
			})
			// --- CONTINENTS | RANKING
			.when('/facebook-stats/continents', {
				controller: 'FacebookContinentsCtrl',
				templateUrl: templatesFolder + '/facebook-research-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.research.continents.ranking(); } }
			})
			// --- REGIONS | RANKING
			.when('/facebook-stats/regions', {
				controller: 'FacebookRegionsCtrl',
				templateUrl: templatesFolder + '/facebook-research-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.research.regions.ranking(); } }
			})
			.when('/facebook-stats/regions/country/:country', {
				controller: 'FacebookRegionsCtrl',
				templateUrl: templatesFolder + '/facebook-research-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.research.regions.ranking(); } }
			})
			// --- CITIES | RANKING
			.when('/facebook-stats/cities', {
				controller: 'FacebookCitiesCtrl',
				templateUrl: templatesFolder + '/facebook-research-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.research.cities.ranking(); } }
			})
			.when('/facebook-stats/cities/country/:country', {
				controller: 'FacebookCitiesCtrl',
				templateUrl: templatesFolder + '/facebook-research-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.research.cities.ranking(); } }
			})
			// --- COUNTRIES | RANKING
			.when('/facebook-stats/countries', {
				controller: 'FacebookCountriesCtrl',
				templateUrl: templatesFolder + '/facebook-research-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.research.countries.ranking(); } }
			})
			// --- COUNTRIES | INTERNAL
			.when('/facebook-stats/:country', {
				controller: 'FacebookCountryCtrl',
				templateUrl: templatesFolder + '/facebook-research-country.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.research.countries.internal(); } }
			})
			/*
			|--------------------------------------------------------------------------
			| FACEBOOK ANALYTICS
			|--------------------------------------------------------------------------
			*/
			// --- LANDING
			.when('/facebook-analytics', {
				controller: 'FacebookAnalyticsLandingCtrl',
				templateUrl: templatesFolder + '/facebook-analytics-landing.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.analytics.landing(); } }
			})
			// --- PAGES | RANKING
			.when('/facebook-analytics/pages', {
				controller: 'FacebookPagesRankingCtrl',
				templateUrl: templatesFolder + '/facebook-analytics-pages-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.analytics.pages.ranking(); } }
			})
			.when('/facebook-analytics/pages/tag/:tag', {
				controller: 'FacebookPagesRankingCtrl',
				templateUrl: templatesFolder + '/facebook-analytics-pages-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.analytics.pages.ranking(); } }
			})
			.when('/facebook-analytics/pages/country/:country', {
				controller: 'FacebookPagesRankingCtrl',
				templateUrl: templatesFolder + '/facebook-analytics-pages-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.analytics.pages.ranking(); } }
			})
			.when('/facebook-analytics/pages/country/:country/tag/:tag', {
				controller: 'FacebookPagesRankingCtrl',
				templateUrl: templatesFolder + '/facebook-analytics-pages-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.analytics.pages.ranking(); } }
			})
			.when('/facebook-analytics/pages/tag/:tag/country/:country', {
				controller: 'FacebookPagesRankingCtrl',
				templateUrl: templatesFolder + '/facebook-analytics-pages-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.analytics.pages.ranking(); } }
			})
			// --- PAGES | INTERNAL
			.when('/facebook-analytics/pages/:username', {
				controller: 'FacebookPagesInternalCtrl',
				templateUrl: templatesFolder + '/facebook-analytics-pages-internal.html',
				resolve: { data: function(Resolver) { return Resolver.facebook.analytics.pages.internal(); } }
			})
			/*
			|--------------------------------------------------------------------------
			| TWITTER ANALYTICS
			|--------------------------------------------------------------------------
			*/
			// -- LANDING
			.when('/twitter-analytics', {
				controller: 'TwitterLandingCtrl',
				templateUrl: templatesFolder + '/twitter-analytics-landing.html',
				resolve: { data: function(Resolver) { return Resolver.twitter.analytics.landing(); } }
			})
			// --- PROFILES | RANKING
			.when('/twitter-analytics/profiles', {
				controller: 'TwitterProfilesRankingCtrl',
				templateUrl: templatesFolder + '/twitter-analytics-profiles-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.twitter.analytics.profiles.ranking(); } }
			})
			// --- PROFILES | INTERNAL
			.when('/twitter-analytics/profiles/:username?', {
				controller: 'TwitterProfilesInternalCtrl',
				templateUrl: templatesFolder + '/twitter-analytics-profiles-internal.html',
				resolve: { data: function(Resolver) { return Resolver.twitter.analytics.profiles.internal(); } }
			})
			/*
			|--------------------------------------------------------------------------
			| INSTAGRAM ANALYTICS
			|--------------------------------------------------------------------------
			*/
			// -- LANDING
			.when('/instagram-analytics', {
				controller: 'InstagramLandingCtrl',
				templateUrl: templatesFolder + '/instagram-analytics-landing.html',
				resolve: { data: function(Resolver) { return Resolver.instagram.analytics.landing(); } }
			})
			// --- ACCOUNTS | RANKING
			.when('/instagram-analytics/accounts', {
				controller: 'InstagramAccountsRankingCtrl',
				templateUrl: templatesFolder + '/instagram-analytics-accounts-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.instagram.analytics.accounts.ranking(); } }
			})
			.when('/instagram-analytics/accounts/tag/:tag', {
				controller: 'InstagramAccountsRankingCtrl',
				templateUrl: templatesFolder + '/instagram-analytics-accounts-ranking.html',
				resolve: { data: function(Resolver) { return Resolver.instagram.analytics.accounts.ranking(); } }
			})
			// --- ACCOUNTS | INTERNAL
			.when('/instagram-analytics/accounts/:username?', {
				controller: 'InstagramAccountsInternalCtrl',
				templateUrl: templatesFolder + '/instagram-analytics-accounts-internal.html',
				resolve: { data: function(Resolver) { return Resolver.instagram.analytics.accounts.internal(); } }
			})
			.otherwise({redirectTo: '/'});
	});

});