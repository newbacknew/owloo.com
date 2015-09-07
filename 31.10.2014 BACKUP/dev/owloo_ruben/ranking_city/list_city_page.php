<?php

    $datos = '[{"id_city":"5001","nombre":"Bangkok, Thailand","total_user":"14200000","total_female":"7200000","total_male":"7000000","id_country":"51","code":"TH","name":"Thailand","countryName":"Tailandia","crecimiento":800000},{"id_city":"3001","nombre":"Mexico City, Mexico","total_user":"11000000","total_female":"5400000","total_male":"5400000","id_country":"31","code":"MX","name":"Mexico","countryName":"M\u00e9xico","crecimiento":200000},{"id_city":"2101","nombre":"Jakarta, Indonesia","total_user":"10600000","total_female":"4400000","total_male":"6200000","id_country":"22","code":"ID","name":"Indonesia","countryName":"Indonesia","crecimiento":400000},{"id_city":"1401","nombre":"Cairo, Egypt","total_user":"9800000","total_female":"3600000","total_male":"6200000","id_country":"15","code":"EG","name":"Egypt","countryName":"Egipto","crecimiento":1000000},{"id_city":"701","nombre":"S\u00e3o Paulo, Brazil","total_user":"9600000","total_female":"5000000","total_male":"4600000","id_country":"8","code":"BR","name":"Brazil","countryName":"Brasil","crecimiento":400000},{"id_city":"5101","nombre":"Istanbul, Turkey","total_user":"9200000","total_female":"3600000","total_male":"5400000","id_country":"52","code":"TR","name":"Turkey","countryName":"Turqu\u00eda","crecimiento":600000},{"id_city":"4501","nombre":"Seoul, South Korea","total_user":"7400000","total_female":"3200000","total_male":"4200000","id_country":"46","code":"KR","name":"South Korea","countryName":"Corea del Sur","crecimiento":0},{"id_city":"201","nombre":"London, United Kingdom","total_user":"7000000","total_female":"3400000","total_male":"3400000","id_country":"3","code":"GB","name":"United Kingdom","countryName":"Reino Unido","crecimiento":-200000},{"id_city":"4901","nombre":"Taipei, Taiwan","total_user":"6600000","total_female":"3400000","total_male":"3200000","id_country":"50","code":"TW","name":"Taiwan","countryName":"Taiw\u00e1n","crecimiento":0},{"id_city":"2001","nombre":"New Delhi, India","total_user":"6200000","total_female":"1700000","total_male":"4600000","id_country":"21","code":"IN","name":"India","countryName":"India","crecimiento":400000},{"id_city":"3701","nombre":"Lima, Peru","total_user":"6200000","total_female":"2800000","total_male":"3400000","id_country":"38","code":"PE","name":"Peru","countryName":"Per\u00fa","crecimiento":200000},{"id_city":"1001","nombre":"Bogot\u00e1, Colombia","total_user":"6000000","total_female":"3000000","total_male":"3000000","id_country":"11","code":"CO","name":"Colombia","countryName":"Colombia","crecimiento":200000},{"id_city":"2002","nombre":"Mumbai, India","total_user":"6000000","total_female":"1760000","total_male":"4200000","id_country":"21","code":"IN","name":"India","countryName":"India","crecimiento":200000},{"id_city":"702","nombre":"Rio De Janeiro, Brazil","total_user":"5600000","total_female":"3000000","total_male":"2600000","id_country":"8","code":"BR","name":"Brazil","countryName":"Brasil","crecimiento":200000},{"id_city":"301","nombre":"Buenos Aires, Argentina","total_user":"5400000","total_female":"2800000","total_male":"2600000","id_country":"4","code":"AR","name":"Argentina","countryName":"Argentina","crecimiento":0},{"id_city":"8601","nombre":"Hanoi, Vietnam","total_user":"5000000","total_female":"2200000","total_male":"2800000","id_country":"87","code":"VN","name":"Vietnam","countryName":"Vietnam","crecimiento":0},{"id_city":"3301","nombre":"Lagos, Nigeria","total_user":"4600000","total_female":"1720000","total_male":"2800000","id_country":"34","code":"NG","name":"Nigeria","countryName":"Nigeria","crecimiento":0},{"id_city":"8602","nombre":"Ho Chi Minh City, Vietnam","total_user":"4600000","total_female":"2200000","total_male":"2400000","id_country":"87","code":"VN","name":"Vietnam","countryName":"Vietnam","crecimiento":0},{"id_city":"2003","nombre":"Bangalore, India","total_user":"4400000","total_female":"1240000","total_male":"3200000","id_country":"21","code":"IN","name":"India","countryName":"India","crecimiento":400000},{"id_city":"801","nombre":"Santiago, Chile","total_user":"4200000","total_female":"2200000","total_male":"2000000","id_country":"9","code":"CL","name":"Chile","countryName":"Chile","crecimiento":0}]'; 
    $datos = json_decode($datos, true);
    
    $page = 1;
    if(isset($_POST['page']) && is_numeric($_POST['page'])){
        if($_POST['page'] < 1)
            $page = 1;
        elseif($_POST['page'] > ceil((MAX_LIST_CITY_COUNT/PAGER_PP)))
            $page = ceil((MAX_LIST_CITY_COUNT/PAGER_PP));
        else
            $page = $_POST['page'];
    }
	
	$cont = 1 + (PAGER_PP * ($page-1));
	foreach($datos as $fila){ 
	    
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
                            <a href="<?=URL_ROOT?>facebook-stats/cities/<?=convert_to_url_string($fila['name'])?>/" title="Estadísticas de Facebook en <?=substr($fila['nombre'], 0, strpos($fila['nombre'], ','))?>"><?=substr($fila['nombre'], 0, strpos($fila['nombre'], ','))?></a>
                        </div>
                        <div class="owloo_description">
                            <strong><?=substr($fila['nombre'], 0, strpos($fila['nombre'], ','))?></strong> cuenta con <strong><?=owloo_number_format($fila['total_user'])?> usuarios</strong> de los cuales el <?=round(($fila['total_female'] * 100 / $fila['total_user']), 2)?>% son mujeres y <?=round(($fila['total_male'] * 100 / $fila['total_user']), 2);?>% son hombres.
                        </div>
                    </div>
                </div>
		<?php
	} ?>