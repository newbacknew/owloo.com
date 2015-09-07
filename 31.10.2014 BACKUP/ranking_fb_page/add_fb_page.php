<?php
    if(!empty($_GET['username'])){
        $fb_page_id = get_fb_page_id_from_url($_GET['username']);
        if(!empty($fb_page_id) && is_numeric($fb_page_id)){
            $datos = get_url_content('https://graph.facebook.com/'.$fb_page_id.'?fields=id,username,name,about,description,link,picture,cover,location,is_verified,likes,talking_about_count,category&locale=es_LA');
			
			//$datos = @file_get_contents('http://23.88.103.193/~david/owl/get_fb_data.php?url='.urlencode('https://graph.facebook.com/'.$fb_page_id.'?fields=id,username,name,about,description,link,picture,cover,location,is_verified,likes,talking_about_count,category&locale=es_LA'));
			$datos = json_decode ($datos, true);
            
            //print_r($datos);
			
            if(isset($datos['id']) && isset($datos['name'])){
                /*
                echo '<br/><br/>';
                echo 'id: '.$datos['id'].'<br/>';
                echo 'username: '.$datos['username'].'<br/>';
                echo 'name: '.$datos['name'].'<br/>';
                echo 'about: '.$datos['about'].'<br/>';
                echo 'description: '.$datos['description'].'<br/>';
                echo 'link: '.$datos['link'].'<br/>';
                echo 'picture: '.$datos['picture']['data']['url'].'<br/>';
                echo 'cover: '.$datos['cover']['source'].'<br/>';
                echo 'location: '.$datos['location']['country'].'<br/>';
                echo 'is_verified: '.$datos['is_verified'].'<br/>';
                echo 'likes: '.$datos['likes'].'<br/>';
                echo 'talking_about_count: '.$datos['talking_about_count'].'<br/>';
                echo 'category: '.$datos['category'].'<br/>';
                */
                
                $datos['username'] = (!empty($datos['username'])?$datos['username']:$datos['id']);
                
                $_fb_username = $datos['username'];
                
                if(!is_exist_fb_page($datos['id'])){
                
                    $id_page = add_fb_page($datos['id'], $datos['username'], $datos['name'], (isset($datos['about'])?$datos['about']:''), (isset($datos['description'])?$datos['description']:''), $datos['link'], $datos['picture']['data']['url'], $datos['cover']['source'], (isset($datos['location']['country'])?$datos['location']['country']:''), $datos['is_verified'], $datos['likes'], $datos['talking_about_count'], $datos['category']);
                    
                    if(!empty($id_page)){
                        
                        /***** Add likes and talking_about *****/
                        if(!is_exist_today_fb_page_likes_talking_about($id_page)){
                            add_fb_page_likes_talking_about($id_page, $datos['likes'], $datos['talking_about_count']);
                        }
                        /***** END - Add likes and talking_about *****/
                        
                        $access_token = get_fb_page_access_token();
                        $until = date('U');
                        $since = $until - (90 * 24 * 60 * 60);
                        $local_fans_country = get_url_content('https://graph.facebook.com/'.$datos['id'].'/insights/page_fans_country?since='.$since.'&until='.$until.'&locale=es_LA&access_token='.$access_token);
						
						//$local_fans_country = @file_get_contents('http://23.88.103.193/~david/owl/get_fb_data.php?url='.urlencode('https://graph.facebook.com/'.$datos['id'].'/insights/page_fans_country?since='.$since.'&until='.$until.'&locale=es_LA&access_token='.$access_token));
                        $local_fans_country = json_decode ($local_fans_country, true);
                        
                        //print_r($local_fans_country); die();
                        
                        if(isset($local_fans_country['data']) && $local_fans_country['data'][0]['name'] == 'page_fans_country'){
                            $sql_insert = '';
                            $aux_first = true;
                            foreach ($local_fans_country['data'][0]['values'] as $local_fan) {
                                $date = explode('T', $local_fan['end_time']);
                                foreach ($local_fan['value'] as $key => $value) {
                                    $_country_id = get_country_id_from_code($key);
                                    if(!empty($_country_id)){
                                        if(!$aux_first) $sql_insert .= ','; else $aux_first = false;
                                        $sql_insert .= "(NULL, ".mysql_real_escape_string($id_page).",".mysql_real_escape_string($_country_id).",".mysql_real_escape_string($value).",'".mysql_real_escape_string($date[0])."')";
                                    }
                                }
                            }
                            
                            if(!empty($sql_insert))
                                add_lote_fb_page_local_fan($sql_insert);
                        }
                        
                        update_fb_page_likes_talking_about_local_fans($id_page, $datos['likes'], $datos['talking_about_count'], get_first_country_local_fans($id_page));
                        
                    }
                    else {
                        $mensaje_new_fan_page = '<div>Lo sentimos, <strong>no hemos podido procesar su petición</strong>.</div>
                                                 <div>Favor, intentelo más tarde...</div>';
                    }
                }
            }
            else {
                $mensaje_new_fan_page = "<div>Puede que <strong>".$_GET["username"]."</strong> no esté registrado en Facebook.</div>
                               <div>Favor verifique la página ingresada y vuelve a intentarlo!</div>";
            }
        }
        else {
            $mensaje_new_fan_page = "<div>Puede que <strong>".$_GET["username"]."</strong> no esté registrado en Facebook.</div>
                               <div>Favor verifique la página ingresada y vuelve a intentarlo!</div>";
        }
    }
?>