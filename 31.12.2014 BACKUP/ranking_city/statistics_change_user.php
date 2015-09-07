<?php
	/*********** Variables **************/
	//Estadísticas de habla hispana
	$hh_city_mayor_crec_mes = array('id_city' => '', 'ciudad' => '', 'country' => '', 'pais' => '', 'cambio' => '', 'cambio_number' => '', 'total_user' => '');
	$hh_city_mayor_decrec_mes = array('id_city' => '', 'ciudad' => '', 'country' => '', 'pais' => '', 'cambio' => '', 'cambio_number' => '', 'total_user' => '');
	$hh_city_mayor_crec_3_meses = array('id_city' => '', 'ciudad' => '', 'country' => '', 'pais' => '', 'cambio' => '', 'cambio_number' => '', 'total_user' => '');
	$hh_city_mayor_decrec_3_meses = array('id_city' => '', 'ciudad' => '', 'country' => '', 'pais' => '', 'cambio' => '', 'cambio_number' => '', 'total_user' => '');
	
	/************************************/

/************************************************ ULTIMOS 30 DIAS *********************************************************/
    //Crecimiento de los últimos 30 días de las ciudades de Hispanoamérica
    $sql =   "SELECT id_city, total_user, cambio
                FROM record_city_grow_temp
                WHERE period = 30
                ORDER BY 3 DESC, 2 ASC;
                ";
	$que = mysql_query($sql) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] > 0 ){
		    $_name_city = get_city_data($fila['id_city'], 'name');
            $hh_city_mayor_crec_mes = array('id_city' => $fila['id_city'], 'ciudad' => substr($_name_city, 0, strpos($_name_city, ',')), 'country' => get_country_data(get_city_data($fila['id_city'], 'id_country'), 'name'), 'pais' =>  get_country_data(get_city_data($fila['id_city'], 'id_country'), 'nombre'), 'cambio' => $fila['cambio'], 'cambio_number' => $fila['cambio'], 'total_user' => $fila['total_user']);
		}
	}

	//Imprimimos la ciudad con el mayor decremento en los últimos 30 días
	//La consulta anterior se ordena en forma descendente por su cantidad de crecimiento de usuario, por tanto, el último registro contiene al país que sufrió el mayor DECREMENTO (en caso de que sea negativo).
	//Nos posicionamos al último registro
	mysql_data_seek ($que, (mysql_num_rows($que) - 1));
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] < 0 ){
		    $_name_city = get_city_data($fila['id_city'], 'name');
			$hh_city_mayor_decrec_mes = array('id_city' => $fila['id_city'], 'ciudad' => substr($_name_city, 0, strpos($_name_city, ',')), 'country' => get_country_data(get_city_data($fila['id_city'], 'id_country'), 'name'), 'pais' =>  get_country_data(get_city_data($fila['id_city'], 'id_country'), 'nombre'), 'cambio' => $fila['cambio']*-1, 'cambio_number' => $fila['cambio']*-1, 'total_user' => $fila['total_user']);
		}
	}
	
/************************************************ FIN ULTIMOS 30 DIAS *********************************************************/

/************************************************ ULTIMOS 90 DIAS *********************************************************/
    //Crecimiento de los últimos 90 días de las ciudades de Hispanoamérica
    $sql =   "SELECT id_city, total_user, cambio
                FROM record_city_grow_temp
                WHERE period = 90
                ORDER BY 3 DESC, 2 ASC;
                ";
    $que = mysql_query($sql) or die(mysql_error());
    if($fila = mysql_fetch_assoc($que)){
        if($fila['cambio'] > 0 ){
            $_name_city = get_city_data($fila['id_city'], 'name');
            $hh_city_mayor_crec_3_meses = array('id_city' => $fila['id_city'], 'ciudad' => substr($_name_city, 0, strpos($_name_city, ',')), 'country' => get_country_data(get_city_data($fila['id_city'], 'id_country'), 'name'), 'pais' =>  get_country_data(get_city_data($fila['id_city'], 'id_country'), 'nombre'), 'cambio' => $fila['cambio'], 'cambio_number' => $fila['cambio'], 'total_user' => $fila['total_user']);
        }
    }

    //Imprimimos la ciudad con el mayor decremento en los últimos 90 días
    //La consulta anterior se ordena en forma descendente por su cantidad de crecimiento de usuario, por tanto, el último registro contiene al país que sufrió el mayor DECREMENTO (en caso de que sea negativo).
    //Nos posicionamos al último registro
    mysql_data_seek ($que, (mysql_num_rows($que) - 1));
    if($fila = mysql_fetch_assoc($que)){
        if($fila['cambio'] < 0 ){
            $_name_city = get_city_data($fila['id_city'], 'name');
            $hh_city_mayor_decrec_3_meses = array('id_city' => $fila['id_city'], 'ciudad' => substr($_name_city, 0, strpos($_name_city, ',')), 'country' => get_country_data(get_city_data($fila['id_city'], 'id_country'), 'name'), 'pais' =>  get_country_data(get_city_data($fila['id_city'], 'id_country'), 'nombre'), 'cambio' => $fila['cambio']*-1, 'cambio_number' => $fila['cambio']*-1, 'total_user' => $fila['total_user']);
        }
    }
    
/************************************************ FIN ULTIMOS 30 DIAS *********************************************************/

	//Estadísticas de Habla Hispana
	//Por mes
	if($hh_city_mayor_crec_mes['ciudad'] != ''){
		$hh_city_mayor_crec_mes['ciudad'] = '<a href="'.URL_ROOT.'facebook-stats/cities/'.convert_to_url_string($hh_city_mayor_crec_mes['country']).'/" title="Estadísticas de Facebook en '.$hh_city_mayor_crec_mes['pais'].'">'.$hh_city_mayor_crec_mes['ciudad'].'</a>';
		$hh_city_mayor_crec_mes['cambio'] = owloo_number_format($hh_city_mayor_crec_mes['cambio']);
	}
	else{
		$hh_city_mayor_crec_mes['ciudad'] = '<span>sin cambio</span>';
		$hh_city_mayor_crec_mes['cambio'] = '<span>0</span>';
	}
	
	if($hh_city_mayor_decrec_mes['ciudad'] != ''){
        $hh_city_mayor_decrec_mes['ciudad'] = '<a href="'.URL_ROOT.'facebook-stats/cities/'.convert_to_url_string($hh_city_mayor_decrec_mes['country']).'/" title="Estadísticas de Facebook en '.$hh_city_mayor_decrec_mes['pais'].'">'.$hh_city_mayor_decrec_mes['ciudad'].'</a>';
		$hh_city_mayor_decrec_mes['cambio'] = owloo_number_format($hh_city_mayor_decrec_mes['cambio']);
	}
	else{
		$hh_city_mayor_decrec_mes['ciudad'] = '<span>sin cambio</span>';
		$hh_city_mayor_decrec_mes['cambio'] = '<span>0</span>';
	}
	
	//Por 3 meses
	if($hh_city_mayor_crec_3_meses['ciudad'] != ''){
        $hh_city_mayor_crec_3_meses['ciudad'] = '<a href="'.URL_ROOT.'facebook-stats/cities/'.convert_to_url_string($hh_city_mayor_crec_3_meses['country']).'/" title="Estadísticas de Facebook en '.$hh_city_mayor_crec_3_meses['pais'].'">'.$hh_city_mayor_crec_3_meses['ciudad'].'</a>';
		$hh_city_mayor_crec_3_meses['cambio'] = owloo_number_format($hh_city_mayor_crec_3_meses['cambio']);
	}
	else{
		$hh_city_mayor_crec_3_meses['ciudad'] = '<span>sin cambio</span>';
		$hh_city_mayor_crec_3_meses['cambio'] = '<span>0</span>';
	}
	
	if($hh_city_mayor_decrec_3_meses['ciudad'] != ''){
        $hh_city_mayor_decrec_3_meses['ciudad'] = '<a href="'.URL_ROOT.'facebook-stats/cities/'.convert_to_url_string($hh_city_mayor_decrec_3_meses['country']).'/" title="Estadísticas de Facebook en '.$hh_city_mayor_decrec_3_meses['pais'].'">'.$hh_city_mayor_decrec_3_meses['ciudad'].'</a>';
		$hh_city_mayor_decrec_3_meses['cambio'] = owloo_number_format($hh_city_mayor_decrec_3_meses['cambio']);
	}
	else{
		$hh_city_mayor_decrec_3_meses['ciudad'] = '<span>sin cambio</span>';
		$hh_city_mayor_decrec_3_meses['cambio'] = '<span>0</span>';
	}