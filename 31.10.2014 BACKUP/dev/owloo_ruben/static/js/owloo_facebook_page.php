<?php require_once('../../owloo_config.php'); ?>
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
                    categories: ['11 Ago','12 Ago','13 Ago','14 Ago','15 Ago','16 Ago','17 Ago','18 Ago','19 Ago','20 Ago','21 Ago','22 Ago','23 Ago','24 Ago','25 Ago','26 Ago','27 Ago','28 Ago','29 Ago','30 Ago','31 Ago','01 Set','02 Set','03 Set','04 Set','05 Set','06 Set','07 Set','08 Set','09 Set','10 Set'],
                    lineColor: '#C0D0E0',
                    lineWidth: 0.5,
                    labels: {
                        step: 3, align: 'center', staggerLines: 1 
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
                    min: 94670131,
                    max: 97627263,
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
                    name: 'Cristiano Ronaldo',
                    data: [94670131,94781456,94902499,95021365,95137971,95264193,95387680,95478997,95574738,95681933,95794485,95897839,96002383,96093023,96178330,96239538,96352024,96452375,96578017,96683589,96787381,96887837,96981043,97078459,97167956,97256923,97332332,97409108,97478447,97555857,97627263]
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
                    categories: ['11 Ago','12 Ago','13 Ago','14 Ago','15 Ago','16 Ago','17 Ago','18 Ago','19 Ago','20 Ago','21 Ago','22 Ago','23 Ago','24 Ago','25 Ago','26 Ago','27 Ago','28 Ago','29 Ago','30 Ago','31 Ago','01 Set','02 Set','03 Set','04 Set','05 Set','06 Set','07 Set','08 Set','09 Set','10 Set'],
                    lineColor: '#C0D0E0',
                    lineWidth: 0.5,
                    labels: {
                        step: 3, align: 'center', staggerLines: 1 
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
                                        min: 1232553,
                    max: 6143387,
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
                series: [                              {
                        name: 'People talking about',
                        data: [2410640,2491610,2533969,2083715,2113136,2235210,2306601,1987355,1899491,1899491,2029016,1877774,1717623,1605581,1560135,1560135,1324582,1232553,1605511,2833115,4716067,5363225,5619553,5741318,6143387,6139093,5479832,3598458,3049792,2976263,2985798]
                    }
                ]
            });
            
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
                    categories: ['09 Ago','10 Ago','11 Ago','12 Ago','13 Ago','14 Ago','15 Ago','16 Ago','17 Ago','18 Ago','19 Ago','20 Ago','21 Ago','22 Ago','23 Ago','24 Ago','25 Ago','26 Ago','27 Ago','28 Ago','29 Ago','30 Ago','31 Ago','01 Set','03 Set','04 Set','05 Set','06 Set','07 Set','08 Set'],
                    lineColor: '#C0D0E0',
                    lineWidth: 0.5,
                    labels: {
                        step: 3, align: 'center', staggerLines: 1 
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
                    min: 8026857,
                    max: 8357817,
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
                            this.x +': '+ formatNumber(this.y) +' fans en Indonesia';
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
                    name: 'Cristiano Ronaldo',
                    data: [8026857,8038182,8047264,8057977,8068055,8076798,8086474,8097138,8108438,8116165,8124106,8132547,8140434,8147409,8156423,8164375,8170147,8178116,8190079,8197918,8212510,8228946,8244228,8259630,8294174,8308973,8322687,8336472,8350582,8357817]
                }]
            });
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
                            return '<span>Excelente</span>';
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
                    data: [3.06],
                    dataLabels: {
                        format: '<div style="text-align:center"><span style="color: #505050;font-family: OpenSans-Light;font-size: 25px;font-weight: normal;">3,06%</span></div>'
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
    $('#owloo_chart_local_fans_table').html('<table><thead><tr><th>Periodo</th><th class="owloo_country_table_2">Fans</th><th>Porcentaje</th></tr></thead><tbody><tr><td>DÃ­a</td><td><span class="owloo_change_audition owloo_arrow_up">7.235</span></td><td><span class="owloo_arrow_up_porcent">0,086566%</span></td></tr><tr><td>Semana</td><td><span class="owloo_change_audition owloo_arrow_up">98.187</span></td><td><span class="owloo_arrow_up_porcent">1,17%</span></td></tr><tr><td>Dos semanas</td><td><span class="owloo_change_audition owloo_arrow_up">193.442</span></td><td><span class="owloo_arrow_up_porcent">2,31%</span></td></tr><tr><td>Mes</td><td><span class="owloo_change_audition owloo_arrow_up">330.960</span></td><td><span class="owloo_arrow_up_porcent">3,96%</span></td></tr><tr><td>Dos meses</td><td><span class="owloo_change_audition owloo_arrow_up">573.808</span></td><td><span class="owloo_arrow_up_porcent">6,87%</span></td></tr></tbody></table>');
    
    $('#owloo_fb_local_fans_select').change(function(){
        $('#owloo_chart_local_fans, #owloo_chart_local_fans_table').html('');
        $('#owloo_section_fb_local_fans').addClass('owloo_country_content_tw_chart');
        $.post('<?=URL_JS?>owloo_facebook_page_local_fans.php', {page: 'Cristiano', country: $(this).val()}, function(res){
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