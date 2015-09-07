define([
	'angular',
	'angularAnimate',
	'angularFoundation',
	'services',
	'controllers',
	'directives',
	'filters',
	'routes',
	'translators',
	'underscore',
	'highchartsStandalone',
	'highcharts',
	'circlesCharts'
], function (angular) {

	'use strict';

	return angular.module('owloo', [
		'ngAnimate',
		'owloo.services', 'owloo.controllers', 'owloo.directives', 'owloo.filters', 'owloo.routes', 'owloo.translators'
	])

	.run(function ($rootScope) {

		$rootScope.idioms = {
			en: 'ENGLISH',
			es: 'SPANISH',
			it: 'ITALIAN',
			fr: 'FRENCH',
			pt: 'PORTUGUESE'
		};

		$rootScope.urlRoot = 'http://www.owloo.com/dev/3.1';

		var url = {
				root: 'http://www.owloo.com/dev/3.1',
				signin: 'http://www.owloo.com/dev/3.1/signin',
				features: 'http://www.owloo.com/dev/3.1/features',
				privacy: 'http://www.owloo.com/dev/3.1/privacy',
				facebook: {
					root: 'http://www.owloo.com/dev/3.1/facebook-statistics',
					analytics: {
						root: 'http://www.owloo.com/dev/3.1/facebook-analytics',
						pages: 'http://www.owloo.com/dev/3.1/facebook-analytics/pages',
					},
					research: {
						root: 'http://www.owloo.com/dev/3.1/facebook-research',
						country: 'http://www.owloo.com/dev/3.1/facebook-stats',
						countries: 'http://www.owloo.com/dev/3.1/facebook-stats/countries',
						regions: 'http://www.owloo.com/dev/3.1/facebook-stats/regions',
						cities: 'http://www.owloo.com/dev/3.1/facebook-stats/cities',
						continents: 'http://www.owloo.com/dev/3.1/facebook-stats/continents',
					}
				},
				twitter: {
					analytics: {
						root: 'http://www.owloo.com/dev/3.1/twitter-analytics',
						profiles: 'http://www.owloo.com/dev/3.1/twitter-analytics/profiles',
					}
				},
				instagram: {
					analytics: {
						root: 'http://www.owloo.com/dev/3.1/instagram-analytics',
						accounts: 'http://www.owloo.com/dev/3.1/instagram-analytics/accounts',
					}
				}
			};

		$rootScope.url = url;
		$rootScope.imagesFolder = 'http://www.owloo.com/dev/3.1/app/img';

	});

});