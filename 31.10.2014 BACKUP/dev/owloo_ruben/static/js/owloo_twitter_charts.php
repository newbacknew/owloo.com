/***** TWITTER CHARTS *****/


<?php require_once('statistics/highcharts.js'); ?>

var preview_num = -1;
$(function () {
    var chartAudiencia;
    chartAudiencia = new Highcharts.Chart({
        chart: {
            renderTo: 'owloo_tw_chart_1',
            type: 'area'
        },
        colors: [
           '#007CC3',
           '#0071B1'
        ],
        title: {
            text: ''
        },
        xAxis: {
            title: {
                text: ''
            },
            categories: ['11 Ago','12 Ago','13 Ago','14 Ago','15 Ago','16 Ago','17 Ago','18 Ago','19 Ago','20 Ago','21 Ago','22 Ago','23 Ago','25 Ago','26 Ago','27 Ago','28 Ago','29 Ago','30 Ago','31 Ago','01 Set','02 Set','03 Set','04 Set','05 Set','06 Set','07 Set','08 Set','09 Set','10 Set'],
            labels: {
                step: 3
            }
        },
        yAxis: {
            title: {
                text: ''
            },
            min: 55239004,
            max: 56742350,
            labels: {
                formatter: function()
                {
                    return this.value;
                }
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        tooltip: {
            formatter: function() {
                    var aumento = '';
                    if(preview_num == -1){
                        preview_num = this.y;
                    }
                    else{
                        aumento = this.y - preview_num;
                        preview_num = this.y;
                    }
                    return '<b>'+ this.series.name +'</b><br/>'+
                    this.x +': '+ formatNumber(this.y) +' seguidores<br/>Nuevos seguidores: ' + formatNumber(aumento) ;
            },
            borderColor: '#F0F0F0',
            borderRadius: 2,
            borderWidth: 1
        },
        plotOptions: {
            area: {
                pointStart: 0,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        series: [
            {
                name: "Crecimiento diario de katyperry",
                data: [55239004,55292415,55317982,55352651,55394085,55433803,55546465,55613313,55681136,55722649,55764937,55804368,55845324,55950830,55989738,56029965,56071979,56119562,56207359,56222173,56273950,56348537,56381954,56466699,56515364,56535540,56586929,56635337,56710031,56742350],
                lineWidth: 0
            },
        ]
    });
});
$(document).ready(function(){
    $("#owloo_tw_chart_desc_1").html('Se mide el crecimiento de seguidores por dí­a durante el peri­odo de 1 mes. El promedio de nuevos seguidores diarios es de 51.840.');
});
$(function () {
    var chartAudiencia;
    chartAudiencia = new Highcharts.Chart({
        chart: {
            renderTo: 'owloo_tw_chart_2',
            type: 'area'
        },
        colors: [
           '#007CC3',
           '#0071B1'
        ],
        title: {
            text: ''
        },
        xAxis: {
            title: {
                text: 'Hora del dí­a (zona horaria UTC +0000)'
            },
            categories: [0,1]
        },
        yAxis: {
            title: {
                text: ''
            },
            labels: {
                formatter: function() {
                    return this.value;
                }
            }
        },
        credits: {
            enabled: false
        },
        tooltip: {
            formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                    'A las ' + this.x + ' horas' +': '+ formatNumber(this.y) +' tweets';
            },
            borderColor: '#F0F0F0',
            borderRadius: 2,
            borderWidth: 1
        },
        plotOptions: {
            area: {
                pointStart: 0,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        series: [
            {
                name: 'Durante todo el periodo',
                data: [300,284,290,219,366,308,207,187,147,90,63,62,75,75,114,145,125,178,272,253,268,313,285,358,],
                lineWidth: 0
            },
            {
                name: "Tweets de hoy",
                data: [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,],
                lineWidth: 0
            }
        ]
    });
});
$(document).ready(function(){
    $("#owloo_tw_chart_desc_2").html('Se puede analizar el horario promedio en que @katyperry publica mástweets. Este dato es calculado desde que este perfil se encuentra registrado en Owloo y sumando todos los tweets publicados hasta el momento.');
});
$(function () {
    var chartAudiencia;
    chartAudiencia = new Highcharts.Chart({
        chart: {
            renderTo: 'owloo_tw_chart_3',
            type: 'area'
        },
        colors: [
           '#007CC3',
           '#0071B1'
        ],
        title: {
            text: ''
        },
        xAxis: {
            title: {
                text: ''
            },
            categories: ['10 Ago','11 Ago','12 Ago','13 Ago','14 Ago','15 Ago','16 Ago','17 Ago','18 Ago','19 Ago','20 Ago','21 Ago','22 Ago','23 Ago','24 Ago','25 Ago','26 Ago','27 Ago','28 Ago','29 Ago','30 Ago','31 Ago','01 Set','02 Set','04 Set','05 Set','06 Set','08 Set','09 Set'],
            labels: {
                step: 3
            }
        },
        yAxis: {
            title: {
                text: ''
            },
            /*min: 1,
            max: 15,*/
            labels: {
                formatter: function()
                {
                    return this.value;
                }
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        tooltip: {
            formatter: function() {
                    return '<b>'+ this.series.name +'</b><br/>'+
                    this.x +': '+ formatNumber(this.y) +' tweets';
            },
            borderColor: '#F0F0F0',
            borderRadius: 2,
            borderWidth: 1
        },
        plotOptions: {
            area: {
                pointStart: 0,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        series: [
            {
                name: 'Tweets por dí­a',
                data: [4,13,4,7,10,4,2,7,5,8,8,8,7,5,5,15,1,7,3,3,6,2,3,1,3,1,2,2,2],
                lineWidth: 0
            },
        ]
    });
});
$(document).ready(function(){
    $("#owloo_tw_chart_desc_3").html('Se muestra la cantidad de Tweets por día que @katyperry publicó durante el último mes.');
});
$(document).ready(function(){
    $("#owloo_tw_chart_4").html('<table><thead><tr><th>Menciones</th><th class="owloo_country_table_3">Cantidad</th></tr></thead><tbody><tr><td>@katyperry</td><td>1.149</td></tr><tr><td>@shannonwoodward</td><td>75</td></tr><tr><td>@sofifii</td><td>47</td></tr><tr><td>@BonnieMcKee</td><td>40</td></tr><tr><td>@KaceyMusgraves</td><td>35</td></tr></tbody></table>');
    $("#owloo_tw_chart_desc_4").html('Los cinco perfiles másmencionados de @katyperry desde que esta cuenta se encuentra registrada en Owloo.');
});
$(document).ready(function(){
    $("#owloo_tw_chart_5").html('<table><thead><tr><th>Hashtag</th><th class="owloo_country_table_3">Cantidad</th></tr></thead><tbody><tr><td>#ROAR</td><td>255</td></tr><tr><td>#PRISM</td><td>222</td></tr><tr><td>#THISISHOWWEDO</td><td>108</td></tr><tr><td>#THEPRISMATICWORLDTOUR</td><td>101</td></tr><tr><td>#KP3D</td><td>91</td></tr></tbody></table>');
    $("#owloo_tw_chart_desc_5").html('Los cinco hashtags másutilizados por @katyperry desde que esta cuenta se encuentra registrada en Owloo.');
});
/***** END TWITTER CHARTS *****/