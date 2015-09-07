<?php
//$since_id = $since_id;
$screenname = $_GET["screen"];
$tweetcount = $_GET["twcnt"];
//$param = $param . 'user_id=' . get_user_id($screenname);
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
    $qrydata = mysql_query($qry);
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
    mysql_query($qry);
    $qry = "";
    $qry = "INSERT INTO owloo_last_id_str (owloo_user_id, owloo_id_str, owloo_tweet_count) VALUES ";
    $qry = $qry . "('" . $uid . "','" . $laststr . "','" . $tweetcount . "')";
    mysql_query($qry);
    $cursewrds = "";
    $cursewrds = "INSERT INTO owloo_tweet_curse_data (owloo_user_id, owloo_id_str, owloo_tweet_curse) VALUES ";
    $cursewrds = $cursewrds . substr($cursestr, 0, strlen($cursestr) - 1) . ";";
    if (strlen($cursestr) > 1)
        mysql_query($cursewrds);
    
    $tweetqry1 = "INSERT INTO owoo_tweet_data (owloo_user_id, owloo_id_str, owloo_tweet_text, owloo_tweet_time, owloo_tweet_date) VALUES ";
    $tweetqry1 = $tweetqry1 . substr($tweetqry, 0, strlen($tweetqry) - 1) . ";";
    if (strlen($tweetqry) > 1)
        mysql_query($tweetqry1);
    
    $mentionqry = "INSERT INTO owloo_mentions (owloo_user_id, owloo_screenanme, owloo_id_str) VALUES ";
    $mentionqry = $mentionqry . substr($mentionname, 0, strlen($mentionname) - 1) . ";";
    if (strlen($mentionname) > 1)
        mysql_query($mentionqry);
    
    $hashtagqry = "INSERT INTO owloo_hashtag (owloo_user_id, owloo_hashword, owloo_id_str) VALUES ";
    $hashtagqry = $hashtagqry . substr($hashtagword, 0, strlen($hashtagword) - 1) . ";";
    if (strlen($hashtagword) > 1)
        mysql_query($hashtagqry);
}
?>