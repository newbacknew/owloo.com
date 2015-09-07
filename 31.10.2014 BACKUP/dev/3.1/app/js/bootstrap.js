require([
	'angular',
	'app'
], function(angular) {

	'use strict';

	angular.element().ready(function() {
		angular.bootstrap(document, ['owloo']);
	});

});