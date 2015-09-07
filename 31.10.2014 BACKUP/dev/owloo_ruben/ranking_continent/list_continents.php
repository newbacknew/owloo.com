<?php

    $datos = '[{"nombre":"Asia","total_user":"474520000","total_female":"178260000","total_male":"293842000","cambio":"21972000"},{"nombre":"Am\u00e9rica del Norte","total_user":"280570740","total_female":"148663620","total_male":"131434600","cambio":"6871560"},{"nombre":"Europa","total_user":"250812260","total_female":"125161860","total_male":"124311060","cambio":"2233940"},{"nombre":"Am\u00e9rica del Sur","total_user":"194446000","total_female":"100300960","total_male":"93341060","cambio":"3650020"},{"nombre":"\u00c1frica","total_user":"95959600","total_female":"35474400","total_male":"60769200","cambio":"4978200"},{"nombre":"Ocean\u00eda","total_user":"16790720","total_female":"8788440","total_male":"7736620","cambio":"250900"}]';
    $datos = json_decode($datos, true);
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