<?php
    
    define('DB_USER', 'owloo_admin');
    define('DB_PASS', 'fblatamx244');
    define('DB_NAME', 'owloo_owloo');
    define('DB_NAME_TW', 'owloo_twitter');
    define('CASSANDRA_DB_NAME', 'owloo');
    define('CASSANDRA_DB_USER', 'owloo');
    define('CASSANDRA_DB_PASS', 'ow#Lat@0oC1cK');
    define('CASSANDRA_NODE_IP', '198.204.242.82');
    define('HTTP_HTTPS', 'http');
    define('DOMAIN', 'www.owloo.com/');
    define('URL_ROOT', HTTP_HTTPS.'://'.DOMAIN);
    define('URL_ROOT_HTTP', 'http://'.DOMAIN);
    define('URL_ROOT_HTTPS', 'https://'.DOMAIN);
    define('URL_IMAGES', HTTP_HTTPS.'://'.DOMAIN.'static/images/');
    define('URL_CSS', HTTP_HTTPS.'://'.DOMAIN.'static/css/');
    define('URL_JS', HTTP_HTTPS.'://'.DOMAIN.'static/js/');
    define('FOLDER_INCLUDE', 'static/include/');
    
    /***** WITH PHP Cassandra binary *****/
    /*
    require_once(__DIR__.'/php-cassandra-binary-master/vendor/autoload.php');
    
    function cassandra_query($query){
        $cassandra_nodes = array(
        CASSANDRA_NODE_IP => array(
                'username' => CASSANDRA_DB_USER,
                'password' => CASSANDRA_DB_PASS
            )
        );
        $cassandra_db = new evseevnn\Cassandra\Database($cassandra_nodes, CASSANDRA_DB_NAME);
        $cassandra_db->connect();
        
        $datos = $cassandra_db->query($query);
        
        $cassandra_db->disconnect();
        
        return $datos;
    }
    
    function cassandra_insert($query){
        $cassandra_nodes = array(
        CASSANDRA_NODE_IP => array(
                'username' => CASSANDRA_DB_USER,
                'password' => CASSANDRA_DB_PASS
            )
        );
        $cassandra_db = new evseevnn\Cassandra\Database($cassandra_nodes, CASSANDRA_DB_NAME);
        $cassandra_db->connect();
        
        $cassandra_db->query($query);
        
        $cassandra_db->disconnect();
    }
    */
    
    /***** WITH YACassandraPDO *****/
    
    $db_handle = new PDO('cassandra:host='.CASSANDRA_NODE_IP.';port=9160;cqlversion=3.0.0', CASSANDRA_DB_USER, CASSANDRA_DB_PASS);
    $db_handle->query('USE '.CASSANDRA_DB_NAME.';');
    
    function cassandra_query($query){
        
        global $db_handle;
        
        $stmt = $db_handle->prepare($query);
        $stmt->execute();
        
        $datos = $stmt->fetchAll();
        
        return $datos;
    }
    
    function cassandra_insert($query){
        
        global $db_handle;
        
        $stmt = $db_handle->prepare($query);
        $stmt->execute();
        
    }
    
    function register_today_success($type){
        
        global $cassandra_db;
        
        $date = date('Ymd');
        cassandra_insert("INSERT INTO owloo_cron_send_success(type,date) VALUES('$type', $date);");
    }
    
    function register_error($type, $error){
        
        global $cassandra_db;
        
        $date = date('Ymd');
        cassandra_insert("INSERT INTO owloo_cron_error_info(type,error,date) VALUES('$type', '$error', $date);");
    }
    
    function is_sent_today_success($type){
        
        global $cassandra_db;
        
        $date = date('Ymd');
        $sent = cassandra_query("SELECT * FROM owloo_cron_send_success WHERE type = '$type' AND date = $date;");
        if(count($sent) > 0){
            return true;
        }
        return false;
    }
    
    function is_sent_today_error($type){
        
         global $cassandra_db;
        
        $date = date('Ymd');
        $sent = cassandra_query("SELECT * FROM owloo_cron_error_info WHERE type = '$type' AND date = $date;");
        if(count($sent) > 0){
            return true;
        }
        return false;
    }
    
    function send_email($type, $asunto, $mensaje, $error = false, $msj_error = NULL){
        $para = 'mmolinas@latamclick.com';
        $cabeceras = 'From: dev@owloo.com' . "\r\n";
        if($error){
            if(is_sent_today_error($type)){
                //register_error($type, $msj_error);
                exit();
            }
            else {
                register_error($type, $msj_error);
            }
        }
        else{
            if(is_sent_today_success($type)){
                exit();
            }
            else {
                register_today_success($type);
            }
        }
        
        mail($para, $asunto, $mensaje, $cabeceras);
        exit();
    }