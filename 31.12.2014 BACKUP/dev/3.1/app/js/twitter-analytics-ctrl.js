/*
|--------------------------------------------------------------------------
| TWITTER ANALYTICS
|--------------------------------------------------------------------------
*/
// --- Landing
function TwitterLandingCtrl ($rootScope, $scope, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_TWITTER_ANALYTICS', link: $rootScope.url.twitter.analytics.root},
	];
	// --- Filters
	$scope.filters = null;
	// --- Content
	$scope.content = {
		twitterLastAdded: data.twitterLastAdded
	};
}
// --- Ranking
function TwitterProfilesRankingCtrl ($rootScope, $scope, $location, data) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_TWITTER_ANALYTICS', link: $rootScope.url.twitter.analytics.root},
		{text: 'BREADCRUMBS_TWITTER_ANALYTICS_PROFILES', link: $rootScope.url.twitter.analytics.profiles}
	];
	// --- Filters
	$scope.filters = {
		idiom: data.idiom
	};
	// --- Content
	$scope.content = {
		rankingTitle: 'TWITTER_ANALYTICS_PROFILES_RANKING_TITLE',
		idioms: $rootScope.idioms,
		ranking: data.ranking
	};
}
// --- Internal
function TwitterProfilesInternalCtrl ($rootScope, $scope, $location, data, ChartMaker) {
	// --- Breadcrumbs
	$scope.breadcrumbs = [
		{text: 'BREADCRUMBS_TWITTER_ANALYTICS', link: $rootScope.url.twitter.analytics.root},
		{text: 'BREADCRUMBS_TWITTER_ANALYTICS_PROFILES', link: $rootScope.url.twitter.analytics.profiles},
		{text: data.profile.name, link: $rootScope.url.twitter.analytics.profiles + '/' + data.profile.screen_name}
	];
	// --- Content
	$scope.content = {
		profile: data.profile,
	};
	// --- Charts
	var followers = data.profile.charts.followers;
	var daily_followers_grow = data.profile.charts.daily_followers_grow;
	var daily_following_grow = data.profile.charts.daily_following_grow;
	var tweets_made_by_day_of_the_week = data.profile.charts.tweets_made_by_day_of_the_week;
	var klout = data.profile.klout;
	
	console.log(followers.x_axis);
	console.log(followers.series_data);

	ChartMaker.line('followers', data.profile.name, followers.x_axis, followers.series_data_min, followers.series_data_max, data.profile.name, followers.series_data, 'followers');
	ChartMaker.verticalBar('followers_grow_by_day', data.profile.name, daily_followers_grow.x_axis, daily_followers_grow.series_data_min, daily_followers_grow.series_data_max, data.profile.name, daily_followers_grow.series_data, 'followers');
	ChartMaker.verticalBar('followings_grow_by_day', data.profile.name, daily_following_grow.x_axis, daily_following_grow.series_data_min, daily_following_grow.series_data_max, data.profile.name, daily_following_grow.series_data, 'followings');
	ChartMaker.horizontalBar('tweets_made_by_day_of_the_week', data.profile.name, tweets_made_by_day_of_the_week.x_axis, tweets_made_by_day_of_the_week.series_data_min, tweets_made_by_day_of_the_week.series_data_max, data.profile.name, tweets_made_by_day_of_the_week.series_data, 'tweets');
	ChartMaker.circle('klout-metric', klout.current_klout_data.score, 100);
	ChartMaker.circle('ff-ratio', data.profile.ff_ratio.value, data.profile.ff_ratio.max_value);
}