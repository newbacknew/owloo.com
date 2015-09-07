<?php
    
    set_time_limit(0);
    
    include('../config/config_cronjob.php');
    
    $user_id = 308;
    
    $sql = 'SELECT owloo_id_str FROM `owoo_tweet_data` WHERE `owloo_user_id` = '.$user_id.' GROUP BY 1 HAVING count(*) > 1 LIMIT 1;';
    $result = mysql_query($sql) or die(mysql_error());
    
    while ($fila = mysql_fetch_array($result)) {
        
        
        
        $query = 'SELECT id FROM `owoo_tweet_data` WHERE `owloo_user_id` = '.$user_id.' AND `owloo_id_str` = '.$fila['owloo_id_str'].' ORDER BY id ASC LIMIT 1;';
        $id_result = mysql_query($query) or die(mysql_error());
        $id_repeat = 0;
        if ($id_fila = mysql_fetch_array($id_result)) {
            $id_repeat = $id_fila['id'];
        }
        
        $query_delete = 'SELECT id FROM `owoo_tweet_data` WHERE `owloo_user_id` = '.$user_id.' AND `owloo_id_str` = '.$fila['owloo_id_str'].' AND id > '.$id_repeat.' ORDER BY id ASC;';
        $id_delete_result = mysql_query($query_delete) or die(mysql_error());
        while ($id_delete_fila = mysql_fetch_array($id_delete_result)) {
            echo 'DELETE ID -> '.$id_delete_fila['id'].'<br/>';
        }
        
        
    }