<?php require_once('../../owloo_config.php'); ?>
<?php require_once('../../ranking_country/statistics_change_user.php'); ?>
/***** INDEX CHARTS *****/
$(function () {
    var chartAudiencia;
    $(document).ready(function() {
        <?php  foreach($chart_crec_decrec as $country_chart){ if(!empty($country_chart['codigoPais'])){ ?>
        chartAudiencia = new Highcharts.Chart({
            chart: {
                renderTo: 'owloo_chart_audience_small_<?=$country_chart['aux_unique_code']?>_<?=$country_chart['codigoPais']?>',
                type: 'area',
                marginLeft: -3,
                marginRight: 2,
                marginBottom:  0,
                marginTop: 3
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                title: {text:null},
                labels: { enabled:false},
                categories: [<?=$country_chart['xAxisCategories']?>],
                lineColor: '#C0D0E0',
                lineWidth: 1
            },
            yAxis: {
                title: {text:null},
                labels: { enabled:false},
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
                min: <?=$country_chart['seriesDataMin']?>,
                max: <?=$country_chart['seriesDataMax']?>,
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
                    shadow: false,
                    color: '#cbebfb',
                    fillOpacity: 0.5,
                    lineWidth: 1,
                    lineColor: '#229be2',
                    marker: {
                        fillColor: '#FFFFFF',
                        lineWidth: 1,
                        lineColor: '#229be2',
                        radius: 0
                    }
                }
            },
            series: [{name:'<?=$country_chart['nombrePais']?>',data:[<?=$country_chart['seriesData']?>]}]
        });
        <?php }} ?>
    });
});
<?php require_once('statistics/highcharts.js'); ?>
/***** END INDEX CHARTS *****/