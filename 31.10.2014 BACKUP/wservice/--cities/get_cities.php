<?php

set_time_limit(0);

function microtime_float(){
	list($useg, $seg) = explode(" ", microtime());
	return ((float)$useg + (float)$seg);
}

$tiempo_inicio = microtime_float();

$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
mysql_query("SET NAMES 'utf8'");


$query =   "SELECT id_country, code, name 
			FROM country 
			ORDER BY 1
			;";
$que = mysql_query($query, $conexion) or die(mysql_error());
while($fila = mysql_fetch_assoc($que)){
	$countryfirtsLetter = strtolower(substr($fila['name'], 0, 1));
	$countryCode = strtolower($fila['code']);
	$idCountry = $fila['id_country'];
	
	$datos = file_get_contents('https://graph.facebook.com/search?q='.$countryfirtsLetter.'&type=adcity&country_list=["'.$countryCode.'"]&limit=100');
	$datosarray = json_decode ($datos, true);
	foreach($datosarray['data'] as $city){
		$sql = "INSERT INTO facebook_city(name, key_city, subtext, id_country) VALUES('".mysql_real_escape_string($city['name'])."',".$city['key'].", '".mysql_real_escape_string($city['subtext'])."', ".$idCountry.");";
		$res = mysql_query($sql, $conexion) or die(mysql_error());
	}
}


echo '<br>Tiempo empleado: '.(microtime_float() - $tiempo_inicio);





