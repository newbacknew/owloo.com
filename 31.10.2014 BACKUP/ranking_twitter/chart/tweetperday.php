<?php
$qry = "";
$qry = $qry . " SELECT count, owloo_user_id, owloo_tweet_date FROM (SELECT count(owloo_tweet_date) as count, owloo_user_id, owloo_tweet_date";
$qry = $qry . " FROM owoo_tweet_data";
$qry = $qry . " WHERE owloo_user_id = '" . $update_id . "' AND owloo_tweet_date >= '".get_twitter_date_30_day($update_id, 'owoo_tweet_data', 'owloo_user_id', 'owloo_tweet_date')."'";
$qry = $qry . " group by owloo_tweet_date, owloo_user_id";
$qry = $qry . " Order by owloo_tweet_date Desc";
$qry = $qry . " Limit 0, 30) T Order by owloo_tweet_date ASC ";

$qrydata = mysql_query($qry);
$valoverall = '';
$xAxisCategories = "";
$seriesDataMin = 0;
$seriesDataMax = 0;
$ban = 1;
while ($fetchdata = mysql_fetch_array($qrydata))
{
	//Formatear fecha
	$auxformat = explode("-", $fetchdata['owloo_tweet_date']);
	$dia = $auxformat[2];
	$mes = getMes($auxformat[1], 'short');
	
	if($ban == 1){
		$valoverall .= 		$fetchdata["count"];
		$xAxisCategories .= "'".$dia." ".$mes."'";
		$seriesDataMin = 	$fetchdata["count"];
		$seriesDataMax = 	$fetchdata["count"];
		$ban = 0;
	}
	else{
		$valoverall .= ','.$fetchdata["count"];
		$xAxisCategories .= ",'".$dia." ".$mes."'";
		if($fetchdata["count"] < $seriesDataMin)
			$seriesDataMin = $fetchdata["count"];
		else
		if($fetchdata["count"] > $seriesDataMax)
			$seriesDataMax = $fetchdata["count"];
	}
}
?>
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
            categories: [<?=$xAxisCategories?>],
            labels: {
                step: 3
            }
        },
        yAxis: {
            title: {
                text: ''
            },
			/*min: <?=$seriesDataMin?>,
			max: <?=$seriesDataMax?>,*/
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
                name: 'Tweets por día',
                data: [<?php echo $valoverall;?>],
                lineWidth: 0
            },
        ]
    });
});
$(document).ready(function(){
    $("#owloo_tw_chart_desc_3").html('Se muestra la cantidad de Tweets por día que @<?=$_GET['screenname']?> publicó durante el último mes.');
});