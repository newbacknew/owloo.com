<?php

require_once('../../owloo_config.php');

if(!isset($_GET['page'])){
    header('Location: '.URL_ROOT);
    exit();
}

$sql = "SELECT * FROM facebook_page WHERE username LIKE '".mysql_real_escape_string($_GET['page'])."' AND active = 1;";
$res = mysql_query($sql) or die(mysql_error());
if(!$page_data = mysql_fetch_assoc($res)){
    header('Location: '.URL_ROOT);
    exit();
}

$value_gauge = owlooFormatPorcent($page_data['talking_about'], $page_data['likes'], 2, '.', '');


/********************************* CHART: Historial de total de fans en los ultimos 30 dias ***************************************/
    $sql =   "SELECT likes, talking_about, date 
                FROM facebook_pages_likes_talking_about 
                WHERE id_page = ".mysql_real_escape_string($page_data['id_page'])."
                    AND DATE_SUB(STR_TO_DATE('".get_fb_page_likes_last_update($page_data['id_page'])."', '%Y-%m-%d'),INTERVAL 30 DAY) <= date
                ORDER BY date ASC;
                "; 
    $que = mysql_query($sql) or die(mysql_error());
    $seriesData = ""; //Estadística vertical. Cantidad de usuarios que posee en Facebook
    $seriesData_talking_about = ""; //Estadística vertical. Cantidad de usuarios que posee en Facebook
    $seriesDataMin = 0; //Número mínimo de usuarios
    $seriesDataMax = 0; //Número máximo de usuarios
    $seriesDataMin_talking_about = 0; //Número mínimo de usuarios
    $seriesDataMax_talking_about = 0; //Número máximo de usuarios
    $xAxisCategories = ""; //Estadística horizontal. Fechas de los datos
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
                $seriesData .=      $fila['likes'];
                $seriesData_talking_about .= $fila['talking_about'];
                $xAxisCategories .= "'".$dia." ".$mes."'";
                $seriesDataMin =    $fila['likes'];
                $seriesDataMax =    $fila['likes'];
                $seriesDataMin_talking_about = $fila['talking_about'];
                $seriesDataMax_talking_about = $fila['talking_about'];
                $ban = 0;
            }
            else{
                $seriesData .= ','.$fila['likes'];
                $seriesData_talking_about .= ','.$fila['talking_about'];
                $xAxisCategories .= ",'".$dia." ".$mes."'";
                if($fila['likes'] < $seriesDataMin)
                    $seriesDataMin = $fila['likes'];
                else
                if($fila['likes'] > $seriesDataMax)
                    $seriesDataMax = $fila['likes'];
                
                if($fila['talking_about'] < $seriesDataMin_talking_about)
                    $seriesDataMin_talking_about = $fila['talking_about'];
                else
                if($fila['talking_about'] > $seriesDataMax_talking_about)
                    $seriesDataMax_talking_about = $fila['talking_about'];
            }
        }
        $cont++;
    }
    
    $step_1 = 1;
    if($cont-1 > 11)
        $step_1 = 2;
    if($cont-1 > 21)
        $step_1 = 3;
    
    $local_fans_last_date = get_fb_page_local_fans_last_date($page_data['id_page']);
    $local_fans_country = NULL;
    if(!empty($local_fans_last_date)){
        $sql = "SELECT c.id_country id_country, name, nombre, likes FROM facebook_page_local_fans_country lf JOIN country c ON lf.id_country = c.id_country WHERE id_page = ".mysql_real_escape_string($page_data['id_page'])." AND date = '".mysql_real_escape_string($local_fans_last_date)."' ORDER BY likes DESC, nombre ASC;";
        $local_fans_country = mysql_query($sql) or die(mysql_error());
        if($local_fan = mysql_fetch_assoc($local_fans_country)){
            $sql =   "SELECT likes, date 
                FROM facebook_page_local_fans_country 
                WHERE id_page = ".mysql_real_escape_string($page_data['id_page'])."
                    AND id_country = ".mysql_real_escape_string($local_fan['id_country'])."
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
            $fb_page_local_fans_likes_nun_dates = get_fb_page_local_fans_likes_nun_dates($page_data['id_page'], $local_fan['id_country']);
            if($fb_page_local_fans_likes_nun_dates > 1)
                $crecimiento_fans['dia'] = getCrecimientoFacebookLocalFansPage($page_data['id_page'], $local_fan['id_country'], $local_fans_last_date, 1);
            if($fb_page_local_fans_likes_nun_dates > 7)
                $crecimiento_fans['semana'] = getCrecimientoFacebookLocalFansPage($page_data['id_page'], $local_fan['id_country'], $local_fans_last_date, 7);
            if($fb_page_local_fans_likes_nun_dates > 15)
                $crecimiento_fans['quincena'] = getCrecimientoFacebookLocalFansPage($page_data['id_page'], $local_fan['id_country'], $local_fans_last_date, 15);
            if($fb_page_local_fans_likes_nun_dates > 30)
                $crecimiento_fans['mes'] = getCrecimientoFacebookLocalFansPage($page_data['id_page'], $local_fan['id_country'], $local_fans_last_date, 30);
            if($fb_page_local_fans_likes_nun_dates > 60)
                $crecimiento_fans['dos_meses'] = getCrecimientoFacebookLocalFansPage($page_data['id_page'], $local_fan['id_country'], $local_fans_last_date, 60);
            
            $local_fans_table = '<table><thead><tr><th>Periodo</th><th class="owloo_country_table_2">Fans</th><th>Porcentaje</th></tr></thead><tbody><tr><td>Día</td><td>'.$crecimiento_fans['dia']['value'].'</td><td>'.$crecimiento_fans['dia']['porcentaje'].'</td></tr><tr><td>Semana</td><td>'.$crecimiento_fans['semana']['value'].'</td><td>'.$crecimiento_fans['semana']['porcentaje'].'</td></tr><tr><td>Dos semanas</td><td>'.$crecimiento_fans['quincena']['value'].'</td><td>'.$crecimiento_fans['quincena']['porcentaje'].'</td></tr><tr><td>Mes</td><td>'.$crecimiento_fans['mes']['value'].'</td><td>'.$crecimiento_fans['mes']['porcentaje'].'</td></tr><tr><td>Dos meses</td><td>'.$crecimiento_fans['dos_meses']['value'].'</td><td>'.$crecimiento_fans['dos_meses']['porcentaje'].'</td></tr></tbody></table>';
        }
    }
    
/********************************* FIN - CHART: Historial de total de usuarios en los ultimos 30 dias ***************************************/
?>

/***** FACEBOOK PAGE CHARTS *****/
$(function () {
            var chartAudiencia;
            chartAudiencia = new Highcharts.Chart({
                chart: {
                    renderTo: 'owloo_chart_likes',
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
                    categories: [<?=$xAxisCategories?>],
                    lineColor: '#C0D0E0',
                    lineWidth: 0.5,
                    labels: {
                        step: <?=$step_1?>, align: 'center', staggerLines: 1 
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
                    min: <?=$seriesDataMin?>,
                    max: <?=$seriesDataMax?>,
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
                            this.x +': '+ formatNumber(this.y) +' fans';
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
                    data: [<?=$seriesData?>]
                }]
            });
            
            chartAudiencia = new Highcharts.Chart({
                chart: {
                    renderTo: 'owloo_chart_likes_talking_about',
                    type: 'column',
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
                    categories: [<?=$xAxisCategories?>],
                    lineColor: '#C0D0E0',
                    lineWidth: 0.5,
                    labels: {
                        step: <?=$step_1?>, align: 'center', staggerLines: 1 
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
                    <?php if(false){ ?>min: 0,
                    max: <?=$seriesDataMax?>,<?php } ?>
                    min: <?=$seriesDataMin_talking_about?>,
                    max: <?=$seriesDataMax_talking_about?>,
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
                            this.x +': '+ formatNumber(this.y) +' usuarios';
                    },
                    borderColor: '#F0F0F0',
                    borderRadius: 1,
                    borderWidth: 0.5,
                    backgroundColor: '#FFFFFF'
                },
                legend: {
                    enabled: false
                },
                colors: ['#007CC3', '#f7a35c'],
                plotOptions: {
                    series: {
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
                series: [<?php if(false){ ?>
                        name: 'Fans',
                        data: [<?=$seriesData?>]
                    },<?php } ?>
                              {
                        name: 'People talking about',
                        data: [<?=$seriesData_talking_about?>]
                    }
                ]
            });
            
            <?php if(!empty($local_fans_country)){ ?>
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
                            this.x +': '+ formatNumber(this.y) +' fans en <?=$local_fan['nombre']?>';
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
            <?php } ?>
            var gaugeOptions = {
                chart: {
                    type: 'solidgauge',
                    backgroundColor: 'transparent'
                },
                title: null,
                pane: {
                    center: ['50%', '85%'],
                    size: '140%',
                    startAngle: -90,
                    endAngle: 90,
                    background: {
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#FCFCFC',
                        innerRadius: '60%',
                        outerRadius: '100%',
                        shape: 'arc'
                    }
                },
                tooltip: {
                    positioner: function () {
                        return { x: 117, y: 93 };
                    },
                    formatter: function() {
                            return '<span><?=($value_gauge<1?'Negativo':($value_gauge<3?'Regular':($value_gauge<6?'Excelente':'Óptimo')))?></span>';
                    },
                    borderColor: '#F0F0F0',
                    borderRadius: 1,
                    borderWidth: 0.5,
                    backgroundColor: '#FFFFFF'
                }, 
                yAxis: {
                    stops: [
                        [0.165, '#DF5353'], // red
                        [0.166, '#F0AD4E'], // orange
                        [0.49834, '#F0AD4E'],
                        [0.49835, '#DDDF0D'], // yellow
                        [0.999, '#DDDF0D'],
                        [1, '#55BF3B'] // green
                    ],
                    lineWidth: 0,
                    minorTickInterval: null,
                    tickWidth: 0,
                    labels: {
                        enabled: false
                    }        
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            y: 5,
                            borderWidth: 0,
                            useHTML: true
                        }
                    }
                }
            };
            $('#owloo_chart_pta').highcharts(Highcharts.merge(gaugeOptions, {
                yAxis: {
                    min: 0,
                    max: 6,
                    title: {
                        text: null
                    }       
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Speed',
                    data: [<?=($value_gauge<=6?$value_gauge:6)?>],
                    dataLabels: {
                        format: '<div style="text-align:center"><span style="color: #505050;font-family: OpenSans-Light;font-size: 25px;font-weight: normal;"><?=owlooFormatPorcent($page_data['talking_about'], $page_data['likes'])?>%</span></div>'
                    },
                    tooltip: {
                        enabled: false
                    }
                }]
            
            }));
            
        });
<?php require_once('statistics/highcharts_v4.0/highcharts.js'); ?>
<?php require_once('statistics/highcharts_v4.0/highcharts-more.js'); ?>
<?php require_once('statistics/highcharts_v4.0/solid-gauge.src.js'); ?>
<?php require_once('selectize.js'); ?>

$(document).ready(function(){
    $('#owloo_fb_local_fans_select').selectize();
    $('.owloo_country_content').removeClass('owloo_country_content_tw_chart');
    $('#owloo_chart_local_fans_table').html('<?=$local_fans_table?>');
    
    $('#owloo_fb_local_fans_select').change(function(){
        $('#owloo_chart_local_fans, #owloo_chart_local_fans_table').html('');
        $('#owloo_section_fb_local_fans').addClass('owloo_country_content_tw_chart');
        $.post('<?=URL_JS?>owloo_facebook_page_local_fans.php', {page: '<?=$page_data['username']?>', country: $(this).val()}, function(res){
            if(res != ''){
                $('#owloo_section_fb_local_fans').removeClass('owloo_country_content_tw_chart');
                $('#owloo_section_fb_local_fans .owloo_country_content').html(res);
            }
        });
    });
    
    $('.owloo_country_more_info').click(function(){
        $('#' + $(this).attr('data') + ' .owloo_tr_hidden').toggle("100");
        var section = $(this).attr('section');
        if($(this).html() == 'Ver más '+section)
            $(this).html('Ver menos');
        else
            $(this).html('Ver más '+section);
    });
});