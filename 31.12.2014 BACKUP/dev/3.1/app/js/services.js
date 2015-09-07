define(['angular'], function (angular) {

	'use strict';

	var server = 'http://www.owloo.com/dev/3.1/server/public' ;

	angular.module('owloo.services', [])

	.factory("API", function ($http) {
		function call (request) {
			return $http.get(server + request, { cache: true }).success(function (data) { return data; });
		}
		return {
			facebook: {
				continents: {
					getRank: function() {
						return call('/facebook/continent/ranking');
					}
				},
				countries: {
					getCountries: function() {
						return call('/facebook/page/countries');
					},
					/*
						@param | string  | idiom | ej: {idiom: all, en, es, it, fr, pt}
						@param | integer | page  | ej: 1, 8, 13, ...
					*/
					getRank: function(idiom, page) {
						return call('/facebook/country/ranking/' + idiom + '/' + page);
					},
					/*
						@param | string  | idiom | ej: {idiom: all, en, es, it, fr, pt}
					*/
					getTotal: function(idiom) {
						return call('/facebook/country/total/' + idiom);
					},
					/*
						@param | string  | code  | ej: py, us, br, ...
					*/
					getCountry: function(code) {
						return call('/facebook/country/' + code);
					},
					/*
						@param | string  | idiom | ej: {idiom: all, en, es, it, fr, pt}
					*/
					getGrow: function(idiom) {
						return call('/facebook/country/grow/' + idiom);
					}
				},
				regions: {
					/*
						@param | string  | country | ej: world, {idiom: all, en, es, it, fr, pt}, {country: brazil, united-states, ...}
						@param | integer | page    | ej: 1, 8, 13, ...
					*/
					getRank: function(where, page) {
						return call('/facebook/region/ranking/' + where + '/' + page);
					},
					/*
						@param | integer | region | ej: 1, 8, 13, ... (id_region)
					*/
					getRegion: function(region) {
						return call('/facebook/region/details/' + region);
					},
					/*
						@param | string  | country | ej: world, hispanic, {idiom = en, es, it, fr, pt}, {country = brazil, united-states, ...}
					*/
					getTotal: function(where) {
						return call('/facebook/region/total/'+ where);
					},
					/*
						@param | null
					*/
					getCountries: function() {
						return call('/facebook/region/countries');
					}
				},
				cities: {
					/*
						@param | string  | country | ej: world, hispanic, {idiom = en, es, it, fr, pt}, {country = paraguay, united-states, ...}
						@param | integer | page    | ej: 1, 8, 13, ...
					*/
					getRank: function(country, page) {
						return call('/facebook/city/ranking/' + country + '/' + page);
					},
					/*
						@param | integer | city | ej: 1, 8, 13, ... (id_city)
					*/
					getCity: function(city) {
						return call('/facebook/city/details/' + city);
					},
					/*
						@param | string  | country | ej: world, hispanic, {idiom = en, es, it, fr, pt}, {country = paraguay, united-states, ...}
					*/
					getTotal: function(where) {
						return call('/facebook/city/total/'+ where);
					},
					getCountries: function() {
						return call('/facebook/city/countries');
					}
				},
				pages: {
					getCountries: function () {
						return call('/facebook/page/countries');
					},
					getCategories: function () {
						return call('/facebook/page/categories');
					},
					/*
						@param | string | where | ej: world, hispanic, {idiom = en, es, it, fr, pt}, {country = paraguay, united-states, ...}
						@param | string | tag 	| ej: all, internet, media, ...
					*/
					getTotal: function (where, tag) {
						return call('/facebook/page/total/' + where + '/' + tag);
					},
					/*
						@param | string  | where | ej: all, {idiom = en, es, it, fr, pt}
						@param | integer | limit | ej: 1, 2, 3, ...
					*/
					getGrowRank: function (where, limit) {
						return call('/facebook/page/grow/' + where + '/' + limit);
					},
					/*
						@param | string  | where    | ej: world, hispanic, {idiom = en, es, it, fr, pt}, {country = paraguay, united-states, ...}
						@param | string  | tag 		| ej: internet, media, ...
						@param | integer | page     | ej: 1, 8, 13, ...
					*/
					getRank: function (where, tag, page) {
						return call('/facebook/page/ranking/' + where + '/' + tag + '/' + page);
					},
					/*
						@param | string  | username | ej: cristiano, fcbarcelona, ...
					*/
					getPage: function (username) {
						return call('/facebook/page/username/' + username);
					},
					/*
						@param | string  | username | ej: cristiano, fcbarcelona, ...
						@param | string  | country  | ej: py, us, br, ...
					*/
					getLocalFans: function (username, country) {
						return call('/facebook/page/username/' + username + '/local-fans/' + country);
					}
				},
				users: {
					getTotal: function(who) {
						return call('/facebook/user/total/' + who);
					},
					getAverageCpc: function() {
						return call('/facebook/user/cpc');
					}
				}
			},
			twitter: {
				profiles: {
					/*
						@param | string  | country | ej: world, hispanic, {idiom = en, es, it, fr, pt}
					*/
					getTotal: function (where) {
						return call('/twitter/profile/total/' + where);
					},
					/*
						@param | string  | where | ej: all, {idiom = en, es, it, fr, pt}
						@param | integer | limit | ej: 1, 2, 3, ...
					*/
					getGrowRank: function (where, limit) {
						return call('/twitter/profile/grow/' + where + '/' + limit);
					},
					/*
						@param | string  | where | ej: all, {idiom = en, es, it, fr, pt}
						@param | integer | limit | ej: 1, 2, 3, ...
					*/
					getLastAdded: function (where, limit) {
						return call('/twitter/profile/last-added/' + where + '/' + limit);
					},
					/*
						@param | string  | where | ej: world, hispanic, {idiom = en, es, it, fr, pt}
						@param | integer | page    | ej: 1, 8, 13, ...
					*/
					getRank: function (where, page) {
						return call('/twitter/profile/ranking/' + where + '/' + page);
					},
					/*
						@param | string  | username | ej:  katyperry, barackobama, ...
					*/
					getProfile: function (username) {
						return call('/twitter/profile/username/' + username);
					}
				}
			},
			instagram: {
				accounts: {
					/*
						@param | null
					*/
					getCategories: function () {
						return call('/instagram/profile/categories');
					},
					/*
						@param | string  | category | ej:  all, sports, brands, ...
					*/
					getTotal: function(category) {
						return call('/instagram/profile/total/' + category);
					},
					/*
						@param | integer | limit | ej: 1, 2, 3, ... 
					*/
					getGrowRank: function (limit) {
						return call('/instagram/profile/grow/' + limit);
					},
					/*
						@param | integer | limit | ej: 1, 2, 3, ...
					*/
					getLastAdded: function (limit) {
						return call('/instagram/profile/last-added/' + limit);
					},
					/*
						@param | string  | category | ej:  all, sports, brands, ...
						@param | integer | page     | ej: 1, 8, 13, ...
					*/
					getRank: function (category, page) {
						return call('/instagram/profile/ranking/' + category + '/' + page);
					},
					/*
						@param | string  | username | ej:  nike, bmw, ...
					*/
					getAccount: function (username) {
						return call('/instagram/profile/username/' + username);
					}
				}
			},
			owloo: {
				users: {
					getTotal: function () {
						return call('/owloo/user/total');
					}
				}
			}
		}
	})

	.factory("Resolver", function ($q, $route, $location, API) {
		var response = function (response) { return response.data; };
		return {
			home: function() {
				// Params
				var idiom 	= 'es',
					limit 	= 4;
				// Services
				var totalOwlooUsers		= API.owloo.users.getTotal().then(response),
					facebookGrowRank 	= API.facebook.pages.getGrowRank(idiom, limit).then(response),
					twitterGrowRank 	= API.twitter.profiles.getGrowRank(idiom, limit).then(response),
					instagramGrowRank	= API.instagram.accounts.getGrowRank(limit).then(response),
					countryGrowRank		= API.facebook.countries.getGrow(idiom).then(response);
				// Promise
				return $q.all([totalOwlooUsers, facebookGrowRank, twitterGrowRank, instagramGrowRank, countryGrowRank]).then(function(result) {
					return {
						'totalOwlooUsers'	: result[0],
						'facebookGrowRank'	: result[1],
						'twitterGrowRank'	: result[2],
						'instagramGrowRank'	: result[3],
						'countryGrowRank'	: result[4]
					}
				});
			},
			facebook: {
				research: {
					landing: function() {
						// Services
						var total = API.facebook.users.getTotal('all').then(response),
							women = API.facebook.users.getTotal('women').then(response),
							men   = API.facebook.users.getTotal('men').then(response),
							cpc   = API.facebook.users.getAverageCpc().then(response);
						// Promise
						return $q.all([total, women, men, cpc]).then(function(result) {
							return {
								'facebookAllUsers'	: result[0].total,
								'facebookWomenUsers': result[1].total,
								'facebookMenUsers'	: result[2].total,
								'facebookAverageCPC': result[3]
							}
						});
					},
					continents: {
						ranking: function() {
							// Services
							var ranking = API.facebook.continents.getRank().then(response);
							// Promise
							return $q.all([ranking]).then(function(result) {
								return {
									'ranking': result[0]
								}
							});	
						}
					},
					countries: {
						ranking: function() {
							// Params
							var idiom 	= ($route.current.params.idiom) ? $route.current.params.idiom : 'world',
								page 	= ($route.current.params.page) ? $route.current.params.page : 1;
							// Services
							var ranking	= API.facebook.countries.getRank(idiom, page).then(response),
								total 	= API.facebook.countries.getTotal(idiom).then(response);
							// Promise
							return $q.all([ranking, total]).then(function(result) {
								return {
									'ranking'	: result[0],
									'total'		: result[1].total,
									'idiom'		: idiom,
									'page'		: page
								}
							});	
						},
						internal: function() {
							// Params
							var country = $route.current.params.country;
							// Services
							var stats 	= API.facebook.countries.getCountry($route.current.params.country).then(response);
							// Promise
							return $q.all([stats]).then(function(result) {
								return {
									'country' : result[0]
								}
							});
						}
					},
					regions: {
						ranking: function() {
							// Params
							var country		= ($route.current.params.country) ? $route.current.params.country : 'world',
								idiom		= ($route.current.params.idiom) ? $route.current.params.idiom : 'all',
								page		= ($route.current.params.page) ? $route.current.params.page : 1;
							var where		= (country == 'world' && idiom != 'all') ? idiom : country;
							// Services
							var ranking		= API.facebook.regions.getRank(where, page).then(response),
								total 		= API.facebook.regions.getTotal(where).then(response),
								countries 	= API.facebook.regions.getCountries().then(response);
							// Promise
							return $q.all([ranking, total, countries]).then(function(result) {
								return {
									'ranking'	: result[0],
									'total'		: result[1].total,
									'countries'	: result[2],
									'country'	: country,
									'idiom'		: idiom,
									'page'		: page,
								}
							});	
						}
					},
					cities: {
						ranking: function() {
							// Params
							var country 	= ($route.current.params.country) ? $route.current.params.country : 'world',
								idiom		= ($route.current.params.idiom) ? $route.current.params.idiom : 'all',
								page		= ($route.current.params.page) ? $route.current.params.page : 1;
							var where		= (country == 'world' && idiom != 'all') ? idiom : country;
							// Services
							var ranking		= API.facebook.cities.getRank(where, page).then(response),
								total 		= API.facebook.cities.getTotal(where).then(response),
								countries 	= API.facebook.cities.getCountries().then(response);
							// Promise
							return $q.all([ranking, total, countries]).then(function(result) {
								return {
									'ranking'	: result[0],
									'total'		: result[1].total,
									'countries'	: result[2],
									'country'	: country,
									'idiom'		: idiom,
									'page'		: page
								}
							});
						}
					}
				},
				analytics: {
					landing: function() {
						// Services
						var facebookGrowRank = API.facebook.pages.getGrowRank('all', 6).then(response);
						// Promise
						return $q.all([facebookGrowRank]).then(function(result) {
							return {
								'facebookGrowRank'	: result[0]
							}
						});
					},
					pages: {
						ranking: function() {
							// Params
							var country		= ($route.current.params.country) ? $route.current.params.country : 'world',
								tag			= ($route.current.params.tag) ? $route.current.params.tag : 'all',
								idiom 		= ($route.current.params.idiom) ? $route.current.params.idiom : 'all',
								page 		= ($route.current.params.page) ? $route.current.params.page : 1,
								response	= function (response) { return response.data; };
							// Services
							var ranking		= API.facebook.pages.getRank(country, tag, page).then(response),
								total 		= API.facebook.pages.getTotal(country, tag).then(response),
								countries 	= API.facebook.pages.getCountries().then(response),
								categories 	= API.facebook.pages.getCategories().then(response);
							// Promise
							return $q.all([ranking, total, countries, categories]).then(function(result) {
								return {
									'ranking'		: result[0],
									'total'			: result[1].total,
									'countries'		: result[2],
									'categories'	: result[3],
									'country'		: country,
									'tag'			: tag,
									'idiom'			: idiom,
									'page'			: page
								}
							});
						},
						internal: function() {
							// Params
							var username = $route.current.params.username;
							// Services
							var page = API.facebook.pages.getPage(username).then(response);
							// Promise
							return $q.all([page]).then(function(result) {
								return {
									'page': result[0]
								}
							});
						}
					}
				}
			},
			twitter: {
				analytics: {
					landing: function() {
						// Services
						var twitterLastAdded = API.twitter.profiles.getLastAdded('all', 6).then(response);
						// Promise
						return $q.all([twitterLastAdded]).then(function(result) {
							return {
								'twitterLastAdded': result[0]
							}
						});
					},
					profiles: {
						ranking: function() {
							// Params
							var idiom 	= ($route.current.params.idiom) ? $route.current.params.idiom : 'world',
								page 	= ($route.current.params.page) ? $route.current.params.page : 1;
							// Services
							var ranking	= API.twitter.profiles.getRank(idiom, page).then(response),
								total 	= API.twitter.profiles.getTotal(idiom).then(response);
							// Promise
							return $q.all([ranking, total]).then(function(result) {
								return {
									'ranking'	: result[0],
									'total'		: result[1].total,
									'idiom'		: idiom,
									'page'		: page
								}
							});
						},
						internal: function() {
							// Filters
							var username	= $route.current.params.username;
							// Services
							var profile 	= API.twitter.profiles.getProfile(username).then(function (response) { return response.data; });
							// Promise
							return $q.all([profile]).then(function(result) {
								return {
									'profile'	: result[0]
								}
							});
						}
					}
				}
			},
			instagram: {
				analytics: {
					landing: function() {
						// Services
						var instagramGrowRank 	= API.instagram.accounts.getGrowRank(6).then(response);
						var instagramLastAdded 	= API.instagram.accounts.getLastAdded(6).then(response);
						// Promise
						return $q.all([instagramGrowRank, instagramLastAdded]).then(function(result) {
							return {
								'instagramGrowRank': result[0],
								'instagramLastAdded': result[1]
							}
						});
					},
					accounts: {
						ranking: function() {
							// Params
							var tag 	= ($route.current.params.tag) ? $route.current.params.tag : 'all',
								page 	= ($route.current.params.page) ? $route.current.params.page : 1;
							// Services
							var ranking	= API.instagram.accounts.getRank(tag, page).then(response),
								total 	= API.instagram.accounts.getTotal(tag).then(response);
							// Promise
							return $q.all([ranking, total]).then(function(result) {
								return {
									'ranking'	: result[0],
									'total'		: result[1].total,
									'tag'		: tag,
									'page'		: page
								}
							});
						},
						internal: function() {
							// Filters
							var username	= $route.current.params.username;
							// Services
							var profile 	= API.instagram.accounts.getAccount(username).then(function (response) { return response.data; });
							// Promise
							return $q.all([profile]).then(function(result) {
								return {
									'profile'	: result[0]
								}
							});
						}
					}
				}
			}
		}
	})

	.factory("Formatter", function () {
		return {
			number: function (number) {
				var number = new String(number);
				var result = '';
				while( number.length > 3 ){
					result = '.' + number.substr(number.length - 3) + result;
					number = number.substring(0, number.length - 3);
				}
				return number + result;
			}
	    }
	})

	.factory("ChartMaker", function (Formatter) {
		return {
			line: function (container, tooltip_name, x_axis_labels, y_axis_min, y_axis_max, series_name, series_data, tooltip_label) {
				var chart = new Highcharts.Chart({
					chart: { renderTo: container, backgroundColor: null },
					tooltip: {
						shared: true, backgroundColor: '#000000', borderWidth: 0, shadow: false, borderRadius: 5,
						formatter: function() { return '<b>' + tooltip_name + '</b><br/>'+ this.x +': '+ Formatter.number(this.y) +' ' + tooltip_label; },
						style: { color: '#FFFFFF' }, hideDelay: 100
					},
					xAxis: {
						categories: x_axis_labels, lineWidth: 0, gridLineWidth: 0, tickWidth: 0,
						labels: { step: 6, align: 'center', staggerLines: 1, style: { color: '#b8bfc8' } }
					},
					yAxis: { lineWidth: 0, gridLineWidth: 0, labels: { enabled: false }, title: { text: '' } },
					series: [{ name: series_name, data: series_data }],
					title: { text: '' },
					plotOptions: {
						series: {
							lineWidth: 3, lineColor: '#62BDF6', shadow: false,
							marker: { enabled: false, states: { hover: { fillColor: '#000000', lineColor: '#FFFFFF', lineWidth: 2, radius: 6 } } },
							states: { hover: { lineWidth: 3, halo: { size: 0 } } }
						}
					},
					legend: { enabled: false },
					credits: { enabled: false },
					exporting: { enabled: false }
				});
			},
			verticalBar: function (container, tooltip_name, x_axis_labels, y_axis_min, y_axis_max, series_name, series_data, tooltip_label) {
				var chart = new Highcharts.Chart({
					chart: {
						renderTo: container, backgroundColor: null, type: 'column'
					},
					tooltip: {
						shared: true, backgroundColor: '#000000', borderWidth: 0, shadow: false, borderRadius: 5,
						formatter: function() { return '<b>' + tooltip_name + '</b><br/>' + this.x +': '+ Formatter.number(this.y) +' ' + tooltip_label; },
						style: { color: '#FFFFFF' }, hideDelay: 100
					},
					xAxis: {
						categories: x_axis_labels, lineColor: '#F1F1F1', lineWidth: 0, gridLineWidth: 0, tickWidth: 0,
						labels: { step: 6, align: 'center', staggerLines: 1, style: { color: '#b8bfc8' } }
					},
					yAxis: {
						min: y_axis_min, max: y_axis_max, lineWidth: 0, gridLineWidth: 0, labels: { enabled: false }, title: { text: '' }
					},
					series: [{ name: series_name, data: series_data }],
					title: { text: '' },
					plotOptions: {
						column: {
							borderWidth: 1, bordercolor: '#FFFFFF', color: '#D2F1F9', pointPadding: 0,
							states: { hover: { color: '#77CCF7' } }
						}
					},
					legend: { enabled: false },
					credits: { enabled: false },
					exporting: { enabled: false }
				});
			},
			horizontalBar: function (container, tooltip_name, x_axis_labels, y_axis_min, y_axis_max, series_name, series_data, tooltip_label) {
				var chart = new Highcharts.Chart({
					chart: {
						renderTo: container, backgroundColor: null, type: 'bar'
					},
					tooltip: {
						shared: true, backgroundColor: '#000000', borderWidth: 0, shadow: false, borderRadius: 5,
						formatter: function() { return '<b>' + tooltip_name + '</b><br/>' + this.x +': '+ Formatter.number(this.y) +' ' + tooltip_label; },
						style: { color: '#FFFFFF' }, hideDelay: 100						
					},
					xAxis: {
						categories: x_axis_labels, lineColor: '#F1F1F1', lineWidth: 0, gridLineWidth: 0, tickWidth: 0,
						labels: { enabled: false }
					},
					yAxis: {
						lineWidth: 0, gridLineWidth: 0, labels: { enabled: false }, title: { text: '' },
				        stackLabels: {
				            formatter: function() { return this.axis.chart.xAxis[0].categories[this.x]; },
				            enabled: true, verticalAlign: 'middle', align: 'left', style: { color: '#FFFFFF' }
				        }
					},
					series: [{ name: series_name, data: series_data }],
					title: { text: '' },
					plotOptions: {
						bar: {
				            borderWidth: 1, bordercolor: '#FFFFFF', color: '#d2f1f9', pointWidth: 28, states: { hover: { color: '#77CCF7' } }, stacking: 'normal'
				        }
					},
					legend: { enabled: false },
					credits: { enabled: false },
					exporting: { enabled: false }
				});
			},
			circle: function (container, value, max_value) {
				var myCircle = Circles.create({
				  id:           container,
				  radius:       90,
				  value:        value,
				  maxValue:     max_value,
				  width:        10,
				  text:         function(value){return value + '%';},
				  colors:       ['#F8F8F8', '#77CCF7'],
				  duration:       400,
				  wrpClass:     'circles-wrp',
				  textClass:      'circles-text',
				  styleWrapper: true,
				  styleText:    true
				});
			}
	    }
	});
});