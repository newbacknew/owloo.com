<?php
    $ultimas_12_semanas = get_city_date_last_update(5 + 8);

	$cont = 1;
	$sql =   "SELECT fc.id_city id_city, fc.name nombre, total_user, total_female, total_male, c.id_country, c.code code, c.name name
				FROM record_city rc 
					JOIN facebook_city fc 
						ON rc.id_city = fc.id_city 
					JOIN country c 
						ON fc.id_country = c.id_country 
				WHERE date = STR_TO_DATE('".CITY_DATE_LAST_UPDATE."','%Y-%m-%d') 
					AND c.code LIKE '".$countryCode."'
				ORDER BY 3 DESC
				"; 
	$res = mysql_query($sql) or die(mysql_error());
	
	$country = getCountryData($countryCode);
	$cityMayorAudiencia = array();
	
	while($fila = mysql_fetch_assoc($res)){	
		if($cont <= 5){
			$cityMayorAudiencia[] = array('id_city' => $fila['id_city'], 'nombre' => substr($fila['nombre'], 0, strpos($fila['nombre'], ',')), 'total_user' => $fila['total_user'], 'total_female' => $fila['total_female'], 'total_male' => $fila['total_male']);
		}else{ break; }
		$cont++;
		
	}
	mysql_data_seek($res, 0);