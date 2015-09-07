<?php
include("config/config.php");
include("twitter/twitterfunctions.php");
$screenname = "";
$buildnexturl = "";
$recnt = "";
$owloo_user_id = "";
if ($_GET["twittername"]) {
    $owloo_user_id = $_GET["uid"];
    $screenname = $_GET["twittername"];
    $recnt = $_GET["srno"];
    //Get Next Data
    $qry = "";
    $qry = " SELECT * FROM owloo_user_master";
    $qry = $qry . " Order By 1 Desc";
    $qry = $qry . " LIMIT " . $recnt . ", 1";
    $qrydata = mysql_query($qry);
    while ($fetchdata = mysql_fetch_array($qrydata)) {
        $uid = $fetchdata["owloo_user_id"];
        $buildnexturl = SITE_URL . "cronjob.php?srno=" . ($recnt + 1) . "&uid=" . $uid . "&twittername=" . $fetchdata["owloo_screen_name"];
    }
    $url = "https://api.twitter.com/1.1/users/lookup.json";
    $parameters = "screen_name=" . $screenname;
    $retdata = getdata($url, $parameters);
} else {
    //Get 1st Data
    $screenname = "";
    $recnt = 1;
    //Get Next Data
    $qry = "";
    $qry = " SELECT * FROM owloo_user_master";
    $qry = $qry . " Order By 1 Desc";
    $qry = $qry . " LIMIT 0 , 1";
    $qrydata = mysql_query($qry);
    while ($fetchdata = mysql_fetch_array($qrydata)) {
        $owloo_user_id = $fetchdata["owloo_user_id"];
        $uid = $fetchdata["owloo_user_id"];
        $screenname = $fetchdata["owloo_screen_name"];
        $buildnexturl = SITE_URL . "cronjob.php?srno=" . ($recnt + 1) . "&uid=" . $uid . "&twittername=" . $fetchdata["owloo_screen_name"];
    }
    if ($screenname)
    {
        $url = "https://api.twitter.com/1.1/users/lookup.json";
        $parameters = "screen_name=" . $screenname;
        $retdata = getdata($url, $parameters);
    }
}
if ($retdata) {
    $twdatas = json_decode($retdata, true);
    foreach ($twdatas as $twdata) {
        if ($twdata[0]["message"]) {
            echo "<br/>Error : " . $twdata[0]["message"];
            echo "<br/>Code : " . $twdata[0]["code"];
            echo "<script>window.location='" . SITE_URL . "404.php'</script>";
        } else {
            $hfflag = 0;
            $twitter_id = $twdata["id"];
            $twitter_name = $twdata["name"];
            $screen_name = $twdata["screen_name"];
            $profile_image_url = $twdata["profile_image_url"];
            $description = $twdata["description"];
            $location = $twdata["location"];
            $timezone = $twdata["time_zone"];
            $creationdate = $twdata["created_at"];
            $lang = $twdata["lang"];
            $verified = $twdata["verified"];
            $followers_count = $twdata["followers_count"];
            $following_count = $twdata["friends_count"];
            $tweetcount = $twdata["statuses_count"];
            $listedcount = $twdata["listed_count"];
            $owloo_user_id = $fetch_cntr['owloo_user_id'];
            $qry = "";
            $qry = "Update owloo_user_master Set ";
            $qry = $qry . " owloo_user_twitter_id = '" . $twitter_id . "',";
            $qry = $qry . " owloo_user_name = '" . $twitter_name . "',";
            $qry = $qry . " owloo_screen_name = '" . $screen_name . "',";
            $qry = $qry . " owloo_user_photo = '" . $profile_image_url . "',";
            $qry = $qry . " owloo_user_description = '" . $description . "',";
            $qry = $qry . " owloo_user_location = '" . $location . "',";
            $qry = $qry . " owloo_user_language = '" . $lang . "',";
            $qry = $qry . " owloo_user_verified_account = '" . $verified . "',";
            $qry = $qry . " owloo_user_timezone = '" . $timezone . "',";
            $qry = $qry . " owloo_user_created_on = '" . $creationdate . "',";
            $qry = $qry . " owloo_followers_count = '" . $followers_count . "',";
            $qry = $qry . " owloo_following_count = '" . $following_count . "',";
            $qry = $qry . " owloo_tweetcount = '" . $tweetcount . "',";
            $qry = $qry . " owloo_listed_count = '" . $listedcount . "',";
            $qry = $qry . " owloo_updated_on = '" . Date("Y-m-d") . "'";
            $qry = $qry . " Where owloo_user_twitter_id = '" . $twitter_id . "'";
            $qry = $qry . " AND owloo_screen_name = '" . $screen_name . "'";
            mysql_query($qry);
            $qry = "";
            $qry = "Delete from owloo_daily_track where owloo_updated_on = '" . Date("Y-m-d") . "'";
            mysql_query($qry);
            $qry = "";
            $qry = " INSERT INTO owloo_daily_track ( owloo_user_twitter_id, owloo_followers_count,";
            $qry = $qry . " owloo_following_count, owloo_tweetcount, owloo_listed_count,";
            $qry = $qry . " owloo_updated_on) VALUES (";
            $qry = $qry . " '" . $owloo_user_id . "',";
            $qry = $qry . " '" . ($followers_count - $fetch_cntr['owloo_followers_count']) . "',";
            $qry = $qry . " '" . ($following_count - $fetch_cntr['owloo_following_count']) . "',";
            $qry = $qry . " '" . ($tweetcount - $fetch_cntr['owloo_tweetcount']) . "',";
            $qry = $qry . " '" . ($listedcount - $fetch_cntr['owloo_listed_count']) . "',";
            $qry = $qry . " '" . Date("Y-m-d") . "')";
            mysql_query($qry);
            // Update User Hash/Mentions
            $since_id = "";
            $since_tweet = "";
            $qry = "";
            $qry = " Select owloo_id_str, owloo_tweet_count From owloo_last_id_str";
            $qry = $qry . " Where owloo_user_id = '" . $owloo_user_id . "'";
            $dataarr = mysql_query($qry);
            $fetch_cntr = mysql_fetch_array($dataarr);
            if ($fetch_cntr) {
                $since_id = $fetch_cntr['owloo_id_str'];
                $since_tweet = $fetch_cntr['owloo_tweet_count'];
            }
            //include("cron-updatehash.php");
        }
    }
} else {
    echo "<script>window.location='" . $buildnexturl . "'</script>";
}
?>