<?php 
$qry = "";
$qry = $qry . " SELECT owloo_user_id, owloo_tweet_curse , count(owloo_user_id) AS count";
$qry = $qry . " FROM owloo_tweet_curse_data";
$qry = $qry . " WHERE owloo_user_id = '" . $update_id . "'";
$qry = $qry . " GROUP BY owloo_tweet_curse";
$qry = $qry . " ORDER BY count DESC";
$qry = $qry . " LIMIT 0 , 5";
$qrydata = mysql_query($qry);
$cat = "";
$val = "";
while ($fetchdata = mysql_fetch_array($qrydata))
{
    $cat = $cat . "'" . $fetchdata["owloo_tweet_curse"] .  "',";
    $val = $val . $fetchdata["count"] .  ",";
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
                categories: [<?php echo $cat;?>]
            },
            yAxis: {
                title: {
                    text: "<?php echo $screen_name; ?>'s Used Curse Words"
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
                name: '<?php echo $screen_name; ?>',
                data: [<?php echo $val; ?>]
            }]
        });
    });
</script>
<div class="owloo_tw_charts">
    <h1 class="owloo_country_fb_stats centered"><?php echo $screen_name; ?>'s Most Used Curse Words</h1>
    <div id="container3" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
</div>