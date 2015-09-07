<?php
    require_once('../owloo_config.php');
    
    /************************************************ ULTIMOS 30 DIAS *********************************************************/
    $ultimos30Dias = get_city_date_last_update(5);
    
    //Obtenemos todos los países de Hispoamérica
    $_hh_countries = array();
    $sql =   "SELECT id_country
                FROM `country`
                WHERE `habla_hispana` =1;
                ";
    $que = mysql_query($sql) or die(mysql_error());
    while($fila = mysql_fetch_assoc($que)){
        $_hh_countries[] = $fila['id_country'];
    }

    //Obtenemos todas las ciudades de Hispoamérica
    $_hh_cities = array();
    $sql =   "SELECT id_city 
                FROM `facebook_city` 
                WHERE id_country IN(".implode(',', $_hh_countries).");
                ";
    $que = mysql_query($sql) or die(mysql_error());
    while($fila = mysql_fetch_assoc($que)){
        $_hh_cities[] = $fila['id_city'];
    }
    
    //Vaciamos la tala auxiliar
    $sql =   "TRUNCATE TABLE record_city_grow_temp;
                ";
    $que = mysql_query($sql) or die(mysql_error());
    
    //Registramos el crecimiento de los últimos 30 días de las ciudades de Hispanoamérica
    $sql =   "INSERT INTO record_city_grow_temp (id_city, period, date_start, date_end, total_user, cambio) SELECT DISTINCT id_city, 30, date, STR_TO_DATE('".$ultimos30Dias."','%Y-%m-%d'), total_user, (total_user - (
                            SELECT total_user 
                            FROM record_city 
                            WHERE date = STR_TO_DATE('".$ultimos30Dias."','%Y-%m-%d') 
                                AND id_city = rc1.id_city
                    )) cambio
                FROM record_city rc1
                WHERE date = STR_TO_DATE('".CITY_DATE_LAST_UPDATE."','%Y-%m-%d')
                    AND id_city IN(".implode(',', $_hh_cities).")
                ORDER BY 6 DESC, 5 ASC;
                ";
    $que = mysql_query($sql) or die(mysql_error());
    
    
    $ultimos90Dias = get_city_date_last_update(5 + 8);
    //Registramos el crecimiento de los últimos 90 días de las ciudades de Hispanoamérica
    $sql =   "INSERT INTO record_city_grow_temp (id_city, period, date_start, date_end, total_user, cambio) SELECT DISTINCT id_city, 90, date, STR_TO_DATE('".$ultimos90Dias."','%Y-%m-%d'), total_user, (total_user - (
                            SELECT total_user 
                            FROM record_city 
                            WHERE date = STR_TO_DATE('".$ultimos90Dias."','%Y-%m-%d') 
                                AND id_city = rc1.id_city
                    )) cambio
                FROM record_city rc1
                WHERE date = STR_TO_DATE('".CITY_DATE_LAST_UPDATE."','%Y-%m-%d')
                    AND id_city IN(".implode(',', $_hh_cities).")
                ORDER BY 6 DESC, 5 ASC;
                ";
    $que = mysql_query($sql) or die(mysql_error());
