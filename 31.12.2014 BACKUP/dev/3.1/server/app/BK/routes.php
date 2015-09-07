<?php
/*
|--------------------------------------------------------------------------
| FACEBOOK
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'facebook/user'), function() {
	// Get total of users.
	Route::get('/total/{who}', 'FacebookController@getTotalUser');
	// Get average CPC.
	Route::get('/cpc', 'FacebookController@getAverageCPC');
});

/*
|--------------------------------------------------------------------------
| FACEBOOK PAGES
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'facebook/page'), function() {
	// Get countries' list.
	Route::get('/countries', 'FacebookController@getCountries');
	// Get categories' list.
	Route::get('/categories', 'FacebookController@getCategories');
	// Get Owloo's total registered facebook pages.
	Route::get('/total/{idiom}', 'FacebookController@getTotalPage');
	// Get pages' grow ranking.
	Route::get('/grow/{idiom}', 'FacebookController@getRankingPageGrow');
	// Get pages' ranking.
	Route::get('/ranking/{idiom}/{category}/{page}', 'FacebookController@getRankingPage');
	// Get page.
	Route::get('/username/{username}', 'FacebookController@getPage');
	//Get page's local fans history, last 30 days:
	Route::get('/username/{username}/local-fans/{country}/{days?}', 'FacebookController@getPageLocalFansHistory');
});

/*
|--------------------------------------------------------------------------
| FACEBOOK COUNTRIES
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'facebook/country'), function() {
	// Get countries' ranking.
	Route::get('/ranking/{idiom}/{page}', 'FacebookController@getRankingCountry');
    // Get countries' grow ranking.
    Route::get('/grow/{idiom}', 'FacebookController@getRankingCountryGrow');
    // Get Owloo's total registered facebook countries.
    Route::get('/total/{where}', 'FacebookController@getTotalCountries');
	// Get country.
	Route::get('/{code}', 'FacebookController@getCountry');
});

/*
|--------------------------------------------------------------------------
| FACEBOOK CITIES
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'facebook/city'), function() {
	// Get cities' ranking.
	Route::get('/ranking/{country}/{page}', 'FacebookController@getRankingCity');
    // Get Owloo's total registered facebook cities.
    Route::get('/total/{where}', 'FacebookController@getTotalCities');
    // Get countries' list.
    Route::get('/countries', 'FacebookController@getCityCountries');
});

/*
|--------------------------------------------------------------------------
| FACEBOOK REGIONS
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'facebook/region'), function() {
    // Get regions' ranking.
    Route::get('/ranking/{country}/{page}', 'FacebookController@getRankingRegion');
    // Get Owloo's total registered facebook regions.
    Route::get('/total/{where}', 'FacebookController@getTotalRegions');
});

/*
|--------------------------------------------------------------------------
| FACEBOOK CONTINENTS
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'facebook/continent'), function() {
	// Get continents' ranking.
	Route::get('/ranking', 'FacebookController@getRankingContinent');
});

/*
|--------------------------------------------------------------------------
| TWITTER PROFILES
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'twitter/profile'), function() {
	// Get Owloo's total registered twitter profiles.
	Route::get('/total/{idiom}', 'TwitterController@getTotalProfile');
	// Get profiles' grow ranking.
	Route::get('/grow/{idiom}', 'TwitterController@getRankingProfileGrow');
	// Get profiles' ranking.
	Route::get('/ranking/{idiom}/{page}', 'TwitterController@getRankingProfile');
	// Get profile.
	Route::get('/username/{screen_name}', 'TwitterController@getProfile');
});

/*
|--------------------------------------------------------------------------
| INSTAGRAM PROFILES
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'instagram/profile'), function() {
	// Get Owloo's total registered instagram profiles.
    Route::get('/total', 'InstagramController@getTotalProfile');
    // Get profiles' grow ranking.
    Route::get('/grow', 'InstagramController@getRankingProfileGrow');
    // Get profiles' ranking.
    Route::get('/ranking/{page}', 'InstagramController@getRankingProfile');
    // Get profile.
    Route::get('/username/{username}', 'InstagramController@getProfile');
});

/*
|--------------------------------------------------------------------------
| OWLOO USERS
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'owloo/user'), function() {
	// Get Owloo's total registered instagram profiles.
	Route::get('/total', 'OwlooController@getTotalUsers');
});