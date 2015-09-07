<?php
    
    set_time_limit(0);
    
    error_log('   Facebook Page Local Fans (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_fb_page_local_fans_general_last_date(){
        $sql = 'SELECT date, count(*) FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country WHERE date > DATE_SUB(DATE(NOW()), INTERVAL 6 DAY) GROUP BY 1 ORDER BY 2 DESC, 1 DESC';
        
        $res = db_query($sql, array($id_page));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
        
    //Países
    $countries = array();
    $query = 'SELECT id_country, code, nombre, slug FROM '.DB_FACEBOOK_PREFIX.'country_3_1 ORDER BY 1;';
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        $countries[$fila['id_country']] = array('code' => $fila['code'], 'name' => $fila['nombre'], 'slug' => $fila['slug']);
    }
    
    $local_fans_last_date = get_fb_page_local_fans_general_last_date();
    
    $query = 'SELECT * FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country WHERE date = \'$1\';';
    $que_pages = db_query($query, array($local_fans_last_date));
    
    $last_id_page = NULL;
    $page_info = array();
    
    while($pages = mysql_fetch_assoc($que_pages)){
        
        if($last_id_page != $pages['id_page']){
            $page_info = array();
            $query = "SELECT id_page, parent, fb_id, name, username, is_verified, likes, likes_grow_7, talking_about, category_id, sub_category_id FROM ".DB_RESULTS_PREFIX."facebook_pages WHERE id_page = $10;";
            $que_page = db_query_table_results($query, array($pages['id_page']));
            
            if($page_info = mysql_fetch_assoc($que_page)){
                $last_id_page = $pages['id_page'];
            }
            else {
                continue;
            }
        }
       
        $id_page = $pages['id_page'];
                
        $country_code = $countries[$pages['id_country']]['code'];
        $country_name = $countries[$pages['id_country']]['name'];
        $country_slug = $countries[$pages['id_country']]['slug'];
        
        $likes = $pages['likes'];

        /*$query = "DELETE FROM ".DB_RESULTS_PREFIX."facebook_pages_local_fans WHERE id_page = $10;";
        $values = array($id_page);
        $res = db_query_table_results($query, $values, 1);*/

        $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_pages_local_fans VALUES(NULL, $10, $11, $12, '$13', '$14', $15, $16, $17, $18, $19, $20, '$21', '$22', '$23', $24, NOW());";
        $values = array(
                            $id_page,
                            $page_info['parent'],
                            $page_info['fb_id'],
                            $page_info['username'],
                            $page_info['name'],
                            $page_info['is_verified'],
                            $page_info['likes'],
                            (!empty($page_info['likes_grow_7'])?$page_info['likes_grow_7']:'NULL'),
                            $page_info['talking_about'],
                            (!empty($page_info['category_id'])?$page_info['category_id']:'NULL'),
                            (!empty($page_info['sub_category_id'])?$page_info['sub_category_id']:'NULL'),
                            $country_code,
                            $country_name,
                            $country_slug,
                            $likes
                       );
        $res = db_query_table_results($query, $values, 1);

    }
    
    error_log('   Facebook Page Local Fans (f): '.date('d m Y H:i:s'));