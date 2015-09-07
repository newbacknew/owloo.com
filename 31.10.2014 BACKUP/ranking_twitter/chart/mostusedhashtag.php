<?php 
$qry = "";
$qry = $qry . " SELECT owloo_user_id, owloo_hashword, count(owloo_hashword) AS count";
$qry = $qry . " FROM owloo_hashtag";
$qry = $qry . " WHERE owloo_user_id = '" . $update_id . "'";
$qry = $qry . " GROUP BY owloo_hashword, owloo_user_id";
$qry = $qry . " ORDER BY count DESC";
$qry = $qry . " LIMIT 0 , 5";
$qrydata = mysql_query($qry);
$_hashtag = '<table><thead><tr><th>Hashtag</th><th class="owloo_country_table_3">Cantidad</th></tr></thead><tbody>';
while ($fetchdata = mysql_fetch_array($qrydata)){
    $_hashtag .= '<tr><td>#'.$fetchdata["owloo_hashword"].'</td><td>'.owloo_number_format($fetchdata["count"]).'</td></tr>';
}
$_hashtag .= '</tbody></table>';
?>
$(document).ready(function(){
    $("#owloo_tw_chart_5").html('<?=$_hashtag?>');
    $("#owloo_tw_chart_desc_5").html('Los cinco hashtags más utilizados por @<?=$_GET['screenname']?> desde que esta cuenta se encuentra registrada en Owloo.');
});