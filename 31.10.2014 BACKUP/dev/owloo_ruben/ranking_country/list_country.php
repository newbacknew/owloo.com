<?php
    
    $datos = '[{"id_country":"1","nombre":"Estados Unidos","total_user":"184000000","total_female":"100000000","total_male":"84000000","code":"US","name":"United States","cambio":"4000000"},{"id_country":"21","nombre":"India","total_user":"112000000","total_female":"26000000","total_male":"84000000","code":"IN","name":"India","cambio":"6000000"},{"id_country":"8","nombre":"Brasil","total_user":"94000000","total_female":"50000000","total_male":"44000000","code":"BR","name":"Brazil","cambio":"2000000"},{"id_country":"22","nombre":"Indonesia","total_user":"70000000","total_female":"30000000","total_male":"42000000","code":"ID","name":"Indonesia","cambio":"2000000"},{"id_country":"31","nombre":"M\u00e9xico","total_user":"56000000","total_female":"28000000","total_male":"28000000","code":"MX","name":"Mexico","cambio":"2000000"},{"id_country":"52","nombre":"Turqu\u00eda","total_user":"40000000","total_female":"14400000","total_male":"24000000","code":"TR","name":"Turkey","cambio":"2000000"},{"id_country":"39","nombre":"Filipinas","total_user":"38000000","total_female":"20000000","total_male":"18000000","code":"PH","name":"Philippines","cambio":"2000000"},{"id_country":"3","nombre":"Reino Unido","total_user":"36000000","total_female":"18600000","total_male":"17400000","code":"GB","name":"United Kingdom","cambio":"0"},{"id_country":"17","nombre":"Francia","total_user":"30000000","total_female":"15000000","total_male":"14200000","code":"FR","name":"France","cambio":"0"},{"id_country":"51","nombre":"Tailandia","total_user":"30000000","total_female":"15000000","total_male":"14400000","code":"TH","name":"Thailand","cambio":"2000000"},{"id_country":"18","nombre":"Alemania","total_user":"28000000","total_female":"13400000","total_male":"14400000","code":"DE","name":"Germany","cambio":"0"},{"id_country":"4","nombre":"Argentina","total_user":"26000000","total_female":"13400000","total_male":"12200000","code":"AR","name":"Argentina","cambio":"0"},{"id_country":"25","nombre":"Italia","total_user":"26000000","total_female":"12400000","total_male":"14000000","code":"IT","name":"Italy","cambio":"0"},{"id_country":"87","nombre":"Vietnam","total_user":"26000000","total_female":"12000000","total_male":"14400000","code":"VN","name":"Vietnam","cambio":"2000000"},{"id_country":"11","nombre":"Colombia","total_user":"22000000","total_female":"11000000","total_male":"10600000","code":"CO","name":"Colombia","cambio":"0"},{"id_country":"26","nombre":"Jap\u00f3n","total_user":"22000000","total_female":"11000000","total_male":"11400000","code":"JP","name":"Japan","cambio":"0"},{"id_country":"15","nombre":"Egipto","total_user":"20000000","total_female":"7600000","total_male":"13400000","code":"EG","name":"Egypt","cambio":"0"},{"id_country":"47","nombre":"Espa\u00f1a","total_user":"20000000","total_female":"10400000","total_male":"10000000","code":"ES","name":"Spain","cambio":"0"},{"id_country":"2","nombre":"Canad\u00e1","total_user":"19800000","total_female":"10400000","total_male":"9000000","code":"CA","name":"Canada","cambio":"400000"},{"id_country":"30","nombre":"Malasia","total_user":"16400000","total_female":"7400000","total_male":"8800000","code":"MY","name":"Malaysia","cambio":"200000"}]';
    $datos = json_decode($datos, true);
    
    $country_total_count = (isset($_GET['hispanic'])?COUNTRY_HISPANIC_TOTAL_COUNT:COUNTRY_TOTAL_COUNT);
?>
<div id="owloo_ranking">
<?php

    $cont = 1;
	foreach($datos as $fila){
	        
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
	} ?>
</div>
<?php if($country_total_count > PAGER_PP){ ?>
<div class="owloo_nav_container">
<?php
for($p=0; $p*PAGER_PP <  $country_total_count; $p++){
	$pactive = ($p*PAGER_PP) == 0 ? "owloo_pactive" : "" ; //0 = page_start
	$pc = $p+1;
	$pf = "";
	if($pc>7) {
		$pf = "owloo_inactive";
		if($pc >= ($country_total_count / PAGER_PP)){
			$pf = 'owloo_no-pag';
			echo '<div class="owloo_nav owloo_page-space '.$pf.'" id="owloo_no-pag-fin" >...</div>';
		}
	}
	else
	if($pc == 2){
		echo '<div class="owloo_nav owloo_page-space owloo_inactive" id="owloo_no-pag-ini" >...</div>';
	}
	?> 
	<div onclick="next(<?=($p+1)?>, <?=$country_total_count?>, <?=PAGER_PP?>); load_page(<?=($p+1)?>, '<?=(isset($_GET['hispanic'])?'hispanic':'global')?>_country', false, '');" class="owloo_nav <?=$pactive?> <?=$pf?>" id="owloo_nav_<?=($p+1)?>"><?=($p+1)?></div>
	<?php 
}
?>
</div>
<?php } ?>