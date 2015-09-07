<?php
    
    $page_total_count = ($_POST['from_page']=='hispanic_page'?get_fb_page_total_rows_count(' hispanic = 1 AND '):get_fb_page_total_rows_count());
    
    $page = 1;
    if(isset($_POST['page']) && is_numeric($_POST['page'])){
        if($_POST['page'] < 1)
            $page = 1;
        elseif($_POST['page'] > ceil(($page_total_count/PAGER_PP)))
            $page = ceil(($page_total_count/PAGER_PP));
        else
            $page = $_POST['page'];
    }
    
    $n_a = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
    
    //SQL para obtener el ranking de países de la última fecha
	$sql =   "SELECT id_page, username, name, picture, location, is_verified, likes, talking_about, first_local_fans_country 
	               FROM facebook_page 
	               WHERE ".($_POST['from_page']=='hispanic_page'?' hispanic = 1 AND ':'')." active = 1 
	               ORDER BY likes DESC, talking_about DESC LIMIT ".(PAGER_PP * ($page - 1)).", ".PAGER_PP.";"; 
                   
	$res = mysql_query($sql) or die(mysql_error());
    
    $cont = 1 + (PAGER_PP * ($page-1));
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
	<script>window.setTimeout(function(){ load_facebook_avatar() }, 1000);</script>