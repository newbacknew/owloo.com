define(['angular'], function (app) {

	'use strict';

	var module = app.module('directives', []);

	module.directive('owlooFooter', function() {
		return {
			restrict: 'E',
			templateUrl: 'app/partials/footer.html'	
		};
	});

	return module;

});