define(['angular'], function (angular) {

	'use strict';

	var server = 'http://www.owloo.com/dev/3.1/server/public' ;

	angular.module('owloo.services', [])


	.factory("FacebookUsers", function ($http) {
		function call (request) {
			return $http.get(server + '/facebook/user' + request, { cache: true }).success(function (data) { return data; });
		}
		return {
			getTotal: function(who) {
				return call('/total/' + who);
			},
			getAverageCpc: function() {
				return call('/cpc');
			}
	    }
	})

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
			getTotal: function (where) {
				return call('/total/' + where);
			},
			getGrowRank: function (where) {
				return call('/grow/' + where);
			},
			getRank: function (where, category, page) {
				return call('/ranking/' + where + '/' + category + '/' + page);
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
			getRank: function(where, page) {
				return call('/ranking/' + where + '/' + page);
			},
			getTotal: function(where) {
				return call('/total/' + where);
			},
			getCountry: function(code) {
				return call('/' + code);
			},
			getGrow: function(where) {
				return call('/grow/' + where);
			}
	    }
	})

	.factory("FacebookCities", function ($http) {
		function call (request) {
			return $http.get(server + '/facebook/city' + request, { cache: true }).success(function (data) { return data; });
		}
		return {
			getRank: function(country, page) {
				return call('/ranking/' + country + '/' + page);
			},
			getTotal: function(country) {
				return call('/total/'+ country);
			},
			getCountries: function() {
				return call('/countries');
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
			getTotal: function (where) {
				return call('/total/' + where);
			},
			getGrowRank: function (where) {
				return call('/grow/' + where);
			},
			getRank: function (where, page) {
				return call('/ranking/' + where + '/' + page);
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