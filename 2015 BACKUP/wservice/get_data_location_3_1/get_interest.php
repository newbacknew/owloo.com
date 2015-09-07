<?php
    
    die();

    require_once('../config.php');
    
    $conexion_owloo = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
    mysql_select_db(DB_NAME, $conexion_owloo) or die(mysql_error());
    mysql_query('SET NAMES \'utf8\'');
    
    //Intereses
    
    $datos = file_get_contents('https://graph.facebook.com/search?access_token=CAAI4BG12pyIBAKyyg4rwYB3MJkqPF46WZCbiZBlwc7qvfIUDsQatIOa3EsiOFmDjZAEQL9dwUm1AXiX2ZAgKLZBRBRJaYZA04BZBZAZBqVAjSTJVjHdohwv1YibVJy447VxxULclL9JBdxCnIUfTTUD2lgFiUNFImPW6RNsvglH0LmR1odNBzMrg7IffT3wAXbttfZB4YqxrytiprbEw9uF5rHbcOHCRnzyHIZD&endpoint=%2Fsearch&locale=es_LA&method=get&pretty=0&type=adInterestCategory');
    
    $datos_array = json_decode ($datos, true);
    
    //print_r($datos_array);
                
    $datos_in_order = array();
    
    foreach ($datos_array['data'] as $category) {
        switch (count($category['path'])) {
            case 1: 
                $datos_in_order['nivel1'][] = array('id' => $category['id'], 'name' => $category['name'], 'audience_size' => number_format($category['audience_size'], 0, ',', ' '));
                break;
            case 2: 
                $datos_in_order['nivel2'][$category['path'][0]][] = array('id' => $category['id'], 'name' => $category['name'], 'audience_size' => number_format($category['audience_size'], 0, ',', ' '));
                break;
            case 3: 
                $datos_in_order['nivel3'][$category['path'][0]][$category['path'][1]][] = array('id' => $category['id'], 'name' => $category['name'], 'audience_size' => number_format($category['audience_size'], 0, ',', ' '));
                break;
            case 4: 
                $datos_in_order['nivel4'][$category['path'][0]][$category['path'][1]][$category['path'][2]][] = array('id' => $category['id'], 'name' => $category['name'], 'audience_size' => number_format($category['audience_size'], 0, ',', ' '));
                break;
            
            default:
                
                break;
        }
    }

    //print_r($datos_in_order);
    
    $count1 = 1;
    $count2 = 1;
    $count3 = 1;
    $count4 = 1;
    foreach ($datos_in_order['nivel1'] as $category1) {
        
        $sql = "INSERT INTO facebook_interest_3_1 VALUES(null, ".$category1['id'].", '".mysql_real_escape_string($category1['name'])."', 1, NULL, 1, 1);";
        $res = mysql_query($sql) or die(mysql_error());
        $id_nivel_1 = mysql_insert_id();
        
        foreach ($datos_in_order['nivel2'][$category1['name']] as $category2) {
            
            $sql = "INSERT INTO facebook_interest_3_1 VALUES(null, ".$category2['id'].", '".mysql_real_escape_string($category2['name'])."', 2, ".$id_nivel_1.", 1, 1);";
            $res = mysql_query($sql) or die(mysql_error());
            $id_nivel_2 = mysql_insert_id();
            
            if(isset($datos_in_order['nivel3'][$category1['name']][$category2['name']])){
                foreach ($datos_in_order['nivel3'][$category1['name']][$category2['name']] as $category3) {
                    
                    $sql = "INSERT INTO facebook_interest_3_1 VALUES(null, ".$category3['id'].", '".mysql_real_escape_string($category3['name'])."', 3, ".$id_nivel_2.", 1, 1);";
                    $res = mysql_query($sql) or die(mysql_error());
                    $id_nivel_3 = mysql_insert_id();
                    
                    if(isset($datos_in_order['nivel4'][$category1['name']][$category2['name']][$category3['name']])){
                        foreach ($datos_in_order['nivel4'][$category1['name']][$category2['name']][$category3['name']] as $category4) {
                            
                            $sql = "INSERT INTO facebook_interest_3_1 VALUES(null, ".$category4['id'].", '".mysql_real_escape_string($category4['name'])."', 4, ".$id_nivel_3.", 1, 1);";
                            $res = mysql_query($sql) or die(mysql_error());
                    
                        }
                    }
                    $count4 = 1;
                }
            }
            $count3 = 1;
            $count2++;
        }
        $count2 = 1;
        $count1++;
    }