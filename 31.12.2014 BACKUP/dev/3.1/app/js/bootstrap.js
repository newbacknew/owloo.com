require([
	'angular',
	'app',
	'fontLoader'
], function(angular) {

	'use strict';

	WebFont.load({
		typekit: {
			id: 'ayw6oar'
		},
		custom: {
			families: ['FontAwesome']
		}
	});

	angular.element().ready(function() {
		angular.bootstrap(document, ['owloo']);
	});

});