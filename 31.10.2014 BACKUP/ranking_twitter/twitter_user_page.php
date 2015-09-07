<?php
require_once('twitter/twitterfunctions.php');
//Fetch Data From Twitter
$screenname = "";
$exite_user = true;
$user_not_udate = false;

if (isset($_REQUEST["txttwittername"]) && $_REQUEST["txttwittername"] ){
	$_REQUEST["txttwittername"] = str_replace('@', '', $_REQUEST["txttwittername"]);
    $screenname = $_REQUEST["txttwittername"];
} else if (isset($_GET["twittername"]) && $_GET["twittername"]) {
	$_GET["twittername"] = str_replace('@', '', $_GET["twittername"]);
    $screenname = $_GET["twittername"];
} else {
    echo "<script>window.location='" . URL_ROOT . "twitter-stats/'</script>";
}

//Verificamos si el usuario ya ha sido actualizado hoy
$qry = "";
$qry = " Select * ";
$qry = $qry . " from owloo_user_master";
$qry = $qry . " Where owloo_screen_name = '" . mysql_real_escape_string($screenname) . "' AND owloo_updated_on = '" . Date("Y-m-d") . "'";

$chk_oldpass = mysql_query($qry);
$fetch_cntr = mysql_fetch_array($chk_oldpass);
if ($fetch_cntr['owloo_user_id'] == "") {
	if (isset($_REQUEST["txttwittername"]) && $_REQUEST["txttwittername"] ){
		$screenname = $_REQUEST["txttwittername"];
		$url = "https://api.twitter.com/1.1/users/lookup.json";
		$parameters = "screen_name=" . $screenname;
		$retdata = getdata($url, $parameters);
	} else if (isset($_GET["twittername"]) && $_GET["twittername"]) {
		$screenname = $_GET["twittername"];
		$url = "https://api.twitter.com/1.1/users/lookup.json";
		$parameters = "screen_name=" . $screenname;
		$retdata = getdata($url, $parameters);
	} else {
		echo "<script>window.location='" . URL_ROOT . "twitter-stats/'</script>";
	}
	if ($retdata) {
		$twdatas = json_decode($retdata, true);
		foreach ($twdatas as $twdata) {
			if (isset($twdata[0]["message"]) && $twdata[0]["message"]) {
				//echo "<br/>Error : " . $twdata[0]["message"];
				//echo "<br/>Code : " . $twdata[0]["code"];
				if($twdata[0]["code"] == 34)
					echo '<div class="owloo_msj_alert">
					           <div>Puede que <strong>@'.($_REQUEST["txttwittername"]?$_REQUEST["txttwittername"]:$_GET["twittername"]).'</strong> no esté registrado en Twitter.</div>
					           <div>Favor verifique el perfil ingresado y vuelve a intentarlo!</div>
					      </div>';
				else
					echo '<div class="owloo_msj_alert">
					           <div>Lo sentimos, <strong>no hemos podido procesar su petición</strong>.</div>
					           <div>Favor, intentelo más tarde...</div>
					      </div>
					      ';
				$exite_user = false;
			} elseif($twdata["followers_count"] < 10) {
			    echo '<div class="owloo_msj_alert">
                           <div>Lo sentimos, <strong>no hemos podido procesar su petición</strong>.</div>
                           <div>Favor, intentelo más tarde...</div>
                      </div>
                      ';
                $exite_user = false;
            }else{
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
				// Save Data Into DB
				$qry = "";
				$qry = " Select owloo_user_id, owloo_followers_count, owloo_following_count,owloo_tweetcount, owloo_listed_count";
				$qry = $qry . " from owloo_user_master";
				$qry = $qry . " Where owloo_user_twitter_id = '" . $twitter_id . "'";
				$qry = $qry . " AND owloo_screen_name = '" . $screen_name . "'";
				$chk_oldpass = mysql_query($qry);
				$fetch_cntr = mysql_fetch_array($chk_oldpass);
				if ($fetch_cntr['owloo_user_id'] == "") {
//echo '<h1>Nuevo usuario!</h1>';
					$qry = "";
					$qry = " INSERT INTO owloo_user_master ( owloo_user_twitter_id, owloo_user_name,";
					$qry = $qry . " owloo_screen_name, owloo_user_photo, owloo_user_description,";
					$qry = $qry . " owloo_user_location, owloo_user_language, owloo_user_verified_account,";
					$qry = $qry . " owloo_user_timezone, owloo_user_created_on, owloo_followers_count,";
					$qry = $qry . " owloo_following_count, owloo_tweetcount, owloo_listed_count,";
					$qry = $qry . " owloo_user_status, owloo_created_on, owloo_updated_on) VALUES (";
					$qry = $qry . " '" . $twitter_id . "',";
					$qry = $qry . " '" . $twitter_name . "',";
					$qry = $qry . " '" . $screen_name . "',";
					$qry = $qry . " '" . $profile_image_url . "',";
					$qry = $qry . " '" . $description . "',";
					$qry = $qry . " '" . $location . "',";
					$qry = $qry . " '" . $lang . "',";
					$qry = $qry . " '" . $verified . "',";
					$qry = $qry . " '" . $timezone . "',";
					$qry = $qry . " '" . $creationdate . "',";
					$qry = $qry . " '" . $followers_count . "',";
					$qry = $qry . " '" . $following_count . "',";
					$qry = $qry . " '" . $tweetcount . "',";
					$qry = $qry . " '" . $listedcount . "',";
					$qry = $qry . " '1',";
					$qry = $qry . " '" . Date("Y-m-d") . "',";
					$qry = $qry . " '" . Date("Y-m-d") . "')";
					$result = mysql_query($qry);
					$update_id = mysql_insert_id();
					$hfflag = 1;
					
					$qry = "";
					$qry = " INSERT INTO owloo_daily_track ( owloo_user_twitter_id, owloo_followers_count,";
					$qry = $qry . " owloo_following_count, owloo_tweetcount, owloo_listed_count,";
					$qry = $qry . " owloo_updated_on) VALUES (";
					$qry = $qry . " '" . $update_id . "',";
					$qry = $qry . " '" . $followers_count . "',";
					$qry = $qry . " '" . $following_count . "',";
					$qry = $qry . " '" . $tweetcount . "',";
					$qry = $qry . " '" . $listedcount . "',";
					$qry = $qry . " '" . Date("Y-m-d") . "')";
					mysql_query($qry);
				} else {
					
//echo '<h1>EL usuario está siendo actualizado</h1>';
					
					$update_id = $fetch_cntr['owloo_user_id'];
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
					mysql_query($qry);
					$qry = "";
					$qry = "Delete from owloo_daily_track where owloo_user_twitter_id = ".$update_id ." AND owloo_updated_on = '" . Date("Y-m-d") . "' ";
					mysql_query($qry);
					$qry = "";
					$qry = " INSERT INTO owloo_daily_track ( owloo_user_twitter_id, owloo_followers_count,";
					$qry = $qry . " owloo_following_count, owloo_tweetcount, owloo_listed_count,";
					$qry = $qry . " owloo_updated_on) VALUES (";
					$qry = $qry . " '" . $update_id . "',";
					$qry = $qry . " '" . $followers_count . "',";
					$qry = $qry . " '" . $following_count . "',";
					$qry = $qry . " '" . $tweetcount . "',";
					$qry = $qry . " '" . $listedcount . "',";
					$qry = $qry . " '" . Date("Y-m-d") . "')";
					mysql_query($qry);
				}
			}
		}
	} else {
		echo "<script>window.location='" . URL_ROOT . "twitter-stats/'</script>";
	}
}else{
	
//echo '<h1>EL usuario ya ha sido actualizado hoy</h1>';	
	$twitter_id = $fetch_cntr['owloo_user_twitter_id'];
	$update_id = $fetch_cntr['owloo_user_id'];
	$twitter_name = $fetch_cntr['owloo_user_name'];
	$screen_name = $fetch_cntr['owloo_screen_name'];
	$profile_image_url = $fetch_cntr['owloo_user_photo'];
	$description = $fetch_cntr['owloo_user_description'];
	$location = $fetch_cntr['owloo_user_location'];
	$lang = $fetch_cntr['owloo_user_language'];
	$verified = $fetch_cntr['owloo_user_verified_account'];
	$timezone = $fetch_cntr['owloo_user_timezone'];
	$creationdate = $fetch_cntr['owloo_user_created_on'];
	$followers_count = $fetch_cntr['owloo_followers_count'];
	$following_count = $fetch_cntr['owloo_following_count'];
	$tweetcount = $fetch_cntr['owloo_tweetcount'];
	$listedcount = $fetch_cntr['owloo_listed_count'];
	$hfflag = 0;
	$user_not_udate = true; //Para evitar que vuelva a actualizarse
}
if($exite_user){
    $_SESSION['owloo_tw_download'] = $screenname;
?>
    <div class="owloo_main">
        <div class="owloo_country_section">
            <h2>
                 Resumen de las estadísticas
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 1</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                 <div class="owloo_left">
                    <table>
                        <tbody>
                            <tr>
                                <td class="owloo_country_table_th">Seguidores</td>
                                <td class="owloo_country_table_th owloo_country_table_1"><?=owloo_number_format($followers_count)?></td>
                            </tr>
                            <tr>
                                <td>Siguiendo</td>
                                <td><?=owloo_number_format($following_count)?></td>
                            </tr>
                            
                            <tr>
                                <td>Tweets</td>
                                <td><?=owloo_number_format($tweetcount)?></td>
                            </tr>
                            <tr>
                                <td>Cuenta verificada</td>
                                <td><?php
                                        if($verified == 1){
                                            $verimg = '<img src="'.URL_IMAGES.'owloo_user_verified_account.png" class="owloo_tooltip" title="Cuenta verificada" alt="Si"/>';
                                        }else{
                                            $verimg = '<img src="'.URL_IMAGES.'owloo_tw_not_verified.png" class="owloo_tooltip" title="Cuenta no verificada" alt="No"/>';
                                        }
                                        echo $verimg;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <?php
                                    $date_register = '';
                                    $qry = "";
                                    $qry = " SELECT owloo_created_on";
                                    $qry = $qry . " FROM owloo_user_master";
                                    $qry = $qry . " Where owloo_user_id = '" . $update_id . "'";
                                    $chk_qry = mysql_query($qry);
                                    $fetch_cntr = mysql_fetch_array($chk_qry);
                                    $date_register = $fetch_cntr['owloo_created_on'];
                                ?>
                                <td>En Owloo desde</td>
                                <td><?php
                                        $date = new DateTime($date_register);
                                        echo $date->format('d-M-Y');
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Localización</td>
                                <td><?=(!empty($location)?$location:'<span class="owloo_not_change_audition"><em>n/a</em></span>')?></td>
                            </tr>
                            <tr>
                                <td>Idioma</td>
                                <td><?php
                                        $language  = '';
                                        if ($lang == "en") {
                                            $language = 'Inglés';
                                        }
                                        if ($lang == "es") {
                                            $language = 'Español';
                                        }
                                        echo $language;?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="owloo_right">
                    <p>
                        <div class="owloo_tw_profile">
                            <a href="https://twitter.com/<?=$screen_name?>" rel="nofollow" target="_blank">
                                <img src="<?=convert_imagen_to_https($profile_image_url)?>" alt="" title="" />
                            </a>
                            <a  class="owloo_resume_tw_user_title" href="https://twitter.com/<?=$screen_name?>" rel="nofollow" target="_blank">
                                <?=$twitter_name?> (@<?=$screen_name?>)
                            </a><br/>
                            <?=$description?>.
                        </div>
                    </p>
                    <p>
                        <strong><?=$twitter_name?></strong> cuenta con <strong><?=owloo_number_format($followers_count)?> seguidores</strong> en todo el mundo, con <strong><?=owloo_number_format($tweetcount)?> tweets</strong> publicados. Actualmente le está <strong>siguiendo a <?=owloo_number_format($following_count)?> perfiles</strong>.
                    </p>
                </div>
            </div>
        </div>
        
        <input type="hidden" id="votcnt" value="0"/>
        <input type="hidden" id="hfuidid" value="<?php echo $update_id; ?>"/>
        <input type="hidden" id="hfflag" value="<?php echo $hfflag; ?>"/>
        <input type="hidden" id="hftweetcnt" value="<?php echo $tweetcount; ?>"/>
        <input type="hidden" id="hfscreen" value="<?php echo $screen_name; ?>"/>

        <?php if($hfflag == 1) { ?>
        <div id="owloo_tw_charts_content">
            <div class="owloo_country_section ">
                <h2>
                    Procesando los datos de @<?=$screen_name?>
                </h2>
                <div class="owloo_country_content">
                     <div>
                        <img id="owloo_tw_logo_loader" src="<?=URL_ROOT?>ranking_twitter/images/twitter-logo.png"/>
                     </div>
                     <div>
                        <span class="owloo-tw-info-new">Algunos datos se mostrarán después de las <b>24 horas</b>.</span>
                     </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div<?=($hfflag == 1?' id="owloo_content_charts_preup" class="owloo_hide"':'')?>>
            <div class="owloo_country_section ">
                <h2>
                    Crecimiento de seguidores de <?=$twitter_name?>
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 2</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content owloo_country_content_tw_chart">
                     <div class="owloo_left owloo_tw_table_chart_more">
                         <div class="owloo_tw_charts_content" id="owloo_tw_chart_1"></div>
                    </div>
                    <div class="owloo_right owloo_tw_table_chart_less">
                        <p id="owloo_tw_chart_desc_1">
                            
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="owloo_country_section">
                <h2>
                    Tweets por hora durante todo el periodo
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 3</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content owloo_country_content_tw_chart">
                     <div class="owloo_left owloo_tw_table_chart_less">
                        <p id="owloo_tw_chart_desc_2">
                            
                        </p>
                    </div>
                    <div class="owloo_right owloo_tw_table_chart_more">
                        <div class="owloo_tw_charts_content" id="owloo_tw_chart_2"></div>
                    </div>
                </div>
            </div>
            
            <div class="owloo_country_section">
                <h2>
                    Tweets publicados por día
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 4</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content owloo_country_content_tw_chart">
                     <div class="owloo_left owloo_tw_table_chart_more">
                         <div class="owloo_tw_charts_content" id="owloo_tw_chart_3"></div>
                    </div>
                    <div class="owloo_right owloo_tw_table_chart_less">
                        <p id="owloo_tw_chart_desc_3">
                            
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="owloo_country_section">
                <h2>
                    Perfiles más mencionados
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 5</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content owloo_country_content_tw_chart">
                     <div class="owloo_left owloo_tw_table_chart_more">
                         <p id="owloo_tw_chart_desc_4">
                            
                         </p>
                    </div>
                    <div class="owloo_right owloo_tw_table_chart_less">
                        <div class="owloo_tw_table_content" id="owloo_tw_chart_4"></div>
                    </div>
                </div>
            </div>
            
            <div class="owloo_country_section">
                <h2>
                    Hashtags más usados
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 6</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content owloo_country_content_tw_chart">
                     <div class="owloo_left owloo_tw_table_chart_less">
                         <div class="owloo_tw_table_content" id="owloo_tw_chart_5"></div>
                    </div>
                    <div class="owloo_right owloo_tw_table_chart_more">
                        <p id="owloo_tw_chart_desc_5">
                            
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="owloo_country_section">
                <h2>
                    Descarga las estadísticas de @<?=$screen_name?>
                    <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 7</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
                </h2>
                <div class="owloo_country_content">
                     <div class="owloo_tw_download">
                        <div id="owloo_tw_download_sl_btn" class="owloo_btn owloo_btn_big owloo_btn_orange">Descargar ahora</div>
                        <h4 class="owloo_tw_download_stats">Reporte completo con datos históricos</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>