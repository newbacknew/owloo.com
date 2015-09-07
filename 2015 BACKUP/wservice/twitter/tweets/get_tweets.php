<?php

    $screenname = $screen_name;
    $tweetcount = $tweetcount;
    $param = '';
    $param .= 'screen_name=' . $screenname;
    //$param = 'user_id=' . $twitter_id . '&trim_user=1';
    if(!$is_new_insert){
        $param .= '&since_id=' . $since_id;
    }
    $param .= '&trim_user=1';
    
    $laststr = "";
    $cnt1 = 0;
    
    if($is_new_insert){
        $totaltw = $tweetcount;
    }else{
        $totaltw = $tweetcount - $since_tweet;
    }
    $maxid = 0;
    $lastid = "";
    $mentionname = '';
    $hashtagword = '';
    $urlword = '';
    $mediaword = '';
    $tweetqry = "";
    $flgmain = false;
    $threadflg = false;
    if ($totaltw > 3200) {
        $totaltw = 3200;
        $threadflg = true;
    }
    
    while ($cnt1 <= $totaltw) {
        
        $json = gettwitterdata($maxid, $param);
        
        //print_r(json_decode($json, true)); die();
        
        if ($json) {
            $tweets = json_decode($json, true);
            foreach ($tweets as $tweet) {
                
                if($tweet['user']['id'] != $twitter_id){
                    return NULL;
                }
                
                if($tweet['id_str'] == $maxid){
                    continue;
                }
                
                $lastid = mysql_real_escape_string($tweet['id_str']);
                $text = mysql_real_escape_string($tweet['text']);
                $source = mysql_real_escape_string($tweet['source']);
                $retweet_count = mysql_real_escape_string($tweet['retweet_count']);
                $favorite_count = mysql_real_escape_string($tweet['favorite_count']);
                $lang = mysql_real_escape_string($tweet['lang']);
                
                $type_tweet = 1;
                
                $retweeted_from_profile_id = 'NULL';
                if(isset($tweet['retweeted_status']['user']['id']) && !empty($tweet['retweeted_status']['user']['id'])){
                    $retweeted_from_profile_id = mysql_real_escape_string($tweet['retweeted_status']['user']['id']);
                    $type_tweet = 2;
                }
                
                $in_reply_to_screen_name = 'NULL';
                if(isset($tweet['in_reply_to_screen_name']) && !empty($tweet['in_reply_to_screen_name'])){
                    $in_reply_to_screen_name = "'".mysql_real_escape_string($tweet['in_reply_to_screen_name'])."'";
                    $type_tweet = 3;
                }
                
                $has_media = 0;
                $tweet_created_at = strtotime($tweet['created_at']);
                
                $usrmentions = $tweet["entities"];
                $hastagarrs = $usrmentions["hashtags"];
                $mentionsarrs = $usrmentions["user_mentions"];
                $urls = $usrmentions['urls'];
                $medias = $usrmentions['media'];
                
                if ($mentionsarrs) {
                    foreach ($mentionsarrs as $mentionsarr) {
                        $mentionname = $mentionname . " (" . $uid . ",";
                        $mentionname = $mentionname . "" . $lastid . ",";
                        $mentionname = $mentionname . "'" . mysql_real_escape_string($mentionsarr["screen_name"]) . "',";
                        $mentionname = $mentionname . "'" . mysql_real_escape_string($mentionsarr["name"]) . "',";
                        $mentionname = $mentionname . "" . mysql_real_escape_string($mentionsarr["id"]) . ",";
                        $mentionname = $mentionname . "" . $type_tweet . "),";
                    }
                }
                
                if ($hastagarrs) {
                    foreach ($hastagarrs as $hastagarr) {
                        $hashtagword = $hashtagword . " (" . $uid . ",";
                        $hashtagword = $hashtagword . "" . $lastid . ",";
                        $hashtagword = $hashtagword . "'" . mysql_real_escape_string($hastagarr["text"]) . "',";
                        $hashtagword = $hashtagword . "" . $type_tweet . "),";
                    }
                }
                
                if ($urls) {
                    foreach ($urls as $url) {
                        $urlword = $urlword . " (" . $uid . ",";
                        $urlword = $urlword . "" . $lastid . ",";
                        $urlword = $urlword . "'" . mysql_real_escape_string($url["url"]) . "',";
                        $urlword = $urlword . "'" . mysql_real_escape_string($url["expanded_url"]) . "',";
                        $urlword = $urlword . "" . $type_tweet . "),";
                    }
                }
                
                if ($medias) {
                    foreach ($medias as $media) {
                        $has_media = 1;
                        $mediaword = $mediaword . " (" . $uid . ",";
                        $mediaword = $mediaword . "" . $lastid . ",";
                        $mediaword = $mediaword . "'" . mysql_real_escape_string($media["id"]) . "',";
                        $mediaword = $mediaword . "'" . mysql_real_escape_string($media["media_url"]) . "',";
                        $mediaword = $mediaword . "'" . mysql_real_escape_string($media["url"]) . "',";
                        $mediaword = $mediaword . "'" . mysql_real_escape_string($media["type"]) . "',";
                        $mediaword = $mediaword . "" . $type_tweet . "),";
                    }
                }
                
                $tweetqry = $tweetqry . " (" . $uid . ",";
                $tweetqry = $tweetqry . " " . $lastid . ",";
                $tweetqry = $tweetqry . " '" . $text . "',";
                $tweetqry = $tweetqry . " '" . $source . "',";
                $tweetqry = $tweetqry . " " . $retweet_count . ",";
                $tweetqry = $tweetqry . " " . $favorite_count . ",";
                $tweetqry = $tweetqry . " '" . $lang . "',";
                $tweetqry = $tweetqry . " " . $retweeted_from_profile_id . ",";
                $tweetqry = $tweetqry . " " . $in_reply_to_screen_name . ",";
                $tweetqry = $tweetqry . " " . $has_media . ",";
                $tweetqry = $tweetqry . " " . $type_tweet . ",";
                $tweetqry = $tweetqry . " " . $tweet_created_at . ",";
                $tweetqry = $tweetqry . " NOW(), NOW()),";
                
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
    if ($flgmain && !empty($lastid)) {
        
        if (strlen($tweetqry) > 1) {
            $tweetqry1 = "INSERT INTO twitter_tweet_data (owloo_user_id, twitter_tweet_id, text, source, retweet_count, favorite_count, lang, retweeted_from_profile_id, in_reaply_to_screen_name, has_media, type_tweet, tweet_created_at, in_owloo_from, updated_at) VALUES ";
            $tweetqry1 = $tweetqry1 . substr($tweetqry, 0, strlen($tweetqry) - 1) . ";";
            mysql_query($tweetqry1) or die(mysql_error());
            
            $qry_last_tweet_id = "";
            $qry_last_tweet_id .= " SELECT twitter_tweet_id FROM twitter_tweet_data"; 
            $qry_last_tweet_id .= " WHERE owloo_user_id = " . $uid;
            $qry_last_tweet_id .= " ORDER BY twitter_tweet_id DESC";
            $qry_last_tweet_id .= " LIMIT 1;";
            $que_last_tweet_id = mysql_query($qry_last_tweet_id) or die(mysql_error());
            if ($que_last_tweet_id_array = mysql_fetch_assoc($que_last_tweet_id)) {
                
                if($que_last_tweet_id_array['twitter_tweet_id'] != $laststr){
                    return NULL;
                }else{
                    $qry = "";
                    $qry = "Delete from twitter_last_id_str where owloo_user_id = '" . $uid . "'";
                    mysql_query($qry) or die(mysql_error());
                    
                    $qry = "";
                    $qry = "INSERT INTO twitter_last_id_str (owloo_user_id, owloo_id_str, owloo_tweet_count) VALUES ";
                    $qry = $qry . "(" . $uid . ",'" . $laststr . "','" . $tweetcount . "')";
                    mysql_query($qry) or die(mysql_error());
                }
                
            }
            
        }
        if (strlen($mentionname) > 1) {
            $mentionqry = "INSERT INTO twitter_mentions (owloo_user_id, twitter_tweet_id, screen_name, name, user_twitter_id, type_tweet) VALUES ";
            $mentionqry = $mentionqry . substr($mentionname, 0, strlen($mentionname) - 1) . ";";
            mysql_query($mentionqry) or die(mysql_error());
        }
        if (strlen($hashtagword) > 1) {
            $hashtagqry = "INSERT INTO twitter_hashtag (owloo_user_id, twitter_tweet_id, hashtags, type_tweet) VALUES ";
            $hashtagqry = $hashtagqry . substr($hashtagword, 0, strlen($hashtagword) - 1) . ";";
            mysql_query($hashtagqry) or die(mysql_error());
        }
        if (strlen($urlword) > 1) {
            $urlqry = "INSERT INTO twitter_url (owloo_user_id, twitter_tweet_id, url, expanded_url, type_tweet) VALUES ";
            $urlqry = $urlqry . substr($urlword, 0, strlen($urlword) - 1) . ";";
            mysql_query($urlqry) or die(mysql_error());
        }
        if (strlen($mediaword) > 1) {
            $mediaqry = "INSERT INTO twitter_media (owloo_user_id, twitter_tweet_id, media_id, media_url, url, type, type_tweet) VALUES ";
            $mediaqry = $mediaqry . substr($mediaword, 0, strlen($mediaword) - 1) . ";";
            mysql_query($mediaqry) or die(mysql_error());
        }
    }