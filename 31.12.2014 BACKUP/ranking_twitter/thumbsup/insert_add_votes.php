<?php

mysql_connect('localhost', 'owloo_admin', 'fblatamx244');
mysql_select_db('owloo_thumbsup');

$qry = "";
$qry = " SELECT name, votes_up, votes_down FROM thumbsup_items;";
$qrydata = mysql_query($qry);
while ($fetchdata = mysql_fetch_array($qrydata)){
	if($fetchdata['votes_up'] > 0){
		for($i=0; $i<($fetchdata['votes_up']/10); $i++){
			echo '<br>INSERT INTO owloo_user_rating(owloo_user_id, owloo_rating_points, owloo_rating_ip, owloo_rating_date) VALUES((SELECT owloo_user_id FROM owloo_user_master WHERE owloo_user_twitter_id = "'.$fetchdata['name'].'"), 10, "", now());';
		}
	}
	if($fetchdata['votes_down'] > 0){
		for($i=0; $i<($fetchdata['votes_down']/10); $i++){
			echo '<br>INSERT INTO owloo_user_rating(owloo_user_id, owloo_rating_points, owloo_rating_ip, owloo_rating_date) VALUES((SELECT owloo_user_id FROM owloo_user_master WHERE owloo_user_twitter_id = "'.$fetchdata['name'].'"), -10, "", now());';
		}
	}
}