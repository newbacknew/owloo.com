<?php
    $n_a = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
    $local_fans_last_date = get_fb_page_local_fans_general_last_date();
    
	/*$sql =   "SELECT p.id_page, username, name, picture, location, is_verified, p.likes total_likes, plf.likes local_likes, talking_about, (talking_about/p.likes) engagement, first_local_fans_country 
	           FROM facebook_page p JOIN facebook_page_local_fans_country plf ON p.id_page = plf.id_page 
	           WHERE id_country = ".mysql_real_escape_string($country_data['id_country'])." 
	               AND plf.date = '".$local_fans_last_date."' 
	               AND active = 1 
	           ORDER BY engagement DESC, local_likes DESC, total_likes DESC, talking_about DESC 
	           LIMIT ".PAGER_PP."
			 ;";
     */
    
    $sql =   "SELECT p.id_page, username, name, picture, location, is_verified, likes total_likes,  talking_about, (talking_about/p.likes) engagement, first_local_fans_country 
               FROM facebook_page p 
               WHERE (location = ".mysql_real_escape_string($country_data['id_country'])." || first_local_fans_country = ".mysql_real_escape_string($country_data['id_country']).")
                   AND active = 1 
               ORDER BY engagement DESC, total_likes DESC, talking_about DESC 
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
                            <a href="<?=URL_ROOT?>facebook-stats/pages/<?=strtolower($fila['username'])?>/">
                                <img class="owloo_fb_page_avatar" src="<?=URL_IMAGES?>loader-24x24.gif" alt="<?=$fila['username']?>" data="<?=$fila['picture']?>" />
                            </a>
                            &nbsp;</div>
                        <a href="<?=URL_ROOT?>facebook-stats/pages/<?=strtolower($fila['username'])?>/"><?=$fila['name']?></a>
                    </div>
                    <div class="owloo_fb_ranking_item">
                        <div class="owloo_title owloo_fb_pta_td">PTA</div>
                        <div class="owloo_fb_num_green"><?=owlooFormatPorcent($fila['talking_about'], $fila['total_likes'])?>%</div>
                    </div>
                    <div class="owloo_fb_ranking_item">
                        <div class="owloo_title">Fans totales</div>
                        <div class="owloo_fb_num_green"><?=owloo_number_format($fila['total_likes'])?></div>
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