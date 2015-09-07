<?php
	set_time_limit(0);
	
	$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
	mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
    mysql_query("SET NAMES 'utf8'");
	
	$query =   "SELECT id_page, idfb_page 
				FROM  facebook_pages
				ORDER BY 1
				;";
	$que = mysql_query($query, $conexion) or die(mysql_error());
	$cont = 0;
	$sql_in = '';
	$sql_insert = '';
	while($fila = mysql_fetch_assoc($que)){
		$cont++;
		if($cont != 1)
			$sql_in .= ',';
		$sql_in .= $fila['idfb_page'];
		if($cont == 1000){
			
			$datos = file_get_contents('https://graph.facebook.com/fql?q=SELECT+page_id,fan_count,talking_about_count+FROM+page+WHERE+page_id+IN('.$sql_in.')');
            
            die($datos);
            
			$datosarray = json_decode ($datos, true);
			$sql_insert = '';
			foreach($datosarray['data'] as $page){
				$sql_insert .= "(".$page['page_id'].",".$page['fan_count'].",".$page['talking_about_count'].",now()),";
			}
			$sql = "INSERT INTO facebook_pages_likes_talking_about(idfb_page, likes, talking_about, date) VALUES".$sql_insert.";";
			echo $sql;
			$res = mysql_query($sql, $conexion) or die(mysql_error());
			$sql_insert = "";
			$cont = 0;
			$sql_in = '';
		}
	}
	if($cont > 0){
			
		$datos = file_get_contents('https://graph.facebook.com/fql?q=SELECT+page_id,fan_count,talking_about_count+FROM+page+WHERE+page_id+IN('.$sql_in.')');
		$datosarray = json_decode ($datos, true);
		$sql_insert = '';
		foreach($datosarray['data'] as $page){
			$sql_insert .= "(".$page['page_id'].",".$page['fan_count'].",".$page['talking_about_count'].",now()),";
		}
		$sql = "INSERT INTO facebook_pages_likes_talking_about(idfb_page, likes, talking_about, date) VALUES".$sql_insert.";";
		$res = mysql_query($sql, $conexion) or die(mysql_error());
		$sql_insert = "";
		$cont = 0;
		$sql_in = '';
	} 