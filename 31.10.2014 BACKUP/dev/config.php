<?php
    
    define('MYSQL_DB_USER', 'owloo_admin');
    define('MYSQL_DB_PASS', 'fblatamx244');
    define('MYSQL_DB_NAME', 'owloo_owloo');
    define('MYSQL_DB_NAME_TW', 'owloo_twitter');
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

    
    function db_mysql_query($sql, $values, $insert_update = false){
        $conexion_owloo = mysql_connect('localhost', MYSQL_DB_USER, MYSQL_DB_PASS) or die(mysql_error());
        mysql_select_db(MYSQL_DB_NAME, $conexion_owloo) or die(mysql_error());
        mysql_query('SET NAMES \'utf8\'');
        
        $count = 1; foreach ($values as $value) {
            $sql = str_replace('$'.$count, mysql_real_escape_string($value), $sql);
            $count++;
        }
        
        $result = mysql_query($sql) or die(mysql_error());
        
        $last_id = NULL;
        if($insert_update){
            if($insert_update == 1){ //fue un insert
                $last_id = mysql_insert_id();
            }
        }
        
        mysql_close($conexion_owloo);
        
        if($insert_update){
            if($insert_update == 1){ //fue un insert
                if($last_id){
                    return $last_id;
                }
            }
        }
        else {
            return $result;
        }
    }