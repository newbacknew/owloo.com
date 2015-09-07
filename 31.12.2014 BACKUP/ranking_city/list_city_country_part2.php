<?php
    $max_list_country_city_count = mysql_num_rows($res); 
?>
<div id="owloo_ranking">
	<?php
		$cont = 1;
		while($fila = mysql_fetch_assoc($res)){
            if($cont > PAGER_PP) break;
            $crecimiento = $fila['total_user'] - get_city_total_audience_for_date($fila['id_city'], $ultimas_12_semanas);
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
                            <strong><?=substr($fila['nombre'], 0, strpos($fila['nombre'], ','))?></strong>
                        </div>
                        <div class="owloo_description">
                            <?=substr($fila['nombre'], 0, strpos($fila['nombre'], ','))?> cuenta con <strong><?=owloo_number_format($fila['total_user'])?> usuarios</strong> de los cuales el <?=round(($fila['total_female'] * 100 / $fila['total_user']), 2)?>% son mujeres y <?=round(($fila['total_male'] * 100 / $fila['total_user']), 2);?>% son hombres.
                        </div>
                    </div>
                </div>
			<?php
		} ?>
</div>
<?php if($max_list_country_city_count > PAGER_PP){ ?>
<div class="owloo_nav_container">
<?php
for($p=0; $p*PAGER_PP <  $max_list_country_city_count; $p++){
    $pactive = ($p*PAGER_PP) == 0 ? "owloo_pactive" : "" ;
    $pc = $p+1;
    $pf = "";
    if($pc>7) {
        $pf = "owloo_inactive";
        if($pc >= ($max_list_country_city_count / PAGER_PP)){
            $pf = 'owloo_no-pag';
            echo '<div class="owloo_nav owloo_page-space '.$pf.'" id="owloo_no-pag-fin" >...</div>';
        }
    }
    else
    if($pc == 2){
        echo '<div class="owloo_nav owloo_page-space owloo_inactive" id="owloo_no-pag-ini" >...</div>';
    }
    ?> 
    <div onclick="next(<?=($p+1)?>, <?=$max_list_country_city_count?>, <?=PAGER_PP?>); load_page(<?=($p+1)?>, 'country_city', false, '<?=$countryCodeName?>');" class="owloo_nav <?=$pactive?> <?=$pf?>" id="owloo_nav_<?=($p+1)?>"><?=($p+1)?></div>
    <?php 
}
?>
</div>
<?php } ?>