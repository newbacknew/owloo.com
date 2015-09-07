<?php
	set_time_limit(3600);
	
	function microtime_float()
	{
		list($useg, $seg) = explode(" ", microtime());
		return ((float)$useg + (float)$seg);
	}
	
	$tiempo_inicio = microtime_float();

	
	$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
	mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
        mysql_query("SET NAMES 'utf8'");
	
	for($idCountry = 1; $idCountry <= 212; $idCountry++){
		$query =   "SELECT id_country, idfb_page 
					FROM temp_pages_social_bakers_numbers 
					WHERE id_country = ".$idCountry."
					ORDER BY 1, 2
					;";
		$que = mysql_query($query, $conexion) or die(mysql_error());
		$cont = 0;
		$sql_in = '';
		while($fila = mysql_fetch_assoc($que)){
			$cont++;
			if($cont != 1)
				$sql_in .= ',';
			$sql_in .= $fila['idfb_page'];
			if($cont == 1000){
				
				$datos = file_get_contents('https://graph.facebook.com/fql?locale=es_LA&q=SELECT+page_id,name,username,page_url,type,location+FROM+page+WHERE+page_id+IN('.$sql_in.')');
				$datosarray = json_decode ($datos, true);
				
				foreach($datosarray['data'] as $page){
					$location = (!empty($page['location']['country'])) ? "'".mysql_real_escape_string($page['location']['country'])."'" : 'NULL';
					$sql = "INSERT INTO facebook_pages(idfb_page, name, username, link, type, location, id_country) VALUES(".$page['page_id'].",'".mysql_real_escape_string($page['name'])."', '".mysql_real_escape_string($page['username'])."', '".mysql_real_escape_string($page['page_url'])."', '".mysql_real_escape_string(ucfirst(strtolower($page['type'])))."', ".$location.", ".$idCountry.");";
					$res = mysql_query($sql, $conexion) or die(mysql_error());
				}
	
				$cont = 0;
				$sql_in = '';
			}
		}
		if($cont > 0){
				
			$datos = file_get_contents('https://graph.facebook.com/fql?locale=es_LA&q=SELECT+page_id,name,username,page_url,type,location+FROM+page+WHERE+page_id+IN('.$sql_in.')');
			$datosarray = json_decode ($datos, true);
			
			foreach($datosarray['data'] as $page){
				$location = (!empty($page['location']['country'])) ? "'".mysql_real_escape_string($page['location']['country'])."'" : 'NULL';
					$sql = "INSERT INTO facebook_pages(idfb_page, name, username, link, type, location, id_country) VALUES(".$page['page_id'].",'".mysql_real_escape_string($page['name'])."', '".mysql_real_escape_string($page['username'])."', '".mysql_real_escape_string($page['page_url'])."', '".mysql_real_escape_string(ucfirst(strtolower($page['type'])))."', ".$location.", ".$idCountry.");";
					$res = mysql_query($sql, $conexion) or die(mysql_error());
			}
	
			$cont = 0;
			$sql_in = '';
		}
	}
	
	
	echo "Tiempo empleado: " . (microtime_float() - $tiempo_inicio); 