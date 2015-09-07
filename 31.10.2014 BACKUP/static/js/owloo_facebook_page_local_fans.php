<?php
require_once('../../owloo_config.php');

if(!isset($_POST['page']) || !isset($_POST['country'])){
    exit();
}

$sql = "SELECT * FROM facebook_page WHERE username LIKE '".mysql_real_escape_string($_POST['page'])."' AND active = 1;";
$res = mysql_query($sql) or die(mysql_error());
if(!$page_data = mysql_fetch_assoc($res)){
    exit();
}

$country_data = get_country_data_from_name($_POST['country']);
if(empty($country_data)){
    exit();
}



/********************************* CHART: Historial de total de fans locales en los ultimos 30 dias ***************************************/

    $local_fans_last_date = get_fb_page_local_fans_last_date($page_data['id_page']);
    $local_fans_country = NULL;
    if(!empty($local_fans_last_date)){
        $sql =   "SELECT likes, date 
            FROM facebook_page_local_fans_country 
            WHERE id_page = ".mysql_real_escape_string($page_data['id_page'])."
                AND id_country = ".mysql_real_escape_string($country_data['id_country'])."
                AND DATE_SUB(STR_TO_DATE('".$local_fans_last_date."', '%Y-%m-%d'),INTERVAL 30 DAY) <= date
            ORDER BY date ASC;
            ";
        
        $que = mysql_query($sql) or die(mysql_error());
        $seriesData_local_fans = ""; //Estadística vertical. Cantidad de usuarios que posee en Facebook
        $seriesDataMin_local_fans = 0; //Número mínimo de usuarios
        $seriesDataMax_local_fans = 0; //Número máximo de usuarios
        $xAxisCategories_local_fans = ""; //Estadística horizontal. Fechas de los datos
        $ban = 1; //Bandera 
        $cont = 1;
        $_num_rango = 1;
        $_num_discard = mysql_num_rows($que) - ($_num_rango * floor(mysql_num_rows($que)/$_num_rango));
        while($fila = mysql_fetch_assoc($que)){
            if($_num_discard-- > 0) continue;
            if($cont % $_num_rango == 0){
                //Formatear fecha
                $auxformat = explode("-", $fila['date']);
                $dia = $auxformat[2];
                $mes = getMes($auxformat[1], 'short');
                if($ban == 1){
                    $seriesData_local_fans .=      $fila['likes'];
                    $xAxisCategories_local_fans .= "'".$dia." ".$mes."'";
                    $seriesDataMin_local_fans =    $fila['likes'];
                    $seriesDataMax_local_fans =    $fila['likes'];
                    $ban = 0;
                }
                else{
                    $seriesData_local_fans .= ','.$fila['likes'];
                    $xAxisCategories_local_fans .= ",'".$dia." ".$mes."'";
                    if($fila['likes'] < $seriesDataMin_local_fans)
                        $seriesDataMin_local_fans = $fila['likes'];
                    else
                    if($fila['likes'] > $seriesDataMax_local_fans)
                        $seriesDataMax_local_fans = $fila['likes'];
                }
            }
            $cont++;
        }
        
        $step_2 = 1;
        if($cont-1 > 11)
            $step_2 = 2;
        if($cont-1 > 21)
            $step_2 = 3;
        
        //Local gans country grow
        $n_a = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
        $crecimiento_fans = array(
                            'dia' => array('value' => $n_a, 'porcentaje' => $n_a),            //Cambio de usuarios en las ultimas 24 Horas
                            'semana' => array('value' => $n_a, 'porcentaje' => $n_a),         //Cambio de usuarios en los ultimos 7 dias
                            'quincena' => array('value' => $n_a, 'porcentaje' => $n_a),       //Cambio de usuarios en los ultimos 15 dias
                            'mes' => array('value' => $n_a, 'porcentaje' => $n_a),            //Cambio de usuarios en los ultimos 30 dias
                            'dos_meses' => array('value' => $n_a, 'porcentaje' => $n_a),      //Cambio de usuarios en los ultimos 60 dias
                            'tres_meses' => array('value' => $n_a, 'porcentaje' => $n_a),     //Cambio de usuarios en los ultimos 90 dias
                        ); 
        
        $fb_page_local_fans_likes_nun_dates = get_fb_page_local_fans_likes_nun_dates($page_data['id_page'], $country_data['id_country']);
        if($fb_page_local_fans_likes_nun_dates > 1)
            $crecimiento_fans['dia'] = getCrecimientoFacebookLocalFansPage($page_data['id_page'], $country_data['id_country'], $local_fans_last_date, 1);
        if($fb_page_local_fans_likes_nun_dates > 7)
            $crecimiento_fans['semana'] = getCrecimientoFacebookLocalFansPage($page_data['id_page'], $country_data['id_country'], $local_fans_last_date, 7);
        if($fb_page_local_fans_likes_nun_dates > 15)
            $crecimiento_fans['quincena'] = getCrecimientoFacebookLocalFansPage($page_data['id_page'], $country_data['id_country'], $local_fans_last_date, 15);
        if($fb_page_local_fans_likes_nun_dates > 30)
            $crecimiento_fans['mes'] = getCrecimientoFacebookLocalFansPage($page_data['id_page'], $country_data['id_country'], $local_fans_last_date, 30);
        if($fb_page_local_fans_likes_nun_dates > 60)
            $crecimiento_fans['dos_meses'] = getCrecimientoFacebookLocalFansPage($page_data['id_page'], $country_data['id_country'], $local_fans_last_date, 60);
        
        $local_fans_table = '<table><thead><tr><th>Periodo</th><th class="owloo_country_table_2">Fans</th><th>Porcentaje</th></tr></thead><tbody><tr><td>Día</td><td>'.$crecimiento_fans['dia']['value'].'</td><td>'.$crecimiento_fans['dia']['porcentaje'].'</td></tr><tr><td>Semana</td><td>'.$crecimiento_fans['semana']['value'].'</td><td>'.$crecimiento_fans['semana']['porcentaje'].'</td></tr><tr><td>Dos semanas</td><td>'.$crecimiento_fans['quincena']['value'].'</td><td>'.$crecimiento_fans['quincena']['porcentaje'].'</td></tr><tr><td>Mes</td><td>'.$crecimiento_fans['mes']['value'].'</td><td>'.$crecimiento_fans['mes']['porcentaje'].'</td></tr><tr><td>Dos meses</td><td>'.$crecimiento_fans['dos_meses']['value'].'</td><td>'.$crecimiento_fans['dos_meses']['porcentaje'].'</td></tr></tbody></table>';
    }
    else {
    	exit();
    }
    
/********************************* FIN - CHART: Historial de total de usuarios en los ultimos 30 dias ***************************************/
?>
<div class="owloo_left owloo_country_table_chart_audience">
    <div id="owloo_chart_local_fans" class="owloo_chart_audiencie"></div>
</div>
<div id="owloo_chart_local_fans_table" class="owloo_right owloo_country_table_2_content">
</div>
<script type="text/javascript">
/***** FACEBOOK PAGE CHARTS *****/
    $(function () {
        var chartAudiencia;
        $(document).ready(function() {
            chartAudiencia = new Highcharts.Chart({
                chart: {
                    renderTo: 'owloo_chart_local_fans',
                    type: 'area',
                    marginRight: 10,
                    marginBottom: 40
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: [<?=$xAxisCategories_local_fans?>],
                    lineColor: '#C0D0E0',
                    lineWidth: 0.5,
                    labels: {
                        step: <?=$step_2?>, align: 'center', staggerLines: 1
                    },
                    tickWidth: 0.5,
                    tickLength: 5
                },
                yAxis: {
                    title: {
                        text: ''
                    },
                    plotLines: [{
                        value: 0,
                        width: 0.5,
                        color: '#808080'
                    }],
                    min: <?=$seriesDataMin_local_fans?>,
                    max: <?=$seriesDataMax_local_fans?>,
                    gridLineColor: '#FFF',
                    lineColor: '#C0D0E0',
                    lineWidth: 0.5,
                    labels: {
                        formatter: function() {
                            return formatNumber(this.value);
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    formatter: function() {
                            return '<b>'+ this.series.name +'</b><br/>'+
                            this.x +': '+ formatNumber(this.y) +' fans en <?=$country_data['nombre']?>';
                    },
                    borderColor: '#F0F0F0',
                    borderRadius: 1,
                    borderWidth: 0.5,
                    backgroundColor: '#FFFFFF'
                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        color: '#f2fafe',
                        fillOpacity: 0.5,
                        lineWidth: 1,
                        lineColor: '#229be2',
                        shadow: false,
                        marker: {
                            fillColor: '#FFFFFF',
                            lineWidth: 1,
                            lineColor: '#229be2',
                            radius: 1.5
                        }
                    }
                },
                series: [{
                    name: '<?=addslashes($page_data['name'])?>',
                    data: [<?=$seriesData_local_fans?>]
                }]
            });
        });
    });

    $(document).ready(function(){
        $('#owloo_chart_local_fans_table').html('<?=$local_fans_table?>');
    });
</script>