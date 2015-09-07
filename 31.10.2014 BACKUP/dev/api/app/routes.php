<?php

//Facebook
Route::get('/facebook/pages/total', 'FacebookController@getTotalUsers');

//Twitter
Route::get('/twitter/profile/total', 'FacebookController@getTotalUsers');