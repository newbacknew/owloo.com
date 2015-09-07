<?php
	if(!isset($is_hispanic))
		$is_hispanic = '';
	
	$qry = "";
	$qry = " SELECT owloo_user_id, owloo_user_name, owloo_screen_name, owloo_followers_count, owloo_following_count, owloo_user_photo, owloo_user_verified_account, 
		(owloo_followers_count - (SELECT owloo_followers_count FROM owloo_daily_track WHERE owloo_updated_on >= STR_TO_DATE('".get_twitter_date_days(30)."','%Y-%m-%d') AND owloo_user_twitter_id = m.owloo_user_id ORDER BY owloo_updated_on ASC LIMIT 1)) cambio
		 FROM owloo_user_master m";
	$qry = $qry . " ".$is_hispanic." Order By owloo_followers_count DESC";
	$qry = $qry . " LIMIT 0, ".PAGER_PP;
	
	$qrydata = mysql_query($qry); ?>
<div id="owloo_ranking">
	<?php
	$cont = 1;
	while ($fetchdata = mysql_fetch_array($qrydata)){
		//Crecimiento
		$crecimiento = $fetchdata['cambio'];
        $class_crecimiento = '';
        if($crecimiento > 0){
            $class_crecimiento = 'owloo_arrow_up';
        }
        else if($crecimiento < 0){
            $crecimiento *= -1;
            $class_crecimiento = 'owloo_arrow_down';
        }
        
        ?>
        <div class="owloo_ranking_item">
            <div class="owloo_rank"><?=str_pad($cont++, 2, '0', STR_PAD_LEFT);?></div>
            <span class="owloo_change_audition <?=$class_crecimiento ?>">
                <?=($crecimiento!=0?owloo_number_format($crecimiento):'<em>sin cambio</em>')?>
            </span>
            <?php if($fetchdata["owloo_user_verified_account"]){?><img class="owloo_user_twitter_verified owloo_tooltip" src="<?=URL_IMAGES.'owloo_user_verified_account.png'?>" title="Cuenta verificada" alt="" /><?php } ?>
            <div class="owloo_text">
                <div class="owloo_title">
                    <span class="owloo_twitter_profile" >
                        <img class="owloo_user_twitter_small owloo_user_twitter_avatar" src="<?=URL_IMAGES?>loader-24x24.gif" id="<?=convert_imagen_to_https(str_replace('_normal.', '_mini.', $fetchdata["owloo_user_photo"]))?>" title="" alt="" />
                    </span>
                    <a href="<?=URL_ROOT?>twitter-stats/userpage/<?=$fetchdata["owloo_screen_name"]?>" title="Datos y estadísticas de <?=$fetchdata["owloo_user_name"]?> en Twitter">@<?=$fetchdata["owloo_screen_name"]?></a>
                </div>
                <div class="owloo_description">
                    <?=$fetchdata["owloo_user_name"]?> actualmente tiene <strong><?=owloo_number_format($fetchdata["owloo_followers_count"])?> seguidores</strong> y está <strong>siguiendo a <?=owloo_number_format($fetchdata["owloo_following_count"])?> perfiles</strong>.
                </div>
            </div>
        </div>
		<?php
	} ?>
</div>
<?php if(TWITTER_TOTAL_PROFILES > PAGER_PP){ ?>
<div class="owloo_nav_container">
<?php
for($p=0; $p*PAGER_PP <  TWITTER_TOTAL_PROFILES; $p++){
    $pactive = ($p*PAGER_PP) == 0 ? "owloo_pactive" : "" ; //0 = page_start
    $pc = $p+1;
    $pf = "";
    if($pc>7) {
        $pf = "owloo_inactive";
        if($pc >= (TWITTER_TOTAL_PROFILES / PAGER_PP)){
            $pf = 'owloo_no-pag';
            echo '<div class="owloo_nav owloo_page-space '.$pf.'" id="owloo_no-pag-fin" >...</div>';
        }
    }
    else
    if($pc == 2){
        echo '<div class="owloo_nav owloo_page-space owloo_inactive" id="owloo_no-pag-ini" >...</div>';
    }
    ?> 
    <div onclick="next(<?=($p+1)?>, <?=TWITTER_TOTAL_PROFILES?>, <?=PAGER_PP?>); load_page(<?=($p+1)?>, '<?=(isset($_GET['page'])&&$_GET['page']=='hispanic'?'hispanic':'global')?>_twitter', false, '');" class="owloo_nav <?=$pactive?> <?=$pf?>" id="owloo_nav_<?=($p+1)?>"><?=($p+1)?></div>
    <?php 
}
?>
</div>
<?php } ?>