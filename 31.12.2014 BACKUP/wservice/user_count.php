<?php
$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
mysql_select_db("owloo_userManagement", $conexion) or die(mysql_error());
$sql = 'SELECT COUNT(*) cantidad FROM members';

$reg = mysql_query($sql, $conexion);
if($fila = mysql_fetch_assoc($reg))
	echo $fila['cantidad'];