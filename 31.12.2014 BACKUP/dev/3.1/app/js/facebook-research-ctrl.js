/*
|--------------------------------------------------------------------------
| FACEBOOK RESEARCH
|--------------------------------------------------------------------------
*/
// --- Landing
function FacebookResearchLandingCtrl ($rootScope, $scope, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_FACEBOOK_STATISTICS', link: $rootScope.url.facebook.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH', link: $rootScope.url.facebook.research.root}
	];
	// --- Filters
	$scope.filters = false;
	// --- Content
	$scope.content = {
		facebookAllUsers: data.facebookAllUsers,
		facebookWomenUsers: data.facebookWomenUsers,
		facebookMenUsers: data.facebookMenUsers,
		facebookAverageCPC: data.facebookAverageCPC
	};
}
// --- Continents
function FacebookContinentsCtrl ($rootScope, $scope, $location, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_FACEBOOK_STATISTICS', link: $rootScope.url.facebook.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH', link: $rootScope.url.facebook.research.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH_CONTINENTS', link: $rootScope.url.facebook.research.continents}
	];
	// --- Filters
	$scope.filters = false;
	// --- Content
	$scope.content = {
		rankingTitle: 'FACEBOOK_RESEARCH_CONTINENTS_RANKING_TITLE',
		ranking: data.ranking
	};
}	
// --- Countries
function FacebookCountriesCtrl ($rootScope, $scope, $location, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_FACEBOOK_STATISTICS', link: $rootScope.url.facebook.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH', link: $rootScope.url.facebook.research.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH_COUNTRIES', link: $rootScope.url.facebook.research.countries}
	];
	// --- Filters
	$scope.filters = {
		idiom: (data.idiom) ? data.idiom : true
	};
	// --- Content
	$scope.content = {
		rankingTitle: 'FACEBOOK_RESEARCH_COUNTRIES_RANKING_TITLE',
		idioms: $rootScope.idioms,
		countries: data.countries,
		ranking: data.ranking
	};
}
// --- Country
function FacebookCountryCtrl ($rootScope, $scope, $location, data, ChartMaker) {	
	// --- Breadcrumbs
	$scope.$broadcast('breadcrumbs');

	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_FACEBOOK_STATISTICS', link: $rootScope.url.facebook.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH', link: $rootScope.url.facebook.research.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH_COUNTRIES', link: $rootScope.url.facebook.research.countries},
		{text: data.country.name, link: $rootScope.url.facebook.research.country + '/' + data.country.link}
	];
	// --- Filters
	$scope.filters = false;
	// --- Content
	$scope.content = {
		country: data.country
	};
	var android = $scope.content.country.mobile_devices.vs.android,
		ios = $scope.content.country.mobile_devices.vs.ios,
		audience_history = $scope.content.country.audience_history;

	android.history.series_data_min = parseInt(android.history.series_data_min) * 0.95;
	android.history.series_data_max = parseInt(android.history.series_data_max) * 1.05;
	ios.history.series_data_min = parseInt(ios.history.series_data_min) * 0.95;
	ios.history.series_data_max = parseInt(ios.history.series_data_max) * 1.05;

	ChartMaker.verticalBar('android_grow', android.name, android.history.x_axis, android.history.series_data_min, android.history.series_data_max, android.name, android.history.series_data);
	ChartMaker.verticalBar('ios_grow', ios.name, ios.history.x_axis, ios.history.series_data_min, ios.history.series_data_max, ios.name, ios.history.series_data);
	ChartMaker.verticalBar('audience_grow', $scope.content.country.name, audience_history.x_axis, audience_history.series_data_min, audience_history.series_data_max, audience_history.name, audience_history.series_data);
}
// --- Regions
function FacebookRegionsCtrl ($rootScope, $scope, $location, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_FACEBOOK_STATISTICS', link: $rootScope.url.facebook.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH', link: $rootScope.url.facebook.research.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH_REGIONS', link: $rootScope.url.facebook.research.regions}
	];
	// --- Filters
	$scope.filters = {
		idiom: data.idiom,
		country: data.country
	};
	// --- Content
	$scope.content = {
		rankingTitle: 'FACEBOOK_RESEARCH_REGIONS_RANKING_TITLE',
		idioms: $rootScope.idioms,
		countries: data.countries,
		ranking: data.ranking
	};
}
// --- Cities
function FacebookCitiesCtrl ($rootScope, $scope, $location, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_FACEBOOK_STATISTICS', link: $rootScope.url.facebook.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH', link: $rootScope.url.facebook.research.root},
		{text: 'BREADCRUMBS_FACEBOOK_RESEARCH_CITIES', link: $rootScope.url.facebook.research.cities}
	];
	// --- Filters
	$scope.filters = {
		idiom: data.idiom,
		country: data.country
	};
	// --- Content
	$scope.content = {
		rankingTitle: 'FACEBOOK_RESEARCH_CITIES_RANKING_TITLE',
		idioms: $rootScope.idioms,
		countries: data.countries,
		ranking: data.ranking
	};
}