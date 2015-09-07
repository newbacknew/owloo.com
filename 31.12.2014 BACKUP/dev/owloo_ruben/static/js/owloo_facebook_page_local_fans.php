<?php
    require_once('../../owloo_config.php');
    
    if(!isset($_POST['page']) || !isset($_POST['country'])){
        exit();
    }
    $page_data = '{"id_page":"7","fb_id":"81221197163","username":"Cristiano","name":"Cristiano Ronaldo","about":"Welcome to the OFFICIAL Facebook page of Cristiano Ronaldo.","description":"","link":"https:\/\/www.facebook.com\/Cristiano","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfp1\/v\/t1.0-1\/p50x50\/10363769_10152518873712164_4951848658666973254_n.jpg?oh=a58b55ef5e822b30902b977a7ae2ae84&oe=549A1A5F&__gda__=1419871796_c17e96d5391735170849fab441a859b4","cover":"https:\/\/fbcdn-sphotos-g-a.akamaihd.net\/hphotos-ak-xaf1\/v\/t1.0-9\/s720x720\/10441268_10152518873232164_3307823841159892259_n.png?oh=3bd65e113bba9c1e1e76485d9302de78&oe=549E56E5&__gda__=1419091984_8bd025bd1060a7ebb3ad1541f710ab14","location":"47","is_verified":"1","likes":"97627263","talking_about":"2985798","first_local_fans_country":"22","hispanic":"1","active":"1","date_add":"2014-05-29","date_update":"2014-09-10"}';
    $page_data = json_decode($page_data, true);
    
    $country_data = get_country_data_from_name($_POST['country']);
    
    $seriesData_local_fans = '5839160,5853876,5866833,5880096,5892288,5904250,5917764,5933002,5948022,5957060,5965955,5974640,5982445,5990687,5998803,6006173,6011661,6017995,6026323,6029664,6035152,6042974,6048281,6052939,6062842,6066755,6071209,6074966,6078541,6080822';
    
    $seriesDataMin_local_fans = '5839160';
    
    $seriesDataMax_local_fans = '6080822';
    
    $xAxisCategories_local_fans = "'09 Ago','10 Ago','11 Ago','12 Ago','13 Ago','14 Ago','15 Ago','16 Ago','17 Ago','18 Ago','19 Ago','20 Ago','21 Ago','22 Ago','23 Ago','24 Ago','25 Ago','26 Ago','27 Ago','28 Ago','29 Ago','30 Ago','31 Ago','01 Set','03 Set','04 Set','05 Set','06 Set','07 Set','08 Set'";
    
    $step_2 = 3;
    
    $local_fans_table = '<table><thead><tr><th>Periodo</th><th class="owloo_country_table_2">Fans</th><th>Porcentaje</th></tr></thead><tbody><tr><td>Dí­a</td><td><span class="owloo_change_audition owloo_arrow_up">2.281</span></td><td><span class="owloo_arrow_up_porcent">0,037511%</span></td></tr><tr><td>Semana</td><td><span class="owloo_change_audition owloo_arrow_up">27.883</span></td><td><span class="owloo_arrow_up_porcent">0,46%</span></td></tr><tr><td>Dos semanas</td><td><span class="owloo_change_audition owloo_arrow_up">74.649</span></td><td><span class="owloo_arrow_up_porcent">1,23%</span></td></tr><tr><td>Mes</td><td><span class="owloo_change_audition owloo_arrow_up">241.662</span></td><td><span class="owloo_arrow_up_porcent">3,97%</span></td></tr><tr><td>Dos meses</td><td><span class="owloo_change_audition owloo_arrow_up">569.507</span></td><td><span class="owloo_arrow_up_porcent">9,37%</span></td></tr></tbody></table>';
    
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