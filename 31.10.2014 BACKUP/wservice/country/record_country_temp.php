<?php
/*********** Parámetros **************/
$time_max_exe = 7200; //2 horas
$num_intentos = 20; //Intentos de llamadas a facebook después de un error
$aux_num_intentos = 0;

//$data_acount_facebook[0] = array('email' => 'jcanesse78@gmail.com', 'pass' => 'canessej86', 'accountId' => '105368146303815');
$data_acount_facebook[0] = array('email' => 'jamendez567@gmail.com', 'pass' => 'ret789lkj741', 'accountId' => '109036285959199');
$data_acount_facebook[1] = array('email' => 'latamowl@gmail.com', 'pass' => 'a$123456', 'accountId' => '108931395970833');

$data_acount_facebook_index = 0;

/*************************************/

set_time_limit($time_max_exe);

$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
mysql_select_db("owloo_dev", $conexion) or die(mysql_error());

function informarError(){
	//Enviar aviso por email
	$para = 'hsteiner@latamclick.com, mmolinas@latamclick.com';
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

function getAccessToken(){
	return 'AAACZBzNhafycBAJm0leO4EZC8y5DZCVbZC1NsXeR8awHJDSX1KSrIUPJIcZAdJ1jurM7c5LIusYLEei7hz4j0vDUZBtgZAqtl1am0Sfke6L8oM2g53cHIe3';
}

function getNumAudience($code, $gender, $accessToken){
	global $aux_num_intentos, $num_intentos, $data_acount_facebook, $data_acount_facebook_index;
	$numAudience = "";
	$ban = 0;
	while($ban == 0){
		try{
			$datos = file_get_contents('https://graph.facebook.com/act_'.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'/reachestimate?endpoint=/act_'.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'/reachestimate&accountId='.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'&locale=es_LA&targeting_spec={"genders":['.$gender.'],"age_max":65,"age_min":13,"broad_age":true,"regions":[],"countries":["'.$code.'"],"cities":[],"zips":[],"radius":0,"keywords":[],"connections":[],"excluded_connections":[],"friends_of_connections":[],"relationship_statuses":null,"interested_in":[],"college_networks":[],"college_majors":[],"college_years":[],"education_statuses":[0],"locales":[],"work_networks":[],"user_adclusters":[]}&method=get&access_token='.$accessToken);
			$datosarray2 = json_decode ($datos, true);
			$numAudience = $datosarray2['users'];
			
			if($numAudience != "" && is_numeric($numAudience)){
				$ban = 1;
			}
			else{
				sleep(3); //Espera 3 minutos
				$accessToken = getAccessToken(); //Trata de obtener un nuevo access_token
			}
		}
		catch(Exception $e) {
			if($aux_num_intentos++ > $num_intentos){
				informarError();
			}
			
			sleep(3); //Espera 3 minutos
			$accessToken = getAccessToken(); //Trata de obtener un nuevo access_token
		}
	}
	return $numAudience;
}


/***************************************** GET TOTALES ********************************************************/

$accessToken = getAccessToken();

$query = "SELECT id_country, code FROM country ORDER BY 1;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$sql_value = "";
while($fila = mysql_fetch_assoc($que)){
	$ban = 0;
	while($ban == 0){
		$sql_value = "";
		$sql_value .= getNumAudience($fila["code"], "", $accessToken);
		$sql_value .= ','.getNumAudience($fila["code"], "2", $accessToken);
		$sql_value .= ','.getNumAudience($fila["code"], "1", $accessToken);
		
		//Insertamos los datos
		$sql = "INSERT INTO record_country(id_country, date, total_user, total_female, total_male) VALUES (" . $fila['id_country'] . ", DATE_FORMAT(now(),'%Y-%m-%d'), " . $sql_value . ");";
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
$query = "SELECT COUNT(*) cantidad FROM `record_country` WHERE date = DATE_FORMAT(now(), '%Y-%m-%d');"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
if($fila = mysql_fetch_assoc($que)){
	if($fila['cantidad'] == $cantidadPais)
		informarExito();
	else
		informarError();
}

/******************************* FIN - Verificación de finalización **********************************************/

mysql_close($conexion);