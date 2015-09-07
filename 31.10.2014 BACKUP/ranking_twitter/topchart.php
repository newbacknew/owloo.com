<?php include("config/config.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <title><?php echo Home_Page_Title; ?></title>
        <link href="css/style.css" type="text/css" rel="stylesheet" />
        <meta name="description" content="<?php echo Home_Page_Desc; ?>" />
        <meta name="keywords" content="<?php echo Home_Page_Keyword; ?>" />
        <script type="text/javascript" src="<?php echo SITE_URL; ?>js/jquery.js"></script>
    </head>
    <body>
        <?php include("header.php"); ?>
        <div id="solidblack"></div>
        <div id="body">
            <div id="mainbody">
                <div style="display: none;" id="loading"><br>
                    <img id="loaderIMG" src="<?php echo SITE_URL; ?>images/twitter-loader-128.gif">
                    <br>
                </div>
                <div id="results">
                    <center>
                        <img id="loaderIMG" src="<?php echo SITE_URL; ?>images/twitter-logo.gif">
                    </center>
                    <table id="topacctsnote">
                        <tbody>
                            <tr>
                                <td style="font-size: 13px; font-weight: bold; color: #FFFFFF; background-color: #000000; padding:2px; text-align:center;">
                                    <?php echo Top_Account_Label; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo Privacy_Text; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <script src="js/highcharts.js"></script>
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
                                title: {
                                    text: 'Tweets Per Hours'
                                },
                                xAxis: {
                                    title: {
                                        text: 'Hour of Day (EST)'
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
                                    pointFormat: '{series.name} Tweet : <b>{point.y:,.0f}</b><br/>Hours : {point.x}'
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
                                        name: 'Over All Tweets',
                                        data: [<?php echo $valoverall; ?>],
                                        lineWidth: 1
                                    },
                                    {
                                        name: "Today's Tweets",
                                        data: [<?php echo $val; ?>],
                                        lineWidth: 1
                                    }
                                ]
                            });
                        });
                    </script>
                    <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                </div>
                <div id="chartDiv">
                    <?php
                    $qry = "";
                    $qry = $qry . " SELECT count(owloo_tweet_time) as count, owloo_user_id, owloo_tweet_date";
                    $qry = $qry . " FROM owoo_tweet_data";
                    $qry = $qry . " group by owloo_tweet_date";
                    $qry = $qry . " Order by owloo_tweet_date Desc";
                    $qry = $qry . " Limit 0, 50";
                    $qrydata = mysql_query($qry);
                    while ($fetchdata = mysql_fetch_array($qrydata)) {
                        $valoverall = $valoverall . $fetchdata["count"] . ",";
                    }
                    ?>
                    <script type="text/javascript">
                        $(function () {
                            $('#container1').highcharts({
                                chart: {
                                    type: 'area'
                                },
                                title: {
                                    text: 'Tweets Per Day'
                                },
                                xAxis: {
                                    title: {
                                        text: 'Day'
                                    }
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
                                        name: 'Over All Tweets',
                                        data: [<?php echo $valoverall; ?>],
                                        lineWidth: 1
                                    },
                                ]
                            });
                        });
                    </script>
                    <div id="container1" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                </div>
                <div id="chartDiv">
                    <?php
                    $qry = "";
                    $qry = $qry . " SELECT owloo_user_id, owloo_tweet_curse , count(owloo_user_id) AS count";
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
                                title: {
                                    text: "Most Used Curse Words"
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
                                        text: "Used Curse Words"
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
                                        name: 'Most Curse Words',
                                        data: [<?php echo $val; ?>]
                                    }]
                            });
                        });
                    </script>
                    <div id="container3" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                </div>
                <div id="chartDiv">
                    <?php
                    $qry = "";
                    $qry = $qry . " SELECT owloo_user_id, owloo_screenanme , count( owloo_screenanme ) AS count";
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
                                title: {
                                    text: "Most Mentions"
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
                                        text: "Most Mentions"
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
                                        name: 'Most Mentions As Per OTS',
                                        data: [<?php echo $val; ?>]
                                    }]
                            });
                        });
                    </script>
                    <div id="container4" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                </div>
                <div id="chartDiv">
                    <?php
                    $qry = "";
                    $qry = $qry . " SELECT owloo_user_id, owloo_hashword, count(owloo_hashword) AS count";
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
                                title: {
                                    text: "Most Used Hashtags"
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
                                        text: "Most Used Hashtags"
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
                                        name: 'Most HashTag Used',
                                        data: [<?php echo $val; ?>]
                                    }]
                            });
                        });
                    </script>
                    <div id="container5" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                </div>
            </div>
        </div>
        <div id="solidblack"></div>
<?php include("footer.php"); ?>
    </body>
</html>