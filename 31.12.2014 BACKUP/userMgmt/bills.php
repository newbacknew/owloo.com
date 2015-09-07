<?php
session_start();
require_once('../owloo_config.php');
include('system/conn_db.php');

function abort(){
	echo 'Factura no encontrada...';
	exit();
}
if(!isset($_GET['code']) || !isset($_SESSION['twitter_buy_stats_owloo_user_id'])){
	abort();
}

$params = explode('-', $_GET['code']);

$id_bill = mysql_real_escape_string($params[0]);
$code = mysql_real_escape_string($params[1]);

//Datos de la factura
$res = mysql_query("SELECT * FROM buy_twitter_stats WHERE id_buy = $id_bill AND code LIKE '$code' AND date_pay IS NOT NULL;");
if(mysql_num_rows($res) == 0)
	abort();
	
$fila = mysql_fetch_array($res);

if($fila['owloo_user_id'] == $_SESSION['twitter_buy_stats_owloo_user_id']){
	$res = mysql_query("select * FROM profiles WHERE u_id = ".$_SESSION['twitter_buy_stats_owloo_user_id'].";");
	$res_usuario = array();
	while($row = mysql_fetch_array($res)){
		$res_usuario[] = array('f_id'=>$row['f_id'], 'f_val'=>$row['f_val']);
	}
}
else{
	abort();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>owloo - Facturación</title>
<link rel="stylesheet" href="<?=URL_ROOT?>css/style_fonts.css" type="text/css" />
<link rel="stylesheet" href="<?=URL_ROOT?>userMgmt/css//bill.css" type="text/css" />
</head>
<body>
	<div class="content-bills">
    	<div class="header">
        	<img width="140" height="20" border="0" style="cursor: pointer;" alt="Latamclick" src="css/logo_latamclick.png">
        </div>
        <div class="info-bill">
        	<div class="company">
            	<div>Latamclick S.A.</div>
                <div>Edificio Sun Tower 1-D, El Dorado</div>
                <div>Ciudad de Panamá</div>
                <div>+507 2361622</div>
            </div>
            <div class="bill-code">
            	<div class="col"><b>Servicios</b></div>                <div class="value">: owloo</div>
                <div class="col"><b>ID de facturación</b></div>        <div class="value">: <?=$code?></div>
                <div class="col"><b>Fecha de la factura</b></div>      <div class="value">: <?=$fila['date_pay']?></div>
            </div>
        </div>
        <div class="info-client">
        	<div><h3>Facturar a:</h3></div>
        	<div><b>Nombre o compañía:</b> <?=$res_usuario[0]['f_val']?></div>
            <div><b>País:</b> <?=$res_usuario[1]['f_val']?></div>
        </div>
        <div class="bill-details">
        	<table class="mediacenter">
            	<thead>
                	<tr>
                    	<th>Fecha</th>
                        <th>Descripción</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody>
                	<tr>
                    	<td><?=$fila['date_pay']?></td>
                        <td>Estadísticas de @<?=$fila['owloo_twitter_screen_name']?>. Desde: <?=$fila['date_since']?> Hasta: <?=$fila['date_until']?></td>
                        <td><?=($fila['currency']=='USD'?'$':$fila['currency']).$fila['price']?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
