<?php 
require_once('../../owloo_config.php');

if(COUNTRY_DATA_CODE === NULL){
    header('Location: '.URL_ROOT);
    exit();
}

/********************************* CHART: Historial de total de usuarios en los ultimos 90 dias ***************************************/
    $sql =   "SELECT total_user, date, nombre, code 
                FROM record_country r 
                    JOIN country c 
                        ON r.id_country = c.id_country 
                WHERE c.id_country = ".COUNTRY_DATA_ID." 
                    AND DATE_SUB(STR_TO_DATE('".COUNTRY_DATE_LAST_UPDATE."', '%Y-%m-%d'),INTERVAL 180 DAY) <= date
                ORDER BY 2 ASC;
                "; 
    $que = mysql_query($sql) or die(mysql_error());
    $seriesData = ""; //Estadística vertical. Cantidad de usuarios que posee en Facebook
    $seriesDataMin = 0; //Número mínimo de usuarios
    $seriesDataMax = 0; //Número máximo de usuarios
    $xAxisCategories = ""; //Estadística horizontal. Fechas de los datos
    $ban = 1; //Bandera 
    $cont = 1;
    $_num_rango = 15;
    $_num_discard = mysql_num_rows($que) - ($_num_rango * floor(mysql_num_rows($que)/$_num_rango));
    while($fila = mysql_fetch_assoc($que)){
        if($_num_discard-- > 0) continue;
        if($cont % $_num_rango == 0){
            //Formatear fecha
            $auxformat = explode("-", $fila['date']);
            $dia = $auxformat[2];
            $mes = getMes($auxformat[1], 'short');
            if($ban == 1){
                $seriesData .=      $fila['total_user'];
                $xAxisCategories .= "'".$dia." ".$mes."'";
                $seriesDataMin =    $fila['total_user'];
                $seriesDataMax =    $fila['total_user'];
                $ban = 0;
            }
            else{
                $seriesData .= ','.$fila['total_user'];
                $xAxisCategories .= ",'".$dia." ".$mes."'";
                if($fila['total_user'] < $seriesDataMin)
                    $seriesDataMin = $fila['total_user'];
                else
                if($fila['total_user'] > $seriesDataMax)
                    $seriesDataMax = $fila['total_user'];
            }
        }
        $cont++;
    }
    
/********************************* FIN - CHART: Historial de total de usuarios en los ultimos 30 dias ***************************************/
?>
$(document).ready(function(){
    $('.owloo_country_options_1').click(function(){
        $('.owloo_country_options_1').removeClass('owloo_active');
        $(this).addClass('owloo_active');
        $('.owloo_country_ranking_content.owloo_country_t_1').removeClass('owloo_active');
        $('#owloo_ranking_'+$(this).attr('id')).addClass('owloo_active');
    });
    $('.owloo_country_options_2').click(function(){
        $('.owloo_country_options_2').removeClass('owloo_active');
        $(this).addClass('owloo_active');
        $('.owloo_country_ranking_content.owloo_country_t_2').removeClass('owloo_active');
        $('#owloo_ranking_'+$(this).attr('id')).addClass('owloo_active');
    });
});
/***** COUNTRY CHARTS *****/
$(function () {
            var chartAudiencia;
            $(document).ready(function() {
                chartAudiencia = new Highcharts.Chart({
                    chart: {
                        renderTo: 'owloo_chart_audiencia',
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
                        lineWidth: 1
                    },
                    yAxis: {
                        title: {
                            text: ''
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }],
                        min: <?=$seriesDataMin?>,
                        max: <?=$seriesDataMax?>,
                        gridLineColor: '#FFF',
                        lineColor: '#C0D0E0',
                        lineWidth: 1,
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
                        borderRadius: 2,
                        borderWidth: 1
                    },
                    legend: {
                        enabled: false
                    },
                    plotOptions: {
                        series: {
                            color: '#f2fafe',
                            fillOpacity: 0.5,
                            lineWidth: 3,
                            lineColor: '#229be2',
                            marker: {
                                fillColor: '#FFFFFF',
                                lineWidth: 2,
                                lineColor: '#229be2',
                                radius: 5
                            }
                        }
                    },
                    series: [{
                        name: '<?=COUNTRY_DATA_NAME_ES?>',
                        data: [<?=$seriesData?>]
                    }]
                });
            });
        });
<?php require_once('statistics/highcharts.js'); ?>

<?php require_once('statistics/jquery.knob.js'); ?>
$(document).ready(function(){
    //Estadísticas de situación sentimental
    $(".greenCircle").knob({
        'fgColor': '#9FC569'
    });
    $(".redCircle").knob({
        'fgColor': '#ED7A53'
    });
    $(".blueCircle").knob({
        'fgColor': '#6296be'
    });
    $(".purpleCircle").knob({
        'fgColor': '#cad110'
    });
});

/***** END COUNTRY CHARTS *****/

$(document).ready(function(){
    $('.owloo_country_more_info').click(function(){
        $('#' + $(this).attr('data') + ' .owloo_tr_hidden').toggle("100");
        var section = $(this).attr('section');
        if($(this).html() == 'Ver más '+section)
            $(this).html('Ver menos');
        else
            $(this).html('Ver más '+section);
    });
});