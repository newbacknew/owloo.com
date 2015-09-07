<?php
    $page_total_count = ($_GET['ranking']=='hispanic'?get_fb_page_total_rows_count(' hispanic = 1 AND '):get_fb_page_total_rows_count());
    
    $n_a = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
    
    //SQL para obtener el ranking de países de la última fecha
	$sql =   "SELECT id_page, username, name, picture, location, is_verified, likes, talking_about, first_local_fans_country FROM facebook_page WHERE ".($_GET['ranking']=='hispanic'?' hispanic = 1 AND ':'')." active = 1 ORDER BY likes DESC, talking_about DESC LIMIT 0, ".PAGER_PP."
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
                        <div class="owloo_title">Fans totales</div>
                        <div class="owloo_fb_num_green"><?=owloo_number_format($fila['likes'])?></div>
                    </div>
                    <div class="owloo_fb_ranking_item">
                        <div class="owloo_title owloo_fb_pta_td">PTA</div>
                        <div class="owloo_fb_num_green"><?=owlooFormatPorcent($fila['talking_about'], $fila['likes'])?>%</div>
                    </div>
                    <div class="owloo_fb_ranking_item owloo_fb_ranking_item_change_audition">
                        <div class="owloo_title">Semana</div>
                        <div class="owloo_change_audition"><?=$crecimiento_fans['semana']['value']?></div>
                    </div>
                    <div class="owloo_fb_ranking_item">
                        <div class="owloo_fb_flag">
                            <?php if(!empty($fila['location']) || !empty($fila['first_local_fans_country'])){ ?>
                            <a href="<?=URL_ROOT?>facebook-stats/pages/country/<?=convert_to_url_string(get_country_data((!empty($fila['location'])?$fila['location']:$fila['first_local_fans_country']), 'code'))?>/" title="Páginas más populares de Facebook en <?=get_country_data((!empty($fila['location'])?$fila['location']:$fila['first_local_fans_country']), 'nombre')?>">
                                <span class="owloo_country_flag" style="background-position:0 <?=(-20 * ((!empty($fila['location'])?$fila['location']:$fila['first_local_fans_country'])-1))?>px"></span>
                            </a>
                            <?php } ?>
                        </div>
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
    <div onclick="next(1, <?=$page_total_count?>, <?=PAGER_PP?>); load_page(1, '<?=($_GET['ranking']=='hispanic'?'hispanic':'global')?>_page', false, '');" class="owloo_nav owloo_inactive" id="owloo_nav_before">Anterior</div>
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
    <div onclick="next(<?=($p+1)?>, <?=$page_total_count?>, <?=PAGER_PP?>); load_page(<?=($p+1)?>, '<?=($_GET['ranking']=='hispanic'?'hispanic':'global')?>_page', false, '');" class="owloo_nav <?=$pactive?> <?=$pf?>" id="owloo_nav_<?=($p+1)?>"><?=($p+1)?></div>
    <?php 
}
?>
<div onclick="next(2, <?=$page_total_count?>, <?=PAGER_PP?>); load_page(2, '<?=($_GET['ranking']=='hispanic'?'hispanic':'global')?>_page', false, '');" class="owloo_nav" id="owloo_nav_after">Siguiente</div>
</div>
<?php } ?>