<?php
/*********** Parámetros **************/
$time_max_exe = 7200; //2 horas
$num_intentos = 20; //Intentos de llamadas a facebook después de un error
$aux_num_intentos = 0;
/*************************************/

set_time_limit($time_max_exe);

$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());

function informarError(){
	//Enviar aviso por email
	$para = 'hsteiner@latamclick.com, mmolinas@latamclick.com';
	$titulo = 'Owloo - Countries';
	$mensaje = 'Se ha detectado un error en la ejecución del script de captura de ranking de países. Favor verificar estado.';
	$cabeceras = 'From: owloo@owloo.com' . "\r\n";
	mail($para, $titulo, $mensaje, $cabeceras);
	
	exit();
}

function getAccessToken(){
	global $aux_num_intentos, $num_intentos;
	$accessToken = "";
	$ban = 0;
	while($ban == 0){
		try{
			$post_data['email'] = 'jcanesse78@gmail.com';
			$post_data['pass'] = 'canessej86';
			foreach ( $post_data as $key => $value) {
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_COOKIEJAR, 'cook.txt');
			curl_setopt($curl, CURLOPT_COOKIEFILE, 'cook.txt');
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
			curl_setopt($curl, CURLOPT_FAILONERROR, 1);
			curl_setopt($curl, CURLOPT_REFERER, 'https://www.facebook.com/login.php?next=https://www.facebook.com/ads/manage/adscreator/');
			//curl_setopt($curl,    CURLOPT_COOKIESESSION,         true);
			curl_setopt($curl, CURLOPT_TIMEOUT, 3);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_string);
			curl_setopt($curl, CURLOPT_HEADER, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
			//$html = curl_exec($curl);
			curl_setopt($curl, CURLOPT_URL, 'https://www.facebook.com/login.php?next=https://www.facebook.com/ads/manage/adscreator/');
			$html2 = curl_exec($curl);
			$doc= new DOMDocument();
			$doc->loadHTML($html2); // result from curl
			$xpath= new DOMXPath($doc);
			
			$accessToken =  substr(json_encode($xpath->query('//body')->item(0)->nodeValue), strpos(json_encode($xpath->query('//body')->item(0)->nodeValue), 'access_token') + 17, 200);
			$accessToken = explode("\\", $accessToken);
			$accessToken = $accessToken[0];
			curl_close($curl);
			if($accessToken != "" && !strpos($accessToken, ' '))
				$ban = 1;
		}
		catch(Exception $e) {
			if($aux_num_intentos++ > $num_intentos){
				informarError();
			}
			
			sleep(3); //Espera 3 minutos para volver a intentarlo
		}
	}
	
	return $accessToken;
}

function getNumAudience($code, $gender, $accessToken){
	global $aux_num_intentos, $num_intentos;
	$numAudience = "";
	$ban = 0;
	while($ban == 0){
		try{
			$datos = file_get_contents('https://graph.facebook.com/act_105368146303815/reachestimate?endpoint=/act_105368146303815/reachestimate&accountId=105368146303815&locale=es_LA&targeting_spec={"genders":['.$gender.'],"age_max":65,"age_min":13,"broad_age":true,"regions":[],"countries":["'.$code.'"],"cities":[],"zips":[],"radius":0,"keywords":[],"connections":[],"excluded_connections":[{"id":"532655710080087","name":"Seee"}],"friends_of_connections":[],"relationship_statuses":null,"interested_in":[],"college_networks":[],"college_majors":[],"college_years":[],"education_statuses":[0],"locales":[],"work_networks":[],"user_adclusters":[]}&method=get&access_token='.$accessToken);
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
$ban = 0;
while($ban == 0){
	
	$accessToken = getAccessToken();
	
	$query = "SELECT id_country, code FROM country;"; 
	$que = mysql_query($query, $conexion) or die(mysql_error());
	while($fila = mysql_fetch_assoc($que)){	
		$num = getNumAudience($fila["code"], "", $accessToken);
		$sql = "INSERT INTO record_country(id_country, date, total_user) VALUES (" . $fila['id_country'] . ", DATE_FORMAT(now(),'%Y-%m-%d'), " . $num . ");";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
	}
	
	//VERIFICA SI SE HA INSERTADO CORRECTAMENTE
	$num_register = 0;
	$num_country = 0;
	
	$query = "SELECT count(*) num_register FROM record_country WHERE date = DATE_FORMAT(now(),'%Y-%m-%d') AND total_user is not null;"; 
	$que = mysql_query($query, $conexion) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){	
		$num_register = $fila['num_register'];
	}
	else{ $ban = 0; }
	
	$query = "SELECT count(*) num_country FROM country;"; 
	$que = mysql_query($query, $conexion) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){	
		$num_country = $fila['num_country'];
	}
	else{ $ban = 0; }
	
	if($num_register == $num_country) $ban = 1;
	
	if($ban == 0){
		$sql = "DELETE FROM record_country WHERE date = DATE_FORMAT(now(),'%Y-%m-%d');";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
	}
}

/***************************************** FIN GET TOTALES ********************************************************/

sleep(5);

/***************************************** GET MUJERES ********************************************************/
$ban = 0;

while($ban == 0){
	
	$accessToken = getAccessToken();
	
	$query = "SELECT id_country, code FROM country;"; 
	$que = mysql_query($query, $conexion) or die(mysql_error());
	while($fila = mysql_fetch_assoc($que)){	
		$num = getNumAudience($fila["code"], "2", $accessToken);
				
		$sql = "UPDATE record_country SET total_female = " . $num . " WHERE id_country = ".$fila['id_country']." AND date = DATE_FORMAT(now(),'%Y-%m-%d');";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
	}
	
	//VERIFICA
	$num_register = 0;
	$num_country = 0;
	
	$query = "SELECT count(*) num_register FROM record_country WHERE date = DATE_FORMAT(now(),'%Y-%m-%d') AND total_female is not null;"; 
	$que = mysql_query($query, $conexion) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){	
		$num_register = $fila['num_register'];
	}
	else{ $ban = 0; }
	
	$query = "SELECT count(*) num_country FROM country;"; 
	$que = mysql_query($query, $conexion) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){	
		$num_country = $fila['num_country'];
	}
	else{ $ban = 0; }
	
	if($num_register == $num_country) $ban = 1;
	
	if($ban == 0){
		$sql = "UPDATE record_country SET total_female = NULL WHERE date = DATE_FORMAT(now(),'%Y-%m-%d');";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
	}
}
/***************************************** FIN GET MUJERES ********************************************************/

sleep(5);

/***************************************** GET HOMBRES ********************************************************/
$ban = 0;

while($ban == 0){
	
	$accessToken = getAccessToken();
	
	$query = "SELECT id_country, code FROM country;"; 
	$que = mysql_query($query, $conexion) or die(mysql_error());
	while($fila = mysql_fetch_assoc($que)){	
		
		$num = getNumAudience($fila["code"], "1", $accessToken);
			
		$sql = "UPDATE record_country SET total_male = " . $num . " WHERE id_country = ".$fila['id_country']." AND date = DATE_FORMAT(now(),'%Y-%m-%d');";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
	}
	
//VERIFICA
	$num_register = 0;
	$num_country = 0;
	
	$query = "SELECT count(*) num_register FROM record_country WHERE date = DATE_FORMAT(now(),'%Y-%m-%d') AND total_male is not null;"; 
	$que = mysql_query($query, $conexion) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){	
		$num_register = $fila['num_register'];
	}
	else{ $ban = 0; }
	
	$query = "SELECT count(*) num_country FROM country;"; 
	$que = mysql_query($query, $conexion) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){	
		$num_country = $fila['num_country'];
	}
	else{ $ban = 0; }
	
	if($num_register == $num_country) $ban = 1;
	
	if($ban == 0){
		$sql = "UPDATE record_country SET total_male = NULL WHERE date = DATE_FORMAT(now(),'%Y-%m-%d');";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
	}
}
/***************************************** FIN GET HOMBRES ********************************************************/

mysql_close($conexion);