<?php
    require_once('twitterconfig_profile.php');
    $cont_access_token = 0;
    $informe_errores = '';
    
    $cont_access_token_rotation = 0;
    
    $informe_errores .= "
    	INICIO: ".date("H:i:s");
//echo "<br>INICIO: ".date("H:i:s");
    
    function next_access_token(){
    	global $cont_access_token, $code_app, $informe_errores, $cont_access_token_rotation;
    	++$cont_access_token;
    	if($cont_access_token < count($code_app)){
    		//sleep(10); //esperamos 10 segundos
    	}
    	else{
    	    $cont_access_token_rotation++;
            if($cont_access_token_rotation > 3){
//echo '<br>Twitter user: '.$code_app[$cont_access_token]['user'];
//echo '<br><br>CANCELAR EJECUCIÓN E INFORMAR!';
                informarError('Error del API de Twitter, más de 3 iteraciones - '.$parameters.'<br><hr><br>'.$informe_errores);
                die();
            }
            
    		$cont_access_token = 0;
    		$informe_errores .= "
    			Inicio SLEEP: ".date("H:i:s");
//echo '<br>'."Inicio SLEEP: ".date("H:i:s");
    		sleep(120); //esperamos 2 minutos
    		$informe_errores .= "
    			Fin SLEEP: ".date("H:i:s");
//echo "<br>Fin SLEEP: ".date("H:i:s");
    	}
    //print_r($code_app[$cont_access_token]);
    }
    
    function informarError($text){
    	//Enviar aviso por email
    	$para = 'mmolinas@latamclick.com';
    	$titulo = 'Owloo - Twitter update profile';
    	$mensaje = 'Error en el script de captura de actualización de perfiles...<br><br>'.$text;
    	$cabeceras = 'From: dev@owloo.com' . "\r\n";
    	mail($para, $titulo, $mensaje, $cabeceras);
    }
    
    function content_error($result, $parameters){
    	global $cont_access_token, $code_app, $informe_errores;
    	$twdatas = json_decode($result, true);
    	
    	//print_r($twdatas);
    	
    	foreach ($twdatas as $twdata) {
    		if (isset($twdata[0]["message"]) && $twdata[0]["message"]) {
    			$informe_errores .= "
    				Twitter code error: ".$twdata[0]["code"]." - ".$twdata[0]["message"]." - ".$parameters;
//echo '<br>Twitter code error: '.$twdata[0]["code"].' - '.$twdata[0]["message"].' - '.$parameters;
    			if($twdata[0]["code"] != 34){
    				if($twdata[0]["code"] == 32){
    				    
                        return false;
                        
//echo '<br>Twitter user: '.$code_app[$cont_access_token]['user'];
//echo '<br><br>CANCELAR EJECUCIÓN E INFORMAR!';
    					informarError('Error 32 del API de Twitter - '.$parameters.'<br><hr><br>'.$informe_errores);
    					die();
    				}
    				$informe_errores .= "
    					Twitter user: ".$code_app[$cont_access_token]['user'];
//echo '<br>Twitter user: '.$code_app[$cont_access_token]['user'];
    				next_access_token();
    				return true;
    			}
    			else{
    				return false;
    			}
    				
    		}
    	}
    	return false;
    }
    
    function getsignature($url, $parameters) {
    	global $cont_access_token, $code_app, $informe_errores;
        $oauth_hash .= 'oauth_consumer_key=' . $code_app[$cont_access_token]['Consumer_Key'] . '&';
        $oauth_hash .= 'oauth_nonce=' . time() . '&';
        $oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
        $oauth_hash .= 'oauth_timestamp=' . time() . '&';
        $oauth_hash .= 'oauth_token=' . $code_app[$cont_access_token]['Access_Token'] . '&';
        $oauth_hash .= 'oauth_version=1.0&';
        $oauth_hash .= $parameters;
        $base = '';
        $base .= 'GET';
        $base .= '&';
        $base .= rawurlencode($url);
        $base .= '&';
        $base .= rawurlencode($oauth_hash);
        $key = '';
        $key .= rawurlencode($code_app[$cont_access_token]['Consumer_Secret']);
        $key .= '&';
        $key .= rawurlencode($code_app[$cont_access_token]['Access_Token_Secret']);
        $signature = base64_encode(hash_hmac('sha1', $base, $key, true));
        $signature = rawurlencode($signature);
        return $signature;
    }
    
    function getdata($url, $parameters) {
    	global $cont_access_token, $code_app, $informe_errores;
    	do{
    		$signature = getsignature($url, $parameters);
    		$oauth_header = '';
    		$oauth_header .= 'oauth_consumer_key="' . $code_app[$cont_access_token]['Consumer_Key'] . '",';
    		$oauth_header .= 'oauth_nonce="' . time() . '", ';
    		$oauth_header .= 'oauth_signature="' . $signature . '", ';
    		$oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
    		$oauth_header .= 'oauth_timestamp="' . time() . '", ';
    		$oauth_header .= 'oauth_token="' . $code_app[$cont_access_token]['Access_Token'] . '", ';
    		$oauth_header .= 'oauth_version="1.0", ';
    		$curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');
    		$curl_request = curl_init();
    		curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
    		curl_setopt($curl_request, CURLOPT_HEADER, false);
    		curl_setopt($curl_request, CURLOPT_URL, $url . '?' . $parameters);
    		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
    		$json = curl_exec($curl_request);
    		curl_close($curl_request);
    		
//echo '<br>'.$oauth_header.'<br>';
    	
    	}while(content_error($json, $parameters));
    	
        return $json;
    }
    
    function gettwitterdata($maxid, $param){
    	global $cont_access_token, $code_app, $informe_errores;
    	do{
    		$oauth_hash .= 'count=200&';
    		if ($maxid > 0) {
    			$oauth_hash .= 'max_id=' . $maxid . '&';
    		}
    		$oauth_hash .= 'oauth_consumer_key=' . $code_app[$cont_access_token]['Consumer_Key'] . '&';
    		$oauth_hash .= 'oauth_nonce=' . time() . '&';
    		$oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
    		$oauth_hash .= 'oauth_timestamp=' . time() . '&';
    		$oauth_hash .= 'oauth_token=' . $code_app[$cont_access_token]['Access_Token'] . '&';
    		$oauth_hash .= 'oauth_version=1.0&';
    		$oauth_hash .= $param;
    		$base = '';
    		$base .= 'GET';
    		$base .= '&';
    		$base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');
    		$base .= '&';
    		$base .= rawurlencode($oauth_hash);
    		$key = '';
    		$key .= rawurlencode($code_app[$cont_access_token]['Consumer_Secret']);
    		$key .= '&';
    		$key .= rawurlencode($code_app[$cont_access_token]['Access_Token_Secret']);
    		$signature = base64_encode(hash_hmac('sha1', $base, $key, true));
    		$signature = rawurlencode($signature);
    		$oauth_header = '';
    		$oauth_header .= 'oauth_consumer_key="' . $code_app[$cont_access_token]['Consumer_Key'] . '",';
    		$oauth_header .= 'oauth_nonce="' . time() . '", ';
    		$oauth_header .= 'oauth_signature="' . $signature . '", ';
    		$oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
    		$oauth_header .= 'oauth_timestamp="' . time() . '", ';
    		$oauth_header .= 'oauth_token="' . $code_app[$cont_access_token]['Access_Token'] . '", ';
    		$oauth_header .= 'oauth_version="1.0", ';
    		$curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');
    		$curl_request = curl_init();
    		curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
    		curl_setopt($curl_request, CURLOPT_HEADER, false);
    		$turl = 'https://api.twitter.com/1.1/statuses/user_timeline.json?count=200';
    		if ($maxid > 0) {
    			$turl = $turl . '&max_id=' . $maxid . "&";
    			$turl = $turl . $param;
    		} else {
    			$turl = $turl . "&" . $param;
    		}
    		curl_setopt($curl_request, CURLOPT_URL, $turl);
    		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
    		$json = curl_exec($curl_request);
    		curl_close($curl_request);
            
    	}while(content_error($json, $param));
        return $json;
    }
    
    function getsincetwitterdata($maxid,$param) {
    	global $cont_access_token, $code_app, $informe_errores;
    	do{
    		$oauth_hash .= 'count=200&';
    		if ($maxid > 0) {
    			$oauth_hash .= 'max_id=' . $maxid . '&';
    		}
    		$oauth_hash .= 'oauth_consumer_key=' . $code_app[$cont_access_token]['Consumer_Key'] . '&';
    		$oauth_hash .= 'oauth_nonce=' . time() . '&';
    		$oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
    		$oauth_hash .= 'oauth_timestamp=' . time() . '&';
    		$oauth_hash .= 'oauth_token=' . $code_app[$cont_access_token]['Access_Token'] . '&';
    		$oauth_hash .= 'oauth_version=1.0&';
    		$oauth_hash .= $param;
    		$base = '';
    		$base .= 'GET';
    		$base .= '&';
    		$base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');
    		$base .= '&';
    		$base .= rawurlencode($oauth_hash);
    		$key = '';
    		$key .= rawurlencode($code_app[$cont_access_token]['Consumer_Secret']);
    		$key .= '&';
    		$key .= rawurlencode($code_app[$cont_access_token]['Access_Token_Secret']);
    		$signature = base64_encode(hash_hmac('sha1', $base, $key, true));
    		$signature = rawurlencode($signature);
    		$oauth_header = '';
    		$oauth_header .= 'oauth_consumer_key="' . $code_app[$cont_access_token]['Consumer_Key'] . '",';
    		$oauth_header .= 'oauth_nonce="' . time() . '", ';
    		$oauth_header .= 'oauth_signature="' . $signature . '", ';
    		$oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
    		$oauth_header .= 'oauth_timestamp="' . time() . '", ';
    		$oauth_header .= 'oauth_token="' . $code_app[$cont_access_token]['Access_Token'] . '", ';
    		$oauth_header .= 'oauth_version="1.0", ';
    		$curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');
    		$curl_request = curl_init();
    		curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
    		curl_setopt($curl_request, CURLOPT_HEADER, false);
    		$turl = 'https://api.twitter.com/1.1/statuses/user_timeline.json?count=200';
    		if ($maxid > 0) {
    			$turl = $turl . '&max_id=' . $maxid . "&";
    			$turl = $turl . $param;
    		} else {
    			$turl = $turl . "&" . $param;
    		}
    		curl_setopt($curl_request, CURLOPT_URL, $turl);
    		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
    		$json = curl_exec($curl_request);
    		curl_close($curl_request);
    	}while(content_error($json, $param));
        return $json;
    }
    
    /*$informe_errores .= "
    	FIN!".date("H:i:s");
    echo '<br>FIN!'.date("H:i:s");*/
    
    /*$f = fopen('new_xxx.txt', 'w+');
    fwrite($f, $informe_errores);
    fclose($f);*/
