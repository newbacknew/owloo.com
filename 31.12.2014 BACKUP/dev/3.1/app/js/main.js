'use strict';

require.config({
	paths: {
		underscore: 'https://d8p6r5bkbveek.cloudfront.net/app/components/underscore/underscore-min',
		fontLoader: 'http://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont',
		angular: 'http://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular.min',
		angularRoute: 'https://d8p6r5bkbveek.cloudfront.net/app/components/angular-route/angular-route.min',
		angularAnimate: 'https://d8p6r5bkbveek.cloudfront.net/app/components/angular-animate/angular-animate.min',
		angularFoundation: 'https://d8p6r5bkbveek.cloudfront.net/app/components/angular-foundation/mm-foundation-tpls.min',
		angularTranslate: 'https://d8p6r5bkbveek.cloudfront.net/app/components/angular-translate/angular-translate.min',
		highchartsStandalone: 'http://code.highcharts.com/adapters/standalone-framework',
		highcharts: 'http://code.highcharts.com/highcharts',
		circlesCharts: 'https://d8p6r5bkbveek.cloudfront.net/app/components/circles/circles.min'
	},
	shim: {
		'angular' : {'exports' : 'angular'},
		'angularAnimate': ['angular'],
		'angularFoundation': ['angular'],
		'angularRoute': ['angular'],
		'angularTranslate': ['angular'],
		'highcharts': ['highchartsStandalone']
	},
	priority: ['angular', 'highchartsStandalone'],
	deps: ['bootstrap']
});