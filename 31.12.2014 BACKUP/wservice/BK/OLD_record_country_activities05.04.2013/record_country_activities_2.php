<?php
/*********** Parámetros **************/
$time_max_exe = 25200; //7 horas
$num_intentos = 20; //Intentos de llamadas a facebook después de un error
$aux_num_intentos = 0;

//$data_acount_facebook[0] = array('email' => 'jcanesse78@gmail.com', 'pass' => 'canessej86', 'accountId' => '105368146303815');
$data_acount_facebook[0] = array('email' => 'jamendez567@gmail.com', 'pass' => 'ret789lkj741', 'accountId' => '109036285959199');
$data_acount_facebook[1] = array('email' => 'latamowl@gmail.com', 'pass' => 'a$123456', 'accountId' => '108931395970833');

$data_acount_facebook_index = 0;

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

function generaCod() {
	$codigo = "";
	for ($i = 0; $i < 10; $i++) {
		switch (rand(1, 3)) {
			case 1: $codigo.= (string) chr(rand(65, 90));
				break;
			case 2: $codigo.= (string) chr(rand(97, 122));
				break;
			case 3: $codigo.= (string) rand(0, 9);
				break;
		}
	}
	return $codigo;
}

function getAccessToken(){
	global $aux_num_intentos, $num_intentos, $data_acount_facebook, $data_acount_facebook_index;
	$accessToken = "";
	$ban = 0;
	while($ban == 0){
		try{
			
			$post_data['email'] = $data_acount_facebook[$data_acount_facebook_index]['email'];
			$post_data['pass'] = $data_acount_facebook[$data_acount_facebook_index]['pass'];
			
			$name_cook = generaCod();
			
			foreach ( $post_data as $key => $value) {
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_COOKIEJAR, $name_cook);
			curl_setopt($curl, CURLOPT_COOKIEFILE, $name_cook);			
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
	unlink($name_cook);
	return $accessToken;
}


function getNumAudience($code, $f_actividades, $accessToken){
	global $aux_num_intentos, $num_intentos, $data_acount_facebook, $data_acount_facebook_index;
	$numAudience = "";
	$ban = 0;
	while($ban == 0){
		try{
		 	$datos = file_get_contents('https://graph.facebook.com/act_'.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'/reachestimate?endpoint=/act_'.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'/reachestimate&accountId='.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'&locale=es_LA&targeting_spec={"genders":[],"age_max":65,"age_min":13,"broad_age":true,"regions":[],"countries":["'.$code.'"],"cities":[],"zips":[],"radius":0,"keywords":[],"connections":[],"excluded_connections":[],"friends_of_connections":[],"relationship_statuses":null,"interested_in":[],"college_networks":[],"college_majors":[],"college_years":[],"education_statuses":[0],"locales":[],"work_networks":[],"user_adclusters":['.$f_actividades.']}&method=get&access_token='.$accessToken);
			
			echo $datos."<br /><br />";
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


/***************************************** GET POR ACTIVIDADES ********************************************************/
echo '<br>Pea hae: '.$accessToken = getAccessToken();

$query = "SELECT id_country, code FROM country WHERE id_country >= 21 AND id_country <= 40 ORDER BY 1 LIMIT 1;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$sql_value = "";
while($fila = mysql_fetch_assoc($que)){
	$ban = 0;
	while($ban == 0){
		$sql_value = "";
		for($i = 0; $i < count($actividades);$i++){
			$sql_value .= ','.getNumAudience($fila["code"], $actividades[$i]['actividad'], $accessToken);
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
mysql_close($conexion);
/***************************************** FIN GET POR ACTIVIDADES ********************************************************/