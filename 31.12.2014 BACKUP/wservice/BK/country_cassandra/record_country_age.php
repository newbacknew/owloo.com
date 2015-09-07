<?php

if(!is_numeric($_SERVER['argv'][1]) || strlen($_SERVER['argv'][2]) != 2 || !is_numeric($_SERVER['argv'][3]) || !is_numeric($_SERVER['argv'][4]) || !is_numeric($_SERVER['argv'][5]) || !is_numeric($_SERVER['argv'][6]) || $_SERVER['argv'][7] != 'c45a5f3b2cfa74ac94bd5bbfb2c5d6a5'){
    die();
}

require_once('../config.php');
require_once('../access_token_cassandra/get_access_token.php');

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
            send_email('country_age', 'Owloo ERROR - Pais AGE', 'ERROR en la captura de datos.', true, 'Access Token - Pais AGE - ID = '.$_SERVER['argv'][1]);
        }
    }
}

function getNumAudience($code, $age_min, $age_max, $gender, $_accessToken, $_accountId, $_pageId, $_pageName){
    $numAudience = "";
    try{
        $datos = file_get_contents('https://graph.facebook.com/act_'.$_accountId.'/reachestimate?access_token='.$_accessToken.'&accountId='.$_accountId.'&bid_for=["conversion"]&currency=USD&endpoint=/act_'.$_accountId.'/reachestimate&locale=es_LA&method=get&pretty=0&targeting_spec={"genders":['.$gender.'],"age_max":'.$age_max.',"age_min":'.$age_min.',"regions":[],"countries":[],"cities":[],"zips":[],"keywords":[],"connections":[],"friends_of_connections":[],"relationship_statuses":null,"interested_in":[],"college_years":[],"education_statuses":[],"locales":[],"user_adclusters":[],"user_os":[],"user_device":[],"wireless_carrier":[],"work_positions":[],"education_majors":[],"education_schools":[],"work_employers":[],"page_types":null,"geo_locations":{"countries":["'.$code.'"],"cities":[],"regions":[],"zips":[]},"excluded_geo_locations":{"countries":[],"cities":[],"regions":[],"zips":[]}}');
        
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
$next_code = $_SERVER['argv'][2];
$total_age_rows = $_SERVER['argv'][3];
$age_id = $_SERVER['argv'][4];
$age_min = $_SERVER['argv'][5];
$age_max = $_SERVER['argv'][6];
$date = date('Ymd');

if(!empty($next_id)){
    
    $row = cassandra_query("SELECT id_country FROM facebook_record_country_age WHERE id_country = $next_id AND id_age = $age_id AND date = $date;");
    
    if(count($row) == 0){

        $ban = 0;
        while($ban == 0){
            //Audiencia total
            $check_audience = false;
            $numAudience = NULL;
            while(!$check_audience){
                $numAudience = getNumAudience($next_code, $age_min, $age_max, "", $accessToken, $accountId, $pageId, $pageName);
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
                $numAudienceFemale = getNumAudience($next_code, $age_min, $age_max, "2", $accessToken, $accountId, $pageId, $pageName);
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
                $numAudienceMale = getNumAudience($next_code, $age_min, $age_max, "1", $accessToken, $accountId, $pageId, $pageName);
                if(!$numAudienceMale){
                    nextAccessToken();
                }
                else
                    $check_audience = true;
            }
            
            if(is_numeric($numAudience) && is_numeric($numAudienceMale) && is_numeric($numAudienceFemale)){
                
                cassandra_insert("INSERT INTO facebook_record_country_age(id_country,id_age,total_user,total_female,total_male,date,date_time) VALUES($next_id, $age_id, $numAudience, $numAudienceFemale, $numAudienceMale, $date, dateof(NOW()));");
                
                $ban = 1;
                
            }
            else {
                send_email('country_age', 'Owloo ERROR - Pais AGE - '.$next_id, 'ERROR en la captura de datos.', true, 'Captura de datos - Pais AGE - ID = '.$_SERVER['argv'][1].' => '.$numAudience.' - '.$numAudienceFemale.' - '.$numAudienceMale);
            }
        }
    
        /***************************************** FIN GET TOTALES ********************************************************/
        
        /********************************** Verificación de finalización *************************************************/

        //Cantidad de filas insertadas
        
        $inserts = cassandra_query("SELECT COUNT(*) FROM facebook_record_country_age WHERE date = $date;");
        if(count($inserts) > 0){
            if($inserts[0]['count'] == ($total_age_rows))
                send_email('country_age', 'Owloo EXITO - Pais AGE', 'EXITO en la captura de datos.');
        }
        
        /******************************* FIN - Verificación de finalización **********************************************/
    }
}

