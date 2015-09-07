<?php
    
    function is_exist_fb_page($page_id){
        $sql = 'SELECT * FROM '.DB_FACEBOOK_PREFIX.'page
                WHERE id_page = $1;';
        $res = db_query($sql, array($page_id));
        if($fila = mysql_fetch_assoc($res)){
            return true;
        }
        return false;
    }
    
    function get_country_data($id_country){
        $query = 'SELECT id_country, code, nombre, slug FROM '.DB_FACEBOOK_PREFIX.'country_3_1 WHERE id_country = $1;';
        $que = db_query($query, array($id_country));
        if($fila = mysql_fetch_assoc($que)){
            return array('code' => $fila['code'], 'name' => $fila['nombre'], 'slug' => $fila['slug']);
        }
        return NULL;
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
    
    function register_local_fans($page_id){
        
        $data_return = array(
                'has_local_fans' => false
       );
        
        $local_fans_last_date = get_local_fans_last_date($page_id);
        
        if(!empty($local_fans_last_date)){
            
            //Get page's data
            $query = 'SELECT id_page, parent, fb_id, name, username, is_verified, likes, likes_grow_7, talking_about, category_id, sub_category_id
                        FROM '.DB_RESULTS_PREFIX.'facebook_pages
                        WHERE id_page = $10;';
            $que_pages = db_query_table_results($query, array($page_id));
            $page = mysql_fetch_assoc($que_pages);
            
            //Get local fans
            $query = 'SELECT * FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country
                      WHERE id_page = $1 
                        AND date = \'$2\';';
            $que_local_fans = db_query($query, array($page_id, $local_fans_last_date));
            
            if(mysql_num_rows($que_local_fans) > 0){
                
                $data_return['has_local_fans'] = true;
                $data_return['date'] = $local_fans_last_date;
                $data_return['items'] = array();
                
                //Controlamos que no se duplique
                $local_fans_exist_in_result = false;
                $query = 'SELECT id_page
                            FROM '.DB_RESULTS_PREFIX.'facebook_pages_local_fans
                            WHERE id_page = $10
                            LIMIT 1;';
                $result = db_query_table_results($query, array($page_id));
                if(mysql_num_rows($result) > 0){
                    $local_fans_exist_in_result = true;
                }
                
                
                while($local_fans = mysql_fetch_assoc($que_local_fans)){
                    $data_return['items'][] = array('c' => $local_fans['id_country'], 'l' => $local_fans['likes']);
                    
                    if(!$local_fans_exist_in_result){
                        $country = get_country_data($local_fans['id_country']);
                        
                        $country_code = $country['code'];
                        $country_name = $country['name'];
                        $country_slug = $country['slug'];
                        
                        $likes = $local_fans['likes'];
                        
                        //Insert into result's table
                        $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_pages_local_fans VALUES(NULL, $10, $11, $12, '$13', '$14', $15, $16, $17, $18, $19, $20, '$21', '$22', '$23', $24, NOW());";
                        $values = array(
                                            $page['id_page'],
                                            $page['parent'],
                                            $page['fb_id'],
                                            $page['username'],
                                            $page['name'],
                                            $page['is_verified'],
                                            $page['likes'],
                                            (!empty($page['likes_grow_7'])?$page['likes_grow_7']:'NULL'),
                                            $page['talking_about'],
                                            (!empty($page['category_id'])?$page['category_id']:'NULL'),
                                            (!empty($page['sub_category_id'])?$page['sub_category_id']:'NULL'),
                                            $country_code,
                                            $country_name,
                                            $country_slug,
                                            $likes/*,
                                            $likes_history*/
                                       );
                        $res = db_query_table_results($query, $values, 1);
                    }
                }
            }
        }
        return $data_return;
    }
