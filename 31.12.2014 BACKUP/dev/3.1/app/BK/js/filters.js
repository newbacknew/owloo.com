define(['angular'], function (angular) {

	'use strict';

	return angular.module('owloo.filters', [])

	.filter('replace', function () {

		return function (input, search, replace) {

			return input.replace(search, replace);

		}

	})

	.filter("truncate", function() {
	    return function(text, length, end) {
	        return text ? (isNaN(length) && (length = 10), void 0 === end && (end = "..."), text.length <= length ? text : String(text).substring(0, length - end.length) + end) : text
	    }
    });

});