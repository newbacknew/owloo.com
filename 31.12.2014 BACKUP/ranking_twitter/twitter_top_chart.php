<?php
?>
<div class="">
	<script src="<?=URL_ROOT?>ranking_twitter/js/highcharts.js"></script>
    <div id="chartDiv">
        <?php
        $valtoday = array();
        $valoveralls = array();
        $qry = "";
        $qry = $qry . " SELECT count(owloo_tweet_time) as count,owloo_tweet_time";
        $qry = $qry . " FROM owoo_tweet_data";
        $qry = $qry . " group by owloo_tweet_time";
        $qry = $qry . " Order by owloo_tweet_time ASC";
        $qrydata = mysql_query($qry);
        while ($fetchdata = mysql_fetch_array($qrydata)) {
            if ($fetchdata["owloo_tweet_time"] != "") {
                if ($fetchdata["owloo_tweet_time"] == "00") {
                    $tmp = $fetchdata["owloo_tweet_time"];
                    $valoveralls[0] = $fetchdata["count"];
                } else {
                    $tmp = ltrim($fetchdata["owloo_tweet_time"], "0");
                    $valoveralls[$tmp] = $fetchdata["count"];
                }
            }
        }
		$valoverall = '';
        for ($cnt = 0; $cnt < 24; $cnt++) {
            if ($valoveralls[$cnt]) {
                $valoverall = $valoverall . $valoveralls[$cnt] . ",";
            } else {
                $valoverall = $valoverall . "0,";
                ;
            }
        }
        $qry = "";
        $qry = $qry . " SELECT count(owloo_tweet_date) as count, owloo_tweet_time,";
        $qry = $qry . " owloo_tweet_date FROM owoo_tweet_data";
        $qry = $qry . " group by owloo_tweet_date, owloo_tweet_time";
        $qry = $qry . " AND owloo_tweet_date = '" . Date("Y-m-d") . "'";
        $qry = $qry . " Order by owloo_tweet_time ASC";
        $qrydata = mysql_query($qry);
        while ($fetchdata = mysql_fetch_array($qrydata)) {
            if ($fetchdata["owloo_tweet_time"] != "") {
                if ($fetchdata["owloo_tweet_time"] == 0) {
                    $tmp = $fetchdata["owloo_tweet_time"];
                    $valtoday[0] = $tmp;
                } else {
                    $tmp = ltrim($fetchdata["owloo_tweet_time"], "0");
                    $valtoday[$tmp] = $fetchdata["count"];
                }
            }
        }
		$val = '';
        for ($cnt = 0; $cnt < 24; $cnt++) {
            if ($valtoday[$cnt]) {
                $val = $val . $valtoday[$cnt] . ",";
            } else {
                $val = $val . "0,";
                ;
            }
        }
        ?>
        <script type="text/javascript">
            $(function () {
                $('#container').highcharts({
                    chart: {
                        type: 'area'
                    },
					colors: [
					   '#6296BE',
					   '#4D7492'
					],
                    title: {
                        text: ''
                    },
                    xAxis: {
                        title: {
                            text: 'Hora del día (EST)'
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
                    tooltip: {
                        pointFormat: '{series.name} Tweet : <b>{point.y:,.0f}</b><br/>Horas : {point.x}'
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
                            name: 'En todos los Tweets',
                            data: [<?php echo $valoverall; ?>],
                            lineWidth: 1
                        },
                        {
                            name: "Tweets de hoy",
                            data: [<?php echo $val; ?>],
                            lineWidth: 1
                        }
                    ]
                });
            });
        </script>
        <div class="owloo_tw_charts">
    		<h1 class="owloo_country_fb_stats centered">Tweets por horas</h1>
        	<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    <div id="chartDiv">
        <?php
		
		function getMes($mes, $format){ //Formateo de meses
			switch((int)$mes){
				case 1: if($format == 'short') return 'Ene'; else if($format == 'large') return 'Enero';
				case 2: if($format == 'short') return 'Feb'; else if($format == 'large') return 'Febrero';
				case 3: if($format == 'short') return 'Mar'; else if($format == 'large') return 'Marzo';
				case 4: if($format == 'short') return 'Abr'; else if($format == 'large') return 'Abril';
				case 5: if($format == 'short') return 'May'; else if($format == 'large') return 'Mayo';
				case 6: if($format == 'short') return 'Jun'; else if($format == 'large') return 'Junio';
				case 7: if($format == 'short') return 'Jul'; else if($format == 'large') return 'Julio';
				case 8: if($format == 'short') return 'Ago'; else if($format == 'large') return 'Agosto';
				case 9: if($format == 'short') return 'Set'; else if($format == 'large') return 'Setiembre';
				case 10: if($format == 'short') return 'Oct'; else if($format == 'large') return 'Octubre';
				case 11: if($format == 'short') return 'Nov'; else if($format == 'large') return 'Noviembre';
				case 12: if($format == 'short') return 'Dic'; else if($format == 'large') return 'Diciembre';
			}
		}
		
        $qry = "";
        $qry = $qry . " SELECT count, owloo_tweet_date FROM (SELECT count(owloo_tweet_date) as count, owloo_tweet_date";
        $qry = $qry . " FROM owoo_tweet_data";
        $qry = $qry . " group by owloo_tweet_date";
        $qry = $qry . " Order by owloo_tweet_date Desc";
        $qry = $qry . " Limit 0, 30) T Order by owloo_tweet_date ASC";
		
		$valoverall = '';
		$xAxisCategories = "";
		$seriesDataMin = 0;
		$seriesDataMax = 0;
		$ban = 1;
		
        $qrydata = mysql_query($qry);
        while ($fetchdata = mysql_fetch_array($qrydata)) {
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
        <script type="text/javascript">
            $(function () {
                $('#container1').highcharts({
                    chart: {
                        type: 'area'
                    },
					colors: [
					   '#6296BE',
					   '#4D7492'
					],
                    title: {
                        text: ''
                    },
                    xAxis: {
                        title: {
                            text: 'Día'
                        },
                		categories: [<?=$xAxisCategories?>]
                    },
                    yAxis: {
                        title: {
                            text: ''
                        },
                        labels: {
                            formatter: function()
                            {
                                return this.value;
                            }
                        }
                    },
                    tooltip: {
                        pointFormat: '{series.name} Tweet : <b>{point.y:,.0f}</b>'
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
                            name: 'En todos los Tweets',
                            data: [<?php echo $valoverall; ?>],
                            lineWidth: 1
                        },
                    ]
                });
            });
        </script>
        <div class="owloo_tw_charts">
    		<h1 class="owloo_country_fb_stats centered">Tweets por día</h1>
        	<div id="container1" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    <div id="chartDiv">
        <?php
        $qry = "";
        $qry = $qry . " SELECT owloo_tweet_curse , count(owloo_user_id) AS count";
        $qry = $qry . " FROM owloo_tweet_curse_data GROUP BY owloo_tweet_curse";
        $qry = $qry . " ORDER BY count DESC";
        $qry = $qry . " LIMIT 0 , 5";
        $qrydata = mysql_query($qry);
        $cat = "";
        $val = "";
        while ($fetchdata = mysql_fetch_array($qrydata)) {
            $cat = $cat . "'" . $fetchdata["owloo_tweet_curse"] . "',";
            $val = $val . $fetchdata["count"] . ",";
        }
        ?>
        <script type="text/javascript">
            $(function () {
                $('#container3').highcharts({
                    chart: {
                        type: 'bar'
                    },
					colors: [
					   '#6296BE',
					   '#4D7492'
					],
                    title: {
                        text: ""
                    },
                    subtitle: {
                        style: {
                            position: 'absolute',
                            right: '0px',
                            bottom: '10px'
                        }
                    },
                    legend: {
    
                    },
                    xAxis: {
                        categories: [<?php echo $cat; ?>]
                    },
                    yAxis: {
                        title: {
                            text: ""
                        },
                        labels: {
                            formatter: function() {
                                return this.value;
                            }
                        },
                        min: 0
                    },
                    plotOptions: {
                        area: {
                            fillOpacity: 0.5
                        }
                    },
                    series: [{
                            name: 'Palabras negativas',
                            data: [<?php echo $val; ?>]
                        }]
                });
            });
        </script>
        <div class="owloo_tw_charts">
    		<h1 class="owloo_country_fb_stats centered">Palabras negativas más usadas</h1>
        	<div id="container3" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    <div id="chartDiv">
        <?php
        $qry = "";
        $qry = $qry . " SELECT owloo_screenanme , count( owloo_screenanme ) AS count";
        $qry = $qry . " FROM owloo_mentions GROUP BY owloo_screenanme";
        $qry = $qry . " ORDER BY count DESC";
        $qry = $qry . " LIMIT 0 , 5";
        $qrydata = mysql_query($qry);
        $cat = "";
        $val = "";
        while ($fetchdata = mysql_fetch_array($qrydata)) {
            $cat = $cat . "'@" . $fetchdata["owloo_screenanme"] . "',";
            $val = $val . $fetchdata["count"] . ",";
        }
        ?>
        <script type="text/javascript">
            $(function () {
                $('#container4').highcharts({
                    chart: {
                        type: 'bar'
                    },
					colors: [
					   '#6296BE',
					   '#4D7492'
					],
                    title: {
                        text: ""
                    },
                    subtitle: {
                        style: {
                            position: 'absolute',
                            right: '0px',
                            bottom: '10px'
                        }
                    },
                    legend: {
    
                    },
                    xAxis: {
                        categories: [<?php echo $cat; ?>]
                    },
                    yAxis: {
                        title: {
                            text: ""
                        },
                        labels: {
                            formatter: function() {
                                return this.value;
                            }
                        },
                        min: 0
                    },
                    plotOptions: {
                        area: {
                            fillOpacity: 0.5
                        }
                    },
                    series: [{
                            name: 'Las cuenta más mencionadas registradas en owloo',
                            data: [<?php echo $val; ?>]
                        }]
                });
            });
        </script>
        <div class="owloo_tw_charts">
    		<h1 class="owloo_country_fb_stats centered">Más mencionados</h1>
        	<div id="container4" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    <div id="chartDiv">
        <?php
        $qry = "";
        $qry = $qry . " SELECT owloo_hashword, count(owloo_hashword) AS count";
        $qry = $qry . " FROM owloo_hashtag GROUP BY owloo_hashword";
        $qry = $qry . " ORDER BY count DESC";
        $qry = $qry . " LIMIT 0 , 5";
        $qrydata = mysql_query($qry);
        $cat = "";
        $val = "";
        while ($fetchdata = mysql_fetch_array($qrydata)) {
            $cat = $cat . "'#" . $fetchdata["owloo_hashword"] . "',";
            $val = $val . $fetchdata["count"] . ",";
        }
        ?>
        <script type="text/javascript">
            $(function () {
                $('#container5').highcharts({
                    chart: {
                        type: 'bar'
                    },
					colors: [
					   '#6296BE',
					   '#4D7492'
					],
                    title: {
                        text: ""
                    },
                    subtitle: {
                        style: {
                            position: 'absolute',
                            right: '0px',
                            bottom: '10px'
                        }
                    },
                    legend: {
    
                    },
                    xAxis: {
                        categories: [<?php echo $cat; ?>]
                    },
                    yAxis: {
                        title: {
                            text: ""
                        },
                        labels: {
                            formatter: function() {
                                return this.value;
                            }
                        },
                        min: 0
                    },
                    plotOptions: {
                        area: {
                            fillOpacity: 0.5
                        }
                    },
                    series: [{
                            name: 'Hashtags más usados',
                            data: [<?php echo $val; ?>]
                        }]
                });
            });
        </script>
        <div class="owloo_tw_charts">
    		<h1 class="owloo_country_fb_stats centered">Hashtags más usados</h1>
        	<div id="container5" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
</div>