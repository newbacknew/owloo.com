<?php
    
    $ultimos90Dias = get_country_date_last_x_days(COUNTRY_DATE_LAST_UPDATE, 89);
    
	$sql =   "SELECT cont1.nombre nombre, sum(total_user) total_user, sum(total_female) total_female, sum(total_male) total_male, (
					sum(total_user) - (SELECT sum(total_user) total_user 
							FROM record_country r 
								join country c on r.id_country = c.id_country 
								join continent cont on c.id_continent = cont.id_continent 
							WHERE date = STR_TO_DATE('".$ultimos90Dias."','%Y-%m-%d') 
								AND cont.id_continent = cont1.id_continent)
					) cambio 
				FROM record_country rc1 
					JOIN country c1 ON rc1.id_country = c1.id_country 
					join continent cont1 on c1.id_continent = cont1.id_continent 
				WHERE date = STR_TO_DATE('".COUNTRY_DATE_LAST_UPDATE."','%Y-%m-%d') 
				GROUP BY date, nombre
				ORDER BY 2 DESC;
				"; 
	$res = mysql_query($sql) or die(mysql_error());
?>
<div id="owloo_ranking">
<?php
	$cont = 1;
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
                    <div class="owloo_rank owloo_no_margin"><?=str_pad($cont++, 2, '0', STR_PAD_LEFT);?></div>
                    <span class="owloo_change_audition owloo_no_link <?=$class_crecimiento ?>">
                        <?=($crecimiento!=0?owloo_number_format($crecimiento):'<em>sin cambio</em>')?>
                    </span>
                    <div class="owloo_text">
                        <div class="owloo_title owloo_title_strong">
                            <strong><?=$fila['nombre']?></strong>
                        </div>
                        <div class="owloo_description">
                            <?=$fila['nombre']?> cuenta con <strong><?=owloo_number_format($fila['total_user'])?> usuarios</strong> de los cuales el <?=round(($fila['total_female'] * 100 / $fila['total_user']), 2)?>% son mujeres y <?=round(($fila['total_male'] * 100 / $fila['total_user']), 2);?>% son hombres.
                        </div>
                    </div>
                </div>
		<?php
	} ?>
</div>