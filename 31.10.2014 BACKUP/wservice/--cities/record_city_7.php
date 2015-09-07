<?php

require_once('../access_token/get_access_token.php');

/*********** Parámetros **************/
set_time_limit(18000); //5 horas
$max_nun_intentos = 6;
/************************************/

$accessToken_data = getAccessToken();
$accessToken_data_index = -1;
$nun_intentos = 0;
$accessToken = NULL;
$accountId = NULL;

function informarErrorActividades(){
	//Enviar aviso por email
	$para = 'mmolinas@latamclick.com';
	$titulo = 'Owloo - Ciudades';
	$mensaje = 'Se ha detectado un error en la ejecución del script de captura de ranking de ciudades (121-140). Favor verificar estado.';
	$cabeceras = 'From: owloo@owloo.com' . "\r\n";
	mail($para, $titulo, $mensaje, $cabeceras);
	exit();
}

function informarExito(){
	//Enviar aviso por email
	$para = 'mmolinas@latamclick.com';
	$titulo = 'Owloo - Ciudades';
	$mensaje = 'El script de captura de ranking de ciudades se ha ejecutado exitosamente!!!';
	$cabeceras = 'From: dev@owloo.com' . "\r\n";
	mail($para, $titulo, $mensaje, $cabeceras);
}

function nextAccessToken(){
	global $accessToken_data, $accessToken_data_index, $accessToken, $accountId, $nun_intentos, $max_nun_intentos;
	$accessToken_data_index++;
	if($accessToken_data_index < count($accessToken_data)){
		$accessToken = $accessToken_data[$accessToken_data_index]['access_token'];
		$accountId = $accessToken_data[$accessToken_data_index]['accountId'];
	}
	else{
		$nun_intentos++;
		if($nun_intentos < $max_nun_intentos){
			setAccessToken();
			$accessToken_data = getAccessToken();
			$accessToken_data_index = -1;
			nextAccessToken();
		}
		else{
			informarErrorActividades();
		}
	}
}

function getNumAudience($countryCode, $gender, $cityCode, $nameCity, $_accessToken, $_accountId){
    $numAudience = "";
    try{            
        $datos = file_get_contents('https://graph.facebook.com/act_'.$_accountId.'/reachestimate?access_token='.$_accessToken.'&accountId='.$_accountId.'&currency=USD&endpoint=/act_'.$_accountId.'/reachestimate&locale=es_LA&method=get&pretty=0&targeting_spec={"genders":['.$gender.'],"age_max":65,"age_min":13,"regions":[],"countries":[],"cities":[],"zips":[],"keywords":[],"connections":[],"excluded_connections":[],"friends_of_connections":[],"relationship_statuses":null,"interested_in":[],"college_networks":[],"college_majors":[],"college_years":[],"education_statuses":[],"locales":[],"work_networks":[],"user_adclusters":[],"user_os":[],"user_device":[],"wireless_carrier":[],"education_majors":[],"education_schools":[],"work_employers":[],"geo_locations":{"countries":[],"cities":[{"key":"'.$cityCode.'","radius":20,"distance_unit":"kilometer"}],"regions":[],"zips":[]},"excluded_geo_locations":{"countries":[],"cities":[],"regions":[],"zips":[]}}');
        
        $datosarray2 = json_decode ($datos, true);
		$numAudience = $datosarray2['users'];
		
		if($numAudience != "" && is_numeric($numAudience)){
			return $numAudience;
		}
	}
	catch(Exception $e) {
		return false;
	}
	return false;
}


/***************************************** GET POR CIUDADES ********************************************************/

nextAccessToken();

$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());

$query = "SELECT id_city, fc.name name, key_city, code FROM facebook_city fc JOIN country c ON fc.id_country = c.id_country WHERE fc.id_country >= 121 AND fc.id_country <= 140 order by fc.id_country, id_city;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$sql_value = "";
while($fila = mysql_fetch_assoc($que)){
	$ban = 0;
	while($ban == 0){
		$sql_value = "";
		
		$check_audience = false;
		$numAudience = NULL;
		while(!$check_audience){
			$numAudience = getNumAudience($fila["code"], "", $fila["key_city"], str_replace(' ', '%20', $fila["name"]), $accessToken, $accountId);
			if(!$numAudience){
				nextAccessToken();
				$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
				mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
			}
			else
				$check_audience = true;
		}
		$sql_value .= $numAudience;
		
		$check_audience = false;
		$numAudience = NULL;
		while(!$check_audience){
			$numAudience = getNumAudience($fila["code"], "2", $fila["key_city"], str_replace(' ', '%20', $fila["name"]), $accessToken, $accountId);
			if(!$numAudience){
				nextAccessToken();
				$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
				mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
			}
			else
				$check_audience = true;
		}
		$sql_value .= ','.$numAudience;
		
		$check_audience = false;
		$numAudience = NULL;
		while(!$check_audience){
			$numAudience = getNumAudience($fila["code"], "1", $fila["key_city"], str_replace(' ', '%20', $fila["name"]), $accessToken, $accountId);
			if(!$numAudience){
				nextAccessToken();
				$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
				mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
			}
			else
				$check_audience = true;
		}
		$sql_value .= ','.$numAudience;
		
		//Insertamos los datos
		$sql = "INSERT INTO record_city(id_city, date, total_user, total_female, total_male) VALUES (" . $fila['id_city'] . ", DATE_FORMAT(now(),'%Y-%m-%d'), " . $sql_value . ");";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
		$ultimoRegistro = mysql_insert_id();
		
		//VERIFICA SI SE HA INSERTADO CORRECTAMENTE
		$query = "SELECT id_city FROM record_city WHERE id_historial_city = ".$ultimoRegistro." AND total_user is not null AND total_female is not null AND total_male is not null;"; 
		$que2 = mysql_query($query, $conexion) or die(mysql_error());
		if($fila2 = mysql_fetch_assoc($que2)){	
			$ban = 1;
		}
		else{
			$sql = "DELETE FROM record_city WHERE id_historial_city = ".$ultimoRegistro.";";
			$res2 = mysql_query($sql, $conexion) or die(mysql_error());
		}
	}
}

/********************************** Verificación de finalización *************************************************/

//Cantidad total de países
$query = "SELECT COUNT(*) cantidad FROM facebook_city;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$cantidadPais = 0;
if($fila = mysql_fetch_assoc($que)){
	$cantidadPais = $fila['cantidad'];
}

//Cantidad de filas insertadas
$query = "SELECT COUNT(*) cantidad FROM record_city WHERE date = DATE_FORMAT(now(), '%Y-%m-%d');"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
if($fila = mysql_fetch_assoc($que)){
	if($fila['cantidad'] == $cantidadPais)
		informarExito();
}

/******************************* FIN - Verificación de finalización **********************************************/

mysql_close($conexion);
/***************************************** FIN GET POR ACTIVIDADES ********************************************************/