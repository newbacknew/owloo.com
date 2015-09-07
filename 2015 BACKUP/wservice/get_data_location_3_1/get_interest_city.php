<?php

    die();
    
    require_once('../config.php');
    
    //Intereses
    
    $query = 'SELECT id_country FROM `facebook_country_3_1` WHERE active_fb_get_data = 1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        
        $query = 'SELECT fi.id_interest id_interest FROM facebook_record_country_interest_3_1 fr JOIN facebook_interest_3_1 fi ON fr.id_interest = fi.id_interest WHERE id_country = '.$fila['id_country'].' AND date = \'2015-01-23\' ORDER BY total_user DESC LIMIT 5;'; 
        $que2 = db_query($query, array());
        
        while($fila2 = mysql_fetch_assoc($que2)){
            $sql = "INSERT INTO facebook_interest_city_3_1 VALUES(".$fila['id_country'].", ".$fila2['id_interest'].");";
            $res = db_query($sql, array(), true);
        }
    }