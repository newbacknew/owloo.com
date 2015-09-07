<?php
$qry = "";
$qry = $qry . " SELECT DISTINCT owloo_followers_count, owloo_user_twitter_id, owloo_updated_on";
$qry = $qry . " FROM owloo_daily_track";
$qry = $qry . " WHERE owloo_user_twitter_id = '" . $update_id . "' AND owloo_updated_on >= '".get_twitter_date_30_day($update_id, 'owloo_daily_track', 'owloo_user_twitter_id', 'owloo_updated_on')."'";
$qry = $qry . " ORDER BY owloo_updated_on ASC , owloo_followers_count DESC";
$qry = $qry . " LIMIT 0 , 35";

$que = mysql_query($qry);

$_valor_anterior = -1;
$_suma_crecimiento = 0;
$_count_suma_crecimiento = 0;

$seriesData = ""; //Estadística vertical. Cantidad de seguidores
$seriesDataMin = 0; //Número mínimo de seguidores
$seriesDataMax = 0; //Número máximo de seguidores
$xAxisCategories = ""; //Estadística horizontal. Fechas de los datos
$ban = 1; //Bandera
$cont_num_data = mysql_num_rows($que);
while($fila = mysql_fetch_assoc($que)){
	//Formatear fecha
	$auxformat = explode("-", $fila['owloo_updated_on']);
	$dia = $auxformat[2];
	$mes = getMes($auxformat[1], 'short');
	if($ban == 1){
		$seriesData .= 		$fila['owloo_followers_count'];
		$xAxisCategories .= "'".$dia." ".$mes."'";
		$seriesDataMin = 	$fila['owloo_followers_count'];
		$seriesDataMax = 	$fila['owloo_followers_count'];
		$ban = 0;
	}
	else{
		$seriesData .= ','.$fila['owloo_followers_count'];
		$xAxisCategories .= ",'".$dia." ".$mes."'";
		if($fila['owloo_followers_count'] < $seriesDataMin)
			$seriesDataMin = $fila['owloo_followers_count'];
		else
		if($fila['owloo_followers_count'] > $seriesDataMax)
			$seriesDataMax = $fila['owloo_followers_count'];
	}
    
    if($_valor_anterior == -1)
        $_valor_anterior = $fila['owloo_followers_count'];
    else{
        $_suma_crecimiento += ($fila['owloo_followers_count'] - $_valor_anterior);
        $_count_suma_crecimiento++;
        $_valor_anterior = $fila['owloo_followers_count'];
    }
    
}

?>
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
            categories: [<?=$xAxisCategories?>],
            labels: {
                step: 3
            }
        },
        yAxis: {
            title: {
                text: ''
            },
			min: <?=$seriesDataMin?>,
			max: <?=$seriesDataMax?>,
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
                name: "Crecimiento diario de <?php echo $screen_name; ?>",
                data: [<?=$seriesData?>],
                lineWidth: 0
            },
        ]
    });
});
$(document).ready(function(){
    $("#owloo_tw_chart_desc_1").html('Se mide el crecimiento de seguidores por día durante el período de 1 mes. El promedio de nuevos seguidores diarios es de <?=($_count_suma_crecimiento>0?owloo_number_format(round($_suma_crecimiento/$_count_suma_crecimiento, 0)):'n/a')?>.');
});