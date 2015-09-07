<?php
	$qry = "";
	$qry = "SELECT count( a.owloo_user_id ) AS count, b.owloo_user_name, b.owloo_screen_name, b.owloo_user_photo, b.owloo_user_verified_account, b.owloo_user_id";
	$qry = $qry . " FROM owloo_mentions a JOIN owloo_user_master b";
	$qry = $qry . " ON a.owloo_user_id = b.owloo_user_id";
	$qry = $qry . " GROUP BY a.owloo_user_id";
	$qry = $qry . " ORDER BY count DESC";
	$qry = $qry . " LIMIT 0, ".PAGER_PP;
	$qrydata = mysql_query($qry); ?>
<div id="owloo_ranking">
    <?php
    $cont = 1;
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
	</table>
</div>
<?php if(TWITTER_MENTIONS_TOTAL_PROFILES > PAGER_PP){ ?>
<div class="owloo_nav_container">
<?php
for($p=0; $p*PAGER_PP <  TWITTER_MENTIONS_TOTAL_PROFILES; $p++){
    $pactive = ($p*PAGER_PP) == 0 ? "owloo_pactive" : "" ; //0 = page_start
    $pc = $p+1;
    $pf = "";
    if($pc>7) {
        $pf = "owloo_inactive";
        if($pc >= (TWITTER_MENTIONS_TOTAL_PROFILES / PAGER_PP)){
            $pf = 'owloo_no-pag';
            echo '<div class="owloo_nav owloo_page-space '.$pf.'" id="owloo_no-pag-fin" >...</div>';
        }
    }
    else
    if($pc == 2){
        echo '<div class="owloo_nav owloo_page-space owloo_inactive" id="owloo_no-pag-ini" >...</div>';
    }
    ?> 
    <div onclick="next(<?=($p+1)?>, <?=TWITTER_MENTIONS_TOTAL_PROFILES?>, <?=PAGER_PP?>); load_page(<?=($p+1)?>, 'mentions_twitter', false, '');" class="owloo_nav <?=$pactive?> <?=$pf?>" id="owloo_nav_<?=($p+1)?>"><?=($p+1)?></div>
    <?php 
}
?>
</div>
<?php } ?>