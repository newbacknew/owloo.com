<?php

if(!is_numeric($_SERVER['argv'][1]) && !is_numeric($_SERVER['argv'][2]) && !is_numeric($_SERVER['argv'][3])  && $_SERVER['argv'][4] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
    die();
}

require_once('../config.php');
require_once('../access_token_3_1/get_access_token.php');

/*********** Parámetros **************/
set_time_limit(7200); //2 horas
$max_nun_intentos = 6;
/************************************/

$accessToken_data = getAccessToken();
$accessToken_data_index = -1;
$nun_intentos = 0;
$accessToken = NULL;
$accountId = NULL;
$pageId = NULL;
$pageName = NULL;

function nextAccessToken(){
    global $accessToken_data, $accessToken_data_index, $accessToken, $accountId, $pageId, $pageName, $nun_intentos, $max_nun_intentos;
    $accessToken_data_index++;
    if($accessToken_data_index < count($accessToken_data)){
        $accessToken = $accessToken_data[$accessToken_data_index]['access_token'];
        $accountId = $accessToken_data[$accessToken_data_index]['accountId'];
        $pageId = $accessToken_data[$accessToken_data_index]['pageId'];
        $pageName = $accessToken_data[$accessToken_data_index]['pageName'];
    }
    else{
        $nun_intentos++;
        if($nun_intentos < $max_nun_intentos){
            setAccessToken();
            $accessToken_data = getAccessToken();
            $accessToken_data_index = -1;
            nextAccessToken();
        }
        else{
            send_email('country_comportamiento_3_1', 'Owloo ERROR - Pais COMPORTAMIENTO 3.1', 'ERROR en la captura de datos.', true, 'Access Token - Pais COMPORTAMIENTO - ID = '.$_SERVER['argv'][1]);
        }
    }
}

function getNumAudience($code, $gender, $key_comportamiento, $_accessToken, $_accountId, $_pageId, $_pageName){
    $numAudience = "";
    try{
        
        $datos = file_get_contents('https://graph.facebook.com/act_'.$_accountId.'/reachestimate?access_token='.$_accessToken.'&accountId='.$_accountId.'&bid_for=["conversion"]&currency=USD&endpoint=/act_'.$_accountId.'/reachestimate&locale=es_LA&method=get&pretty=0&targeting_spec={"genders":['.$gender.'],"age_max":65,"age_min":13,"regions":[],"countries":[],"cities":[],"zips":[],"keywords":[],"interests":[],"connections":[],"friends_of_connections":[],"relationship_statuses":[],"interested_in":[],"college_years":[],"education_statuses":[],"locales":[],"user_adclusters":[],"behaviors":[{"id":"'.$key_comportamiento.'","name":""}],"user_os":[],"user_device":[],"wireless_carrier":[],"work_positions":[],"education_majors":[],"education_schools":[],"work_employers":[],"page_types":null,"geo_locations":{"countries":["'.$code.'"],"cities":[],"regions":[],"zips":[]},"excluded_geo_locations":{"countries":[],"cities":[],"regions":[],"zips":[]}}');
        
        $datosarray2 = json_decode ($datos, true);
        
        //print_r($datosarray2 ); die();
        
        $numAudience = $datosarray2['users'];
        
        if($numAudience != "" && is_numeric($numAudience)){
            return $numAudience;
        }
    }
    catch(Exception $e) {
        return false;
    }
    return false;
}


/***************************************** GET TOTALES ********************************************************/

nextAccessToken();

$next_id = $_SERVER['argv'][1];
$id_comportamiento = $_SERVER['argv'][2];
$key_comportamiento = $_SERVER['argv'][3];

if(!empty($next_id)){
    
    $query = "SELECT id_country FROM facebook_record_country_comportamiento_3_1 WHERE id_country = ".mysql_real_escape_string($next_id)." AND id_comportamiento = ".mysql_real_escape_string($id_comportamiento)." AND date = DATE_FORMAT(NOW(), '%Y-%m-%d');";
    $que = mysql_query($query) or die(mysql_error());
    
    if(!$row = mysql_fetch_assoc($que)){
        $query = "SELECT id_country, code FROM facebook_country_3_1 WHERE id_country = ".mysql_real_escape_string($next_id).";";
        $que = mysql_query($query) or die(mysql_error());
        $sql_value = "";
        if($fila = mysql_fetch_assoc($que)){
            $ban = 0;
            while($ban == 0){
                //Audiencia total
                $check_audience = false;
                $numAudience = NULL;
                while(!$check_audience){
                    $numAudience = getNumAudience($fila["code"], "", $key_comportamiento, $accessToken, $accountId, $pageId, $pageName);
                    if(!$numAudience){
                        nextAccessToken();
                    }
                    else
                        $check_audience = true;
                }
                //Audiencia total mujeres
                $check_audience = false;
                $numAudienceFemale = NULL;
                while(!$check_audience){
                    $numAudienceFemale = getNumAudience($fila["code"], "2", $key_comportamiento, $accessToken, $accountId, $pageId, $pageName);
                    if(!$numAudienceFemale){
                        nextAccessToken();
                    }
                    else
                        $check_audience = true;
                }
                //Audiencia total hombres
                $check_audience = false;
                $numAudienceMale = NULL;
                while(!$check_audience){
                    $numAudienceMale = getNumAudience($fila["code"], "1", $key_comportamiento, $accessToken, $accountId, $pageId, $pageName);
                    if(!$numAudienceMale){
                        nextAccessToken();
                    }
                    else
                        $check_audience = true;
                }
                
                if(is_numeric($numAudience) && is_numeric($numAudienceMale) && is_numeric($numAudienceFemale)){
                    $sql_value = "";
                    $sql_value .= $numAudience;
                    $sql_value .= ','.$numAudienceFemale;
                    $sql_value .= ','.$numAudienceMale;
                    
                    //Insertamos los datos
                    $sql = "INSERT INTO facebook_record_country_comportamiento_3_1 VALUES(NULL, ".$fila['id_country'].", ".mysql_real_escape_string($id_comportamiento).", ".$sql_value.", NOW());";
                    $res2 = mysql_query($sql) or die(mysql_error());
                    if(is_numeric(mysql_insert_id())){
                        $ban = 1;
                    }
                }
                else {
                    send_email('country_comportamiento_3_1', 'Owloo ERROR - Pais COMPORTAMIENTO - '.$fila['id_country'].' - 3.1', 'ERROR en la captura de datos.', true, 'Captura de datos - Pais COMPORTAMIENTO - ID = '.$_SERVER['argv'][1].' => '.$numAudience.' - '.$numAudienceFemale.' - '.$numAudienceMale);
                }
            }
        }
    
        /***************************************** FIN GET TOTALES ********************************************************/
        
        /********************************** Verificación de finalización *************************************************/
        
        //Cantidad total de países
        $query = "SELECT COUNT(*) cantidad FROM facebook_country_3_1 WHERE active_fb_get_data = 1;"; 
        $que = mysql_query($query) or die(mysql_error());
        $cantidadPais = 0;
        if($fila = mysql_fetch_assoc($que)){
            $cantidadPais = $fila['cantidad'];
        }
        
        //Cantidad total de comportamiento
        $query = "SELECT COUNT(*) cantidad FROM facebook_comportamiento_3_1 WHERE active_fb_get_data = 1;"; 
        $que = mysql_query($query) or die(mysql_error());
        $cantidadcomportamiento = 0;
        if($fila = mysql_fetch_assoc($que)){
            $cantidadcomportamiento = $fila['cantidad'];
        }
        
        //Cantidad de filas insertadas
        $query = "SELECT COUNT(*) cantidad FROM `facebook_record_country_comportamiento_3_1` WHERE date = DATE_FORMAT(now(), '%Y-%m-%d');"; 
        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            if($fila['cantidad'] == ($cantidadPais * $cantidadcomportamiento))
                send_email('country_comportamiento_3_1', 'Owloo EXITO - Pais COMPORTAMIENTO 3.1', 'EXITO en la captura de datos.');
        }
        
        /******************************* FIN - Verificación de finalización **********************************************/
    }
}
