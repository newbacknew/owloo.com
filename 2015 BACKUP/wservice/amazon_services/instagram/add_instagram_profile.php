<?php
    
    set_time_limit(0);
    
    header('Content-Type: application/json');
    
    error_log('   Add Profile (i): '.date('d m Y H:i:s'));
    
    include_once(__DIR__.'/../../config.php');
    
    function generate_data_results($profile_id){
        error_log('      Generate profile (i): '.date('d m Y H:i:s'));
        $json_data_profile = get_url_content('http://www.owloo.com/wservice/table_results/instagram_profiles.php?generate_new_profile=true&generate_new_profile_id='.$profile_id);
        error_log('      Generate profile (f): '.date('d m Y H:i:s'));
        
        echo $json_data_profile;
        
        error_log('   Generate Results (f): '.date('d m Y H:i:s'));
        
        die();
    }
    
    /***** VALIDATIONS ****/
    
    $message = array('message_code' => NULL, 'description' => NULL);
    $category = NULL;
    
    if(!(isset($_GET['username']) AND !empty($_GET['username']))){
        $message['message_code'] = 1;
        $message['description'] = 'Profile not found';
    }
    elseif(!(isset($_GET['category']) AND !empty($_GET['category']))) {
        $message['message_code'] = 2;
        $message['description'] = 'Category not found';
    }else{
        $category = get_category_data($_GET['category']);
        if(empty($category)){
            $message['message_code'] = 2;
            $message['description'] = 'Category not found';
        }
    }
    
    if($message['message_code'] !== NULL){
        echo json_encode($message);
        die();
    }
    
    /***** END - VALIDATIONS ****/
    
    function get_category_data($category_slug){
        $query = 'SELECT id_category, category FROM instagram_category WHERE category = \'$1\';';
        $categories = db_query($query, array($category_slug));
        if($fila = mysql_fetch_assoc($categories)){
            return $fila;
        }
        return NULL;
    }
    
    function get_instagram_profile_id($instagram_id){
        $query = 'SELECT id_profile FROM instagram_profiles WHERE instagram_id = $1;';
        $categories = db_query($query, array($instagram_id));
        if($fila = mysql_fetch_assoc($categories)){
            return $fila['id_profile'];
        }
        return false;
    }
    
    function instagram_clean_username($username){
        $username = urldecode($username);
        $search = array('https', 'http', '://', 'www.', 'instagram.com/');
        $replace = array('');
        $username = str_replace($search, $replace, $username);
        $chart_position = strpos( $username, '/');
        if($chart_position){
            $username = substr($username, 0, $chart_position);
        }
        return strtolower($username);
    }
    
    function instagram_search_username($username){
        $data = get_url_content("https://api.instagram.com/v1/users/search?q=$username&client_id=".INSTAGRAM_CLIENT_ID);
        return json_decode($data, true);
    }
    
    function instagram_user_data($user_id){
        $data = get_url_content("https://api.instagram.com/v1/users/$user_id/?client_id=".INSTAGRAM_CLIENT_ID);
        return json_decode($data, true);
    }
    
    $count_data = 100;
    
    $username = instagram_clean_username($_GET['username']);
    $found_user = NULL;
    $mensaje = '';
    $new_register = false;
    
    $data = instagram_search_username($username);
    
    if($data['meta']['code'] == 200){
        $ban = 0;
        foreach ($data['data'] as $user) {
            if($user['username'] == $username){
                
                $ban = 1;
                
                $user_data = instagram_user_data($user['id']);
                
                if($user_data['meta']['code'] == 200){
                    
                    $found_user = $user_data['data'];
                    
                    $profile_id = get_instagram_profile_id($found_user['id']);
                    
                    if(empty($profile_id)){
                        
                        if(empty($found_user['bio'])){
                            $found_user['bio'] = 'NULL';
                        }
                        if(empty($found_user['website'])){
                            $found_user['website'] = 'NULL';
                        }
                        
                        $query = 'INSERT INTO instagram_profiles VALUES(NULL, $1, \'$2\', '.($found_user['bio']!="NULL"?"'$3'":"$3").', '.($found_user['website']!="NULL"?"'$4'":"$4").', \'$5\', \'$6\', $7, 1, NOW(), NOW());';
                        $row = db_query($query, array($found_user['id'], $found_user['username'], $found_user['bio'], $found_user['website'], $found_user['profile_picture'], $found_user['full_name'], $category['id_category']), 1);
                        $new_register = true;
                        $profile_id = get_instagram_profile_id($found_user['id']);
                        if(!empty($profile_id)){
                            
                            $query = 'INSERT INTO instagram_record VALUES($1, $2, $3, $4, NOW());';
                            $row = db_query($query, array($profile_id, $found_user['counts']['media'], $found_user['counts']['followed_by'], $found_user['counts']['follows']), 1);
                            
                            /***** Insertamos los posteos *****/
                            error_log('Record Posts (i): '.date('d m Y H:i:s'));
                            include('record_posts.php');
                            update_instagram_media('add_new_posts', $profile_id, 5); //Capturamos los nuevos posteos
                            error_log('Record Posts (f): '.date('d m Y H:i:s'));     
                            
                            /***** El usuario se ha registrado! *****/
                            //Generamos y enviamos los datos
                            error_log('   Add Profile (f): '.date('d m Y H:i:s'));
                            error_log('   Generate Results (i): '.date('d m Y H:i:s'));
                            generate_data_results($profile_id);                      
                            
                        }else {
                            $message = array('message_code' => 3, 'description' => 'Sorry, an error has occurred');
                        }
                    }else { // El usuario ya existe!
                        //Generamos y enviamos los datos
                        error_log('   Add Profile (f): '.date('d m Y H:i:s'));
                        error_log('   Generate Results (i): '.date('d m Y H:i:s'));
                        generate_data_results($profile_id);
                    }
                    
                }
                else {
                    $message['message_code'] = 3;
                    $message['description'] = 'Sorry, an error has occurred';
                }
                
                break;
                
            }
        }
        if($ban == 0){
            $message['message_code'] = 1;
            $message['description'] = 'Profile not found';
        }
    }
    else {
        $message['message_code'] = 3;
        $message['description'] = 'Sorry, an error has occurred';
    }
    
    echo json_encode($message);
    die();