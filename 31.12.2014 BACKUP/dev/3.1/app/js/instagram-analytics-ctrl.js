/*
|--------------------------------------------------------------------------
| INSTAGRAM
|--------------------------------------------------------------------------
*/
// --- Landing
function InstagramLandingCtrl ($rootScope, $scope, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_INSTAGRAM_ANALYTICS', link: $rootScope.url.instagram.analytics.root}
	];
	// --- Filters
	$scope.filters = null;
	// --- Content
	$scope.content = {
		instagramGrowRank: data.instagramGrowRank,
		instagramLastAdded: data.instagramLastAdded
	};
}
// --- Ranking
function InstagramAccountsRankingCtrl ($rootScope, $scope, $location, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_INSTAGRAM_ANALYTICS', link: $rootScope.url.instagram.analytics.root},
		{text: 'BREADCRUMBS_INSTAGRAM_ANALYTICS_ACCOUNTS', link: $rootScope.url.instagram.analytics.accounts}
	];
	if (data.tag != 'all') {
		$scope.breadcrumbs.push(
			{text: data.tag.toUpperCase(), link: $rootScope.url.instagram.analytics.accounts + '/tag/' + data.tag}
		);
	}
	// --- Filters
	$scope.filters = {
		tag: data.tag
	};
	// --- Content
	$scope.content = {
		rankingTitle: 'INSTAGRAM_ANALYTICS_ACCOUNTS_RANKING_TITLE',
		tags: data.tags,
		ranking: data.ranking
	};

}
// --- Internal
function InstagramAccountsInternalCtrl ($rootScope, $scope, $location, data, ChartMaker) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_INSTAGRAM_ANALYTICS', link: $rootScope.url.instagram.analytics.root},
		{text: 'BREADCRUMBS_INSTAGRAM_ANALYTICS_ACCOUNTS', link: $rootScope.url.instagram.analytics.accounts},
		{text: data.profile.name, link: $rootScope.url.instagram.analytics.accounts + '/' + data.profile.username}
	];
	// --- Content
	$scope.content = {
		profile: data.profile,
	};
	// --- Charts
	var followers = data.profile.charts.followers;
	var daily_followers_grow = data.profile.charts.daily_followers_grow;
	var daily_following_grow = data.profile.charts.daily_following_grow;
	//var tweets_made_by_day_of_the_week = data.profile.charts.tweets_made_by_day_of_the_week;
	//var klout = data.profile.klout;
	
	console.log(followers.x_axis);
	console.log(followers.series_data);

	ChartMaker.line('followers', data.profile.name, followers.x_axis, followers.series_data_min, followers.series_data_max, data.profile.name, followers.series_data, 'followers');
	ChartMaker.verticalBar('daily_followers_grow', data.profile.name, daily_followers_grow.x_axis, daily_followers_grow.series_data_min, daily_followers_grow.series_data_max, data.profile.name, daily_followers_grow.series_data, 'followers');
	ChartMaker.verticalBar('daily_following_grow', data.profile.name, daily_following_grow.x_axis, daily_following_grow.series_data_min, daily_following_grow.series_data_max, data.profile.name, daily_following_grow.series_data, 'followings');
	//ChartMaker.horizontalBar('tweets_made_by_day_of_the_week', data.profile.name, tweets_made_by_day_of_the_week.x_axis, tweets_made_by_day_of_the_week.series_data_min, tweets_made_by_day_of_the_week.series_data_max, data.profile.name, tweets_made_by_day_of_the_week.series_data, 'tweets');
	//ChartMaker.circle('klout-metric', klout.current_klout_data.score, 100);
	//ChartMaker.circle('ff-ratio', data.profile.ff_ratio.value, data.profile.ff_ratio.max_value);
}