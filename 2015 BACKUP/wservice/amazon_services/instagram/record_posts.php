<?php
    
    function instagram_user_posts($user_id, $max_id = NULL, $min_id = NULL, $pagination = NULL, $count_posts = NULL){
        
        $limit = '';
        if(!empty($max_id)){
            $limit = '&max_id='.$max_id;
        }
        if(!empty($min_id)){
            $limit = '&min_id='.$min_id;
        }
        
        if($count_posts === NULL){
            $count_posts = INSTAGRAM_COUNT_DATA;
        }
        
        if(empty($pagination)){
            $data = get_url_content("https://api.instagram.com/v1/users/$user_id/media/recent/?client_id=".INSTAGRAM_CLIENT_ID."&count=".$count_posts.$limit);
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
    
    function update_instagram_media($update_type, $id_profile, $count_posts = NULL){
        
        $query = 'SELECT id_profile, instagram_id FROM instagram_profiles WHERE id_profile = $1 AND active = 1;';
        $result_profile = db_query($query, array($id_profile));
        
        while ($profile = mysql_fetch_assoc($result_profile)) {
            
            $pagination = NULL;
            $num_posts = 0;
            
            do{
               
                $have_post = false;
                $meta_code = NULL;
               
                if(empty($pagination)){
                    
                    $query = 'SELECT id_instagram_media FROM instagram_media WHERE id_profile = $1 ORDER BY created_time DESC LIMIT 1;';
                    $result_media = db_query($query, array($profile['id_profile']));
                    
                    if($media = mysql_fetch_assoc($result_media)) {
                        $posts = instagram_user_posts($profile['instagram_id'], NULL, $media['id_instagram_media'], NULL, $count_posts);
                        
                    }else {
                        $posts = instagram_user_posts($profile['instagram_id'], NULL, NULL, NULL, $count_posts);
                    }
                }else {
                    $posts = instagram_user_posts($profile['instagram_id'], NULL, NULL, $pagination, $count_posts);
                    $pagination = NULL;
                }
                
                $meta_code = (isset($posts['meta']['code'])?$posts['meta']['code']:NULL);
                
                if(isset($posts['pagination']['next_url'])){
                    $pagination = $posts['pagination']['next_url'];
                }
                else {
                    $pagination = NULL;
                }
                
                if(isset($posts['meta']['code']) && $posts['meta']['code'] == 200){
                     foreach ($posts['data'] as $post) {
                         
                         if($post['id'] == $media['id_instagram_media']){
                             $have_post = false;
                             continue;
                         }
                             
                         $have_post = true;
                         $num_posts++;
                         
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
                         if($count_posts !== NULL && $num_posts >= $count_posts){
                             $have_post = false;
                             break;
                         }
                     }
                 }else{
                     error_log('Meta code: '.json_encode($posts).' - '.date('d m Y H:i:s'));
                 }
    
            }while($have_post && $meta_code == 200);
            
         }
    }