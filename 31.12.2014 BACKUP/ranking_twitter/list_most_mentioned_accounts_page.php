<?php
    $page = 1;
    if(isset($_POST['page']) && is_numeric($_POST['page'])){
        if($_POST['page'] < 1)
            $page = 1;
        elseif($_POST['page'] > round((TWITTER_MENTIONS_TOTAL_PROFILES/PAGER_PP), 0, PHP_ROUND_HALF_UP))
            $page = round((TWITTER_MENTIONS_TOTAL_PROFILES/PAGER_PP), 0, PHP_ROUND_HALF_UP);
        else
            $page = $_POST['page'];
    }
	$qry = "";
	$qry = "SELECT count( a.owloo_user_id ) AS count, b.owloo_user_name, b.owloo_screen_name, b.owloo_user_photo, b.owloo_user_verified_account, b.owloo_user_id";
	$qry = $qry . " FROM owloo_mentions a JOIN owloo_user_master b";
	$qry = $qry . " ON a.owloo_user_id = b.owloo_user_id";
	$qry = $qry . " GROUP BY a.owloo_user_id";
	$qry = $qry . " ORDER BY count DESC";
	$qry = $qry . " LIMIT ".(PAGER_PP * ($page - 1)).", ".PAGER_PP;
	$qrydata = mysql_query($qry);
    
    $cont = 1 + (PAGER_PP * ($page - 1));
	while ($fetchdata = mysql_fetch_array($qrydata)){ ?>
		<div class="owloo_ranking_item">
            <div class="owloo_rank"><?=str_pad($cont++, 2, '0', STR_PAD_LEFT);?></div>
            <div class="owloo_text">
                <?php if($fetchdata["owloo_user_verified_account"]){?><img class="owloo_user_twitter_verified owloo_no_margin_right owloo_tooltip" src="<?=URL_IMAGES.'owloo_user_verified_account.png'?>" title="Cuenta verificada" alt="" /><?php } ?>
                <div class="owloo_title">
                    <span class="owloo_twitter_profile" >
                        <img class="owloo_user_twitter_small owloo_user_twitter_avatar" src="<?=URL_IMAGES?>loader-24x24.gif" id="<?=str_replace('_normal.', '_mini.', $fetchdata["owloo_user_photo"]); ?>" title="" alt="" />
                    </span>
                    <a href="<?=URL_ROOT?>twitter-stats/userpage/<?=$fetchdata["owloo_screen_name"]?>" title="Datos y estadísticas de <?=$fetchdata["owloo_user_name"]?> en Twitter">@<?=$fetchdata["owloo_screen_name"]?></a>
                </div>
                <div class="owloo_description">
                    <?=$fetchdata["owloo_user_name"]?> tiene <strong><?=owloo_number_format($fetchdata["count"])?> de menciones</strong> entre todas las cuentas registradas en el owloo.
                </div>
            </div>
        </div>
		<?php
	} ?>
    <script>window.setTimeout(function(){ load_twitter_avatar() }, 1000);</script>