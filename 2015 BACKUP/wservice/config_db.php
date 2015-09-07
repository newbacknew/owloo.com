<?php
    define('DB_USER', 'owloo_admin');
    define('DB_PASS', 'iiRTwMxs=%am');
    define('DB_NAME', 'owloo_owloo_3_1');
    define('DB_NAME_TW', 'owloo_twitter_3_1');
    
    /*** DB prefix ***/
    define('DB_RESULTS_PREFIX', 'web_');
    define('DB_FACEBOOK_PREFIX', 'facebook_');
    define('DB_TWITTER_PREFIX', 'twitter_');
    define('DB_INSTAGRAM_PREFIX', 'instagram_');
    
    /*
    
    //Insert FB pages
    INSERT INTO `facebook_page`(`id_page`, `fb_id`, `username`, `name`, `about`, `description`, `link`, `picture`, `cover`, `location`, `is_verified`, `likes`, `talking_about`, `first_local_fans_country`, `hispanic`, `active`, `parent`, `date_add`, `date_update`)
    SELECT `id_page`, `fb_id`, `username`, `name`, `about`, `description`, `link`, `picture`, `cover`, 0, `is_verified`, `likes`, `talking_about`, 0, 0, 1, `parent`, `in_owloo_from`, `updated_at` FROM owloo_results.web_facebook_pages
    
    
    //Insert IN profiles
    INSERT INTO `instagram_profiles`(`id_profile`, `instagram_id`, `username`, `bio`, `website`, `profile_picture`, `full_name`, `id_category`, `active`, `date_add`, `date_update`)
    SELECT `id`, 0, `username`, `bio`, `website`, `picture`, `name`, 0, 1, `in_owloo_from`, `updated_at` FROM owloo_results.web_instagram_profiles
    
     
    //Insert TW profiles
    INSERT INTO `twitter_user_master`(`owloo_user_id`, `owloo_user_twitter_id`, `owloo_user_name`, `owloo_screen_name`, `owloo_user_photo`, `owloo_user_cover`, `owloo_user_description`, `owloo_user_location`, `owloo_user_language`, `owloo_user_verified_account`, `owloo_user_timezone`, `owloo_user_created_on`, `owloo_followers_count`, `owloo_following_count`, `owloo_tweetcount`, `owloo_listed_count`, `owloo_user_status`, `owloo_created_on`, `owloo_updated_on`)
    SELECT `id`, '0', `name`, `screen_name`, `picture`, `cover`, `description`, `location`, `idiom`, `is_verified`, '', `in_twitter_from`, `followers_count`, `following_count`, `tweet_count`, 0, 1, `in_owloo_from`, `updated_at` FROM owloo_results.web_twitter_profiles
    
     
    
    */

