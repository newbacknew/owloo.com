<?php
include("config/config.php");
$qry = "";
$qry = " SELECT owloo_user_id, owloo_screen_name FROM owloo_user_master WHERE owloo_user_id > 300 ORDER BY 1;";
$qrydata = mysql_query($qry);
while ($fetchdata = mysql_fetch_array($qrydata)){
	echo '<br><br>'.$fetchdata['owloo_user_id'].' <a href="http://www.owloo.com/dev/twitter/twitter.php?page=userpage&twittername='.$fetchdata['owloo_screen_name'].'">http://www.owloo.com/dev/twitter/twitter.php?page=userpage&twittername='.$fetchdata['owloo_screen_name'].'</a>';
}