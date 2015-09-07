<?php
    
    define('DB_USER', 'owloo_admin');
    define('DB_PASS', 'fblatamx244');
    define('DB_NAME', 'owloo_owloo');
    define('DB_NAME_TW', 'owloo_twitter');
    define('HTTP_HTTPS', 'http');
    define('DOMAIN', 'www.owloo.com/');
    define('URL_ROOT', HTTP_HTTPS.'://'.DOMAIN);
    define('URL_ROOT_HTTP', 'http://'.DOMAIN);
    define('URL_ROOT_HTTPS', 'https://'.DOMAIN);
    define('URL_IMAGES', HTTP_HTTPS.'://'.DOMAIN.'static/images/');
    define('URL_CSS', HTTP_HTTPS.'://'.DOMAIN.'static/css/');
    define('URL_JS', HTTP_HTTPS.'://'.DOMAIN.'static/js/');
    define('FOLDER_INCLUDE', 'static/include/');

    function get_url_content($url){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_URL, $url);
          $data = curl_exec($ch);
          curl_close($ch);
          return $data;
    }
    
    function db_query($sql, $values, $insert_update = false){
        $conexion_owloo = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
        mysql_select_db(DB_NAME, $conexion_owloo) or die(mysql_error());
        mysql_query('SET NAMES \'utf8\'');
        
        $count = 1; foreach ($values as $value) {
            $sql = str_replace('$'.$count, mysql_real_escape_string($value), $sql);
            $count++;
        }
        
        //error_log($sql);
        //echo '<br>'.$sql.'<br>';
        
        $result = mysql_query($sql) or die(mysql_error());
       
        mysql_close($conexion_owloo);
        
        return $result;
        
    }
    
    function db_query_table_results($sql, $values, $insert_update = false){
        $conexion_owloo = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
        mysql_select_db('owloo_results', $conexion_owloo) or die(mysql_error());
        mysql_query('SET NAMES \'utf8\'');
        
        $count = 10; foreach ($values as $value) {
            $sql = str_replace('$'.$count, mysql_real_escape_string($value), $sql);
            $count++;
        }
        
        //echo '<br>'.$sql.'<br>';
        
        $result = mysql_query($sql) or die(mysql_error());
       
        mysql_close($conexion_owloo);
        
        return $result;
        
    }
    
    function register_today_success($type){
        $query = 'INSERT INTO `owloo_cron_send_success_3_1` VALUES(NULL, \'$1\', NOW());'; 
        $values = array($type);
        db_query($query, $values, 1);
    }
    
    function register_error($type, $error){
        $query = 'INSERT INTO `owloo_cron_error_info_3_1` VALUES(NULL, \'$1\', \'$2\', NOW());'; 
        $values = array($type, $error);
        db_query($query, $values, 1);
    }
    
    function is_sent_today_success($type){
        $query = 'SELECT * FROM `owloo_cron_send_success_3_1` WHERE type LIKE \'$1\' AND DATE_FORMAT(date, \'%Y-%m-%d\') = DATE_FORMAT(NOW(), \'%Y-%m-%d\');';
        $values = array($type);
        $que = db_query($query, $values);
        if($fila = mysql_fetch_assoc($que)){
            return true;
        }
        return false;
    }
    
    function is_sent_today_error($type){
        $query = 'SELECT * FROM `owloo_cron_error_info_3_1` WHERE type LIKE \'$1\' AND date = DATE_FORMAT(NOW(), \'%Y-%m-%d\');';
        $values = array($type);
        $que = db_query($query, $values);
        if($fila = mysql_fetch_assoc($que)){
            return true;
        }
        return false;
    }
    
    function send_email($type, $asunto, $mensaje, $error = false, $msj_error = NULL){
        $para = 'mmolinas@latamclick.com';
        $cabeceras = 'From: dev@owloo.com' . "\r\n";
        if($error){
            if(is_sent_today_error($type)){
                register_error($type, $msj_error);
            }
            else {
                register_error($type, $msj_error);
                mail($para, $asunto, $mensaje, $cabeceras);
            }
            die();
        }
        else{
            if(is_sent_today_success($type)){
                exit();
            }
            else {
                register_today_success($type);
                mail($para, $asunto, $mensaje, $cabeceras);
            }
        }
        exit();
    }
