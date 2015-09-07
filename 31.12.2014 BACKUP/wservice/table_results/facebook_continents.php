<?php
    set_time_limit(0);
    
    error_log('   Facebook Continent (i): '.date('d m Y H:i:s'));
    
    require_once(__DIR__.'/../config.php');

    function get_country_date_last_update($count){
        $sql = 'SELECT date 
                  FROM '.DB_FACEBOOK_PREFIX.'record_country_3_1
                  GROUP BY date 
                  HAVING count(date) = $1
                  ORDER BY 1 DESC 
                  LIMIT 1;
               ';
        $res = db_query($sql, array($count));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function get_country_date_last_x_days($last_day, $count_country, $days){
        $sql =   'SELECT date 
                    FROM '.DB_FACEBOOK_PREFIX.'record_country_3_1 
                    WHERE DATE_SUB(STR_TO_DATE(\'$1\', \'%Y-%m-%d\'), INTERVAL $2 DAY) <= date 
                    GROUP BY date
                    HAVING count(date) = $3
                    ORDER BY 1 ASC 
                    LIMIT 1;
                    ';
        $res = db_query($sql, array($last_day, $days, $count_country));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }

    //Países
    $countries = array();
    $query = 'SELECT * FROM '.DB_FACEBOOK_PREFIX.'country_3_1 ORDER BY 1;'; 
    $que = db_query($query, array());
    
    $country_date_last_update = get_country_date_last_update(mysql_num_rows($que));
    
    $ultimos_30_dias = get_country_date_last_x_days($country_date_last_update, mysql_num_rows($que), 30);
    
    $sql =   "SELECT cont1.id_continent id_continent, cont1.nombre nombre, sum(total_user) total_user, sum(total_female) total_female, sum(total_male) total_male, (
                    sum(total_user) - (SELECT sum(total_user) total_user 
                            FROM ".DB_FACEBOOK_PREFIX."record_country_3_1 r 
                                join ".DB_FACEBOOK_PREFIX."country_3_1 c on r.id_country = c.id_country 
                                join ".DB_FACEBOOK_PREFIX."continent cont on c.id_continent = cont.id_continent 
                            WHERE date = STR_TO_DATE('$1','%Y-%m-%d') 
                                AND cont.id_continent = cont1.id_continent)
                    ) cambio 
                FROM ".DB_FACEBOOK_PREFIX."record_country_3_1 rc1 
                    JOIN ".DB_FACEBOOK_PREFIX."country_3_1 c1 ON rc1.id_country = c1.id_country 
                    join ".DB_FACEBOOK_PREFIX."continent cont1 on c1.id_continent = cont1.id_continent 
                WHERE date = STR_TO_DATE('$2','%Y-%m-%d') 
                GROUP BY date, nombre
                ORDER BY 3 DESC;
                ";
    $res_continents = db_query($sql, array($ultimos_30_dias, $country_date_last_update));
    
    while($fila = mysql_fetch_assoc($res_continents)){
        
        $id_continent = $fila['id_continent'];    
        $name = $fila['nombre'];
        $total_user = $fila['total_user'];
        $total_female = $fila['total_female'];
        $total_male = $fila['total_male'];
        $grow_30 = $fila['cambio'];
        
        $query = 'SELECT id FROM '.DB_RESULTS_PREFIX.'facebook_continents WHERE id = $10;';
        $que_country = db_query_table_results($query, array($id_continent));
        if($row = mysql_fetch_assoc($que_country)){
            $query = "UPDATE ".DB_RESULTS_PREFIX."facebook_continents SET 
                                                name = '$10',
                                                total_user = $11,
                                                total_female = $12,
                                                total_male = $13,
                                                grow_30 = $14,
                                                updated_at = NOW()
                                                
                                                WHERE id = $15;";
            $values = array(
                                $name,
                                $total_user,
                                $total_female,
                                $total_male,
                                $grow_30,
                                $id_continent
                          );
            $res = db_query_table_results($query, $values, 1);
        }
        else{
            $query = "INSERT INTO ".DB_RESULTS_PREFIX."facebook_continents VALUES($10, '$11', $12, $13, $14, $15, NOW());";
            $values = array(
                                $id_continent,
                                $name,
                                $total_user,
                                $total_female,
                                $total_male,
                                $grow_30
                           );
            $res = db_query_table_results($query, $values, 1);
        }

    }
    
    error_log('   Facebook Continent (f): '.date('d m Y H:i:s'));