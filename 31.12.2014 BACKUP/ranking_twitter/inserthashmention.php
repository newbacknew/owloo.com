<?php

$screenname = $_GET["screen"];
$tweetcount = $_GET["twcnt"];
$param = 'screen_name=' . $screenname . '&trim_user=1';
//$param = 'user_id=' . get_user_id($screenname) . '&trim_user=1';
$laststr = "";
$cnt1 = 0;
$totaltw = $tweetcount;
$maxid = 0;
$lastid = "";
$mentionname = "";
$hashtagword = "";
$tweetqry = "";
$cursearr = array();
$threadflg = false;
if ($totaltw > 1000) {
    $totaltw = 1000;
    $threadflg = true;
}
if ($totaltw > 0) {
    $i = 0;
    $qry = "";
    $qry = "SELECT owloo_curse_text FROM owloo_curse_words";
    $qrydata = mysql_query($qry);
    while ($fetchdata = mysql_fetch_array($qrydata)) {
        $cursearr[$i] = $fetchdata["owloo_curse_text"];
        $i++;
    }
}
while ($cnt1 <= $totaltw) {
    $flgmain = false;
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
            if ($cursearr) {
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
            if ($mentionsarrs) {
                foreach ($mentionsarrs as $mentionsarr) {
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
//Save Last Transaction ID
if ($flgmain) {
    $qry = "";
    $qry = "Delete from owloo_last_id_str where owloo_user_id = '" . $uid . "'";
    mysql_query($qry);
    $qry = "";
    $qry = "INSERT INTO owloo_last_id_str (owloo_user_id, owloo_id_str, owloo_tweet_count) VALUES ";
    $qry = $qry . "('" . $uid . "','" . $laststr . "','" . $tweetcount . "')";
    mysql_query($qry);
    if (strlen($cursestr) > 1) {
        $cursewrds = "";
        $cursewrds = "INSERT INTO owloo_tweet_curse_data (owloo_user_id, owloo_id_str, owloo_tweet_curse) VALUES ";
        $cursewrds = $cursewrds . substr($cursestr, 0, strlen($cursestr) - 1) . ";";
        mysql_query($cursewrds);
    }
    if (strlen($tweetqry) > 1) {
        $tweetqry1 = "INSERT INTO owoo_tweet_data (owloo_user_id, owloo_id_str, owloo_tweet_text, owloo_tweet_time, owloo_tweet_date) VALUES ";
        $tweetqry1 = $tweetqry1 . substr($tweetqry, 0, strlen($tweetqry) - 1) . ";";
        mysql_query($tweetqry1);
    }
    if (strlen($mentionname) > 1) {
        $mentionqry = "INSERT INTO owloo_mentions (owloo_user_id, owloo_screenanme, owloo_id_str) VALUES ";
        $mentionqry = $mentionqry . substr($mentionname, 0, strlen($mentionname) - 1) . ";";
        mysql_query($mentionqry);
    }
    if (strlen($hashtagword) > 1) {
        $hashtagqry = "INSERT INTO owloo_hashtag (owloo_user_id, owloo_hashword, owloo_id_str) VALUES ";
        $hashtagqry = $hashtagqry . substr($hashtagword, 0, strlen($hashtagword) - 1) . ";";
        mysql_query($hashtagqry);
    }
}
if ($threadflg) {
    // Loop Through
    $tweetcount = $tweetcount - $totaltw;
    $url = "?uid=" . $uid . "&screen=" . $screenname . "&twcnt=" . $tweetcount . "&maxid=" . $maxid;
    echo "<script>window.location='" . SITE_URL . "insert_thread.php" . $url . "'</script>";
}
?>