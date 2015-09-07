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
	$para = 'mmolinas@latamclick.com';
	$titulo = 'Owloo - Edades, idiomas y relaciones';
	$mensaje = 'Se ha detectado un error en la ejecución del script de captura de ranking de edades, idiomas y relaciones (81-100). Favor verificar estado.';
	$cabeceras = 'From: owloo@owloo.com' . "\r\n";
	mail($para, $titulo, $mensaje, $cabeceras);
	exit();
}

function informarExito(){
	//Enviar aviso por email
	$para = 'mmolinas@latamclick.com';
	$titulo = 'Owloo - Edades, idiomas y relaciones';
	$mensaje = 'El script de captura de ranking de edades, idiomas y relaciones se ha ejecutado exitosamente!!!';
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

$rangoEdades[] = array('min' => 13, 'max' => 15);
$rangoEdades[] = array('min' => 16, 'max' => 17);
$rangoEdades[] = array('min' => 18, 'max' => 28);
$rangoEdades[] = array('min' => 29, 'max' => 34);
$rangoEdades[] = array('min' => 35, 'max' => 44);
$rangoEdades[] = array('min' => 45, 'max' => 54);
$rangoEdades[] = array('min' => 55, 'max' => 64);
$rangoEdades[] = array('min' => 65, 'max' => 65);

$idiomas[] = array('name' => 'Español (todos)', 'code' => '"1002"');
$idiomas[] = array('name' => 'English (all)', 'code' => '"1001"');
$idiomas[] = array('name' => 'Chino (Todo)', 'code' => '"1004"');
$idiomas[] = array('name' => 'Portugués (Todo)', 'code' => '"1005"');
$idiomas[] = array('name' => 'Hindi', 'code' => '"46"');
$idiomas[] = array('name' => 'Francés (Todo)', 'code' => '"1003"');
$idiomas[] = array('name' => 'Alemán', 'code' => '"5"');
$idiomas[] = array('name' => 'Italiano', 'code' => '"10"');
$idiomas[] = array('name' => 'Ruso', 'code' => '"17"');
$idiomas[] = array('name' => 'Japonés', 'code' => '"11"');
$idiomas[] = array('name' => 'Coreano', 'code' => '"12"');
$idiomas[] = array('name' => 'Holandés', 'code' => '"14"');
$idiomas[] = array('name' => 'Árabe', 'code' => '"28"');
$idiomas[] = array('name' => 'Bengalí', 'code' => '"45"');
$idiomas[] = array('name' => 'Turco', 'code' => '"19"');
$idiomas[] = array('name' => 'Malayo', 'code' => '"41"');
$idiomas[] = array('name' => 'Polaco', 'code' => '"15"');
$idiomas[] = array('name' => 'Indonesio', 'code' => '"25"');
$idiomas[] = array('name' => 'Filipino', 'code' => '"26"');
$idiomas[] = array('name' => 'Tailandés', 'code' => '"35"');
$idiomas[] = array('name' => 'Vietnamita', 'code' => '"27"');

$relaciones[] = array('name' => 'Soltero', 'code' => '[1]');
$relaciones[] = array('name' => 'Tiene relación', 'code' => '[2]');
$relaciones[] = array('name' => 'Casado', 'code' => '[3]');
$relaciones[] = array('name' => 'Comprometido', 'code' => '[4]');

$educacion[] = array('name' => 'En la escuela secundaria', 'code' => '1');
$educacion[] = array('name' => 'En la universidad', 'code' => '2');
$educacion[] = array('name' => 'Con estudios universitarios', 'code' => '3');


/*************************************/

function getNumAudience($code, $gender, $minage, $maxage, $relacion, $locales, $broad_age, $education, $_accessToken, $_accountId){
	$numAudience = "";
	try{
		$datos = file_get_contents('https://graph.facebook.com/act_'.$_accountId.'/reachestimate?access_token='.$_accessToken.'&accountId='.$_accountId.'&currency=USD&endpoint=/act_'.$_accountId.'/reachestimate&locale=es_LA&method=get&pretty=0&targeting_spec={"genders":['.$gender.'],"age_max":'.$maxage.',"age_min":'.$minage.',"regions":[],"countries":[],"cities":[],"zips":[],"keywords":[],"connections":[],"excluded_connections":[],"friends_of_connections":[],"relationship_statuses":'.$relacion.',"interested_in":[],"college_networks":[],"college_majors":[],"college_years":[],"education_statuses":['.$education.'],"locales":['.$locales.'],"work_networks":[],"user_adclusters":[],"user_os":[],"user_device":[],"wireless_carrier":[],"work_positions":[],"education_majors":[],"education_schools":[],"work_employers":[],"geo_locations":{"countries":["'.$code.'"],"cities":[],"regions":[],"zips":[]},"excluded_geo_locations":{"countries":[],"cities":[],"regions":[],"zips":[]}}');
        
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


/***************************************** GET POR EDADES ********************************************************/
	
nextAccessToken();

$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());

$query = "SELECT id_country, code FROM country WHERE id_country >= 81 AND id_country <= 100 ORDER BY 1;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$sql_value = "";
while($fila = mysql_fetch_assoc($que)){
	$ban = 0;
	while($ban == 0){
		$sql_value = "";
		//Edades
		for($i = 0; $i < count($rangoEdades);$i++){
			$check_audience = false;
			$numAudience = NULL;
			while(!$check_audience){
				$numAudience = getNumAudience($fila["code"], "", $rangoEdades[$i]['min'], $rangoEdades[$i]['max'], 'null', '', 'false', '0', $accessToken, $accountId);
				if(!$numAudience){
					nextAccessToken();
					$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
					mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
				}
				else
					$check_audience = true;
			}
			$sql_value .= $numAudience.',';
		}
		//Lenguajes
		for($i = 0; $i < count($idiomas);$i++){
			$check_audience = false;
			$numAudience = NULL;
			while(!$check_audience){
				$numAudience = getNumAudience($fila["code"], "", '13', '65', 'null', $idiomas[$i]['code'], 'true', '0', $accessToken, $accountId);
				if(!$numAudience){
					nextAccessToken();
					$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
					mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
				}
				else
					$check_audience = true;
			}
			$sql_value .= $numAudience.',';
		}
		//Relaciones
		for($i = 0; $i < count($relaciones);$i++){
			$check_audience = false;
			$numAudience = NULL;
			while(!$check_audience){
				$numAudience = getNumAudience($fila["code"], "", '13', '65', $relaciones[$i]['code'], '', 'true', '0', $accessToken, $accountId);
				if(!$numAudience){
					nextAccessToken();
					$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
					mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
				}
				else
					$check_audience = true;
			}
			$sql_value .= $numAudience.',';
		}
		//Educación
		for($i = 0; $i < count($educacion);$i++){
			$check_audience = false;
			$numAudience = NULL;
			while(!$check_audience){
				$numAudience = getNumAudience($fila["code"], "", '13', '65', 'null', '', 'true', $educacion[$i]['code'], $accessToken, $accountId);
				if(!$numAudience){
					nextAccessToken();
					$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
					mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());
				}
				else
					$check_audience = true;
			}
			$sql_value .= $numAudience.',';
		}
		
		//Insertamos los datos
		$sql = "INSERT INTO record_country_for_age_language VALUES(null, ".$fila['id_country'].", ".$sql_value." now());";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
		$ultimoRegistro = mysql_insert_id();
		
		$query = "SELECT id FROM record_country_for_age_language WHERE id = ".$ultimoRegistro."
		 AND rango_13_15 is not null
		 AND rango_16_17 is not null
		 AND rango_18_28 is not null
		 AND rango_29_34 is not null
		 AND rango_35_44 is not null
		 AND rango_45_54 is not null
		 AND rango_55_64 is not null
		 AND rango_65_65 is not null
		 AND language_spanish is not null
		 AND language_english is not null
		 AND language_chinese is not null
		 AND language_portuguese is not null
		 AND language_hindi is not null
		 AND language_frances is not null
		 AND language_aleman is not null
		 AND language_italiano is not null
		 AND language_ruso is not null
		 AND language_japones is not null
		 AND language_coreano is not null
		 AND language_holandes is not null
		 AND language_arabe is not null
		 AND language_bengali is not null
		 AND language_turco is not null
		 AND language_malayo is not null
		 AND language_polaco is not null
		 AND language_indonesio is not null
		 AND language_filipino is not null
		 AND language_tailandes is not null
		 AND language_vietnamita is not null
		 AND relationship_single is not null
		 AND relationship_has_a_relationship is not null
		 AND relationship_married is not null
		 AND relationship_comprometido is not null
		 AND education_en_la_escuela_secundaria is not null
		 AND education_en_la_universidad is not null
		 AND education_con_estudios_universitarios is not null;"; 
		$que2 = mysql_query($query, $conexion) or die(mysql_error());
		if($fila2 = mysql_fetch_assoc($que2)){	
			$ban = 1;
		}
		else{
			$sql = "DELETE FROM record_country_for_age_language WHERE id = ".$ultimoRegistro.";";
			$res2 = mysql_query($sql, $conexion) or die(mysql_error());
		}
	}
}

/***************************************** FIN GET POR EDADES ********************************************************/

/********************************** Verificación de finalización *************************************************/

//Cantidad total de países
$query = "SELECT COUNT(*) cantidad FROM country;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$cantidadPais = 0;
if($fila = mysql_fetch_assoc($que)){
	$cantidadPais = $fila['cantidad'];
}

//Cantidad de filas insertadas
$query = "SELECT COUNT(*) cantidad FROM `record_country_for_age_language` WHERE date = DATE_FORMAT(now(), '%Y-%m-%d');"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
if($fila = mysql_fetch_assoc($que)){
	if($fila['cantidad'] == $cantidadPais)
		informarExito();
}

/******************************* FIN - Verificación de finalización **********************************************/

mysql_close($conexion);