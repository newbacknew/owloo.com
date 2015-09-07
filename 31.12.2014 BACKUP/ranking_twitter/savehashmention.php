<?php
require_once('../owloo_config.php');
include("config/config.php");
include("twitter/twitterfunctions.php");
$uid = $_GET["uid"];
$updateflg = $_GET["flg"]; // 1 for new 0 for update
//If new record get all hashtag and mentions
//Fetch Last Record Updated

if ($updateflg == 0)
{
    $since_id = "";
    $since_tweet = "";
    $qry = "";
    $qry = "Select owloo_id_str, owloo_tweet_count From owloo_last_id_str";
    $qry = $qry . " Where owloo_user_id = '" . mysql_real_escape_string($uid) . "'";
    $dataarr = mysql_query($qry);
    $fetch_cntr = mysql_fetch_array($dataarr);
    if($fetch_cntr)
    {   
        $since_id = $fetch_cntr['owloo_id_str'];
        $since_tweet = $fetch_cntr['owloo_tweet_count'];
    }
    if(!$since_id)
    {
        $updateflg = 1;
    }
}

if ($updateflg == 1)
{
    include("inserthashmention.php");
}
else
{
    include("updatehashmention.php");
}
?>