<?php 
$qry = "";
$qry = $qry . " SELECT owloo_user_id, owloo_screenanme , count( owloo_screenanme ) AS count";
$qry = $qry . " FROM owloo_mentions";
$qry = $qry . " WHERE owloo_user_id = '" . $update_id . "'";
$qry = $qry . " GROUP BY owloo_screenanme, owloo_user_id";
$qry = $qry . " ORDER BY count DESC";
$qry = $qry . " LIMIT 0 , 5";
$qrydata = mysql_query($qry);
$_menciones = '<table><thead><tr><th>Menciones</th><th class="owloo_country_table_3">Cantidad</th></tr></thead><tbody>';
while ($fetchdata = mysql_fetch_array($qrydata)){
    $_menciones .= '<tr><td>@'.$fetchdata["owloo_screenanme"].'</td><td>'.owloo_number_format($fetchdata["count"]).'</td></tr>';
}
$_menciones .= '</tbody></table>';
?>
$(document).ready(function(){
    $("#owloo_tw_chart_4").html('<?=$_menciones?>');
    $("#owloo_tw_chart_desc_4").html('Los cinco perfiles más mencionados de @<?=$_GET['screenname']?> desde que esta cuenta se encuentra registrada en Owloo.');
});