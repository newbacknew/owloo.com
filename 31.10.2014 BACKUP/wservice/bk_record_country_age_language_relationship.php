<?php
/*********** Parámetros **************/
$time_max_exe = 25200; //7 horas
$num_intentos = 20; //Intentos de llamadas a facebook después de un error
$aux_num_intentos = 0;

//$data_acount_facebook[0] = array('email' => 'jcanesse78@gmail.com', 'pass' => 'canessej86', 'accountId' => '105368146303815');
$data_acount_facebook[0] = array('email' => 'jamendez567@gmail.com', 'pass' => 'ret789lkj741', 'accountId' => '109036285959199');
$data_acount_facebook[1] = array('email' => 'latamowl@gmail.com', 'pass' => 'a$123456', 'accountId' => '108931395970833');

$data_acount_facebook_index = 0;

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

$relaciones[] = array('name' => 'Soltero', 'code' => '[1]');
$relaciones[] = array('name' => 'Tiene relación', 'code' => '[2]');
$relaciones[] = array('name' => 'Casado', 'code' => '[3]');

/*************************************/

set_time_limit($time_max_exe);

$conexion = mysql_connect("localhost", "owloo_admin", "fblatamx244") or die(mysql_error());
mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());

function informarError(){
	//Enviar aviso por email
	$para = 'hsteiner@latamclick.com, mmolinas@latamclick.com';
	$titulo = 'Owloo - Countries';
	$mensaje = 'Se ha detectado un error en la ejecución del script de captura de ranking de países por edades, lenguajes y relaciones. Favor verificar estado.';
	$cabeceras = 'From: owloo@owloo.com' . "\r\n";
	mail($para, $titulo, $mensaje, $cabeceras);
	
	exit();
}

function getAccessToken(){
	global $aux_num_intentos, $num_intentos, $data_acount_facebook, $data_acount_facebook_index;
	$accessToken = "";
	$ban = 0;
	while($ban == 0){
		try{
			
			$post_data['email'] = $data_acount_facebook[$data_acount_facebook_index]['email'];
			$post_data['pass'] = $data_acount_facebook[$data_acount_facebook_index]['pass'];
			
			foreach ( $post_data as $key => $value) {
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_COOKIEJAR, 'cook.txt');
			curl_setopt($curl, CURLOPT_COOKIEFILE, 'cook.txt');			
			curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');			
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
	unlink('cook.txt');
	return $accessToken;
}

function getNumAudience($code, $gender, $minage, $maxage, $relacion, $locales, $broad_age, $accessToken){
	global $aux_num_intentos, $num_intentos, $data_acount_facebook, $data_acount_facebook_index;
	$numAudience = "";
	$ban = 0;
	while($ban == 0){
		try{
			echo $datos = file_get_contents('https://graph.facebook.com/act_'.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'/reachestimate?endpoint=/act_'.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'/reachestimate&accountId='.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'&locale=es_LA&targeting_spec={"genders":['.$gender.'],"age_max":'.$maxage.',"age_min":'.$minage.',"broad_age":'.$broad_age.',"regions":[],"countries":["'.$code.'"],"cities":[],"zips":[],"radius":0,"keywords":[],"connections":[],"excluded_connections":[],"friends_of_connections":[],"relationship_statuses":'.$relacion.',"interested_in":[],"college_networks":[],"college_majors":[],"college_years":[],"education_statuses":[0],"locales":['.$locales.'],"work_networks":[],"user_adclusters":[]}&method=get&access_token='.$accessToken);
			
			//echo $datos."<br /><br />";
			echo "<br /><br />";
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


/***************************************** GET POR EDADES ********************************************************/
	
$accessToken = getAccessToken();

$query = "SELECT id_country, code FROM country ORDER BY 1;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$sql_value = "";
while($fila = mysql_fetch_assoc($que)){
	$ban = 0;
	while($ban == 0){
		$sql_value = "";
		//Edades
		for($i = 0; $i < count($rangoEdades);$i++){
			$sql_value .= getNumAudience($fila["code"], "", $rangoEdades[$i]['min'], $rangoEdades[$i]['max'], 'null', '', 'false', $accessToken).',';
		}
		//Lenguajes
		for($i = 0; $i < count($idiomas);$i++){
			$sql_value .= getNumAudience($fila["code"], "", '13', '65', 'null', $idiomas[$i]['code'], 'true', $accessToken).',';
		}
		//Relaciones
		for($i = 0; $i < count($relaciones);$i++){
			$sql_value .= getNumAudience($fila["code"], "", '13', '65', $relaciones[$i]['code'], '', 'true', $accessToken).',';
		}
		
		//Insertamos los datos
		$sql = "INSERT INTO record_country_for_age_language VALUES(null, ".$fila['id_country'].", ".$sql_value." now());";
		$res2 = mysql_query($sql, $conexion) or die(mysql_error());
		$ultimoRegistro = mysql_insert_id();
		
		$query = "SELECT id FROM record_country_for_age_language WHERE id = ".$ultimoRegistro." AND rango_13_15 is not null AND rango_16_17 is not null AND rango_18_28 is not null AND rango_29_34 is not null AND rango_35_44 is not null AND rango_45_54 is not null AND rango_55_64 is not null AND rango_65_65 is not null AND language_spanish is not null AND language_english is not null AND language_chinese is not null AND language_portuguese is not null AND language_hindi is not null AND relationship_single is not null AND relationship_has_a_relationship is not null AND relationship_married is not null;"; 
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


mysql_close($conexion);