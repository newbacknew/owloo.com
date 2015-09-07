<?php
    
    die();
    
    set_time_limit(0);
    
    require_once (__DIR__.'/../config.php');
    
    //Complete data from results
    /*$query = 'SELECT id, username, followed_by_count, follows_count, media_count, charts FROM web_instagram_profiles ORDER BY 1;'; 
    $que = db_query_table_results($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        
        $charts = json_decode($fila['charts'], true);
        
        $followers_data = explode(',', $charts['followers']['series_data']);
        $followers_axis = explode(',', $charts['followers']['x_axis']);
        
        $following_grow = explode(',', $charts['daily_following_grow']['series_data']);
        
        $sum_following_grow = array_sum($following_grow);
        
        for($i=1, $limit = count($followers_data); $i < $limit; $i++) {
            
            $date = explode(' ', $followers_axis[$i]);
            $date[1] = ($date[1]=='Dic'?'12':'01'); 
            
            $sum_following_grow -= $following_grow[$i-1];
            
            $query = 'INSERT INTO instagram_record VALUES($1, $2, $3, $4, \'$5\');';
            $row = db_query($query, array($fila['id'], 0, $followers_data[$i], ($fila['follows_count'] - ($sum_following_grow)), $date[2].'-'.$date[1].'-'.$date[0]), 1);
            
        }
        
    }*/
    
    //Complete media part 1
    /*$query = 'SELECT id_profile FROM instagram_profiles ORDER BY 1;'; 
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        
        $id_profile = $fila['id_profile'];
        
        $query = 'SELECT media FROM instagram_record WHERE id_profile = $1 AND date = \'2015-01-21\';'; 
        $que_media = db_query($query, array($id_profile));
        
        $media = NULL;
        if($fila_media = mysql_fetch_assoc($que_media)){
            $media = $fila_media['media'];
        }
        
        $query = 'SELECT id_profile, DATE_FORMAT(FROM_UNIXTIME(`created_time`), \'%Y-%m-%d\') AS date, count(*) AS count FROM `instagram_media` 
                  WHERE id_profile = $1 
                  GROUP BY 1, 2 
                  HAVING date > \'2014-12-20\' AND date < \'2015-01-21\'
                  ORDER BY 2 DESC;'; 
        $que_media = db_query($query, array($id_profile));
        
        while($fila_media = mysql_fetch_assoc($que_media)){
            
            $media -= $fila_media['count'];
            
            $query = 'UPDATE instagram_record SET media = $1 WHERE id_profile = $2 AND date = \'$3\';';
            $row = db_query($query, array($media, $id_profile, $fila_media['date']), 1);
            
        }
        
    }*/
    
    //Complete media part 2
    $query = 'SELECT id_profile FROM instagram_profiles ORDER BY 1;'; 
    $que = db_query($query, array());
    
    while($fila = mysql_fetch_assoc($que)){
        
        $id_profile = $fila['id_profile'];
        
        $query = 'SELECT * FROM instagram_record WHERE id_profile = $1 ORDER BY date ASC;'; 
        $que_media = db_query($query, array($id_profile));
        
        $last_media = -1;
        while($fila_media = mysql_fetch_assoc($que_media)){
            
            if($last_media == -1 && $fila_media['media'] == 0){
                continue;
            }else{
                if($fila_media['media'] == 0){
                    
                    $query = 'UPDATE instagram_record SET media = $1 WHERE id_profile = $2 AND date = \'$3\';';
                    $row = db_query($query, array($last_media, $id_profile, $fila_media['date']), 1);
                    
                }else {
                    $last_media = $fila_media['media'];
                }
                
            }
            
        }
        
    }
    
    