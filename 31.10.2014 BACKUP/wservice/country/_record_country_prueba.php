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
mysql_select_db("owloo_owloo", $conexion) or die(mysql_error());

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
	global $aux_num_intentos, $num_intentos, $data_acount_facebook, $data_acount_facebook_index;
	$accessToken = "";
	$ban = 0;
	while($ban == 0){
		try{
			
			$post_data['email'] = 'jamendez567@gmail.com';
			$post_data['pass'] = 'ret789lkj741';
			
			
			
			foreach ( $post_data as $key => $value) {
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);
			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_COOKIEJAR, 'cook.txt');
			curl_setopt($curl, CURLOPT_COOKIEFILE, 'cook.txt');
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
			curl_setopt($curl, CURLOPT_FAILONERROR, 1);
			curl_setopt($curl, CURLOPT_REFERER, 'https://www.facebook.com/login.php?next=https://www.facebook.com/ads/create/');
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
			curl_setopt($curl, CURLOPT_URL, 'https://www.facebook.com/login.php?next=https://www.facebook.com/ads/create/');
			$html2 = curl_exec($curl);
			
			
			
			
			echo $html2;
			die();
			
			
			
			$doc= new DOMDocument();
			$doc->loadHTML($html2); // result from curl
			$xpath= new DOMXPath($doc);
			
			$accessToken =  substr(json_encode($xpath->query('//body')->item(0)->nodeValue), strpos(json_encode($xpath->query('//body')->item(0)->nodeValue), 'access_token') + 17, 200);
			
			echo '<br><br>AT: '.$accessToken.'<br><br>';
			
			
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
	global $aux_num_intentos, $num_intentos, $data_acount_facebook, $data_acount_facebook_index;
	$numAudience = "";
	$ban = 0;
	while($ban == 0){
		try{
			$datos = file_get_contents('https://graph.facebook.com/act_'.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'/reachestimate?endpoint=/act_'.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'/reachestimate&accountId='.$data_acount_facebook[$data_acount_facebook_index]['accountId'].'&locale=es_LA&targeting_spec={"genders":['.$gender.'],"age_max":65,"age_min":13,"broad_age":true,"regions":[],"countries":["'.$code.'"],"cities":[],"zips":[],"radius":0,"keywords":[],"connections":[],"excluded_connections":[],"friends_of_connections":[],"relationship_statuses":null,"interested_in":[],"college_networks":[],"college_majors":[],"college_years":[],"education_statuses":[0],"locales":[],"work_networks":[],"user_adclusters":[]}&method=get&access_token='.$accessToken);
			
			echo '<br><br>'.$datos.'<br><br>';
			
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

echo '<br><br>A: '.$accessToken.'<br><br>';

$query = "SELECT id_country, code FROM country ORDER BY 1;"; 
$que = mysql_query($query, $conexion) or die(mysql_error());
$sql_value = "";
while($fila = mysql_fetch_assoc($que)){
	$ban = 0;
		$sql_value = "";
		$sql_value .= getNumAudience($fila["code"], "", $accessToken);
		$sql_value .= ','.getNumAudience($fila["code"], "2", $accessToken);
		$sql_value .= ','.getNumAudience($fila["code"], "1", $accessToken);
		
	break;
}

/***************************************** FIN GET TOTALES ********************************************************/

mysql_close($conexion);