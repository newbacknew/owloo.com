<?php

    set_time_limit(0);
    
    require_once(__DIR__.'/../config.php');
    
    function convert_to_url_string($text){
        $caracteresEspeciales = array('á', 'é', 'í', 'ó', 'ú', 'ñ', ' ', '?', ',', '.', '(', ')', 'Å', 'ç', 'ã', 'ü', 'ê', 'ò', 'ø', 'ì', 'ž', "'", 'š', 'Ä', 'ä', '`', 'Ł', 'ż', 'ź', 'Đ', 'à', 'ạ', 'Ø', '/', 'z̧', 'å', 'â', 'ö', 'Ç', 'è', 'û', 'É', 'ô', 'ý', 'ë', 'ß', 'ł', 'Á', 'î', 'Ñ', 'Ó', 'æ', 'Ö', 'č', 'Š', 'ù', 'Ú', 'Ü');
        $caracteresReemplazo = array('a', 'e', 'i', 'o', 'u', 'n', '-', '', '', '', '', '', 'a', 'c', 'a', 'u', 'e', 'o', 'o', 'i', 'z', '', '', 'a', 'a', '', 'l', 'z', 'z', 'd', 'a', 'a', 'O', '-', 'z', 'a', 'a', 'o', 'C', 'e', 'u', 'E', 'o', 'y', 'e', 'B', 'l', 'A', 'i', 'N', 'O', 'ae', 'O', 'c', 'S', 'u', 'U', 'U');
        return str_ireplace($caracteresEspeciales, $caracteresReemplazo,  strtolower($text));
    }
    
    //Country
    /*$query_country = 'SELECT id_country, name FROM facebook_country_3_1 ORDER BY 1;';
    $que_country = db_query($query_country, array());
    while($row_country = mysql_fetch_assoc($que_country)){
        
        $slug = convert_to_url_string($row_country['name']);
        
        $query_insert = "UPDATE facebook_country_3_1 SET slug = '$1' WHERE id_country = $2;";
        $res_insert = db_query($query_insert, array($slug, $row_country['id_country']), 1);
        
    }*/
    
    //City
    $query_city = 'SELECT id_city, fc.name name, code FROM facebook_city_3_1 fc JOIN facebook_country_3_1 c ON fc.id_country = c.id_country ORDER BY 1;';
    $que_city = db_query($query_city, array());
    while($row_city = mysql_fetch_assoc($que_city)){
        
        $row_city['name'] = explode(',', $row_city['name']);
        
        if(count($row_city['name']) > 1){
            unset($row_city['name'][count($row_city['name'])-1]);
        }
        
        $row_city['name'] = implode(',', $row_city['name']);
        
        $slug = convert_to_url_string($row_city['name']);
        
        $slug .= '-'.strtolower($row_city['code']);
        
        $query_insert = "UPDATE facebook_city_3_1 SET slug = '$1' WHERE id_city = $2;";
        $res_insert = db_query($query_insert, array($slug, $row_city['id_city']), 1);
        
    }

    //Region
    $query_region = 'SELECT id_region, fr.name name, code FROM facebook_region_3_1 fr JOIN facebook_country_3_1 c ON fr.id_country = c.id_country ORDER BY 1;';
    $que_region = db_query($query_region, array());
    while($row_region = mysql_fetch_assoc($que_region)){
        
        $row_region['name'] = explode(',', $row_region['name']);
        
        if(count($row_region['name']) > 1){
            unset($row_region['name'][count($row_region['name'])-1]);
        }
        
        $row_region['name'] = implode(',', $row_region['name']);
        
        $slug = convert_to_url_string($row_region['name']);
        
        $slug .= '-'.strtolower($row_region['code']);
        
        $query_insert = "UPDATE facebook_region_3_1 SET slug = '$1' WHERE id_region = $2;";
        $res_insert = db_query($query_insert, array($slug, $row_region['id_region']), 1);
        
    }