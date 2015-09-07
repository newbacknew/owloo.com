define(['angular'], function (app) {

	'use strict';

	var module = app.module('factories', []);

	module.factory("FacebookPages", function ($http) {

		return {
			getTotal: function(where) {
				return $http.post('http://owloo.com/dev/3.1/server/public/facebook/page/' + where + '/total')
				.success(function (data) { return data; });
			},
			getCountries: function () {
				return $http.post('http://owloo.com/dev/3.1/server/public/facebook/page/countries')
				.success(function (data) { return data; });
			},
			getCategories: function () {
				return $http.post('http://owloo.com/dev/3.1/server/public/facebook/page/categories')
				.success(function (data) { return data; });
			},
			getRank: function (where, category, page) {
				return $http.post('http://owloo.com/dev/3.1/server/public/facebook/page/ranking/' + where + '/' + category + '/' + page)
				.success(function (data) { return data; });
			},
			getRankGrow: function (idiom) {
				return $http.post('http://owloo.com/dev/3.1/server/public/facebook/page/grow/' + idiom)
				.success(function (data) { return data; });
			}
		}

	});

	module.factory("TwitterProfiles", function ($http) {
		return {
			getTotal: function() {
				return $http.post('http://owloo.com/dev/3.1/server/public/twitter/profile/world/total')
				.success(function (data) { return data; });
			}
	    }
	});

	module.factory("InstagramProfiles", function ($http) {
		return {
			getTotal: function() {
				return $http.post('http://owloo.com/dev/3.1/server/public/instagram/profile/world/total')
				.success(function (data) { return data; });
			}
	    }
	});

	module.factory("OwlooUsers", function ($http) {
		return {
			getTotal: function() {
				return $http.post('http://owloo.com/dev/3.1/server/public/owloo/user/total')
				.success(function (data) { return data; });
			}
	    }
	});

	return module;
});