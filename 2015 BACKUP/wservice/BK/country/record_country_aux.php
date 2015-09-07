<?php

require_once('../access_token/get_access_token.php');

function get_url_content($url){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
}

/*********** Parámetros **************/
set_time_limit(7200); //2 horas
$max_nun_intentos = 6;

$aux_fecha = "NOW()";
//$aux_fecha = "'2014-08-24'";
/************************************/

$accessToken_data = getAccessToken();
$accessToken_data_index = -1;
$nun_intentos = 0;
$accessToken = NULL;
$accountId = NULL;

function informarErrorActividades(){
	//Enviar aviso por email
	$para = 'mmolinas@latamclick.com';
	$titulo = 'Owloo - Countries';
	$mensaje = 'Se ha detectado un error en la ejecución del script de captura de ranking de países. Favor verificar estado.';
	$cabeceras = 'From: dev@owloo.com' . "\r\n";
	mail($para, $titulo, $mensaje, $cabeceras);
	exit();
}

function informarExito(){
	//Enviar aviso por email
	$para = 'mmolinas@latamclick.com';
	$titulo = 'Owloo - Countries';
	$mensaje = 'El script de captura de ranking de países de ha ejecutado exitosamente!!!';
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

function getNumAudience($code, $gender, $_accessToken, $_accountId){
	$numAudience = "";
	try{
	    $datos = get_url_content('https://graph.facebook.com/act_'.$_accountId.'/reachestimate?access_token='.$_accessToken.'&accountId='.$_accountId.'&currency=USD&endpoint=/act_'.$_accountId.'/reachestimate&locale=es_LA&method=get&pretty=0&targeting_spec={"genders":['.$gender.'],"age_max":65,"age_min":13,"regions":[],"countries":[],"cities":[],"zips":[],"keywords":[],"connections":[],"excluded_connections":[],"friends_of_connections":[],"relationship_statuses":null,"interested_in":[],"college_networks":[],"college_majors":[],"college_years":[],"education_statuses":[],"locales":[],"work_networks":[],"user_adclusters":[],"user_os":[],"user_device":[],"wireless_carrier":[],"work_positions":[],"education_majors":[],"education_schools":[],"work_employers":[],"page_types":null,"geo_locations":{"countries":["'.$code.'"],"cities":[],"regions":[],"zips":[]},"excluded_geo_locations":{"countries":[],"cities":[],"regions":[],"zips":[]}}');
        
		$datosarray2 = json_decode ($datos, true);
        
        //print_r($datosarray2); die();
        
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


/***************************************** GET TOTALES ********************************************************/

nextAccessToken();

$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());

$query = "SELECT id_country, code FROM country WHERE id_country NOT IN (SELECT id_country FROM record_country WHERE date = DATE_FORMAT(".$aux_fecha.", '%Y-%m-%d')) ORDER BY 1"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$sql_value = "";
while($fila = mysql_fetch_assoc($que)){
	$ban = 0;
	while($ban == 0){
		//Audiencia total
		$check_audience = false;
		$numAudience = NULL;
		while(!$check_audience){
			$numAudience = getNumAudience($fila["code"], "", $accessToken, $accountId);
			if(!$numAudience){
				nextAccessToken();
				$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
				mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
			}
			else
				$check_audience = true;
		}
		//Audiencia total mujeres
		$check_audience = false;
		$numAudienceFemale = NULL;
		while(!$check_audience){
			$numAudienceFemale = getNumAudience($fila["code"], "2", $accessToken, $accountId);
			if(!$numAudienceFemale){
				nextAccessToken();
				$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
				mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
			}
			else
				$check_audience = true;
		}
		//Audiencia total hombres
		$check_audience = false;
		$numAudienceMale = NULL;
		while(!$check_audience){
			$numAudienceMale = getNumAudience($fila["code"], "1", $accessToken, $accountId);
			if(!$numAudienceMale){
				nextAccessToken();
				$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
				mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
			}
			else
				$check_audience = true;
		}
		
		$sql_value = "";
		$sql_value .= $numAudience;
		$sql_value .= ','.$numAudienceFemale;
		$sql_value .= ','.$numAudienceMale;
		
		//Insertamos los datos
		$sql = "INSERT INTO record_country(id_country, date, total_user, total_female, total_male) VALUES (" . $fila['id_country'] . ", DATE_FORMAT(".$aux_fecha.",'%Y-%m-%d'), " . $sql_value . ");";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
		$ultimoRegistro = mysql_insert_id();
		
		//VERIFICA SI SE HA INSERTADO CORRECTAMENTE
		$query = "SELECT id_country FROM record_country WHERE id_historial_pais = ".$ultimoRegistro." AND total_user is not null AND total_female is not null AND total_male is not null;"; 
		$que2 = mysql_query($query, $conexion) or die(mysql_error());
		if($fila2 = mysql_fetch_assoc($que2)){	
			$ban = 1;
		}
		else{
			$sql = "DELETE FROM record_country WHERE id_historial_pais = ".$ultimoRegistro.";";
			$res2 = mysql_query($sql, $conexion) or die(mysql_error());
		}
	}
}

/***************************************** FIN GET TOTALES ********************************************************/

/********************************** Verificación de finalización *************************************************/

//Cantidad total de países
$query = "SELECT COUNT(*) cantidad FROM country;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$cantidadPais = 0;
if($fila = mysql_fetch_assoc($que)){
	$cantidadPais = $fila['cantidad'];
}

//Cantidad de filas insertadas
$query = "SELECT COUNT(*) cantidad FROM `record_country` WHERE date = DATE_FORMAT(".$aux_fecha.", '%Y-%m-%d');"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
if($fila = mysql_fetch_assoc($que)){
	if($fila['cantidad'] == $cantidadPais)
		informarExito();
	else
		informarError();
}

/******************************* FIN - Verificación de finalización **********************************************/

mysql_close($conexion);