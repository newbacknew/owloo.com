<?php
    //require_once('../config.php');
    
   /* $conexion_owloo = mysql_connect('localhost', MYSQL_DB_USER, MYSQL_DB_PASS) or die(mysql_error());
    mysql_select_db(MYSQL_DB_NAME, $conexion_owloo) or die(mysql_error());
    mysql_query('SET NAMES \'utf8\'');*/
    
    //Intereses
    
    $query = 'SELECT id_country FROM `facebook_country_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;'; 
    $que = mysql_query($query);
    
    while($fila = mysql_fetch_assoc($que)){
        
        $query = 'SELECT fi.id_interest id_interest FROM facebook_record_country_interest_3_1 fr JOIN facebook_interest_3_1 fi ON fr.id_interest = fi.id_interest WHERE id_country = '.$fila['id_country'].' AND date = \'2014-09-23\' ORDER BY total_user DESC LIMIT 5;'; 
        $que2 = mysql_query($query);
        
        while($fila2 = mysql_fetch_assoc($que2)){
            $sql = "INSERT INTO facebook_interest_city_3_1 VALUES(".$fila['id_country'].", ".$fila2['id_interest'].");";
            $res = mysql_query($sql) or die(mysql_error());
        }
    }