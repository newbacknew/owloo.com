'use strict';

require.config({
	paths: {
		angular: '../components/angular/angular.min',
		angularRoute: '../components/angular-route/angular-route.min',
		angularAnimate: '../components/angular-animate/angular-animate.min',
		angularCookies: '../components/angular-cookies/angular-cookies.min',
		angularFoundation: '../components/angular-foundation/mm-foundation-tpls.min',
		angularTranslate: '../components/angular-translate/angular-translate.min',
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