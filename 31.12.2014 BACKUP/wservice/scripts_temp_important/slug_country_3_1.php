<?php

    set_time_limit(0);
    
    require_once(__DIR__.'/../config.php');
    
    function convert_to_url_string($text){
        $caracteresEspeciales = array('á', 'é', 'í', 'ó', 'ú', 'ñ', ' ', '?', ',', '.', '(', ')', 'Å', 'ç');
        $caracteresReemplazo = array('a', 'e', 'i', 'o', 'u', 'n', '-', '', '', '', '', '', 'a', 'c');
        return str_ireplace($caracteresEspeciales, $caracteresReemplazo,  strtolower($text));
    }
    
    $query_country = 'SELECT id_country, name FROM facebook_country_3_1 ORDER BY 1;';
    $que_country = db_query($query_country, array());
    while($row_country = mysql_fetch_assoc($que_country)){
        
        $slug = convert_to_url_string($row_country['name']);
        
        $query_insert = "UPDATE facebook_country_3_1 SET slug = '$1' WHERE id_country = $2;";
        $res_insert = db_query($query_insert, array($slug, $row_country['id_country']), 1);
        
    }