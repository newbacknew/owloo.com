<?php
$valtoday = array();
$valoveralls = array();
$qry = "";
$qry = $qry . " SELECT count(owloo_tweet_time) as count, owloo_user_id, owloo_tweet_time";
$qry = $qry . " FROM owoo_tweet_data";
$qry = $qry . " WHERE owloo_user_id = '" . $update_id . "'";
$qry = $qry . " group by owloo_tweet_time, owloo_user_id";
$qry = $qry . " Order by owloo_tweet_time ASC";

$qrydata = mysql_query($qry);
while ($fetchdata = mysql_fetch_array($qrydata))
{
    if($fetchdata["owloo_tweet_time"] != "")
    {
        if($fetchdata["owloo_tweet_time"] == "00")
        {
            $tmp = $fetchdata["owloo_tweet_time"];
            $valoveralls[0] = $fetchdata["count"];
        }
        else
        {
            $tmp = ltrim($fetchdata["owloo_tweet_time"], "0");
            $valoveralls[$tmp] = $fetchdata["count"];
        }
    }
}
$valoverall = '';
for($cnt = 0; $cnt < 24; $cnt++)
{
    if(isset($valoveralls[$cnt]) && $valoveralls[$cnt])
    {
        $valoverall = $valoverall . $valoveralls[$cnt] . ",";
    }
    else
    {
        $valoverall = $valoverall . "0,";;
    }
}
$qry = "";
$qry = $qry . " SELECT count(owloo_tweet_date) as count, owloo_user_id, owloo_tweet_time,";
$qry = $qry . " owloo_tweet_date FROM owoo_tweet_data";
$qry = $qry . " group by owloo_tweet_date, owloo_tweet_time";
$qry = $qry . " having owloo_user_id = '" . $update_id . "'";
$qry = $qry . " AND owloo_tweet_date = '" . Date("Y-m-d") . "'";
$qry = $qry . " Order by owloo_tweet_time ASC";
$qrydata = mysql_query($qry);
while ($fetchdata = mysql_fetch_array($qrydata))
{
    if($fetchdata["owloo_tweet_time"] != "")
    {
        if($fetchdata["owloo_tweet_time"] == 0)
        {
            $tmp = $fetchdata["owloo_tweet_time"];
            $valtoday[0] = $tmp;
        }
        else
        {
            $tmp = ltrim($fetchdata["owloo_tweet_time"], "0");
            $valtoday[$tmp] = $fetchdata["count"];
        }
    }
}
$val = '';
for($cnt = 0; $cnt < 24; $cnt++)
{
    if(isset($valtoday[$cnt]) && $valtoday[$cnt])
    {
        $val = $val . $valtoday[$cnt] . ",";
    }
    else
    {
        $val = $val. "0,";;
    }
}
?>
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
                text: 'Hora del día (zona horaria UTC +0000)'
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
                data: [<?php echo $valoverall;?>],
                lineWidth: 0
            },
            {
                name: "Tweets de hoy",
                data: [<?php echo $val;?>],
                lineWidth: 0
            }
        ]
    });
});
$(document).ready(function(){
    $("#owloo_tw_chart_desc_2").html('Se puede analizar el horario promedio en que @<?=$_GET['screenname']?> publica más tweets. Este dato es calculado desde que este perfil se encuentra registrado en Owloo y sumando todos los tweets publicados hasta el momento.');
});