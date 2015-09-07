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
		
		$dataarr = mysql_query($qry);
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
}

function update_datos($screenname, $update_id){
	global $code_app, $cont_access_token; shuffle($code_app); echo '<br>Twitter user: '.$code_app[$cont_access_token]['user'];
	//include("../config/config_cronjob.php");
	//Fetch Data From Twitter
	$url = "https://api.twitter.com/1.1/users/lookup.json";
	$parameters1 = "screen_name=" . $screenname;
	
	$retdata = getdata($url, $parameters1);
	if ($retdata) {
		$twdatas = json_decode($retdata, true);
		foreach ($twdatas as $twdata) {
			if ($twdata[0]["message"]) {
					
			} else {
				$hfflag = 0;
				$twitter_id = $twdata["id"];
				$twitter_name = $twdata["name"];
				$screen_name = $twdata["screen_name"];
				$profile_image_url = mysql_real_escape_string($twdata["profile_image_url"]);
				$description = mysql_real_escape_string($twdata["description"]);
				$location = mysql_real_escape_string($twdata["location"]);
				$timezone = mysql_real_escape_string($twdata["time_zone"]);
				$creationdate = $twdata["created_at"];
				$lang = mysql_real_escape_string($twdata["lang"]);
				$verified = $twdata["verified"];
				$followers_count = $twdata["followers_count"];
				$following_count = $twdata["friends_count"];
				$tweetcount = $twdata["statuses_count"];
				$listedcount = $twdata["listed_count"];
				
				$qry = "";
				$qry = "Update owloo_user_master Set ";
				$qry = $qry . " owloo_user_twitter_id = '" . $twitter_id . "',";
				$qry = $qry . " owloo_user_name = '" . $twitter_name . "',";
				$qry = $qry . " owloo_screen_name = '" . $screen_name . "',";
				$qry = $qry . " owloo_user_photo = '" . $profile_image_url . "',";
				$qry = $qry . " owloo_user_description = '" . $description . "',";
				$qry = $qry . " owloo_user_location = '" . $location . "',";
				$qry = $qry . " owloo_user_language = '" . $lang . "',";
				$qry = $qry . " owloo_user_verified_account = '" . $verified . "',";
				$qry = $qry . " owloo_user_timezone = '" . $timezone . "',";
				$qry = $qry . " owloo_user_created_on = '" . $creationdate . "',";
				$qry = $qry . " owloo_followers_count = '" . $followers_count . "',";
				$qry = $qry . " owloo_following_count = '" . $following_count . "',";
				$qry = $qry . " owloo_tweetcount = '" . $tweetcount . "',";
				$qry = $qry . " owloo_listed_count = '" . $listedcount . "',";
				$qry = $qry . " owloo_updated_on = '" . Date("Y-m-d") . "'";
				$qry = $qry . " Where owloo_user_twitter_id = '" . $twitter_id . "'";
				$qry = $qry . " AND owloo_screen_name = '" . $screen_name . "'";
				
echo '<br>SQL 3: '.$qry.'<br>';

				mysql_query($qry);
				
				update_tweets($update_id, $screen_name, $tweetcount);
				
			}
		}
	}
}

//ALL acounts
$qry = "";
$qry .= " SELECT owloo_user_id, owloo_user_twitter_id, owloo_user_name, owloo_screen_name FROM owloo_user_master"; 
$qry .= " Order By 1";

echo '<br>SQL 1: '.$qry.'<br>';

$qrydata = mysql_query($qry);

$contador = 1;

while ($fetch_cntr = mysql_fetch_array($qrydata)) {

	update_datos($fetch_cntr['owloo_screen_name'], $fetch_cntr['owloo_user_id']);
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