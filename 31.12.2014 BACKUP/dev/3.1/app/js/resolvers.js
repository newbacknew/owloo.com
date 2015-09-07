function getFilters($route, $location) {
	var routes = $route.current.params.filters,
		hash = $location,
		filters = [];
	if (routes) {
		routes = routes.split('/');
		for (var i = 0; i < routes.length; i += 2) {
			filters[routes[i]] = routes[i + 1];
		};
	}
	return filters;
}

function resolveHome ($q, OwlooUsers, FacebookPages, FacebookCountries, TwitterProfiles, InstagramProfiles) {
	// Filters
	var i = 'es', l = 4, r = function (response) { return response.data; };
	// Services
	var totalOwlooUsers  = OwlooUsers.getTotal().then(r),
		facebookGrowRank = FacebookPages.getGrowRank(i, l).then(r),
		twitterGrowRank = TwitterProfiles.getGrowRank(i, l).then(r),
		instagramGrowRank = InstagramProfiles.getGrowRank(l).then(r),
		countryGrowRank = FacebookCountries.getGrow(i).then(r);
	// Promise
	return $q.all([totalOwlooUsers, facebookGrowRank, twitterGrowRank, instagramGrowRank, countryGrowRank]).then(function(result) {
		return {
			'totalOwlooUsers': result[0],
			'facebookGrowRank': result[1],
			'twitterGrowRank': result[2],
			'instagramGrowRank': result[3],
			'countryGrowRank': result[4]
		}
	});
}

function resolveFeatures ($q, FacebookPages, TwitterProfiles, InstagramProfiles, OwlooUsers) {
	// Filters
	var w = 'world', r = function (response) { return response.data; };
	// Services
	var facebook = FacebookPages.getTotal(w).then(r),
		twitter = TwitterProfiles.getTotal(w).then(r),
		instagram = InstagramProfiles.getTotal().then(r),
		owloo = OwlooUsers.getTotal().then(r);
	// return
	return $q.all([facebook, twitter, instagram, owloo]).then(function(result) {
		return {
			'totalFacebookPages': result[0].total,
			'totalTwitterProfiles': result[1].total,
			'totalInstagramProfiles': result[2].total,
			'totalOwlooUsers': result[3],
		}
	});

}
/*
|--------------------------------------------------------------------------
| FACEBOOK ANALYTICS
|--------------------------------------------------------------------------
*/
// --- LANDING
function FacebookAnalyticsLandingResolver ($q, API) {
	// Services
	var facebookGrowRank = API.getGrowRank('all', 6).then(function (response) { return response.data; });
	// Promise
	return $q.all([facebookGrowRank]).then(function(result) {
		return {
			'request'			: 'landing',
			'facebookGrowRank'	: result[0]
		}
	});
}
// --- RANKINGS
function FacebookAnalyticsPagesRankResolver ($q, $route, $location, API) {
	// Settings
	var filters 	= getFilters($route, $location);
	// Filters
	var country 	= (filters.country) ? filters.country : 'world',
		tag 		= (filters.category) ? filters.category : 'all',
		idiom 		= (filters.idiom) ? filters.idiom : 'all',
		page 		= (filters.page) ? filters.page : 1,
		response	= function (response) { return response.data; };
	// Services
	var rank  		= API.getRank(country, tag, page).then(response),
		total 		= API.getTotal(country, tag).then(response),
		countries 	= API.getCountries().then(response),
		categories 	= API.getCategories().then(response);
	// Promise
	return $q.all([rank, total, countries, categories]).then(function(result) {
		return {
			'request'		: 'ranking',
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
}
// --- INTERNAL
function FacebookAnalyticsPagesInternalResolver ($q, $route, $location, API) {
	// Filters
	var username = ($route.current.params.filters.search('/') > 0) ? $route.current.params.filters.replace('/', '') : $route.current.params.filters;
	// Services
	var fanpage = API.getPage(username).then(function (response) { return response.data; });
	// Promise
	return $q.all([fanpage]).then(function(result) {
		return {
			'request'	: 'internal',
			'page'		: result[0]
		}
	});
}
/*
|--------------------------------------------------------------------------
| FACEBOOK RESEARCH
|--------------------------------------------------------------------------
*/
function resolveFacebookResearchLanding ($q, API) {
	// Filters
	var r = function (response) { return response.data; };
	// Services
	var total  = API.getTotal('all').then(r),
		women  = API.getTotal('women').then(r),
		men  = API.getTotal('men').then(r),
		cpc  = API.getAverageCpc().then(r);
	// Promise
	return $q.all([total, women, men, cpc]).then(function(result) {
		return {
			'facebookAllUsers': result[0].total,
			'facebookWomenUsers': result[1].total,
			'facebookMenUsers': result[2].total,
			'facebookAverageCPC': result[3]
		}
	});
}


function resolveFacebookContinentsRank ($q, params, API) {
	// Filters
	var w = (params.where) ? params.where : 'world',
		p = (params.page) ? params.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank = API.getRank(w, p).then(r),
		total = API.getTotal(w).then(r);
	// Promise
	return $q.all([rank, total]).then(function(result) {
		return {
			'where': w,
			'page': p,
			'rank': result[0],
			'total': result[1].total
		}
	});	
}

function resolveFacebookCountriesRank ($q, params, API) {
	var foo = params.filters, filters = [];
	if (foo) {
		foo = foo.split('/');
		for (var i = 0; i < foo.length; i += 2) {
			filters[foo[i]] = foo[i + 1];
		};
	}
	// Filters
	var w = (filters.country) ? filters.country : 'world',
		p = (filters.page) ? filters.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank = API.getRank(w, p).then(r),
		total = API.getTotal(w).then(r);
	// Promise
	return $q.all([rank, total]).then(function(result) {
		return {
			'request': 'ranking-countries',
			'where': w,
			'page': p,
			'rank': result[0],
			'total': result[1].total
		}
	});	
}

function resolveFacebookCountry ($q, params, API) {
	// Filters
	var w = (params.where) ? params.where : 'world',
		p = (params.page) ? params.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank = API.getRank(w, p).then(r),
		total = API.getTotal(w).then(r);
	// Promise
	return $q.all([rank, total]).then(function(result) {
		return {
			'where': w,
			'page': p,
			'rank': result[0],
			'total': result[1].total
		}
	});	
}

function resolveFacebookRegionsRank ($q, params, API) {
	// Filters
	var w = (params.where) ? params.where : 'world',
		p = (params.page) ? params.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank = API.getRank(w, p).then(r),
		total = API.getTotal(w).then(r);
	// Promise
	return $q.all([rank, total]).then(function(result) {
		return {
			'where': w,
			'page': p,
			'rank': result[0],
			'total': result[1].total
		}
	});	
}

function resolveFacebookRegion ($q, params, API) {
	// Filters
	var w = (params.where) ? params.where : 'world',
		p = (params.page) ? params.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank = API.getRank(w, p).then(r),
		total = API.getTotal(w).then(r);
	// Promise
	return $q.all([rank, total]).then(function(result) {
		return {
			'where': w,
			'page': p,
			'rank': result[0],
			'total': result[1].total
		}
	});	
}

function resolveFacebookCitiesRank ($q, params, API) {
	// Filters
	var w = (params.where) ? params.where : 'world',
		p = (params.page) ? params.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank  = API.getRank(w, p).then(r),
		total = API.getTotal(w).then(r),
		countries = API.getCountries().then(r);
	// Promise
	return $q.all([rank, total, countries]).then(function(result) {
		return {
			'where': w,
			'page': p,
			'rank': result[0],
			'total': result[1].total,
			'countries': result[2]
		}
	});
}

function resolveFacebookCity ($q, params, API) {
	// Filters
	var w = (params.where) ? params.where : 'world',
		p = (params.page) ? params.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank = API.getRank(w, p).then(r),
		total = API.getTotal(w).then(r);
	// Promise
	return $q.all([rank, total]).then(function(result) {
		return {
			'where': w,
			'page': p,
			'rank': result[0],
			'total': result[1].total
		}
	});	
}
/*
|--------------------------------------------------------------------------
| TWITTER ANALYTICS
|--------------------------------------------------------------------------
*/
// --- LANDING
function TwitterAnalyticsLandingResolver ($q, API) {
	// Filters
	var r = function (response) { return response.data; };
	// Services
	var twitterLastAdded = API.getLastAdded('all', 6).then(r);
	// Promise
	return $q.all([twitterLastAdded]).then(function(result) {
		return {
			'request': 'landing',
			'twitterLastAdded': result[0]
		}
	});
}
// --- Ranking
function TwitterAnalyticsProfilesRankResolver ($q, params, API) {
	// Filters
	var w = (params.where) ? params.where : 'world',
		p = (params.page) ? params.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank  = API.getRank(w, p).then(r),
		total = API.getTotal(w).then(r);
	// Promise
	return $q.all([rank, total]).then(function(result) {
		return {
			'request': 'ranking',
			'where': w,
			'page': p,
			'rank': result[0],
			'total': result[1].total
		}
	});
}
// --- Internal
function TwitterAnalyticsProfilesInternalResolver($q, params, API) {
	// Filters
	var w = (params.where) ? params.where : 'world',
		p = (params.page) ? params.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank  = API.getRank(w, p).then(r),
		total = API.getTotal(w).then(r);
	// Promise
	return $q.all([rank, total]).then(function(result) {
		return {
			'request': 'internal',
			'where': w,
			'page': p,
			'rank': result[0],
			'total': result[1].total
		}
	});
}

function resolveInstagramAnalyticsLanding ($q, API) {
	// Filters
	var r = function (response) { return response.data; };
	// Services
	var instagramGrowRank = API.getGrowRank(6).then(r);
	var instagramLastAdded = API.getLastAdded(6).then(r);
	// Promise
	return $q.all([instagramGrowRank, instagramLastAdded]).then(function(result) {
		return {
			'instagramGrowRank': result[0],
			'instagramLastAdded': result[1]
		}
	});
}

function resolveInstagramRank ($q, params, API) {
	
	console.log(params);
	
	// Filters
	var c = (params.category) ? params.category : 'all',
		p = (params.page) ? params.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank  = API.getRank(c, p).then(r),
		total = API.getTotal(c).then(r);
	// Promise
	return $q.all([rank, total]).then(function(result) {
		return {
			'page': p,
			'rank': result[0],
			'total': result[1].total
		}
	});
}




/*function resolveTwitterRank ($q, params, API) {
	// Filters
	var w = (params.where) ? params.where : 'world',
		p = (params.page) ? params.page.split("-")[1] : 1,
		r = function (response) { return response.data; };
	// Services
	var rank  = API.getRank(w, p).then(r),
		total = API.getTotal(w).then(r);
	// Promise
	return $q.all([rank, total]).then(function(result) {
		return {
			'where': w,
			'page': p,
			'rank': result[0],
			'total': result[1].total
		}
	});
}*/