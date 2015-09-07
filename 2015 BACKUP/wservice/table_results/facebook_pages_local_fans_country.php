<?php
    
    set_time_limit(0);
    
    error_log('   Facebook Page Local Fans (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');
    
    function get_local_fans_last_date($page_id, $country_id){
        $sql = 'SELECT date FROM '.DB_FACEBOOK_PREFIX.'page_local_fans_country
                WHERE id_page = $1
                    AND id_country = $2
                ORDER BY 1 DESC
                LIMIT 1;';
        
        $res = db_query($sql, array($page_id, $country_id));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    /*** Eliminamos los datos actuales ***/
    $query = "TRUNCATE ".DB_RESULTS_PREFIX."facebook_page_local_fans_country;";
    $values = array();
    $res = db_query_table_results($query, $values, 1);
    /*** END - Eliminamos los datos actuales ***/
    
    /*** Insertamios los datos nuevos ***/
    
    $date_last_40_days = date('Y-m-d', strtotime(date('Y-m-d').' -40 day'));
    
    $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_page_local_fans_country
                SELECT * FROM owloo_owloo.".DB_FACEBOOK_PREFIX."page_local_fans_country WHERE DATE > '$1';";
    $values = array($date_last_40_days);
    $res = db_query_table_results($query, $values, 1);
    /*** END - Insertamios los datos nuevos ***/
    
    error_log('   Facebook Page Local Fans (f): '.date('d m Y H:i:s'));