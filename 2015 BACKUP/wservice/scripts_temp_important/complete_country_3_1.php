<?php
    
    set_time_limit(0);
    
    require_once(__DIR__.'/../config.php');
    
    $query_country = 'SELECT id_country FROM facebook_country_3_1 ORDER BY 1;';
    $que_country = db_query($query_country, array());
    while($row_country = mysql_fetch_assoc($que_country)){
        
        echo 'ID country: '.$row_country['id_country'].'<br/>';
        
        $id_country = $row_country['id_country'];
        
        //Para agregar las estadísticas viejas.
        /*$first_day = NULL;
        $query_day = 'SELECT date FROM facebook_record_country_3_1 WHERE id_country = $1 ORDER BY date ASC LIMIT 1;';
        $que_day = db_query($query_day, array($id_country));
        if($row_day = mysql_fetch_assoc($que_day)){
           $first_day = $row_day['date'];
        }
        
        $query_values = 'SELECT id_country, date, total_user, total_female, total_male FROM record_country WHERE id_country = $1 AND date < DATE(\'$2\') ORDER BY date ASC;';
        $que_values = db_query($query_values, array($id_country, $first_day));
        while($row_values = mysql_fetch_assoc($que_values)){
           
           $query_insert = "INSERT INTO facebook_record_country_3_1 VALUES($1, $2, $3, $4, '$5');";
           $res_insert = db_query($query_insert, array($id_country, $row_values['total_user'], $row_values['total_female'], $row_values['total_male'], $row_values['date']), 1);
                      
        }
        
        //Para agregar las estadísticas entre las fechas 2014-10-03 y 2014-10-08.
        $query_values = 'SELECT id_country, date, total_user, total_female, total_male FROM record_country WHERE id_country = $1 AND date BETWEEN DATE(\'2014-10-03\') AND DATE(\'2014-10-08\') ORDER BY date ASC;';
        $que_values = db_query($query_values, array($id_country));
        while($row_values = mysql_fetch_assoc($que_values)){
           
           $query_insert = "INSERT INTO facebook_record_country_3_1 VALUES($1, $2, $3, $4, '$5');";
           $res_insert = db_query($query_insert, array($id_country, $row_values['total_user'], $row_values['total_female'], $row_values['total_male'], $row_values['date']), 1);
                      
        }
        
        //Para agregar las estadísticas de la fecha 2013-12-25 con los datos del la fecha 2013-12-24.
        $query_values = 'SELECT id_country, date, total_user, total_female, total_male FROM record_country WHERE id_country = $1 AND date = DATE(\'2013-12-24\') ORDER BY date ASC;';
        $que_values = db_query($query_values, array($id_country));
        while($row_values = mysql_fetch_assoc($que_values)){
           
           $query_insert = "INSERT INTO facebook_record_country_3_1 VALUES($1, $2, $3, $4, '2013-12-25');";
           $res_insert = db_query($query_insert, array($id_country, $row_values['total_user'], $row_values['total_female'], $row_values['total_male']), 1);
                      
        }
        
        //Para agregar las estadísticas de la fecha 2014-03-18/26 con los datos del la fecha 2014-03-17.
        $query_values = 'SELECT id_country, date, total_user, total_female, total_male FROM record_country WHERE id_country = $1 AND date = DATE(\'2014-03-17\') ORDER BY date ASC;';
        $que_values = db_query($query_values, array($id_country));
        while($row_values = mysql_fetch_assoc($que_values)){
           
           $query_insert = "INSERT INTO facebook_record_country_3_1 VALUES
                                ($1, $2, $3, $4, '2014-03-18'),
                                ($1, $2, $3, $4, '2014-03-19'),
                                ($1, $2, $3, $4, '2014-03-20'),
                                ($1, $2, $3, $4, '2014-03-21'),
                                ($1, $2, $3, $4, '2014-03-22'),
                                ($1, $2, $3, $4, '2014-03-23'),
                                ($1, $2, $3, $4, '2014-03-24'),
                                ($1, $2, $3, $4, '2014-03-25'),
                                ($1, $2, $3, $4, '2014-03-26')
                                ;";
           $res_insert = db_query($query_insert, array($id_country, $row_values['total_user'], $row_values['total_female'], $row_values['total_male']), 1);
           
        }
        
        //Para agregar las estadísticas entre las fechas 2014-10-03 y 2014-10-08. Para los ID mayores a 212
        $query_values = 'SELECT id_country, date, total_user, total_female, total_male FROM facebook_record_country_3_1 WHERE id_country = $1 AND date = DATE(\'2014-10-02\') ORDER BY date ASC;';
        $que_values = db_query($query_values, array($id_country));
        while($row_values = mysql_fetch_assoc($que_values)){
           
           $query_insert = "INSERT INTO facebook_record_country_3_1 VALUES
                                ($1, $2, $3, $4, '2014-10-03'),
                                ($1, $2, $3, $4, '2014-10-04'),
                                ($1, $2, $3, $4, '2014-10-05'),
                                ($1, $2, $3, $4, '2014-10-06'),
                                ($1, $2, $3, $4, '2014-10-07'),
                                ($1, $2, $3, $4, '2014-10-08')
                                ;";
           $res_insert = db_query($query_insert, array($id_country, $row_values['total_user'], $row_values['total_female'], $row_values['total_male']), 1);
           
        }*/
        
    }