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
    Route::get('/total/{where}/{category?}', 'FacebookController@getTotalPage');
    // Get pages' grow ranking.
    Route::get('/grow/{idiom}/{limit?}', 'FacebookController@getRankingPageGrow');
    // Get last pages added.
    Route::get('/last-added/{idiom}/{limit?}', 'FacebookController@getLastPageAdded');
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
    // Get countries' interest details.
    Route::get('/interest-details/{id}', 'FacebookController@getCountryInterestDetails');
    // Get countries' comportamiento details.
    Route::get('/comportamiento-details/{id}', 'FacebookController@getCountryComportamientoDetails');
    // Get countries' mobile device details.
    Route::get('/mobile-device-details/{id}', 'FacebookController@getCountryMobileDeviceDetails');
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
    // Get cities' details.
    Route::get('/details/{id_city}', 'FacebookController@getCityDetails');
    // Get cities' short details.
    Route::get('/short-details/{id_city}', 'FacebookController@getCityShortDetails');
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
    // Get regions' short details.
    Route::get('/short-details/{id_region}', 'FacebookController@getRegionShortDetails');
    // Get regions' details.
    Route::get('/details/{id_region}', 'FacebookController@getRegionDetails');
    // Get Owloo's total registered facebook regions.
    Route::get('/total/{where}', 'FacebookController@getTotalRegions');
    // Get countries' list.
    Route::get('/countries', 'FacebookController@getRegionCountries');
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
    Route::get('/total/{where}', 'TwitterController@getTotalProfile');
    // Get profiles' grow ranking.
    Route::get('/grow/{idiom}/{limit?}', 'TwitterController@getRankingProfileGrow');
    // Get last profiles added.
    Route::get('/last-added/{idiom}/{limit?}', 'TwitterController@getLastProfileAdded');
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
    // Get categories' list.
    Route::get('/categories', 'InstagramController@getCategories');
    // Get Owloo's total registered instagram profiles.
    Route::get('/total/{category?}', 'InstagramController@getTotalProfile');
    // Get profiles' grow ranking.
    Route::get('/grow/{limit?}', 'InstagramController@getRankingProfileGrow');
    // Get last profiles added.
    Route::get('/last-added/{limit?}', 'InstagramController@getLastProfileAdded');
    // Get profiles' ranking.
    Route::get('/ranking/{category}/{page}', 'InstagramController@getRankingProfile');
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

/*
|--------------------------------------------------------------------------
| OWLOO ADD ACCOUNTS
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'owloo/social-account'), function() {
    // Get social accounts in Facebook, Twitter and Instagram.
    Route::get('/add/{account}', 'OwlooController@searchSocialAccounts');
    // Get social accounts in Facebook.
    Route::get('/add/facebook/{account}', 'OwlooController@searchSocialAccountsFacebook');
    // Get social accounts in Twitter.
    Route::get('/add/twitter/{account}', 'OwlooController@searchSocialAccountsTwitter');
    // Get social accounts in Instagram.
    Route::get('/add/instagram/{account}', 'OwlooController@searchSocialAccountsInstagram');
});