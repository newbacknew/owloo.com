<?php
    
    die();
    
    set_time_limit(0);
    
    require_once(__DIR__.'/../config.php');
    
    function getMesNumeric($mes){ //Formateo de meses
        switch($mes){
            case 'Ene': return '01';
            case 'Feb': return '02';
            case 'Mar': return '03';
            case 'Abr': return '04';
            case 'May': return '05';
            case 'Jun': return '06';
            case 'Jul': return '07';
            case 'Ago': return '08';
            case 'Set': return '09';
            case 'Oct': return '10';
            case 'Nov': return '11';
            case 'Dic': return '12';
        }
    }
    
    function returnDates($fromdate, $todate) {
        $fromdate = \DateTime::createFromFormat('Y-m-d', $fromdate);
        $todate = \DateTime::createFromFormat('Y-m-d', $todate);
        return new \DatePeriod(
            $fromdate,
            new \DateInterval('P1D'),
            $todate->modify('+1 day')
        );
    }
    
    $query_country = 'SELECT id_country, audience_history
                      FROM web_facebook_countries
                      WHERE id_country < 247
                      ORDER BY 1;';
    $que_country = db_query_table_results($query_country, array());
    while($row_country = mysql_fetch_assoc($que_country)){
        
        $id_country = $row_country['id_country'];
        
        $audience_history = json_decode($row_country['audience_history'], true);
        
        $audience_history_data = explode(',', $audience_history['series_data']);
        $audience_history_axis = explode(',', str_replace("'", '', $audience_history['x_axis']));
        
        $array_values = array();
        
        $current_value = NULL;
        
        for($i=0, $limit = count($audience_history_data); $i < $limit; $i++) {
            
            $date = explode(' ', $audience_history_axis[$i]);
            $date[1] = getMesNumeric($date[1]); 
            
            $array_values['20'.$date[2].'-'.$date[1].'-'.$date[0]] = $audience_history_data[$i];
            
        }
        
        reset($array_values);
        $first_key = key($array_values);
    
        $date1 = $first_key;
        $date2 = '2015-01-22';
        
        $datePeriod = returnDates($date1, $date2);
        foreach($datePeriod as $date) {
            
            if(isset($array_values[$date->format('Y-m-d')])){
                $current_value = $array_values[$date->format('Y-m-d')];
            }
            
            $query = 'INSERT INTO facebook_record_country_3_1 VALUES($1, $2, NULL, NULL, \'$3\');';
            $row = db_query($query, array($id_country, $current_value, $date->format('Y-m-d')), 1);
            
        }
        
    }
    