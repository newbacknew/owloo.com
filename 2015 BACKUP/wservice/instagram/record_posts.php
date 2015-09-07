<?php
    
    //SELECT i.id_profile, media, cantidad FROM instagram_record i join (SELECT id_profile, count(*) cantidad FROM `instagram_media` GROUP BY 1) media ON i.id_profile = media.id_profile WHERE i.date = DATE(NOW()) AND media > cantidad
    
    set_time_limit(0);
    
    error_log('Record Posts (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
        
    function instagram_user_posts($user_id, $max_id = NULL, $min_id = NULL, $pagination = NULL){
        
        $limit = '';
        if(!empty($max_id)){
            $limit = '&max_id='.$max_id;
        }
        if(!empty($min_id)){
            $limit = '&min_id='.$min_id;
        }
        
        if(empty($pagination)){
            $data = get_url_content("https://api.instagram.com/v1/users/$user_id/media/recent/?client_id=".INSTAGRAM_CLIENT_ID."&count=".INSTAGRAM_COUNT_DATA.$limit);
        }else {
            $data = get_url_content($pagination);
        }
        
        return json_decode($data, true);
    }
    
    function get_id_media_from_id_instagram_media($id_instagram_media){
        $query = 'SELECT id_media FROM instagram_media WHERE id_instagram_media LIKE \'$1\';';
        $result_media = db_query($query, array($id_instagram_media));
        if($media = mysql_fetch_assoc($result_media)) {
            return $media['id_media'];
        }
        return NULL;
    }
    
    function update_instagram_media($update_type, $days = NULL){
        
        switch ($update_type) {
            case 'update_post_info':
                $query = 'SELECT id_profile, instagram_id FROM instagram_profiles WHERE active = 1;';
                break;
            default:
                $query = 'SELECT id_profile, instagram_id FROM instagram_profiles WHERE active = 1 AND id_profile IN (SELECT i.id_profile FROM instagram_record i LEFT JOIN (SELECT id_profile, count(*) cantidad FROM instagram_media GROUP BY 1) media ON i.id_profile = media.id_profile WHERE i.date = DATE(NOW()) AND (media > cantidad OR cantidad is null));';
                break;
        }
        
        $result_profile = db_query($query, array());
        while ($profile = mysql_fetch_assoc($result_profile)) {
            
            $pagination = NULL;
            
            do{
               
                $have_post = false;
                $meta_code = NULL;
               
                if(empty($pagination)){
                    
                    switch ($update_type) {
                        case 'update_post_info':
                            $query = 'SELECT id_instagram_media FROM instagram_media WHERE id_profile = $1 AND created_time > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL '.$days.' DAY)) ORDER BY created_time ASC LIMIT 1;';
                            break;
                        default:
                            $query = 'SELECT id_instagram_media FROM instagram_media WHERE id_profile = $1 ORDER BY created_time '.($update_type=='add_new_posts'?'DESC':'ASC').' LIMIT 1;';
                            break;
                    }
                    
                    $result_media = db_query($query, array($profile['id_profile']));
                    if($media = mysql_fetch_assoc($result_media)) {
                        if($update_type == 'add_old_posts'){
                            $posts = instagram_user_posts($profile['instagram_id'], $media['id_instagram_media']);
                        }else {
                            $posts = instagram_user_posts($profile['instagram_id'], NULL, $media['id_instagram_media']);
                        }
                    }else {
                        $posts = instagram_user_posts($profile['instagram_id']);
                    }
                }else {
                    $posts = instagram_user_posts($profile['instagram_id'], NULL, NULL, $pagination);
                    $pagination = NULL;
                }
                
                $meta_code = (isset($posts['meta']['code'])?$posts['meta']['code']:NULL);
                
                if(($update_type == 'update_post_info' || $update_type == 'add_new_posts') && isset($posts['pagination']['next_url'])){
                    $pagination = $posts['pagination']['next_url'];
                }
                else {
                    $pagination = NULL;
                }
                
                if(isset($posts['meta']['code']) && $posts['meta']['code'] == 200){
                     foreach ($posts['data'] as $post) {
                         
                         if($update_type == 'add_new_posts'){
                             if($post['id'] == $media['id_instagram_media']){
                                 $have_post = false;
                                 continue;
                             }
                         }
                         
                         $have_post = true;
                         $id_profile = $profile['id_profile'];
                         $id_instagram_media = $post['id'];
                         $caption_text = (isset($post['caption']['text'])?$post['caption']['text']:'');
                         $type = $post['type'];
                         $images_standard_resolution = (isset($post['images']['standard_resolution']['url'])?$post['images']['standard_resolution']['url']:'');
                         $videos_standard_resolution = (isset($post['videos']['standard_resolution']['url'])?$post['videos']['standard_resolution']['url']:'NULL');
                         $filter = $post['filter'];
                         $comments_count = (isset($post['comments']['count'])?$post['comments']['count']:'0');
                         $likes_count = (isset($post['likes']['count'])?$post['likes']['count']:'0');
                         $location_latitude = (isset($post['location']['latitude'])?$post['location']['latitude']:'NULL');
                         $location_longitude = (isset($post['location']['longitude'])?$post['location']['longitude']:'NULL');
                         $location_name = (isset($post['location']['name'])?$post['location']['name']:'NULL');
                         $location_id = (isset($post['location']['id'])?$post['location']['id']:'NULL');
                         $link = $post['link'];
                         $created_time = $post['created_time'];
                         
                         if($update_type == 'update_post_info'){
                             $id_media = get_id_media_from_id_instagram_media($id_instagram_media);
                         }
                         
                         if($update_type == 'update_post_info' && !empty($id_media)){
                             
                             $id_media = get_id_media_from_id_instagram_media($id_instagram_media);
                             
                             $query = "UPDATE instagram_media SET  
                                                                caption_text = '$10',
                                                                type = '$11', 
                                                                images_standard_resolution = '$12', 
                                                                videos_standard_resolution = ".($videos_standard_resolution!="NULL"?"'$13'":"$13").", 
                                                                filter = '$14',
                                                                comments_count = $15,
                                                                likes_count = $16,
                                                                location_latitude = $17,
                                                                location_longitude = $18,
                                                                location_name = ".($location_name!="NULL"?"'$19'":"$19").", 
                                                                location_id = $20, 
                                                                link = '$21',
                                                                created_time = $22, 
                                                                updated_at = NOW()
                                                                
                                                                WHERE id_media = $23;";
                             $values = array(
                                                $caption_text,
                                                $type, 
                                                $images_standard_resolution,
                                                $videos_standard_resolution,
                                                $filter,
                                                $comments_count,
                                                $likes_count,
                                                $location_latitude,
                                                $location_longitude,
                                                $location_name, 
                                                $location_id,
                                                $link,
                                                $created_time,
                                                $id_media
                                          );
                             $res = db_query($query, $values, 1, 10);
                             
                             //Eliminamos los comentarios del post actual
                             $query = 'DELETE FROM instagram_media_comments WHERE id_profile = $1 AND id_media = $2;';
                             $result_insert = db_query($query, array(
                                                                    $id_profile,
                                                                    $id_media
                                                               ), 1);
                             
                             $aux_count = 1;
                             foreach ($post['comments']['data'] as $comment) {
                                 
                                 $query = 'INSERT INTO instagram_media_comments VALUES(NULL, $1, $2, \'$3\', \'$4\', $5, \'$6\', \'$7\', \'$8\', $9);';
                                 $result_insert = db_query($query, array(
                                                                        $id_profile,
                                                                        $id_media,
                                                                        $comment['text'],
                                                                        $comment['from']['username'],
                                                                        $comment['from']['id'],
                                                                        $comment['from']['full_name'],
                                                                        $comment['from']['profile_picture'],
                                                                        $comment['id'],
                                                                        $comment['created_time']
                                                                      ), 1);
                                 
                                 
                                 if($aux_count++ > 4){
                                     break;
                                 }
                             }
                         }else{
                             $query = 'INSERT INTO instagram_media VALUES(NULL, $10, \'$11\', \'$12\', \'$13\', \'$14\', '.($videos_standard_resolution!="NULL"?"'$15'":"$15").', \'$16\', $17, $18, $19, $20, '.($location_name!="NULL"?"'$21'":"$21").', $22, \'$23\', $24, 1, NOW(), NOW());';
                             $result_insert = db_query($query, array(
                                                                    $id_profile,
                                                                    $id_instagram_media,
                                                                    $caption_text,
                                                                    $type,
                                                                    $images_standard_resolution,
                                                                    $videos_standard_resolution,
                                                                    $filter,
                                                                    $comments_count,
                                                                    $likes_count,
                                                                    $location_latitude,
                                                                    $location_longitude,
                                                                    $location_name,
                                                                    $location_id,
                                                                    $link,
                                                                    $created_time
                                                                  ), 1, 10);
                             
                              $id_media = get_id_media_from_id_instagram_media($id_instagram_media);
                             
                              /*** Tags ***/
                              if(isset($post['tags'])){
                                 foreach ($post['tags'] as $tag) {
                                     $query = 'INSERT INTO instagram_media_tags VALUES(NULL, $1, $2, \'$3\');';
                                     $result_insert = db_query($query, array(
                                                                            $id_profile,
                                                                            $id_media,
                                                                            $tag
                                                                          ), 1);
                                  }
                              }
                             
                             /*** Mentions ***/
                             if(preg_match_all('/(?<!\w)@(\w+)/', $caption_text, $matches)){
                                $mentions = $matches[1];
                                foreach ($mentions as $mention){
                                    $query = 'INSERT INTO instagram_media_mentions VALUES(NULL, $1, $2, \'$3\');';
                                    $result_insert = db_query($query, array(
                                                                            $id_profile,
                                                                            $id_media,
                                                                            strtolower($mention)
                                                                          ), 1);
                                }
                             }
                             
                             /*** Comments ***/
                             $aux_count = 1;
                             foreach ($post['comments']['data'] as $comment) {
                                 
                                 $query = 'INSERT INTO instagram_media_comments VALUES(NULL, $1, $2, \'$3\', \'$4\', $5, \'$6\', \'$7\', \'$8\', $9);';
                                 $result_insert = db_query($query, array(
                                                                        $id_profile,
                                                                        $id_media,
                                                                        $comment['text'],
                                                                        $comment['from']['username'],
                                                                        $comment['from']['id'],
                                                                        $comment['from']['full_name'],
                                                                        $comment['from']['profile_picture'],
                                                                        $comment['id'],
                                                                        $comment['created_time']
                                                                      ), 1);
                                 
                                 
                                 if($aux_count++ > 4){
                                     break;
                                 }
                             }
                         }
                     }
                 }else{
                     error_log('Meta code: '.json_encode($posts).' - '.date('d m Y H:i:s'));
                 }
                 
                 if($update_type == 'update_post_info' && empty($pagination)){
                     $have_post = false;
                 }
    
            }while($have_post && $meta_code == 200);
            
         }
    }

    update_instagram_media('add_new_posts'); //Capturamos los nuevos posteos
    
    update_instagram_media('add_old_posts'); //Capturamos los viejos posteos
    
    update_instagram_media('update_post_info', 90); //Actualizamos los datos de los pasteos de hace 3 meses
    
    

    error_log('Record Posts (f): '.date('d m Y H:i:s'));