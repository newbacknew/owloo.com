<?php
    $country_total_count = ($_POST['from_page']=='hispanic_country'?COUNTRY_HISPANIC_TOTAL_COUNT:COUNTRY_TOTAL_COUNT);
    
    $page = 1;
    if(isset($_POST['page']) && is_numeric($_POST['page'])){
    	if($_POST['page'] < 1)
    		$page = 1;
    	elseif($_POST['page'] > ceil(($country_total_count/PAGER_PP)))
    		$page = ceil(($country_total_count/PAGER_PP));
    	else
    		$page = $_POST['page'];
    }
    
    $ultimos90Dias = get_country_date_last_x_days(COUNTRY_DATE_LAST_UPDATE, 89);
    
    //SQL para obtener el ranking de países de la última fecha
    $sql =   "SELECT c.id_country id_country, nombre, total_user, total_female, total_male, c.code code, name, (total_user - (SELECT total_user FROM record_country WHERE date = STR_TO_DATE('".$ultimos90Dias."','%Y-%m-%d') AND id_country = r.id_country)) cambio
                FROM record_country r 
                    JOIN country c 
                        ON r.id_country = c.id_country 
                WHERE date = STR_TO_DATE('".COUNTRY_DATE_LAST_UPDATE."','%Y-%m-%d') 
                ".($_POST['from_page']=='hispanic_country'?'AND habla_hispana = 1':'')."
                ORDER BY 3 DESC, nombre LIMIT ".(PAGER_PP * ($page - 1)).", ".PAGER_PP.";
                ";
    $res = mysql_query($sql) or die(mysql_error());
    
    $cont = 1 + (PAGER_PP * ($page-1));
    while($fila = mysql_fetch_assoc($res)){
        $crecimiento = $fila['cambio'];
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
                    <div class="owloo_text">
                        <div class="owloo_title">
                            <span class="owloo_country_flag" style="background-position:0 <?=(-20 * ($fila['id_country']-1))?>px"></span>
                            <a href="<?=URL_ROOT?>facebook-stats/<?=convert_to_url_string($fila['name'])?>/" title="Estadísticas de Facebook en <?=$fila['nombre']?>"><?=$fila['nombre']?></a>
                        </div>
                        <div class="owloo_description">
                            <strong><?=$fila['nombre']?></strong> cuenta con <strong><?=owloo_number_format($fila['total_user'])?> usuarios</strong> de los cuales el <?=round(($fila['total_female'] * 100 / $fila['total_user']), 2)?>% son mujeres y <?=round(($fila['total_male'] * 100 / $fila['total_user']), 2);?>% son hombres.
                        </div>
                    </div>
                </div>
    <?php
    }
    ?>
