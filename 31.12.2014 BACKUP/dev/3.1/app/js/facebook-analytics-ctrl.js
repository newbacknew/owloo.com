/*
|--------------------------------------------------------------------------
| FACEBOOK ANALYTICS
|--------------------------------------------------------------------------
*/
// --- Landing
function FacebookAnalyticsLandingCtrl ($rootScope, $scope, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_FACEBOOK_STATISTICS', link: $rootScope.url.facebook.root},
		{text: 'BREADCRUMBS_FACEBOOK_ANALYTICS', link: $rootScope.url.facebook.analytics.root}
	];
	// --- Filters
	$scope.filters = null;
	// --- Content
	$scope.content = {
		facebookGrowRank: data.facebookGrowRank
	};
}
// --- Ranking
function FacebookPagesRankingCtrl ($rootScope, $scope, $location, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_FACEBOOK_STATISTICS', link: $rootScope.url.facebook.root},
		{text: 'BREADCRUMBS_FACEBOOK_ANALYTICS', link: $rootScope.url.facebook.analytics.root},
		{text: 'BREADCRUMBS_FACEBOOK_ANALYTICS_PAGES', link: $rootScope.url.facebook.analytics.pages}
	];
	var a = data.country != 'world', b = data.tag != 'all';
	if (a || b) {
		var country_link = false, tag_link = false;
		if (a) {
			country_link = $rootScope.url.facebook.analytics.pages + '/country/' + data.countries[data.country].link;
			if (b) {
				tag_link = country_link  + '/tag/' + data.tag;;
			}
		} else {
			tag_link = $rootScope.url.facebook.analytics.pages + '/tag/' + data.tag;
		}
		if (country_link) $scope.breadcrumbs.push({text: data.countries[data.country].name, link: country_link});
		if (tag_link) $scope.breadcrumbs.push({text: 'facebook_tag_' + data.tag, link: tag_link});
	}
	// --- Filters
	$scope.filters = {
		ranking: true,
		country: data.country,
		tag: data.tag,
		idiom: data.idiom
	};
	// --- Content
	$scope.content = {
		tags: data.tags,
		countries: data.countries,
		idioms: $rootScope.idioms,
		ranking: data.ranking,
		growRank: data.growRank,
		currentUrl: $rootScope.url.facebook.analytics.pages
	};
	if (data.country != 'world') $scope.content.currentUrl = $scope.content.currentUrl + '/country/' + data.country;
}
// --- Internal
function FacebookPagesInternalCtrl ($rootScope, $scope, $location, data, ChartMaker) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_FACEBOOK_STATISTICS', link: $rootScope.url.facebook.root},
		{text: 'BREADCRUMBS_FACEBOOK_ANALYTICS', link: $rootScope.url.facebook.analytics.root},
		{text: 'BREADCRUMBS_FACEBOOK_ANALYTICS_PAGES', link: $rootScope.url.facebook.analytics.pages},
		{text: data.page.name, link: $rootScope.url.facebook.analytics.pages + '/' + data.page.username}
	];
	// --- Content
	$scope.content = {
		page: data.page
	};
	// --- Charts
	ChartMaker.line('likes_history', data.page.name, data.page.likes_history_30.x_axis, data.page.likes_history_30.series_data_min, data.page.likes_history_30.series_data_max, data.page.name, data.page.likes_history_30.series_data);
	ChartMaker.verticalBar('talking_about_history', data.page.name, data.page.talking_about_history_30.x_axis, data.page.talking_about_history_30.series_data_min, data.page.talking_about_history_30.series_data_max, data.page.name, data.page.talking_about_history_30.series_data);
}