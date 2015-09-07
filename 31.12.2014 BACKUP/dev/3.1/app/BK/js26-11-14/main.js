'use strict';

require.config({
	paths: {
		d3: 'https://d8p6r5bkbveek.cloudfront.net/app/components/d3/d3.min',
		underscore: 'https://d8p6r5bkbveek.cloudfront.net/app/components/underscore/underscore-min',
		fontLoader: 'http://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont',
		angular: 'http://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular.min',
		angularRoute: 'https://d8p6r5bkbveek.cloudfront.net/app/components/angular-route/angular-route.min',
		angularAnimate: 'https://d8p6r5bkbveek.cloudfront.net/app/components/angular-animate/angular-animate.min',
		angularCookies: 'https://d8p6r5bkbveek.cloudfront.net/app/components/angular-cookies/angular-cookies.min',
		angularFoundation: 'https://d8p6r5bkbveek.cloudfront.net/app/components/angular-foundation/mm-foundation-tpls.min',
		angularTranslate: 'https://d8p6r5bkbveek.cloudfront.net/app/components/angular-translate/angular-translate.min',
	},
	shim: {
		'angular' : {'exports' : 'angular'},
		'angularAnimate': ['angular'],
		'angularCookies': ['angular'],
		'angularFoundation': ['angular'],
		'angularRoute': ['angular'],
		'angularTranslate': ['angular']
	},
	priority: ['angular', 'typekit'],
	deps: ['bootstrap']
});