define(['angular'], function (angular) {

	'use strict';

	var server = 'http://www.owloo.com/dev/3.1/server/public' ;

	angular.module('owloo.factories', [])

	.factory("FacebookPages", function ($http) {
		function call (request) {
			return $http.get(server + '/facebook/page' + request, { cache: true }).success(function (data) { return data; });
		}
		return {
			getCountries: function () {
				return call('/countries');
			},
			getCategories: function () {
				return call('/categories');
			},
			getTotal: function (idiom) {
				return call('/total/' + idiom);
			},
			getGrowRank: function (idiom) {
				return call('/grow/' + idiom);
			},
			getRank: function (idiom, category, page) {
				return call('/ranking/' + idiom + '/' + category + '/' + page);
			},
			getPage: function (username) {
				return call('/username/' + username);
			},
			getLocalFans: function (username, country) {
				return call('/username/' + username + '/local-fans/' + country);
			}
		}
	})

	.factory("FacebookCountries", function ($http) {
		function call (request) {
			return $http.get(server + '/facebook/country' + request, { cache: true }).success(function (data) { return data; });
		}
		return {
			getRank: function(idiom, page) {
				return call('/ranking/' + idiom + '/' + page);
			},
			getCountry: function(code) {
				return call('/' + code);
			},
/* */		getGrow: function(idiom) {
/* */			return call('/grow/' + idiom);
/* */		}
	    }
	})

	.factory("FacebookCities", function ($http) {
		function call (request) {
			return $http.get(server + 'facebook/city' + request, { cache: true }).success(function (data) { return data; });
		}
		return {
			getRank: function(idiom, page) {
				return call('/ranking/' + idiom + '/' + page);
			},
			getCountryCities: function(code, page) {
				return call('/country/'+ code + '/' + page);
			}
	    }
	})

	.factory("FacebookContinents", function ($http) {
		function call (request) {
			return $http.get(server + '/facebook/continent' + request, { cache: true }).success(function (data) { return data; });
		}
		return {
			getRank: function() {
				return call('/ranking');
			}
	    }
	})

	.factory("TwitterProfiles", function ($http) {
		function call (request) {
			return $http.get(server + '/twitter/profile' + request, { cache: true }).success(function (data) { return data; });
		}
		return {
			getTotal: function (idiom) {
				return call('/total/' + idiom);
			},
			getGrowRank: function (idiom) {
				return call('/grow/' + idiom);
			},
			getRank: function (idiom, page) {
				return call('/ranking/' + idiom + '/' + page);
			},
			getProfile: function (username) {
				return call('/username/' + username);
			}
	    }
	})

	.factory("InstagramProfiles", function ($http) {
		function call (request) {
			return $http.get(server + '/instagram/profile' + request, { cache: true }).success(function (data) { return data; });
		}
		return {
			getTotal: function() {
				return call('/total');
			},
			getGrowRank: function () {
				return call('/grow');
			},
			getRank: function (page) {
				return call('/ranking/' + page);
			},
			getProfile: function (username) {
				return call('/username/' + username);
			}
	    }
	})

	.factory("OwlooUsers", function ($http) {
		function call (request) {
			return $http.get(server + '/owloo/user' + request, { cache: true }).success(function (data) { return data; });
		}
		return {
			getTotal: function () {
				return call('/total');
			}
	    }
	});

});