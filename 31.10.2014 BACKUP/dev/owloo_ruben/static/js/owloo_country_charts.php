<?php 

require_once('../../owloo_config.php');
    
    $xAxisCategories = "'12 Abr','27 Abr','12 May','27 May','11 Jun','26 Jun','11 Jul','26 Jul','11 Ago','26 Ago','10 Set'";
    
    $seriesDataMin = 98000000;
    
    $seriesDataMax = 112000000;
    
    $seriesData = '98000000,102000000,102000000,102000000,106000000,106000000,106000000,110000000,110000000,112000000,112000000';
    
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