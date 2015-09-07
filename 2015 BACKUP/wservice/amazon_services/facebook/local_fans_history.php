<?php
    
    set_time_limit(0);
    
    header('Content-Type: application/json');
    
    if(!(isset($_GET['code']) && !empty($_GET['code']))){
        die();
    }
    
    $var_explode = explode('-', $_GET['code']);
    $page_id = $var_explode[0];
    $country_id = $var_explode[1];
    $days = $var_explode[2];
    
    if(!(is_numeric($page_id) && is_numeric($country_id) && is_numeric($days))){
        die();
    }
    
    require_once(__DIR__.'/../../config.php');
    
    function is_exist_fb_page($page_id){
        $sql = 'SELECT * FROM '.DB_FACEBOOK_PREFIX.'page
                WHERE id_page = $1;';
        $res = db_query($sql, array($page_id));
        if($fila = mysql_fetch_assoc($res)){
            return true;
        }
        return false;
    }
    
    function get_local_fans_last_date($page_id){
        $sql = 'SELECT date FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country
                WHERE id_page = $1
                ORDER BY 1 DESC
                LIMIT 1;';
        
        $res = db_query($sql, array($page_id));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    //Verificamos si existe la página
    if(!is_exist_fb_page($page_id)){
        die();
    }
    
    $data_return = array(
                            'has_local_fans' => false
                   );
    
    $local_fans_last_date = get_local_fans_last_date($page_id);
    $date_last_month = date('Y-m-d', strtotime($local_fans_last_date.' -'.($days).' day'));
    
    if(!empty($local_fans_last_date)){
        $query = 'SELECT * FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country
                  WHERE id_page = $1 
                    AND id_country = $2
                    AND date >= \'$3\';';
        $que_pages = db_query($query, array($page_id, $country_id, $date_last_month));
        
        if(mysql_num_rows($que_pages) > 0){
            
            $data_return['has_local_fans'] = true;
            $data_return['items'] = array();
            
            while($local_fans = mysql_fetch_assoc($que_pages)){
                $data_return['items'][] = array('likes' => $local_fans['likes'], 'date' => $local_fans['date']);
            }
        }
    }
    
    echo json_encode($data_return);
    die();
