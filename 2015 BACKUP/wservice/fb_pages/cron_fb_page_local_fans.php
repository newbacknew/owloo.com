<?php
    set_time_limit(0);
    
    require_once('../config.php');
    
    //Conexión a la base de datos
    $conn = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
    mysql_select_db(DB_NAME, $conn) or die(mysql_error());
    mysql_query('SET NAMES \'utf8\'');
    
    function get_fb_page_access_token(){
        $sql = "SELECT * FROM facebook_access_token_3_1 ORDER BY date_add DESC LIMIT 1;";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['access_token'];
        }
        return NULL;
    }
    
    function add_lote_fb_page_local_fan($sql_insert){
        if(!empty($sql_insert)){
            $sql = "INSERT INTO facebook_page_local_fans_country(id, id_page, id_country, likes, date) VALUES".$sql_insert.";";
            $res = mysql_query($sql) or die(mysql_error());
            if(mysql_affected_rows() > 0){
                return true;
            }
        }
        return NULL;
    }
    
    function get_country_id_from_code($code){
        $sql =  "SELECT id_country 
                    FROM facebook_country_3_1 
                    WHERE code LIKE '".mysql_real_escape_string($code)."';
                 ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['id_country'];
        }
        else{
            return NULL;
        }
    }
    
    function is_fb_page_has_location($id_page){
        $sql = "SELECT location FROM facebook_page WHERE id_page = ".mysql_real_escape_string($id_page).";";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            if(!empty($fila['location'])){
                return true;
            }
        }
        return false;
    }
    
    function is_hispanic_country($id_country){
        $sql = "SELECT idiom FROM facebook_country_3_1 WHERE id_country = ".mysql_real_escape_string($id_country)." AND idiom = 'es';";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return 1;
        }
        return 0;
    }
    
    function get_first_country_local_fans($id_page){
        $id_page = mysql_real_escape_string($id_page);
        $sql = "SELECT id_country FROM facebook_page_local_fans_country WHERE id_page = $id_page ORDER BY date DESC, likes DESC LIMIT 1;";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['id_country'];
        }
        return NULL;
    }
    
    function update_fb_page_local_fans($id_page, $first_country_local_fans){
        if(!empty($id_page)){
            $id_page = mysql_real_escape_string($id_page);
            $first_country_local_fans = (!empty($first_country_local_fans)?$first_country_local_fans:'NULL');
            
            $hispanic = '';
            if(!is_fb_page_has_location($id_page) && !empty($first_country_local_fans)){
                $hispanic = ', hispanic = '.is_hispanic_country($first_country_local_fans).' ';
            }
            
            $sql = "UPDATE facebook_page SET first_local_fans_country = $first_country_local_fans $hispanic WHERE id_page = $id_page;";
            $res = mysql_query($sql) or die(mysql_error());
            if(mysql_affected_rows() > 0){
                return true;
            }
        }
        return NULL;
    }
    
    function is_exist_fb_page_local_fan_date($id_page, $date){
        $sql = "SELECT count(*) count FROM facebook_page_local_fans_country WHERE id_page = ".mysql_real_escape_string($id_page)." AND date LIKE '".mysql_real_escape_string($date)."';";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            if($fila['count'] > 0){
                return true;
            }
        }
        return false;
    }
    
    function get_fb_page_local_fans_last_update($id_page){
        $sql = "SELECT UNIX_TIMESTAMP(date) date FROM facebook_page_local_fans_country WHERE id_page = ".mysql_real_escape_string($id_page)." ORDER BY date DESC LIMIT 1;";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return false;
    }
    
    
    $access_token = get_fb_page_access_token();
    
    $query =   "SELECT id_page, fb_id, username
                FROM facebook_page
                ORDER BY 1;";
    $que = mysql_query($query) or die(mysql_error());
    while($fila = mysql_fetch_assoc($que)){
                   
        $id_page = $fila['id_page'];
        
        $until = date('U');
        $since = get_fb_page_local_fans_last_update($id_page);
        if(empty($since))
            $since = $until - (90 * 24 * 60 * 60);
        
        $local_fans_country = get_url_content('https://graph.facebook.com/'.$fila['fb_id'].'/insights/page_fans_country?since='.$since.'&until='.$until.'&locale=es_LA&access_token='.$access_token);
        $local_fans_country = json_decode ($local_fans_country, true);
        
        if(isset($local_fans_country['data']) && $local_fans_country['data'][0]['name'] == 'page_fans_country'){
            $sql_insert = '';
            $aux_first = true;
            foreach ($local_fans_country['data'][0]['values'] as $local_fan) {
                $date = explode('T', $local_fan['end_time']);
                if(!is_exist_fb_page_local_fan_date($id_page, $date[0])){
                    foreach ($local_fan['value'] as $key => $value) {
                        $_country_id = get_country_id_from_code($key);
                        if(!empty($_country_id)){
                            if(!$aux_first) $sql_insert .= ','; else $aux_first = false;
                            $sql_insert .= "(NULL, ".mysql_real_escape_string($id_page).",".mysql_real_escape_string($_country_id).",".mysql_real_escape_string($value).",'".mysql_real_escape_string($date[0])."')";
                        }
                    }
                }
            }
            
            if(!empty($sql_insert))
                add_lote_fb_page_local_fan($sql_insert);
            else {
                echo '<br>No se actualizó: <a href="http://www.owloo.com/facebook-stats/pages/'.$fila['username'].'/">'.$fila['username'].'</a><br>';
            }
        }
        update_fb_page_local_fans($id_page, get_first_country_local_fans($id_page));        
    }