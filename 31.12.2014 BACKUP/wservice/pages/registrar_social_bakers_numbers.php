<?php
	$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
	mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
        mysql_query("SET NAMES 'utf8'");
	
	for($cont = 1; $cont <= 212; $cont++){
		$query =   "SELECT id_country, idfb_page FROM pages_socialbakers WHERE id_country = ".$cont." UNION SELECT id_country, idfb_page FROM pages_socialnumbers WHERE id_country = ".$cont.";";
		$que = mysql_query($query, $conexion) or die(mysql_error());
		if($fila = mysql_fetch_assoc($que)){
			$sql = "INSERT INTO pages_social_bakers_numbers(id_country, idfb_page) SELECT id_country, idfb_page FROM pages_socialbakers WHERE id_country = ".$cont." UNION SELECT id_country, idfb_page FROM pages_socialnumbers WHERE id_country = ".$cont." ORDER BY 1, 2;";
        	$res = mysql_query($sql, $conexion) or die(mysql_error());
		}
	}
	
	
	