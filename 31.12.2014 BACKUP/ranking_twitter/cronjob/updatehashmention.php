<?php
//$since_id = $since_id;
$screenname = $screen_name;
$tweetcount = $tweetcount;
$param = $param . 'screen_name=' . $screenname;
$param = $param . '&since_id=' . $since_id;
$param = $param . '&trim_user=1';
$laststr = "";
$cnt1 = 0;
$totaltw = $tweetcount - $since_tweet;
$maxid = 0;
$lastid = "";
$mentionname = "";
$hashtagword = "";
$tweetqry = "";
$flgmain = false;
$cursearr = array();
if ($totaltw > 0) {
    $qry = "";
    $qry = "SELECT owloo_curse_text FROM owloo_curse_words";
	
//echo '<br>SQL 4: '.$qry.'<br>';
	
    $qrydata = mysql_query($qry) or die(mysql_error());
    $i = 0;
    while ($fetchdata = mysql_fetch_array($qrydata)) {
        $cursearr[$i] = $fetchdata["owloo_curse_text"];
        $i++;
    }
}
while ($cnt1 < $totaltw) {
    $json = gettwitterdata($maxid, $param);
    if ($json) {
        $tweets = json_decode($json, true);
		
        foreach ($tweets as $tweet) {
            $lastid = $tweet["id_str"];
            $tweettext = $tweet["text"];
            $crtdt = explode(" ", $tweet["created_at"]);
            $tmptime = explode(":", $crtdt[3]);
            $twtime = $tmptime[0];
            $dt = $crtdt[0] . " " . $crtdt[1] . " " . $crtdt[2] . " " . $crtdt[3] . " " . $crtdt[5];
            $twdate = date("Y-m-d", strtotime($dt));
            $tweetqry = $tweetqry . " ('" . $uid . "',";
            $tweetqry = $tweetqry . " '" . $lastid . "',";
            $tweetqry = $tweetqry . " '" . mysql_real_escape_string(addslashes($tweettext)) . "',";
            $tweetqry = $tweetqry . " '" . $twtime . "',";
            $tweetqry = $tweetqry . " '" . $twdate . "'),";
            if($cursearr)
            {
                foreach ($cursearr as $aa) {
                    if (strpos($tweet["text"], $aa)) {
                        $cursestr = $cursestr . " ('" . $uid . "',";
                        $cursestr = $cursestr . " '" . $lastid . "',";
                        $cursestr = $cursestr . " '" . $aa . "'),";
                    }
                }
            }
            $usrmentions = $tweet["entities"];
            $hastagarrs = $usrmentions["hashtags"];
            $mentionsarrs = $usrmentions["user_mentions"];
            if ($mentionsarrs)
            {
                foreach ($mentionsarrs as $mentionsarr)
                {
                    $mentionname = $mentionname . " ('" . $uid . "',";
                    $mentionname = $mentionname . "'" . mysql_real_escape_string($mentionsarr["screen_name"]) . "',";
                    $mentionname = $mentionname . "'" . $tweet["id_str"] . "'),";
                }
            }
            if ($hastagarrs) {
                foreach ($hastagarrs as $hastagarr) {
                    $hashtagword = $hashtagword . " ('" . $uid . "',";
                    $hashtagword = $hashtagword . "'" . mysql_real_escape_string($hastagarr["text"]) . "',";
                    $hashtagword = $hashtagword . "'" . $tweet["id_str"] . "'),";
                }
            }
            $cnt = $cnt + 1;
            if (!$laststr) {
                $laststr = $lastid;
            }
        }
        $maxid = $lastid;
    }
    $cnt1 = $cnt1 + 200;
    $flgmain = true;
}
if ($flgmain && !empty($lastid))
{
    //Save Last Transaction ID
    $qry = "";
    $qry = "Delete from owloo_last_id_str where owloo_user_id = '" . $uid . "'";
	
echo '<br>SQL 5: '.$qry.'<br>';
	
    mysql_query($qry) or die(mysql_error());
    $qry = "";
    $qry = "INSERT INTO owloo_last_id_str (owloo_user_id, owloo_id_str, owloo_tweet_count) VALUES ";
    $qry = $qry . "('" . $uid . "','" . $laststr . "','" . $tweetcount . "')";

echo '<br>SQL 6: '.$qry.'<br>';
    mysql_query($qry) or die(mysql_error());
    
    $cursewrds = "";
    $cursewrds = "INSERT INTO owloo_tweet_curse_data (owloo_user_id, owloo_id_str, owloo_tweet_curse) VALUES ";
    $cursewrds = $cursewrds . substr($cursestr, 0, strlen($cursestr) - 1) . ";";

echo '<br>SQL 7: '.$cursewrds.'<br>';
    if (strlen($cursestr) > 1)
        mysql_query($cursewrds) or die(mysql_error());
    
    $tweetqry1 = "INSERT INTO owoo_tweet_data (owloo_user_id, owloo_id_str, owloo_tweet_text, owloo_tweet_time, owloo_tweet_date) VALUES ";
    $tweetqry1 = $tweetqry1 . substr($tweetqry, 0, strlen($tweetqry) - 1) . ";";

echo '<br>SQL 8: '.$tweetqry1.'<br>';
    if (strlen($tweetqry) > 1)
        mysql_query($tweetqry1) or die(mysql_error());
    
    $mentionqry = "INSERT INTO owloo_mentions (owloo_user_id, owloo_screenanme, owloo_id_str) VALUES ";
    $mentionqry = $mentionqry . substr($mentionname, 0, strlen($mentionname) - 1) . ";";

echo '<br>SQL 9: '.$mentionqry.'<br>';
    if (strlen($mentionname) > 1)
        mysql_query($mentionqry) or die(mysql_error());
    
    $hashtagqry = "INSERT INTO owloo_hashtag (owloo_user_id, owloo_hashword, owloo_id_str) VALUES ";
    $hashtagqry = $hashtagqry . substr($hashtagword, 0, strlen($hashtagword) - 1) . ";";

echo '<br>SQL 10: '.$hashtagqry.'<br>';
    if (strlen($hashtagword) > 1)
        mysql_query($hashtagqry) or die(mysql_error());
   
}