<?php
//ignore_user_abort(true);
set_time_limit(0);
include("../config/config_cronjob.php");
include("twitterfunctions_profile.php");


function update_tweets($update_id, $screen_name, $tweetcount){
	global $code_app, $cont_access_token; shuffle($code_app); echo '<br>Twitter user: '.$code_app[$cont_access_token]['user'];
	//include("../config/config_cronjob.php");
	
	$uid = $update_id;
	$updateflg = 0; // 1 for new 0 for update
	//If new record get all hashtag and mentions
	//Fetch Last Record Updated
	if ($updateflg == 0)
	{
		$since_id = "";
		$since_tweet = "";
		$qry = "";
		$qry = "Select owloo_id_str, owloo_tweet_count From owloo_last_id_str";
		$qry = $qry . " Where owloo_user_id = '" . mysql_real_escape_string($uid) . "'";
		
echo '<br>SQL 2: '.$qry.'<br>';
		
		$dataarr = mysql_query($qry) or die(mysql_error());
		$fetch_cntr = mysql_fetch_array($dataarr);
		if($fetch_cntr)
		{
			$since_id = $fetch_cntr['owloo_id_str'];
			$since_tweet = $fetch_cntr['owloo_tweet_count'];
		}
		if(!$since_id)
		{
			$updateflg = 1;
		}
	}
	
echo '<br>$updateflg: '.$updateflg.'<br>';
	
	if ($updateflg == 0)
	{
		include("updatehashmention.php");
	}
    else {
        include("inserthashmention.php");
    }
}

//ALL acounts
$qry = "";
$qry .= " SELECT owloo_user_id, owloo_user_twitter_id, owloo_user_name, owloo_screen_name, owloo_tweetcount FROM owloo_user_master WHERE owloo_user_id > 308 AND owloo_user_id < 501"; 
$qry .= " Order By 1";

echo '<br>SQL 1: '.$qry.'<br>';

$qrydata = mysql_query($qry) or die(mysql_error());

$contador = 1;

while ($fetch_cntr = mysql_fetch_array($qrydata)) {
            
        
    update_tweets($fetch_cntr['owloo_user_id'], $fetch_cntr['owloo_screen_name'], $fetch_cntr['owloo_tweetcount']);
    
	echo '<br>'.$contador++.'<br>';
	
	/*if($contador == 500){
		echo '<br>DURMIENDO: '.date("H:i:s").'<br>';
		sleep(960); //Espera 16 minutos para resetear las limitaciones del API del Twitter
		echo '<br>CONTINUANDO: '.date("H:i:s").'<br>';
	}*/
	
}

$informe_errores .= "
	CONTADOR: ".$contador;
	
$informe_errores .= "
	FIN! ".date("H:i:s");

echo '<br/>FIN!'.date("H:i:s");

echo $informe_errores;

function informarExito($text){
	//Enviar aviso por email
	$para = 'mmolinas@latamclick.com';
	$titulo = 'Owloo - Twitter update profile';
	$mensaje = 'El script de captura de actualización de perfiles se ha ejecutado exitosamente!!!<br><br>'.$text;
	$cabeceras = 'From: dev@owloo.com' . "\r\n";
	mail($para, $titulo, $mensaje, $cabeceras);
}

informarExito($informe_errores);

/*$f = fopen('xxx.txt', 'w+');
fwrite($f, $informe_errores);
fclose($f);*/