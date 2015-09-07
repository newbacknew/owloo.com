<?php

require_once('access_token/get_access_token.php');

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
	$para = 'hsteiner@latamclick.com, mmolinas@latamclick.com';
	$titulo = 'Owloo - Actividades';
	$mensaje = 'Se ha detectado un error en la ejecución del script de captura de ranking de actividades (1-20). Favor verificar estado.';
	$cabeceras = 'From: owloo@owloo.com' . "\r\n";
	mail($para, $titulo, $mensaje, $cabeceras);
	exit();
}

function informarExito(){
	//Enviar aviso por email
	$para = 'mmolinas@latamclick.com';
	$titulo = 'Owloo - Actividades';
	$mensaje = 'El script de captura de ranking de actividades de ha ejecutado exitosamente!!!';
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

$actividades[] = array('code' => 1, 'actividad' => '{"id":"6002714884772","name":""}');
$actividades[] = array('code' => 2, 'actividad' => '{"id":"6002714884572","name":""}');
$actividades[] = array('code' => 3, 'actividad' => '{"id":"6002714895372","name":""}');
$actividades[] = array('code' => 4, 'actividad' => '{"id":"6002714886772","name":""}');
$actividades[] = array('code' => 5, 'actividad' => '{"id":"6002714888172","name":""}');
$actividades[] = array('code' => 6, 'actividad' => '{"id":"6002714884172","name":""}');
$actividades[] = array('code' => 7, 'actividad' => '{"id":"6002714891172","name":""}');
$actividades[] = array('code' => 8, 'actividad' => '{"id":"6002714885572","name":""}');
$actividades[] = array('code' => 9, 'actividad' => '{"id":"6002714886172","name":""}');
$actividades[] = array('code' => 10, 'actividad' => '{"id":"6002714894572","name":""}');
$actividades[] = array('code' => 11, 'actividad' => '{"id":"6002714887372","name":""}');
$actividades[] = array('code' => 12, 'actividad' => '{"id":"6002714887972","name":""}');
$actividades[] = array('code' => 13, 'actividad' => '{"id":"6002714887572","name":""}');
$actividades[] = array('code' => 14, 'actividad' => '{"id":"6002714888572","name":""}');
$actividades[] = array('code' => 15, 'actividad' => '{"id":"6002714889572","name":""}');
$actividades[] = array('code' => 16, 'actividad' => '{"id":"6002714886372","name":""}');
$actividades[] = array('code' => 17, 'actividad' => '{"id":"6004382299972","name":""}');
$actividades[] = array('code' => 18, 'actividad' => '{"id":"6004386044572","name":""},{"id":"6004386007372","name":""}');
$actividades[] = array('code' => 19, 'actividad' => '{"id":"6004383767972","name":""},{"id":"6004383808772","name":""},{"id":"6004383806772","name":""}');
$actividades[] = array('code' => 20, 'actividad' => '{"id":"6004383941372","name":""},{"id":"6004386303972","name":""},{"id":"6004883585572","name":""}');
$actividades[] = array('code' => 21, 'actividad' => '{"id":"6004383890572","name":""}');
$actividades[] = array('code' => 22, 'actividad' => '{"id":"6004385934572","name":""}');
$actividades[] = array('code' => 23, 'actividad' => '{"id":"6004385895772","name":""}');
$actividades[] = array('code' => 24, 'actividad' => '{"id":"6002714891572","name":""}');
$actividades[] = array('code' => 25, 'actividad' => '{"id":"6002714891972","name":""}');
$actividades[] = array('code' => 26, 'actividad' => '{"id":"6002714892172","name":""}');
$actividades[] = array('code' => 27, 'actividad' => '{"id":"6002714892572","name":""}');
$actividades[] = array('code' => 28, 'actividad' => '{"id":"6002714891772","name":""}');
$actividades[] = array('code' => 29, 'actividad' => '{"id":"6002714893372","name":""}');
$actividades[] = array('code' => 30, 'actividad' => '{"id":"6002714892372","name":""}');
$actividades[] = array('code' => 31, 'actividad' => '{"id":"6002714893572","name":""}');
$actividades[] = array('code' => 32, 'actividad' => '{"id":"6002714894172","name":""}');
$actividades[] = array('code' => 33, 'actividad' => '{"id":"6002714893772","name":""}');
/*************************************/

function getNumAudience($code, $f_actividades, $_accessToken, $_accountId){
	$numAudience = "";
	try{
		$datos = file_get_contents('https://graph.facebook.com/act_'.$_accountId.'/reachestimate?endpoint=/act_'.$_accountId.'/reachestimate&accountId='.$_accountId.'&locale=es_LA&targeting_spec={"genders":[],"age_max":65,"age_min":13,"broad_age":true,"regions":[],"countries":["'.$code.'"],"cities":[],"zips":[],"radius":0,"keywords":[],"connections":[],"excluded_connections":[],"friends_of_connections":[],"relationship_statuses":null,"interested_in":[],"college_networks":[],"college_majors":[],"college_years":[],"education_statuses":[0],"locales":[],"work_networks":[],"user_adclusters":['.$f_actividades.']}&method=get&access_token='.$_accessToken);
		
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

/***************************************** GET POR ACTIVIDADES ********************************************************/

nextAccessToken();

$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());

$query = "SELECT id_country, code FROM country WHERE id_country >= 1 AND id_country <= 20 ORDER BY 1 LIMIT 1;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$sql_value = "";
while($fila = mysql_fetch_assoc($que)){
	$ban = 0;
	while($ban == 0){
		$sql_value = "";
		for($i = 0; $i < count($actividades);$i++){
			$check_audience = false;
			$numAudience = NULL;
			while(!$check_audience){
				$numAudience = getNumAudience($fila["code"], $actividades[$i]['actividad'], $accessToken, $accountId);
				if(!$numAudience){
					nextAccessToken();
$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
}
				else
					$check_audience = true;
			}
			$sql_value .= ','.$numAudience;
		}
		//Insertamos los datos
		$sql = "INSERT INTO record_country_for_user_preference VALUES(null, ".$fila['id_country'].", now() ".$sql_value.");";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
		$ultimoRegistro = mysql_insert_id();
		
		//VERIFICA SI SE HA INSERTADO CORRECTAMENTE
		$query = "SELECT id_user_preference FROM record_country_for_user_preference WHERE id_user_preference = ".$ultimoRegistro." AND category_1 is not null AND category_2 is not null AND category_3 is not null AND category_4 is not null AND category_5 is not null AND category_6 is not null AND category_7 is not null AND category_8 is not null AND category_9 is not null AND category_10 is not null AND category_11 is not null AND category_12 is not null AND category_13 is not null AND category_14 is not null AND category_15 is not null AND category_16 is not null AND category_17 is not null AND category_18 is not null AND category_19 is not null AND category_20 is not null AND category_21 is not null AND category_22 is not null AND category_23 is not null AND category_24 is not null AND category_25 is not null AND category_26 is not null AND category_27 is not null AND category_28 is not null AND category_29 is not null AND category_30 is not null AND category_31 is not null AND category_32 is not null AND category_33 is not null;"; 
		$que2 = mysql_query($query, $conexion) or die(mysql_error());
		if($fila2 = mysql_fetch_assoc($que2)){	
			$ban = 1;
		}
		else{
			$sql = "DELETE FROM record_country_for_user_preference WHERE id = ".$ultimoRegistro.";";
			$res2 = mysql_query($sql, $conexion) or die(mysql_error());
		}
	}
}



/********************************** Verificación de finalización *************************************************/

//Cantidad total de países
$query = "SELECT COUNT(*) cantidad FROM country;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$cantidadPais = 0;
if($fila = mysql_fetch_assoc($que)){
	$cantidadPais = $fila['cantidad'];
}

//Cantidad de filas insertadas
$query = "SELECT COUNT(*) cantidad FROM `record_country_for_user_preference` WHERE date = DATE_FORMAT(now(), '%Y-%m-%d');"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
if($fila = mysql_fetch_assoc($que)){
	if($fila['cantidad'] == $cantidadPais)
		informarExito();
}

/******************************* FIN - Verificación de finalización **********************************************/

mysql_close($conexion);
/***************************************** FIN GET POR ACTIVIDADES ********************************************************/