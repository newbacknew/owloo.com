<?php
    
    $max_list_country_city_count = 100;
?>
<div id="owloo_ranking">
	<?php
	
	   $datos = '[{"id_city":"2001","nombre":"New Delhi, India","total_user":"6200000","total_female":"1700000","total_male":"4600000","id_country":"21","code":"IN","name":"India","crecimiento":400000},{"id_city":"2002","nombre":"Mumbai, India","total_user":"6000000","total_female":"1760000","total_male":"4200000","id_country":"21","code":"IN","name":"India","crecimiento":200000},{"id_city":"2003","nombre":"Bangalore, India","total_user":"4400000","total_female":"1240000","total_male":"3200000","id_country":"21","code":"IN","name":"India","crecimiento":400000},{"id_city":"2004","nombre":"Hyderabad, India","total_user":"4000000","total_female":"1000000","total_male":"3000000","id_country":"21","code":"IN","name":"India","crecimiento":400000},{"id_city":"2005","nombre":"Chennai, India","total_user":"3600000","total_female":"940000","total_male":"2800000","id_country":"21","code":"IN","name":"India","crecimiento":200000},{"id_city":"2006","nombre":"Pune, India","total_user":"3600000","total_female":"860000","total_male":"2600000","id_country":"21","code":"IN","name":"India","crecimiento":600000},{"id_city":"2008","nombre":"Calcutta, India","total_user":"3000000","total_female":"860000","total_male":"2200000","id_country":"21","code":"IN","name":"India","crecimiento":400000},{"id_city":"2007","nombre":"Delhi, India","total_user":"1980000","total_female":"560000","total_male":"1400000","id_country":"21","code":"IN","name":"India","crecimiento":320000},{"id_city":"2010","nombre":"Jaipur, India","total_user":"1480000","total_female":"340000","total_male":"1140000","id_country":"21","code":"IN","name":"India","crecimiento":140000},{"id_city":"2011","nombre":"Ahmedabad, India","total_user":"1360000","total_female":"320000","total_male":"1060000","id_country":"21","code":"IN","name":"India","crecimiento":100000},{"id_city":"2012","nombre":"Chandigarh, India","total_user":"1240000","total_female":"360000","total_male":"880000","id_country":"21","code":"IN","name":"India","crecimiento":140000},{"id_city":"2009","nombre":"Lucknow, India","total_user":"1160000","total_female":"300000","total_male":"840000","id_country":"21","code":"IN","name":"India","crecimiento":140000},{"id_city":"2013","nombre":"Surat, India","total_user":"940000","total_female":"190000","total_male":"760000","id_country":"21","code":"IN","name":"India","crecimiento":100000},{"id_city":"2016","nombre":"Indore, India","total_user":"820000","total_female":"220000","total_male":"600000","id_country":"21","code":"IN","name":"India","crecimiento":60000},{"id_city":"2014","nombre":"Ludhiana, India","total_user":"800000","total_female":"220000","total_male":"580000","id_country":"21","code":"IN","name":"India","crecimiento":-40000},{"id_city":"2018","nombre":"Bhopal, India","total_user":"720000","total_female":"188000","total_male":"520000","id_country":"21","code":"IN","name":"India","crecimiento":20000},{"id_city":"2015","nombre":"Nagpur, India","total_user":"660000","total_female":"188000","total_male":"480000","id_country":"21","code":"IN","name":"India","crecimiento":20000},{"id_city":"2022","nombre":"Coimbatore, India","total_user":"640000","total_female":"172000","total_male":"480000","id_country":"21","code":"IN","name":"India","crecimiento":20000},{"id_city":"2025","nombre":"Gurgaon, India","total_user":"640000","total_female":"164000","total_male":"460000","id_country":"21","code":"IN","name":"India","crecimiento":40000},{"id_city":"2017","nombre":"Kanpur, India","total_user":"580000","total_female":"152000","total_male":"420000","id_country":"21","code":"IN","name":"India","crecimiento":40000}]';
	   $datos = json_decode($datos, true);
	
		$cont = 1;
		foreach($datos as $fila){ 
            if($cont > PAGER_PP) break;
            
            $crecimiento = $fila['crecimiento'];

            
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