<?php
require_once('../owloo_config.php');
include("config/config.php");
$qry = "";
	$qry = " SELECT owloo_screen_name, owloo_user_name, owloo_user_description, owloo_user_photo, owloo_created_on FROM owloo_user_master WHERE owloo_user_description != ''";
	$qry = $qry . " Order By owloo_user_id DESC";
	$qry = $qry . " LIMIT 0, 4";
	$qrydata = mysql_query($qry);
	while ($fetchdata = mysql_fetch_array($qrydata)){?>
        <div class="owloo_wrap_tw_last_add">
            <div>
                <a href="<?=URL_ROOT?>twitter-stats/userpage/<?=$fetchdata["owloo_screen_name"]?>"><img alt="<?=$fetchdata["owloo_user_name"]?>" class="owloo_tw_last_add_img" src="<?=convert_imagen_to_https($fetchdata["owloo_user_photo"])?>"></a>
                <div>
                    <span class="owloo_tw_last_add_follow_pre">+</span><a href="https://twitter.com/intent/user?screen_name=<?=$fetchdata["owloo_screen_name"]?>" rel="nofollow" target="_blank" class="owloo_tw_last_add_follow">Seguir</a>
                </div>
            </div>
            <div>
                <div><a href="<?=URL_ROOT?>twitter-stats/userpage/<?=$fetchdata["owloo_screen_name"]?>"><?=$fetchdata["owloo_user_name"]?></a></div>
                <div><?=$fetchdata["owloo_user_description"]?></div>
            </div>
        </div>
<?php } ?>