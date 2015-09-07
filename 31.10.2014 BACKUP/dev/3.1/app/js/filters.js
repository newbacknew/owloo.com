define(['angular'], function (app) {

	'use strict';

	var module = app.module('filters', []);

	module.filter('replace', function () {

		return function (input, search, replace) {

			return input.replace(search, replace);

		}

	});

	return module;

});