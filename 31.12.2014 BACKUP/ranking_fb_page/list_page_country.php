<?php
    $n_a = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
    $local_fans_last_date = get_fb_page_local_fans_general_last_date();
    
    $page_total_count = get_fb_page_country_total_rows_count($country_data['id_country'], $local_fans_last_date);
    
	$sql =   "SELECT p.id_page, username, name, picture, location, is_verified, p.likes total_likes, plf.likes local_likes, talking_about, first_local_fans_country 
	           FROM facebook_page p JOIN facebook_page_local_fans_country plf ON p.id_page = plf.id_page 
	           WHERE id_country = ".mysql_real_escape_string($country_data['id_country'])." 
	               AND plf.date = '".$local_fans_last_date."' 
	               AND active = 1 
	           ORDER BY local_likes DESC, total_likes DESC, talking_about DESC 
	           LIMIT ".PAGER_PP."
			 ;";
             
	$res = mysql_query($sql) or die(mysql_error()); ?>
<div id="owloo_ranking">
<?php
    $cont = 1;
	while($fila = mysql_fetch_assoc($res)){
	    
        $crecimiento_fans = array('semana' => array('value' => $n_a, 'porcentaje' => $n_a)); 
	    
        $fb_page_likes_last_update = get_fb_page_likes_last_update($fila['id_page']);
        $fb_page_likes_nun_dates = get_fb_page_likes_nun_dates($fila['id_page']);
        
        if($fb_page_likes_nun_dates > 7)
            $crecimiento_fans['semana'] = getCrecimientoFacebookFansPage($fila['id_page'], $fb_page_likes_last_update, 7);
	        
		?>
                <div class="owloo_ranking_item">
                    <div class="owloo_rank"><?=str_pad($cont++, 2, '0', STR_PAD_LEFT);?></div>
                    <div class="owloo_fb_username">
                        <div class="owloo_fb_avatar">
                            <a href="<?=URL_ROOT?>facebook-stats/pages/<?=strtolower($fila['username'])?>/" title="Estadísticas de <?=$fila['name']?> en Facebook">
                                <img class="owloo_fb_page_avatar" src="<?=URL_IMAGES?>loader-24x24.gif" alt="<?=$fila['username']?>" data="<?=$fila['picture']?>" />
                            </a>
                            &nbsp;
                        </div>
                        <a href="<?=URL_ROOT?>facebook-stats/pages/<?=strtolower($fila['username'])?>/" title="Estadísticas de <?=$fila['name']?> en Facebook"><?=$fila['name']?></a>
                    </div>
                    <div class="owloo_fb_ranking_item">
                        <div class="owloo_title">Fans locales</div>
                        <div class="owloo_fb_num_green"><?=owloo_number_format($fila['local_likes'])?></div>
                    </div>
                    <div class="owloo_fb_ranking_item">
                        <div class="owloo_title">Fans totales</div>
                        <div class="owloo_fb_num_green"><?=owloo_number_format($fila['total_likes'])?></div>
                    </div>
                    <div class="owloo_fb_ranking_item">
                        <div class="owloo_title owloo_fb_pta_td">PTA</div>
                        <div class="owloo_fb_num_green"><?=owlooFormatPorcent($fila['talking_about'], $fila['total_likes'])?>%</div>
                    </div>
                    <div class="owloo_fb_ranking_item owloo_fb_ranking_item_change_audition">
                        <div class="owloo_title">Semana</div>
                        <div class="owloo_change_audition"><?=$crecimiento_fans['semana']['value']?></div>
                    </div>
                    <?php if($fila['is_verified']){ ?>
                    <div class="owloo_fb_ranking_item owloo_no_border">
                        <span class="owloo_fb_page_verified"></span>
                    </div>
                    <?php } ?>
                    
                </div>
	<?php
	} ?>
</div>
<?php if($page_total_count > PAGER_PP){ ?>
<div class="owloo_nav_container">
<?php
for($p=0; $p*PAGER_PP <  $page_total_count; $p++){
    $pactive = ($p*PAGER_PP) == 0 ? "owloo_pactive" : "" ; //0 = page_start
    $pc = $p+1;
    $pf = "";
    if($pc>7) {
        $pf = "owloo_inactive";
        if($pc >= ($page_total_count / PAGER_PP)){
            $pf = 'owloo_no-pag';
            echo '<div class="owloo_nav owloo_page-space '.$pf.'" id="owloo_no-pag-fin" >...</div>';
        }
    }
    else
    if($pc == 2){
        echo '<div class="owloo_nav owloo_page-space owloo_inactive" id="owloo_no-pag-ini" >...</div>';
    }
    ?> 
    <div onclick="next(<?=($p+1)?>, <?=$page_total_count?>, <?=PAGER_PP?>); load_page(<?=($p+1)?>, 'country_page', false, '<?=convert_to_url_string($country_data['name'])?>');" class="owloo_nav <?=$pactive?> <?=$pf?>" id="owloo_nav_<?=($p+1)?>"><?=($p+1)?></div>
    <?php 
}
?>
</div>
<?php } ?>