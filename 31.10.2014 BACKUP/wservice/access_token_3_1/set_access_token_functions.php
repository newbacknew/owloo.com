<?php

function informarError(){
	//Enviar aviso por email
	//$para = 'hsteiner@latamclick.com, mmolinas@latamclick.com';
	$para = 'mmolinas@latamclick.com';
	$titulo = 'Owloo ERROR - Get acess token';
	$mensaje = 'No se ha podido obtener el acess token. Verificar estado!';
	$cabeceras = 'From: dev@owloo.com' . "\r\n";
	mail($para, $titulo, $mensaje, $cabeceras);
	die();
}

function getAccessToken_code($email, $pass){
	$accessToken = "";
	try{
		$post_data['email'] = $email;
		$post_data['pass'] = $pass;
		
		foreach ( $post_data as $key => $value) {
			$post_items[] = $key . '=' . $value;
		}
		
		$post_string = implode ('&', $post_items);
		$curl = curl_init();
		//curl_setopt($curl, CURLOPT_COOKIE, 'COOKIENAME=COOKIEVALUE;COOKINAME2=COOKIEVALUE2');
		curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__.'/cookie/cookies.txt');
		curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__.'/cookie/cookies.txt');			
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');			
		curl_setopt($curl, CURLOPT_FAILONERROR, 1);
		curl_setopt($curl, CURLOPT_REFERER, 'https://www.facebook.com/login.php?next=https://www.facebook.com/ads/manage/adscreator/');
		curl_setopt($curl, CURLOPT_COOKIESESSION, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 3);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_string);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		//$html = curl_exec($curl);
		curl_setopt($curl, CURLOPT_URL, 'https://www.facebook.com/login.php?next=https://www.facebook.com/ads/manage/adscreator/');
		$html2 = curl_exec($curl);
		
		//echo $html2;
		//die();
			
		$access_token_position = strpos($html2, 'access_token');
		
		
		if($access_token_position){
			$accessToken =  substr($html2, $access_token_position, 500);
			$accessToken = explode('"', $accessToken);
			$accessToken = $accessToken[2];
			
			//echo $accessToken.'<br>';
			
			curl_close($curl);
			
			if($accessToken != "" && !strpos($accessToken, ' '))
				return $accessToken;
		}
		else{
			curl_close($curl);
			return false;
		}
	}
	catch(Exception $e) {
		return false;
	}
	return false;
}

function getAccessTokenExpireDate($access_token){
	$curl = curl_init();
	//curl_setopt($curl, CURLOPT_COOKIE, 'COOKIENAME=COOKIEVALUE;COOKINAME2=COOKIEVALUE2');
	curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__.'/cookie/cook.txt');
	curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__.'/cookie/cook.txt');
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.224 Safari/534.10');			
	curl_setopt($curl, CURLOPT_FAILONERROR, 1);
	curl_setopt($curl, CURLOPT_REFERER, 'https://developers.facebook.com/tools/debug/access_token?q='.$access_token);
	//curl_setopt($curl,    CURLOPT_COOKIESESSION,         true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 3);
	curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
	//$html = curl_exec($curl);
	curl_setopt($curl, CURLOPT_URL, 'https://developers.facebook.com/tools/debug/access_token?q='.$access_token);
	$html2 = curl_exec($curl);
	
	curl_close($curl);
	
	//echo $html2;
	//die();
	
	$accessTokenDate =  substr($html2, strpos($html2, 'data-utime') + 12, 25);
	$accessTokenDate = explode('"', $accessTokenDate);
	return $accessTokenDate[0];
}

function setAccessToken(){
    
    $query = 'SELECT id, email, pass, accountId FROM facebook_access_token_account_3_1 ORDER BY 1 ASC;'; 
    $que = db_query($query, array());
    $ban = false;
	while($fila = mysql_fetch_assoc($que)){

		if(file_exists(__DIR__.'/cookie/cookies.txt')) unlink(__DIR__.'/cookie/cookies.txt');
		$f = fopen(__DIR__.'/cookie/cookies.txt', 'w');
		$access_token = getAccessToken_code($fila['email'], $fila['pass']);
		if(!$access_token)
			$access_token = getAccessToken_code($fila['email'], $fila['pass']);
		
		if($access_token){
			/*if(file_exists('/home/owloo/public_html/wservice/access_token/cookie/cook.txt')) unlink('/home/owloo/public_html/wservice/access_token/cookie/cook.txt');
			$f = fopen('/home/owloo/public_html/wservice/access_token/cookie/cook.txt', 'w');
			$access_token_expire = getAccessTokenExpireDate($access_token);
			if(!$access_token_expire)
				$access_token_expire = getAccessTokenExpireDate($access_token);*/
			//$sql = "INSERT INTO access_token VALUES (NULL , '".$access_token."', '".$fila['accountId']."', ".date('U').", ".$access_token_expire.");";
			
			$query = 'INSERT INTO facebook_access_token_3_1 VALUES (NULL , \'$1\', \'$2\', $3, $4, NOW());'; 
            $values = array($access_token, $fila['accountId'], date('U'), (date('U')+36000));
            db_query($query, $values, 1);
			$ban = true;
		}
			
	}
	
	/*if(!$ban)
		informarError();*/
}