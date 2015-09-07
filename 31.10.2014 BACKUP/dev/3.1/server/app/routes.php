<?php

/*
|--------------------------------------------------------------------------
| Facebook Data
|--------------------------------------------------------------------------
|
| Get list of countries:
|
*/
Route::post('/facebook/page/countries', 'FacebookController@getCountries');
/*
|
| Get list of categories:
|
*/
Route::post('/facebook/page/categories', 'FacebookController@getCategories');
/*
|
| Get Facebook page:
|
*/
Route::post('/facebook/page/username/{username}', 'FacebookController@getPage');
/*
|
| Get Facebook page local fans history, last 30 days:
|
*/
Route::post('/facebook/page/local-fans/history/{username}/{country}/{days?}', 'FacebookController@getPageLocalFansHistory');
/*
|
| Ranking page grow:
|
*/
Route::post('/facebook/page/grow/{idiom}', 'FacebookController@getRankingPageGrow');
/*
|
| Get total of Facebook pages on Owloo:
|
*/
Route::post('/facebook/page/{idiom}/total', 'FacebookController@getTotalPage');
/*
|
| Ranking Page:
|
*/
Route::post('/facebook/page/{goup}/{page}', 'FacebookController@getRankingPage');
/*
|
| Ranking Page by Country
|
*/
Route::post('/facebook/page/ranking/{where}/{category}/{page}', 'FacebookController@getRankingPage');
Route::post('/dev/facebook/page/ranking/{where}/{category}/{page}', 'FacebookController@getRankingPageDev');
/*
|
| Ranking Country:
|
*/
Route::post('/facebook/country/ranking/{idiom}/{page}', 'FacebookController@getRankingCountry');
/*
|
| Get Facebook country:
|
*/
Route::post('/facebook/country/{code}', 'FacebookController@getCountry');

/*
|--------------------------------------------------------------------------
| Twitter Data
|--------------------------------------------------------------------------
|
| Ranking:
|
*/
Route::post('/twitter/profile/{idiom}/ranking/{country}/{from}/{to}/{limit}', 'TwitterController@getRanking');
/*
|
| Get total of Twitter users on Owloo:
|
*/
Route::post('/twitter/profile/{idiom}/total', 'TwitterController@getTotalProfile');

/*
|--------------------------------------------------------------------------
| Instagram Data
|--------------------------------------------------------------------------
|
| Get total of Instagram users on Owloo:
|
*/
Route::post('/instagram/profile/{idiom}/total', 'InstagramController@getTotalProfile');

/*
|--------------------------------------------------------------------------
| Owloo Data
|--------------------------------------------------------------------------
|
| Get list of owloo users
|
*/
Route::post('/owloo/user/total', 'OwlooController@getTotalUsers');


