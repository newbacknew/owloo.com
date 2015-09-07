<?php
	/*********** Variables **************/
	//Estadísticas generales
	$g_mayor_crec_mes = array('pais' => '', 'code' => '', 'cambio' => '', 'cambio_number' => '', 'country' => '', 'id_country' => '');
	$g_mayor_decrec_mes = array('pais' => '', 'code' => '', 'cambio' => '', 'cambio_number' => '', 'country' => '', 'id_country' => '');
	$g_mayor_crec_3_meses = array('pais' => '', 'code' => '', 'cambio' => '', 'cambio_number' => '', 'country' => '', 'id_country' => '');
	$g_mayor_decrec_3_meses = array('pais' => '', 'code' => '', 'cambio' => '', 'cambio_number' => '', 'country' => '', 'id_country' => '');
	
	//Estadísticas de habla hispana
	$hh_mayor_crec_mes = array('pais' => '', 'code' => '', 'cambio' => '', 'cambio_number' => '', 'country' => '', 'id_country' => '');
	$hh_mayor_decrec_mes = array('pais' => '', 'code' => '', 'cambio' => '', 'cambio_number' => '', 'country' => '', 'id_country' => '');
	$hh_mayor_crec_3_meses = array('pais' => '', 'code' => '', 'cambio' => '', 'cambio_number' => '', 'country' => '', 'id_country' => '');
	$hh_mayor_decrec_3_meses = array('pais' => '', 'code' => '', 'cambio' => '', 'cambio_number' => '', 'country' => '', 'id_country' => '');
	
	/************************************/

/************************************************ ULTIMOS 30 DIAS *********************************************************/
	$ultimos30Dias = get_country_date_last_x_days(COUNTRY_DATE_LAST_UPDATE, 30);

	//Obtenemos el país con el mayor crecimiento en el último 30 día
	$sql =   "SELECT DISTINCT c.code code, c.nombre nombre, c.name name, abbreviation, ((
							SELECT total_user 
							FROM record_country 
							WHERE date = STR_TO_DATE('".COUNTRY_DATE_LAST_UPDATE."','%Y-%m-%d') 
							AND id_country = rc1.id_country
						) - (
							SELECT total_user 
							FROM record_country 
							WHERE date = STR_TO_DATE('".$ultimos30Dias."','%Y-%m-%d') 
								AND id_country = rc1.id_country
					)) cambio, c.id_country
				FROM record_country rc1 
					JOIN country c 
						ON rc1.id_country = c.id_country 
				ORDER BY 5 DESC;
				";
                
	$que = mysql_query($sql) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] > 0 ){
			$auxNombre = ($fila['abbreviation'] != '') ? $fila['abbreviation'] : $fila['nombre'];
			$g_mayor_crec_mes = array('pais' => $auxNombre, 'code' => $fila['code'], 'cambio' => $fila['cambio'], 'cambio_number' => $fila['cambio'], 'country' => $fila['name'], 'id_country' => $fila['id_country']);
		}
	}

	//Imprimimos el país con el mayor decremento en el último 30 días
	//La consulta anterior se ordena en forma descendente por su cantidad de crecimiento de usuario, por tanto, el último registro contiene al país que sufrió el mayor DECREMENTO (en caso de que sea negativo).
	//Nos posicionamos al último registro
	mysql_data_seek ($que, (mysql_num_rows($que) - 1));
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] < 0 ){
			$auxNombre = ($fila['abbreviation'] != '') ? $fila['abbreviation'] : $fila['nombre'];
			$g_mayor_decrec_mes = array('pais' => $auxNombre, 'code' => $fila['code'], 'cambio' => $fila['cambio'] * -1, 'cambio_number' => $fila['cambio']*-1, 'country' => $fila['name'], 'id_country' => $fila['id_country']);
		}
	}
	
/************************************************ FIN ULTIMOS 30 DIAS *********************************************************/

/************************************************ ULTIMOS 90 DIAS *********************************************************/
	$ultimos90Dias = get_country_date_last_x_days(COUNTRY_DATE_LAST_UPDATE, 90);

	//Obtenemos el país con el mayor crecimiento en el último 90 día
	$sql =   "SELECT DISTINCT c.code code, c.nombre nombre, c.name name, abbreviation, ((
							SELECT total_user 
							FROM record_country 
							WHERE date = STR_TO_DATE('".COUNTRY_DATE_LAST_UPDATE."','%Y-%m-%d') 
							AND id_country = rc1.id_country
						) - (
							SELECT total_user 
							FROM record_country 
							WHERE date = STR_TO_DATE('".$ultimos90Dias."','%Y-%m-%d') 
								AND id_country = rc1.id_country
					)) cambio, c.id_country
				FROM record_country rc1 
					JOIN country c 
						ON rc1.id_country = c.id_country 
				ORDER BY 5 DESC;
				";
	$que = mysql_query($sql) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] > 0 ){
			$auxNombre = ($fila['abbreviation'] != '') ? $fila['abbreviation'] : $fila['nombre'];
			$g_mayor_crec_3_meses = array('pais' => $auxNombre, 'code' => $fila['code'], 'cambio' => $fila['cambio'], 'cambio_number' => $fila['cambio'], 'country' => $fila['name'], 'id_country' => $fila['id_country']);
		}
	}

	//Imprimimos el país con el mayor decremento en el último 90 día
	//La consulta anterior se ordena en forma descendente por su cantidad de crecimiento de usuario, por tanto, el último registro contiene al país que sufrió el mayor DECREMENTO (en caso de que sea negativo).
	//Nos posicionamos al último registro
	mysql_data_seek ($que, (mysql_num_rows($que) - 1));
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] < 0 ){
			$auxNombre = ($fila['abbreviation'] != '') ? $fila['abbreviation'] : $fila['nombre'];
			$g_mayor_decrec_3_meses = array('pais' => $auxNombre, 'code' => $fila['code'], 'cambio' => $fila['cambio'] * -1, 'cambio_number' => $fila['cambio']*-1, 'country' => $fila['name'], 'id_country' => $fila['id_country']);
		}
	}
	
/************************************************ FIN ULTIMOS 90 DIAS *********************************************************/

/************************************************ ULTIMOS 30 DIAS - HABLA HISPANA *********************************************************/
	//Se repite el mismo cálculo para obtener el país con el mayor creciemiento en el último 30 día, pero sólo para los países de Habla Hispana
	$sql =   "SELECT DISTINCT c.code code, c.nombre nombre, c.name name, abbreviation, ((
							SELECT total_user 
							FROM record_country 
							WHERE date = STR_TO_DATE('".COUNTRY_DATE_LAST_UPDATE."','%Y-%m-%d') 
								AND id_country = rc1.id_country
						) - (
							SELECT total_user 
							FROM record_country 
							WHERE date = STR_TO_DATE('".$ultimos30Dias."','%Y-%m-%d') 
								AND id_country = rc1.id_country
					)) cambio, c.id_country 
				FROM record_country rc1 
					JOIN country c 
						ON rc1.id_country = c.id_country 
				WHERE habla_hispana = 1 
				ORDER BY 5 DESC;
				";
	$que = mysql_query($sql) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] > 0 ){
			$auxNombre = ($fila['abbreviation'] != '') ? $fila['abbreviation'] : $fila['nombre'];
			$hh_mayor_crec_mes = array('pais' => $auxNombre, 'code' => $fila['code'], 'cambio' => $fila['cambio'], 'cambio_number' => $fila['cambio'], 'country' => $fila['name'], 'id_country' => $fila['id_country']);
		}
	}

	//Se repite el mismo cálculo para obtener el país con el mayor decremento en el último 30 día, pero sólo para los países de Habla Hispana
	mysql_data_seek ($que, (mysql_num_rows($que) - 1));
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] < 0 ){
			$auxNombre = ($fila['abbreviation'] != '') ? $fila['abbreviation'] : $fila['nombre'];
			$hh_mayor_decrec_mes = array('pais' => $auxNombre, 'code' => $fila['code'], 'cambio' => $fila['cambio'] * -1, 'cambio_number' => $fila['cambio']*-1, 'country' => $fila['name'], 'id_country' => $fila['id_country']);
		}
	}
/************************************************ FIN ULTIMOS 30 DIAS - HABLA HISPANA *********************************************************/

/************************************************ ULTIMOS 90 DIAS - HABLA HISPANA *********************************************************/
	//Se repite el mismo cálculo para obtener el país con el mayor creciemiento en el último 90 día, pero sólo para los países de Habla Hispana
	$sql =   "SELECT DISTINCT c.code code, c.nombre nombre, c.name name, abbreviation, ((
							SELECT total_user 
							FROM record_country 
							WHERE date = STR_TO_DATE('".COUNTRY_DATE_LAST_UPDATE."','%Y-%m-%d') 
								AND id_country = rc1.id_country
						) - (
							SELECT total_user 
							FROM record_country 
							WHERE date = STR_TO_DATE('".$ultimos90Dias."','%Y-%m-%d') 
								AND id_country = rc1.id_country
					)) cambio, c.id_country 
				FROM record_country rc1 
					JOIN country c 
						ON rc1.id_country = c.id_country 
				WHERE habla_hispana = 1 
				ORDER BY 5 DESC;
				";
	$que = mysql_query($sql) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] > 0 ){
			$auxNombre = ($fila['abbreviation'] != '') ? $fila['abbreviation'] : $fila['nombre'];
			$hh_mayor_crec_3_meses = array('pais' => $auxNombre, 'code' => $fila['code'], 'cambio' => $fila['cambio'], 'cambio_number' => $fila['cambio'], 'country' => $fila['name'], 'id_country' => $fila['id_country']);
		}
	}

	//Se repite el mismo cálculo para obtener el país con el mayor decremento en el último 90 día, pero sólo para los países de Habla Hispana
	mysql_data_seek ($que, (mysql_num_rows($que) - 1));
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] < 0 ){
			$auxNombre = ($fila['abbreviation'] != '') ? $fila['abbreviation'] : $fila['nombre'];
			$hh_mayor_decrec_3_meses = array('pais' => $auxNombre, 'code' => $fila['code'], 'cambio' => $fila['cambio'] * -1, 'cambio_number' => $fila['cambio']*-1, 'country' => $fila['name'], 'id_country' => $fila['id_country']);
		}
	}
/************************************************ FIN ULTIMOS 90 DIAS - HABLA HISPANA *********************************************************/

	//Preparamos los datos para imprimirlos
	//Por mes
	if($g_mayor_crec_mes['pais'] != ''){
		$g_mayor_crec_mes['pais'] = '<a href="'.URL_ROOT.'facebook-stats/'.convert_to_url_string($g_mayor_crec_mes['country']).'/" title="Estadísticas de Facebook en '.$g_mayor_crec_mes['pais'].'">'.$g_mayor_crec_mes['pais'].'</a>';
		$g_mayor_crec_mes['cambio'] = owloo_number_format($g_mayor_crec_mes['cambio']);
	}
	else{
		$g_mayor_crec_mes['pais'] = '<span>sin cambio</span>';
		$g_mayor_crec_mes['cambio'] = '<span>0</span>';
	}
	
	if($g_mayor_decrec_mes['pais'] != ''){
		$g_mayor_decrec_mes['pais'] = '<a href="'.URL_ROOT.'facebook-stats/'.convert_to_url_string($g_mayor_decrec_mes['country']).'/" title="Estadísticas de Facebook en '.$g_mayor_decrec_mes['pais'].'">'.$g_mayor_decrec_mes['pais'].'</a>';
		$g_mayor_decrec_mes['cambio'] = owloo_number_format($g_mayor_decrec_mes['cambio']);
	}
	else{
		$g_mayor_decrec_mes['pais'] = '<span>sin cambio</span>';
		$g_mayor_decrec_mes['cambio'] = '<span>0</span>';
	}
	
	//Por 3 meses
	if($g_mayor_crec_3_meses['pais'] != ''){
		$g_mayor_crec_3_meses['pais'] = '<a href="'.URL_ROOT.'facebook-stats/'.convert_to_url_string($g_mayor_crec_3_meses['country']).'/" title="Estadísticas de Facebook en '.$g_mayor_crec_3_meses['pais'].'">'.$g_mayor_crec_3_meses['pais'].'</a>';
		$g_mayor_crec_3_meses['cambio'] = owloo_number_format($g_mayor_crec_3_meses['cambio']);
	}
	else{
		$g_mayor_crec_3_meses['pais'] = '<span>sin cambio</span>';
		$g_mayor_crec_3_meses['cambio'] = '<span>0</span>';
	}
	
	if($g_mayor_decrec_3_meses['pais'] != ''){
		$g_mayor_decrec_3_meses['pais'] = '<a href="'.URL_ROOT.'facebook-stats/'.convert_to_url_string($g_mayor_decrec_3_meses['country']).'/" title="Estadísticas de Facebook en '.$g_mayor_decrec_3_meses['pais'].'">'.$g_mayor_decrec_3_meses['pais'].'</a>';
		$g_mayor_decrec_3_meses['cambio'] = owloo_number_format($g_mayor_decrec_3_meses['cambio']);
	}
	else{
		$g_mayor_decrec_3_meses['pais'] = '<span>sin cambio</span>';
		$g_mayor_decrec_3_meses['cambio'] = '<span>0</span>';
	}
	
	//Estadísticas de Habla Hispana
	//Por mes
	if($hh_mayor_crec_mes['pais'] != ''){
		$hh_mayor_crec_mes['pais'] = '<a href="'.URL_ROOT.'facebook-stats/'.convert_to_url_string($hh_mayor_crec_mes['country']).'/" title="Estadísticas de Facebook en '.$hh_mayor_crec_mes['pais'].'">'.$hh_mayor_crec_mes['pais'].'</a>';
		$hh_mayor_crec_mes['cambio'] = owloo_number_format($hh_mayor_crec_mes['cambio']);
	}
	else{
		$hh_mayor_crec_mes['pais'] = '<span>sin cambio</span>';
		$hh_mayor_crec_mes['cambio'] = '<span>0</span>';
	}
	
	if($hh_mayor_decrec_mes['pais'] != ''){
		$hh_mayor_decrec_mes['pais'] = '<a href="'.URL_ROOT.'facebook-stats/'.convert_to_url_string($hh_mayor_decrec_mes['country']).'/" title="Estadísticas de Facebook en '.$hh_mayor_decrec_mes['pais'].'">'.$hh_mayor_decrec_mes['pais'].'</a>';
		$hh_mayor_decrec_mes['cambio'] = owloo_number_format($hh_mayor_decrec_mes['cambio']);
	}
	else{
		$hh_mayor_decrec_mes['pais'] = '<span>sin cambio</span>';
		$hh_mayor_decrec_mes['cambio'] = '<span>0</span>';
	}
	
	//Por 3 meses
	if($hh_mayor_crec_3_meses['pais'] != ''){
		$hh_mayor_crec_3_meses['pais'] = '<a href="'.URL_ROOT.'facebook-stats/'.convert_to_url_string($hh_mayor_crec_3_meses['country']).'/" title="Estadísticas de Facebook en '.$hh_mayor_crec_3_meses['pais'].'">'.$hh_mayor_crec_3_meses['pais'].'</a>';
		$hh_mayor_crec_3_meses['cambio'] = owloo_number_format($hh_mayor_crec_3_meses['cambio']);
	}
	else{
		$hh_mayor_crec_3_meses['pais'] = '<span>sin cambio</span>';
		$hh_mayor_crec_3_meses['cambio'] = '<span>0</span>';
	}
	
	if($hh_mayor_decrec_3_meses['pais'] != ''){
		$hh_mayor_decrec_3_meses['pais'] = '<a href="'.URL_ROOT.'facebook-stats/'.convert_to_url_string($hh_mayor_decrec_3_meses['country']).'/" title="Estadísticas de Facebook en '.$hh_mayor_decrec_3_meses['pais'].'">'.$hh_mayor_decrec_3_meses['pais'].'</a>';
		$hh_mayor_decrec_3_meses['cambio'] = owloo_number_format($hh_mayor_decrec_3_meses['cambio']);
	}
	else{
		$hh_mayor_decrec_3_meses['pais'] = '<span>sin cambio</span>';
		$hh_mayor_decrec_3_meses['cambio'] = '<span>0</span>';
	}
    
/********************************* CHART: Historial de total de usuarios en los ultimos 30 y 90 días ***************************************/
    /*
    $chart_crec_decrec = array();
    //$chart_crec_decrec = array('nombrePais'=>'', 'codigoPais'=>'', 'seriesDataMin'=>0, 'seriesDataMax'=>0, 'xAxisCategories'=>array(), 'seriesData'=>array(), 'aux_unique_code'=>0);
    $country_need_chart = array();
    $country_need_chart[] = array('code'=> $g_mayor_crec_mes['code'], 'days'=> 30);
    $country_need_chart[] = array('code'=> $g_mayor_crec_3_meses['code'], 'days'=> 90);
    $country_need_chart[] = array('code'=> $g_mayor_decrec_mes['code'], 'days'=> 30);
    $country_need_chart[] = array('code'=> $g_mayor_decrec_3_meses['code'], 'days'=> 90);
    $country_need_chart[] = array('code'=> $hh_mayor_crec_mes['code'], 'days'=> 30);
    $country_need_chart[] = array('code'=> $hh_mayor_crec_3_meses['code'], 'days'=> 90);
    $country_need_chart[] = array('code'=> $hh_mayor_decrec_mes['code'], 'days'=> 30);
    $country_need_chart[] = array('code'=> $hh_mayor_decrec_3_meses['code'], 'days'=> 90);
    
    $aux_unique_code = 0;
    foreach($country_need_chart as $country_code){
        $seriesData = ""; //Estadística vertical. Cantidad de usuarios que posee en Facebook
        $seriesDataMin = 0; //Número mínimo de usuarios
        $seriesDataMax = 0; //Número máximo de usuarios
        $xAxisCategories = ""; //Estadística horizontal. Fechas de los datos
        $nombrePais = ""; //Nombre del país
        $codigoPais = ""; //Código del país
        
        $sql =   "SELECT total_user, date, nombre, code 
                    FROM record_country r 
                        JOIN country c 
                            ON r.id_country = c.id_country 
                    WHERE c.code = '".$country_code['code']."' 
                        AND DATE_SUB(now(),INTERVAL ".$country_code['days']." DAY) <= date
                    ORDER BY 2 ASC;
                    "; 
        $que = mysql_query($sql) or die(mysql_error());
        
        $ban = 1; //Bandera 
        $cont = 1;
        while($fila = mysql_fetch_assoc($que)){
            //if($cont % 3 == 0){
                //Formatear fecha
                $auxformat = explode("-", $fila['date']);
                $dia = $auxformat[2];
                $mes = getMes($auxformat[1], 'short');
                if($ban == 1){
                    $seriesData .=      $fila['total_user'];
                    $xAxisCategories .= "'".$dia." ".$mes."'";
                    $nombrePais =       $fila['nombre'];
                    $codigoPais =       $fila['code'];
                    $seriesDataMin =    $fila['total_user'];
                    $seriesDataMax =    $fila['total_user'];
                    $ban = 0;
                }
                else{
                    $seriesData .= ','.$fila['total_user'];
                    $xAxisCategories .= ",'".$dia." ".$mes."'";
                    if($fila['total_user'] < $seriesDataMin)
                        $seriesDataMin = $fila['total_user'];
                    else
                    if($fila['total_user'] > $seriesDataMax)
                        $seriesDataMax = $fila['total_user'];
                }
            //}
            $cont++;
        }
        
        $chart_crec_decrec[] = array('nombrePais'=>$nombrePais, 'codigoPais'=>$codigoPais, 'seriesDataMin'=>$seriesDataMin, 'seriesDataMax'=>$seriesDataMax, 'xAxisCategories'=>$xAxisCategories, 'seriesData'=>$seriesData, 'aux_unique_code'=>++$aux_unique_code);
    }
    */
    
/********************************* FIN - CHART: Historial de total de usuarios en los ultimos 30 y 90 días ***************************************/