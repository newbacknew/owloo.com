<?php
$qry = "";
$qry = " SELECT sum( owloo_rating_points ) AS count, b.owloo_screen_name, b.owloo_user_name, b.owloo_user_description, b.owloo_user_photo, b.owloo_followers_count, b.owloo_following_count, b.owloo_tweetcount ";
$qry = $qry . " FROM owloo_user_rating a LEFT JOIN owloo_user_master b";
$qry = $qry . " ON a.owloo_user_id = b.owloo_user_id";
$qry = $qry . " GROUP BY a.owloo_user_id";
$qry = $qry . " ORDER BY count DESC";
$qry = $qry . " LIMIT 0, 2";
$qrydata = mysql_query($qry);
while ($fetchdata = mysql_fetch_array($qrydata)){?>
<div class="awloo_tw_box_footer_item">
    <a href="<?=URL_ROOT?>twitter.php?page=userpage&twittername=<?=$fetchdata["owloo_screen_name"]?>"><img src="<?=$fetchdata["owloo_user_photo"]?>" class="awloo_tw_box_footer_img" title="" alt="" /></a>
    <div>
        <div class="awloo_tw_box_footer_title"><b><a href="<?=URL_ROOT?>twitter-stats/userpage/<?=$fetchdata["owloo_screen_name"]?>"><?=$fetchdata["owloo_user_name"]?></a></b></div>
        <div class="awloo_tw_box_footer_values">Seguidores: <b><?=number_format($fetchdata['owloo_followers_count'], 0, '.', ' ')?></b> / Siguiendo: <b><?=number_format($fetchdata['owloo_following_count'], 0, '.', ' ')?></b> / Tweets: <b><?=number_format($fetchdata['owloo_tweetcount'], 0, '.', ' ')?></b></div>
        <div class="awloo_tw_box_footer_text"><?=$fetchdata['owloo_user_description']?></div>
        <div class="awloo_tw_box_footer_btn">
            <a rel="nofollow" href="https://twitter.com/<?=$fetchdata["owloo_screen_name"]?>" class="twitter-follow-button" data-show-count="false" data-lang="es">Seguir a @<?=$fetchdata["owloo_screen_name"]?></a>
        </div>
    </div>
</div>
<?php }