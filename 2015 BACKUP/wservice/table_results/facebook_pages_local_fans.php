<?php
    
    set_time_limit(0);
    
    error_log('   Facebook Page Local Fans (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_fb_page_local_fans_last_date($id_page){
        $sql = 'SELECT date FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country WHERE id_page = $1 ORDER BY 1 DESC LIMIT 1;';
        
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
    
    $query = "DELETE FROM ".DB_RESULTS_PREFIX."facebook_pages_local_fans;";
    $values = array();
    $res = db_query_table_results($query, $values, 1);
    
    $query = 'SELECT id_page, parent, fb_id, name, username, is_verified, likes, likes_grow_7, talking_about, category_id, sub_category_id
              FROM '.DB_RESULTS_PREFIX.'facebook_pages
              ORDER BY 1 ASC;';
    $que_page = db_query_table_results($query, array());
        
    while($page_info = mysql_fetch_assoc($que_page)){
        
        $id_page = $page_info['id_page'];
        $local_fans_last_date = get_fb_page_local_fans_last_date($page_info['id_page']);
        
        $query = 'SELECT * FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country WHERE id_page = $1 AND date = \'$2\';';
        $que_pages = db_query($query, array($page_info['id_page'], $local_fans_last_date));
        while($pages = mysql_fetch_assoc($que_pages)){
            $country_code = $countries[$pages['id_country']]['code'];
            $country_name = $countries[$pages['id_country']]['name'];
            $country_slug = $countries[$pages['id_country']]['slug'];
            $likes = $pages['likes'];
            
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
                                $likes/*,
                                $likes_history*/
                           );
            $res = db_query_table_results($query, $values, 1);

        }
        
    }

    //Update ranking local fans country
    
    $countries = array();
    $query = 'SELECT id_country, code
                FROM '.DB_FACEBOOK_PREFIX.'country_3_1
                WHERE code in(SELECT DISTINCT country_code FROM owloo_results.'.DB_RESULTS_PREFIX.'facebook_pages WHERE country_code is not null)
                    OR code in(SELECT DISTINCT first_country_code FROM owloo_results.'.DB_RESULTS_PREFIX.'facebook_pages WHERE first_country_code is not null)
                ORDER BY 1;'; 
    $que = db_query($query, array());
    
    while($country = mysql_fetch_assoc($que)){
        $sql = "SELECT id_page, likes_local_fans 
                   FROM ".DB_RESULTS_PREFIX."facebook_pages_local_fans
                   WHERE country_code = '$10' 
                   ORDER BY likes_local_fans DESC
                 ;";
        $que_pages = db_query_table_results($sql, array($country['code']));
        $count = 1;
        while($pages = mysql_fetch_assoc($que_pages)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_pages SET first_local_fans_country_ranking = $10 WHERE id_page = $11 AND first_country_code = '$12';";
            $values = array($count, $pages['id_page'], $country['code']);
            $res = db_query_table_results($query, $values, 1);
            
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_pages SET country_ranking = $10 WHERE id_page = $11 AND country_code = '$12';";
            $values = array($count, $pages['id_page'], $country['code']);
            $res = db_query_table_results($query, $values, 1);
            
            $count++;
        }
    }
    
    error_log('   Facebook Page Local Fans (f): '.date('d m Y H:i:s'));