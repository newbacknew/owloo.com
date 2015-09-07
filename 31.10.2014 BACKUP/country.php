<?php
    require_once('owloo_config.php');
    require_once('userMgmt/system/initiater.php');
    set_current_menu('facebook');
    set_current_page('country');
    
    $login_folder = 'notin/';
    if($site->loggedin){
        $login_folder = 'in/';
    }
    include("cache/cache.start.php");
    
    $user_profile = $site->get_profile();;
    if(!empty($user_profile))
        set_current_user_id($user_profile['user_id']);
    
	//Para variables globales
	$LAST_DAY_FOR_USER_PREFERENCE = NULL;
    
    if(COUNTRY_DATA_CODE === NULL){
        header('Location: '.URL_ROOT);
        exit();
    }

	function get_last_date_for_user_preference(){
		global $LAST_DAY_FOR_USER_PREFERENCE;
		if($LAST_DAY_FOR_USER_PREFERENCE)
			return $LAST_DAY_FOR_USER_PREFERENCE;
		
		$sql = "
					SELECT max(date) date
					FROM record_country_for_user_preference
					WHERE id_country = ".COUNTRY_DATA_ID.";
				 ";
				 
		$que = mysql_query($sql) or die(mysql_error());
		if($fila = mysql_fetch_assoc($que)){
			$LAST_DAY_FOR_USER_PREFERENCE = $fila['date'];
			return $fila['date'];
		}
		return NULL;
	}
	
	function getCrecimiento($days, $countryCode){
		$crecimiento = array();
		$sql = "SELECT total_user, (total_user - (
							SELECT total_user 
							FROM record_country 
							WHERE date = (
										SELECT date 
										FROM record_country 
										WHERE DATE_SUB('".COUNTRY_DATE_LAST_UPDATE."',INTERVAL ".$days." DAY) <= date 
										GROUP BY date 
										ORDER BY 1 ASC 
										LIMIT 1
									) 
								AND id_country = ".COUNTRY_DATA_ID."
						)) cambio 
				FROM record_country
				WHERE id_country = ".COUNTRY_DATA_ID." 
					AND date = '".COUNTRY_DATE_LAST_UPDATE."';
				";
		
		$que = mysql_query($sql) or die(mysql_error());
		if($fila = mysql_fetch_assoc($que)){
			if($fila['cambio'] > 0 ){
				$crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_up">'.owloo_number_format($fila['cambio']).'</span>';
                $crecimiento['porcentaje'] = '<span class="owloo_arrow_up_porcent">'.owlooFormatPorcent($fila['cambio'], $fila['total_user']).'%</span>';
			}
			else if($fila['cambio'] == 0){
				$crecimiento['value'] = '<span class="owloo_not_change_audition"><em>sin cambio</em></span>';
				$crecimiento['porcentaje'] = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
			}
			else{
				$crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_down">'.owloo_number_format(($fila['cambio'] * -1)).'</span>';
                $crecimiento['porcentaje'] = '<span class="owloo_arrow_down_porcent">'.owlooFormatPorcent(($fila['cambio']*-1), $fila['total_user']).'%</span>';
			}
		}
		return $crecimiento;
	}
	
	function getCrecimientoMobile($days, $idCountry, $total_user){
		$crecimiento = '';
		
		$sql = 	"SELECT ((rc1.category_18 + rc1.category_19 + rc1.category_20 + rc1.category_21 + rc1.category_22 + rc1.category_23) - (
							SELECT (rc2.category_18 + rc2.category_19 + rc2.category_20 + rc2.category_21 + rc2.category_22 + rc2.category_23) 
							FROM record_country_for_user_preference rc2 
							WHERE date = (
									SELECT date 
									FROM record_country_for_user_preference 
									WHERE DATE_SUB('".get_last_date_for_user_preference()."',INTERVAL ".$days." DAY) <= date 
									GROUP BY date 
									ORDER BY 1 ASC 
									LIMIT 1
								) 
								AND rc2.id_country = ".$idCountry."
						 )) cambio 
				FROM record_country_for_user_preference rc1 
				WHERE date = '".get_last_date_for_user_preference()."'
					AND rc1.id_country = ".$idCountry.";
				";
		$que = mysql_query($sql) or die(mysql_error());
		if($fila = mysql_fetch_assoc($que)){
			if($fila['cambio'] > 0 ){
				$crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_up">'.owloo_number_format($fila['cambio']).'</span>';
                $crecimiento['porcentaje'] = '<span class="owloo_arrow_up_porcent">'.owlooFormatPorcent($fila['cambio'], $total_user).'%</span>';
			}
			else if($fila['cambio'] == 0){
				$crecimiento['value'] = '<span class="owloo_not_change_audition"><em>sin cambio</em></span>';
                $crecimiento['porcentaje'] = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
			}
			else{
				$crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_down">'.owloo_number_format(($fila['cambio'] * -1)).'</span>';
                $crecimiento['porcentaje'] = '<span class="owloo_arrow_down_porcent">'.owlooFormatPorcent(($fila['cambio']*-1), $total_user).'%</span>';
			}
		}
		return $crecimiento;
	}
	
/************************************ VARIABLES *************************************/
		
		//Posición en el mundo (por número de usuario)
		$ranking_position = 0;
		
		//Datos generales del país en cuanto a números de usuarios
		$crecimiento = array(
								'total_user' => '',     //Total de usaurios
								'total_female' => '', 	//Total de usuarios mujeres 
								'total_male' => '', 	//Total de usuarios hombres
								'dia' => '', 			//Cambio de usuarios en las ultimas 24 Horas
								'semana' => '', 		//Cambio de usuarios en los ultimos 7 dias
								'mes' => '',			//Cambio de usuarios en los ultimos 30 dias
								'dos_meses' => '',		//Cambio de usuarios en los ultimos 60 dias
								'tres_meses' => '',		//Cambio de usuarios en los ultimos 90 dias
								'seis_meses' => '',		//Cambio de usuarios en los ultimos 180 dias
								'porcentaje' => ''
							); 
							
		//Situación sentimental de los usuarios del país
		$relacion = array(
							'soltero' => '', 
							'tiene_relacion' => '', 
							'casado' => '',
							'comprometido'
						 );
		
		//Edades
		$edades = array();
        
        //Idiomas
        $listIdiomas = array();
						 
		//Interes de los usuarios
		$intereses = array();
        $intereses_actividades = array();
        $intereses_android = array();
        $intereses_ios = array();
        $intereses_others_mobile_os = array();
        $intereses_deportes = array();
						 
		//Cambios en el número de usuarios que usan dispositivos móviles
		$crecimientoMobileUSers = array(
											'dia' => '', 			//Cambio de usuarios móviles en las ultimas 24 Horas
											'semana' => '', 		//Cambio de usuarios móviles en los ultimos 7 dias
											'mes' => '',			//Cambio de usuarios móviles en los ultimos 30 dias
											'dos_meses' => '',		//Cambio de usuarios móviles en los ultimos 60 dias
											'tres_meses' => '',		//Cambio de usuarios móviles en los ultimos 90 dias
											'seis_meses' => '',		//Cambio de usuarios móviles en los ultimos 180 dias
											'porcentaje' => ''
										);
        //Ítem que mayor crecimiento tuvo en las categorías deportivos de los usuarios
            $mayorCrecimientoDeportes = array(
                                                'id_categoria' => '', 
                                                'nombre' => '', 
                                                'cambio' => ''
                                             );
                                         
        //Ítem que mayor crecimiento tuvo en las categorías de actividades de los usuarios
        $mayorCrecimientoActividades = array(
                                                'id_categoria' => '', 
                                                'nombre' => '', 
                                                'cambio' => ''
                                            ); 
                                            
        //Ítem que mayor crecimiento tuvo en las categorías de intereses los usuarios
        $mayorCrecimientoIntereses = array(
                                                'id_categoria' => '', 
                                                'nombre' => '', 
                                                'cambio' => ''
                                          );
        //Ítem que mayor crecimiento tuvo en las categorías de Android los usuarios
        $mayorCrecimientoAndroid = array(
                                                'id_categoria' => '', 
                                                'nombre' => '', 
                                                'cambio' => ''
                                          );
        //Ítem que mayor crecimiento tuvo en las categorías de Ios los usuarios
        $mayorCrecimientoIos = array(
                                                'id_categoria' => '', 
                                                'nombre' => '', 
                                                'cambio' => ''
                                          );
        //Ítem que mayor crecimiento tuvo en las categorías de Otros moviles los usuarios
        $mayorCrecimientoOtrosMoviles = array(
                                                'id_categoria' => '', 
                                                'nombre' => '', 
                                                'cambio' => ''
                                          );
										  
/************************************* FIN - VARIABLES **************************************/

$ranking_position = getRanking(COUNTRY_DATA_ID);
$total_user = get_country_total_audience_for_date(COUNTRY_DATA_ID, COUNTRY_DATE_LAST_UPDATE);

/********************************* Estadísticas de cambio de usuarios en las ultimas 24 horas, ultimos 7 y 30 dias ***************************************/
		
		//Estadística de cambio de usuarios en las ulimas 24 Horas
		$sql = "SELECT (total_user - (
								SELECT total_user 
								FROM record_country 
								WHERE date = (
										SELECT max(date) 
										FROM record_country 
										WHERE id_country = ".COUNTRY_DATA_ID."
											AND date != '".COUNTRY_DATE_LAST_UPDATE."'
									 ) 
									 AND id_country = ".COUNTRY_DATA_ID."
						)) cambio, total_user, total_female, total_male, id_country 
					FROM record_country
					WHERE id_country = ".COUNTRY_DATA_ID." 
						AND date = '".COUNTRY_DATE_LAST_UPDATE."';
				 ";
		$que = mysql_query($sql) or die(mysql_error());
		if($fila = mysql_fetch_assoc($que)){
			$crecimiento['total_user'] = 	$fila['total_user'];
			$crecimiento['total_female'] = 	$fila['total_female'];
			$crecimiento['total_male'] = 	$fila['total_male'];
			if($fila['cambio'] > 0 ){
                $_crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_up">'.owloo_number_format($fila['cambio']).'</span>';
                $_crecimiento['porcentaje'] = '<span class="owloo_arrow_up_porcent">'.owlooFormatPorcent($fila['cambio'], $fila['total_user']).'%</span>';
            }
            else if($fila['cambio'] == 0){
                $_crecimiento['value'] = '<span class="owloo_not_change_audition"><em>sin cambio</em></span>';
                $_crecimiento['porcentaje'] = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
            }
            else{
                $_crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_down">'.owloo_number_format(($fila['cambio'] * -1)).'</span>';
                $_crecimiento['porcentaje'] = '<span class="owloo_arrow_down_porcent">'.owlooFormatPorcent(($fila['cambio']*-1), $fila['total_user']).'%</span>';  
            }
            $crecimiento['dia'] = $_crecimiento;
		}
		
		//Estadística de cambio de usuarios en los ulimos 7 dias
		$crecimiento['semana'] = getCrecimiento(6, COUNTRY_DATA_CODE);
		
		//Estadística de cambio de usuarios en los ulimos 30 dias
		$crecimiento['mes'] = getCrecimiento(29, COUNTRY_DATA_CODE);
		
		//Estadística de cambio de usuarios en los ulimos 60 dias
		$crecimiento['dos_meses'] = getCrecimiento(59, COUNTRY_DATA_CODE);
		
		//Estadística de cambio de usuarios en los ulimos 90 dias
		$crecimiento['tres_meses'] = getCrecimiento(89, COUNTRY_DATA_CODE);
		
		//Estadística de cambio de usuarios en los ulimos 180 dias
		$crecimiento['seis_meses'] = getCrecimiento(180, COUNTRY_DATA_CODE);
		

/********************************* FIN - Estadísticas de cambio de usuarios en las ultimas 24 horas, ultimos 7 y 30 dias ***************************************/

/********************************* CHART: Historial de total de usuarios en los ultimos 90 dias ***************************************/
	$sql =   "SELECT total_user, date, nombre, code 
				FROM record_country r 
					JOIN country c 
						ON r.id_country = c.id_country 
				WHERE c.id_country = ".COUNTRY_DATA_ID." 
					AND DATE_SUB(STR_TO_DATE('".COUNTRY_DATE_LAST_UPDATE."', '%Y-%m-%d'),INTERVAL 90 DAY) <= date
				ORDER BY 2 ASC;
				"; 
	$que = mysql_query($sql) or die(mysql_error());
	$seriesData = ""; //Estadística vertical. Cantidad de usuarios que posee en Facebook
	$seriesDataMin = 0; //Número mínimo de usuarios
	$seriesDataMax = 0; //Número máximo de usuarios
	$xAxisCategories = ""; //Estadística horizontal. Fechas de los datos
	$ban = 1; //Bandera 
	$cont = 1;
	while($fila = mysql_fetch_assoc($que)){
		if($cont % 3 == 0){
			//Formatear fecha
			$auxformat = explode("-", $fila['date']);
			$dia = $auxformat[2];
			$mes = getMes($auxformat[1], 'short');
			if($ban == 1){
				$seriesData .= 		$fila['total_user'];
				$xAxisCategories .= "'".$dia." ".$mes."'";
				$seriesDataMin = 	$fila['total_user'];
				$seriesDataMax = 	$fila['total_user'];
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
		}
		$cont++;
	}
	
/********************************* FIN - CHART: Historial de total de usuarios en los ultimos 30 dias ***************************************/

/*********************** Segmentacion de usuarios por Edades, Situación sentimental e Idiomas más importantes ******************************/

	$sql =   "SELECT * 
				FROM record_country_for_age_language
				WHERE id_country = ".COUNTRY_DATA_ID." 
				ORDER BY date DESC 
				LIMIT 1;
				";
	$que = mysql_query($sql) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){
		//Situación sentimental
			$relacion['soltero'] = 			$fila['relationship_single'];
			$relacion['tiene_relacion'] = 	$fila['relationship_has_a_relationship'];
			$relacion['casado'] = 			$fila['relationship_married'];
			$relacion['comprometido'] = 	$fila['relationship_comprometido'];
			
		//Edades
            $edades[0] = array('rango' => '13 - 15', 'users' => $fila['rango_13_15']);
            $edades[1] = array('rango' => '16 - 17', 'users' => $fila['rango_16_17']);
            $edades[2] = array('rango' => '18 - 28', 'users' => $fila['rango_18_28']);
            $edades[3] = array('rango' => '29 - 34', 'users' => $fila['rango_29_34']);
            $edades[4] = array('rango' => '35 - 44', 'users' => $fila['rango_35_44']);
            $edades[5] = array('rango' => '45 - 54', 'users' => $fila['rango_45_54']);
            $edades[6] = array('rango' => '55 - 64', 'users' => $fila['rango_55_64']);
            $edades[7] = array('rango' => '65+', 'users' => $fila['rango_65_65']);
		
		//Idiomas
    		$listIdiomas[0] = array('idioma' => 'Español', 'users' => $fila['language_spanish']);
    		$listIdiomas[1] = array('idioma' => 'Inglés', 'users' => $fila['language_english']);
    		$listIdiomas[2] = array('idioma' => 'Chino', 'users' => $fila['language_chinese']);
    		$listIdiomas[3] = array('idioma' => 'Portugués', 'users' => $fila['language_portuguese']);
    		$listIdiomas[4] = array('idioma' => 'Hindi', 'users' => $fila['language_hindi']);
    		$listIdiomas[5] = array('idioma' => 'Francés', 'users' => $fila['language_frances']);
    		$listIdiomas[6] = array('idioma' => 'Alemán', 'users' => $fila['language_aleman']);
    		$listIdiomas[7] = array('idioma' => 'Italiano', 'users' => $fila['language_italiano']);
    		$listIdiomas[8] = array('idioma' => 'Ruso', 'users' => $fila['language_ruso']);
    		$listIdiomas[9] = array('idioma' => 'Japonés', 'users' => $fila['language_japones']);
    		$listIdiomas[10] = array('idioma' => 'Coreano', 'users' => $fila['language_coreano']);
    		$listIdiomas[11] = array('idioma' => 'Holandés', 'users' => $fila['language_holandes']);
    		$listIdiomas[12] = array('idioma' => 'Árabe', 'users' => $fila['language_arabe']);
    		$listIdiomas[13] = array('idioma' => 'Bengalí', 'users' => $fila['language_bengali']);
    		$listIdiomas[14] = array('idioma' => 'Turco', 'users' => $fila['language_turco']);
    		$listIdiomas[15] = array('idioma' => 'Malaya', 'users' => $fila['language_malayo']);
    		$listIdiomas[16] = array('idioma' => 'Polaco', 'users' => $fila['language_polaco']);
    		$listIdiomas[17] = array('idioma' => 'Indonesio', 'users' => $fila['language_indonesio']);
    		$listIdiomas[18] = array('idioma' => 'Filipino', 'users' => $fila['language_filipino']);
    		$listIdiomas[19] = array('idioma' => 'Tailandés', 'users' => $fila['language_tailandes']);
    		$listIdiomas[20] = array('idioma' => 'Vietnamita', 'users' => $fila['language_vietnamita']);
            //Ordenamos los idiomas de mayor a menor cantidad de hablantes
            usort($listIdiomas, function($a, $b){
                return ($a['users'] < $b['users']) ? 1 : -1;
            });
	}
	
/*********************** FIN - Segmentacion de usuarios por Edades, Situación sentimental e Idiomas más importantes ******************************/


/*********************** MINI-CHART: Segmentacion de usuarios por Intereses, de los últimos 15 días ******************************/
	$sql =   "SELECT * 
				FROM record_country_for_user_preference
				WHERE id_country = ".COUNTRY_DATA_ID."
				ORDER BY date DESC 
                LIMIT 1;
				"; 
    
	$que = mysql_query($sql) or die(mysql_error());
	if($fila = mysql_fetch_assoc($que)){
		//Intereses
		$intereses[0] = array('name' => 'Juegos (Consola)', 'users' => $fila['category_1']);
        $intereses[1] = array('name' => 'Juegos (Sociales / Online)', 'users' => $fila['category_2']);
        $intereses[2] = array('name' => 'Viajes', 'users' => $fila['category_3']);
		$intereses[3] = array('name' => 'Comida / Restaurantes', 'users' => $fila['category_4']);
        $intereses[4] = array('name' => 'Literatura / Lectura', 'users' => $fila['category_5']);
        $intereses[5] = array('name' => 'Cocina', 'users' => $fila['category_34']);
        $intereses[6] = array('name' => 'Baile', 'users' => $fila['category_35']);
        $intereses[7] = array('name' => 'Bricolaje / Manualidades', 'users' => $fila['category_36']);
        $intereses[8] = array('name' => 'Planificación de eventos', 'users' => $fila['category_37']);
        $intereses[9] = array('name' => 'Jardinería', 'users' => $fila['category_38']);
        $intereses[10] = array('name' => 'Actividades al aire libre', 'users' => $fila['category_39']);
        $intereses[11] = array('name' => 'Carga de fotos', 'users' => $fila['category_40']);
        $intereses[12] = array('name' => 'Fotografía', 'users' => $fila['category_41']);
        //Ordenamos los intereses de mayor a menor
            usort($intereses, function($a, $b){
                return ($a['users'] < $b['users']) ? 1 : -1;
            });
        
		//Actividades
		$intereses_actividades[0] = array('name' => 'Automóviles', 'users' => $fila['category_6']);
		$intereses_actividades[1] = array('name' => 'Cerveza / Vino / Licores', 'users' => $fila['category_7']);
		$intereses_actividades[2] = array('name' => 'Organizaciones benéficas / Causas', 'users' => $fila['category_8']);
		$intereses_actividades[3] = array('name' => 'Educación / Enseñanza', 'users' => $fila['category_9']);
		$intereses_actividades[4] = array('name' => 'Entretenimiento (TV)', 'users' => $fila['category_10']);
		$intereses_actividades[5] = array('name' => 'Medio ambiente', 'users' => $fila['category_11']);
		$intereses_actividades[6] = array('name' => 'Salud y bienestar', 'users' => $fila['category_12']);
		$intereses_actividades[7] = array('name' => 'Hogar y jardinería', 'users' => $fila['category_13']);
		$intereses_actividades[8] = array('name' => 'Noticias', 'users' => $fila['category_14']);
		$intereses_actividades[9] = array('name' => 'Mascotas (todas)', 'users' => $fila['category_15']);
		$intereses_actividades[10] = array('name' => 'Cultura POP', 'users' => $fila['category_16']);
        //Ordenamos las actividades de mayor a menor
            usort($intereses_actividades, function($a, $b){
                return ($a['users'] < $b['users']) ? 1 : -1;
            });
        
        $intereses_total_mobile_os = 0;
		
		//Cambio por sugerencia de Alessandra Abad
		//$intereses['usuarios_moviles_todos'][] = 			$fila['category_17'];
		//Total usuarios móviles = Android (todos) + iPad (1,2,3) + iPhone (4,4s,5) + iPod touch + Blackberry + Windows
		//Android
		$intereses_android[0] = array('name' => 'HTC', 'users' => $fila['category_48']);
		$intereses_android[1] = array('name' => 'LG', 'users' => $fila['category_49']);
		$intereses_android[2] = array('name' => 'Motorola', 'users' => $fila['category_50']);
		$intereses_android[3] = array('name' => 'Samsung', 'users' => $fila['category_51']);
		$intereses_android[4] = array('name' => 'Sony', 'users' => $fila['category_52']);
        
        //Ordenamos los android de mayor a menor
            usort($intereses_android, function($a, $b){
                return ($a['users'] < $b['users']) ? 1 : -1;
            });
		
        $intereses_android[5] = array('name' => 'Android (otro)', 'users' => $fila['category_53']);
		$intereses_android[6] = array('name' => 'Android (todos)', 'users' => $fila['category_47']);
        
        $intereses_total_mobile_os += $total_intereses_android = $intereses_android[6]['users'];
        
        
        //iOS
		$intereses_ios[0] = array('name' => 'iPad 1', 'users' => $fila['category_55']);
		$intereses_ios[1] = array('name' => 'iPad 2', 'users' => $fila['category_56']);
		$intereses_ios[2] = array('name' => 'iPad 3', 'users' => $fila['category_57']);
		$intereses_ios[3] = array('name' => 'iPhone 4', 'users' => $fila['category_58']);
		$intereses_ios[4] = array('name' => 'iPhone 4S', 'users' => $fila['category_59']);
		$intereses_ios[5] = array('name' => 'iPhone 5', 'users' => $fila['category_60']);
		$intereses_ios[6] = array('name' => 'iPod Touch', 'users' => $fila['category_21']);
        
        //Ordenamos los IOS de mayor a menor
            usort($intereses_ios, function($a, $b){
                return ($a['users'] < $b['users']) ? 1 : -1;
            });
        //$intereses_ios[7] = array('name' => 'iPod 1, 2 y 3', 'users' => $fila['category_19']);
        //$intereses_ios[8] = array('name' => 'iPhone 4, 4S y 5', 'users' => $fila['category_20']);
        $intereses_ios[7] = array('name' => 'IOS/Apple (todos)', 'users' => $fila['category_54']);
        
        $total_intereses_ios = $fila['category_54'];
        $intereses_total_mobile_os += $intereses_ios[7]['users'] +
                                        $fila['category_19'] +
                                        $fila['category_20'] +
                                        $intereses_ios[6]['users'];
        
		
		//Otros OS moviles
		$intereses_others_mobile_os[0] = array('name' => 'RIM / BlackBerry', 'users' => $fila['category_22']);
		$intereses_others_mobile_os[1] = array('name' => 'Windows', 'users' => $fila['category_23']);
        
        $total_intereses_others_mobile_bb = $fila['category_22'];
        $total_intereses_others_mobile_windows = $fila['category_23'];
        $intereses_total_mobile_os += $intereses_others_mobile_os[0]['users'] + $intereses_others_mobile_os[1]['users'];
        
		//Ordenamos los dispositivos móviles de mayor a menor
            usort($intereses_others_mobile_os, function($a, $b){
                return ($a['users'] < $b['users']) ? 1 : -1;
            });
        
		//Deportes
		$intereses_deportes[] = array('name' => 'Béisbol', 'users' => $fila['category_25']);
		$intereses_deportes[] = array('name' => 'Baloncesto', 'users' => $fila['category_26']);
		$intereses_deportes[] = array('name' => 'Deportes extremos', 'users' => $fila['category_27']);
		$intereses_deportes[] = array('name' => 'Fútbol americano', 'users' => $fila['category_28']);
		$intereses_deportes[] = array('name' => 'Golf', 'users' => $fila['category_29']);
		$intereses_deportes[] = array('name' => 'Hockey sobre hielo', 'users' => $fila['category_30']);
		$intereses_deportes[] = array('name' => 'Deportes motor / Nascar', 'users' => $fila['category_31']);
		$intereses_deportes[] = array('name' => 'Fútbol', 'users' => $fila['category_32']);
		$intereses_deportes[] = array('name' => 'Tenis', 'users' => $fila['category_33']);
		$intereses_deportes[] = array('name' => 'Cricket', 'users' => $fila['category_42']);
		$intereses_deportes[] = array('name' => 'Deportes de fantasía', 'users' => $fila['category_43']);
        //Ordenamos los Deportes de mayor a menor
            usort($intereses_deportes, function($a, $b){
                return ($a['users'] < $b['users']) ? 1 : -1;
            });
        
        $intereses_deportes[] = array('name' => 'Todos los deportes', 'users' => $fila['category_24']);
        
	}
	
/*********************** FIN - Segmentacion de usuarios por Intereses ******************************/

/********************************* Estadísticas de cambio de usuarios mobiles en las ultimas 24 horas, ultimos 7, 30, 60 y 90 dias ***************************************/
	
	//Total usuarios móviles = Android (todos) + IOS todos + Blackberry + Windows
	
	//Estadística de cambio de usuarios mobiles en las ulimas 24 Horas 
	$sql = 	"SELECT ((rc1.category_18 + rc1.category_19 + rc1.category_20 + rc1.category_21 + rc1.category_22 + rc1.category_23) - (
							SELECT (rc2.category_18 + rc2.category_19 + rc2.category_20 + rc2.category_21 + rc2.category_22 + rc2.category_23) 
							FROM record_country_for_user_preference rc2 
							WHERE date = (
								SELECT max(date) 
								FROM record_country_for_user_preference 
								WHERE date != '".get_last_date_for_user_preference()."' 
									) 
									AND rc2.id_country = ".COUNTRY_DATA_ID."
						 )) cambio 
				FROM record_country_for_user_preference rc1 
				WHERE date = '".get_last_date_for_user_preference()."' 
					AND rc1.id_country = ".COUNTRY_DATA_ID.";
				";
	$que = mysql_query($sql) or die(mysql_error());
	
	if($fila = mysql_fetch_assoc($que)){
		if($fila['cambio'] > 0 ){
                $_crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_up">'.owloo_number_format($fila['cambio']).'</span>';
                $_crecimiento['porcentaje'] = '<span class="owloo_arrow_up_porcent">'.owlooFormatPorcent($fila['cambio'], $crecimiento['total_user']).'%</span>';
            }
            else if($fila['cambio'] == 0){
                $_crecimiento['value'] = '<span class="owloo_not_change_audition"><em>sin cambio</em></span>';
                $_crecimiento['porcentaje'] = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
            }
            else{
                $_crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_down">'.owloo_number_format(($fila['cambio'] * -1)).'</span>';
                $_crecimiento['porcentaje'] = '<span class="owloo_arrow_down_porcent">'.owlooFormatPorcent(($fila['cambio']*-1), $crecimiento['total_user']).'%</span>';
            }
            $crecimientoMobileUSers['dia'] = $_crecimiento;
	}
	
	//Estadística de cambio de usuarios en los ulimos 7 dias
	$crecimientoMobileUSers['semana'] = getCrecimientoMobile(6, COUNTRY_DATA_ID, $crecimiento['total_user']);
	
	//Estadística de cambio de usuarios en los ulimos 30 dias
	$crecimientoMobileUSers['mes'] = getCrecimientoMobile(29, COUNTRY_DATA_ID, $crecimiento['total_user']);
	
	//Estadística de cambio de usuarios en los ulimos 60 dias
	$crecimientoMobileUSers['dos_meses'] = getCrecimientoMobile(59, COUNTRY_DATA_ID, $crecimiento['total_user']);
	
	//Estadística de cambio de usuarios en los ulimos 90 dias
	$crecimientoMobileUSers['tres_meses'] = getCrecimientoMobile(89, COUNTRY_DATA_ID, $crecimiento['total_user']);
	
	//Estadística de cambio de usuarios en los ulimos 180 dias
	$crecimientoMobileUSers['seis_meses'] = getCrecimientoMobile(179, COUNTRY_DATA_ID, $crecimiento['total_user']);
	
/********************************* FIN - Estadísticas de cambio de usuarios mobiles en las ultimas 24 horas, ultimos 7, 30, 60 y 90 dias ***************************************/

/************************ Mayor crecimiento en las categorías Deportivas, Actividades e Intereses *******************/

    //Obtenemos la fecha de hace 30 días    
        $auxFechaMes = "";
        $query =   "SELECT date 
                    FROM record_country_for_user_preference 
                    WHERE DATE_SUB('".get_last_date_for_user_preference()."',INTERVAL 30 DAY) <= date 
                    GROUP BY date 
                    ORDER BY 1 ASC 
                    LIMIT 1;
                    ";

        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            $auxFechaMes = $fila['date'];
        }

    //FIN - Obtenemos la fecha de hace 30 días  
    
    //Categoría: Deportes (25 - 33)
        $categoriaMin = 25;
        $categoriaMax = 33;
        $auxCantidad = 0;
        $query = "SELECT id_country";
        for($i=$categoriaMin; $i<=$categoriaMax; $i++)
            $query .= ", rc1.category_".$i." category_".$i.", (rc1.category_".$i." - (
                            SELECT rc2.category_".$i." 
                            FROM record_country_for_user_preference rc2 
                            WHERE date = STR_TO_DATE('".$auxFechaMes."','%Y-%m-%d') 
                                AND rc2.id_country = ".COUNTRY_DATA_ID."
                          )) cambio_category_".$i;

        $categoriaMin = 42;
        $categoriaMax = 43;
        for($i=$categoriaMin; $i<=$categoriaMax; $i++)
            $query .= ", rc1.category_".$i." category_".$i.", (rc1.category_".$i." - (
                            SELECT rc2.category_".$i." 
                            FROM record_country_for_user_preference rc2 
                            WHERE date = STR_TO_DATE('".$auxFechaMes."','%Y-%m-%d') 
                                AND rc2.id_country = ".COUNTRY_DATA_ID."
                          )) cambio_category_".$i;
        $query .= " FROM record_country_for_user_preference rc1 
                    WHERE date = '".get_last_date_for_user_preference()."'
                        AND rc1.id_country = ".COUNTRY_DATA_ID.";
                    ";
        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            $mayorCrecimientoDeportes = array('id_categoria' => 0, 'nombre' => '', 'cambio' => 0);
            for($i=$categoriaMin; $i<=$categoriaMax; $i++){
                if($fila['cambio_category_'.$i] > $mayorCrecimientoDeportes['cambio']){
                    $mayorCrecimientoDeportes['cambio'] = $fila['cambio_category_'.$i];
                    $mayorCrecimientoDeportes['id_categoria'] = $i;
                    $auxCantidad = $fila['category_'.$i];
                }
            }
            if($mayorCrecimientoDeportes['id_categoria'] != 0){
                $query =   "SELECT subcategory 
                            FROM category 
                            WHERE id_category = ".$mayorCrecimientoDeportes['id_categoria']."
                            ;";
                $que = mysql_query($query) or die(mysql_error());
                if($fila = mysql_fetch_assoc($que)){
                    $mayorCrecimientoDeportes['nombre'] = $fila['subcategory'];
                    $mayorCrecimientoDeportes['cambio'] = '<span class="owloo_trenp_up_porcent">'.number_format($mayorCrecimientoDeportes['cambio'] * 100 / ($auxCantidad - $mayorCrecimientoDeportes['cambio']), 2, '.', ' ').'%</span>';
                }
            }
            else{
                $mayorCrecimientoDeportes = array('id_categoria' => 0, 'nombre' => 'sin cambio', 'cambio' => '');
            }
        }

    //FIN - Categoría: Deportes (25 - 33)

    //Categoría: Actividades (1 - 5)
        $categoriaMin = 1;
        $categoriaMax = 5;
        $auxCantidad = 0;
        $query = "SELECT id_country";
        for($i=$categoriaMin; $i<=$categoriaMax; $i++)
            $query .= ", rc1.category_".$i." category_".$i.", (rc1.category_".$i." - (
                            SELECT rc2.category_".$i." 
                            FROM record_country_for_user_preference rc2 
                            WHERE date = STR_TO_DATE('".$auxFechaMes."','%Y-%m-%d') 
                                AND rc2.id_country = ".COUNTRY_DATA_ID."
                          )) cambio_category_".$i;

        $categoriaMin = 34;
        $categoriaMax = 41;
        for($i=$categoriaMin; $i<=$categoriaMax; $i++)
            $query .= ", rc1.category_".$i." category_".$i.", (rc1.category_".$i." - (
                            SELECT rc2.category_".$i." 
                            FROM record_country_for_user_preference rc2 
                            WHERE date = STR_TO_DATE('".$auxFechaMes."','%Y-%m-%d') 
                                AND rc2.id_country = ".COUNTRY_DATA_ID."
                          )) cambio_category_".$i;
        $query .= " FROM record_country_for_user_preference rc1 
                    WHERE date = '".get_last_date_for_user_preference()."'
                        AND rc1.id_country = ".COUNTRY_DATA_ID."
                    ;"; 

        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            $mayorCrecimientoActividades = array('id_categoria' => 0, 'nombre' => '', 'cambio' => 0);
            for($i=$categoriaMin; $i<=$categoriaMax; $i++){
                if($fila['cambio_category_'.$i] > $mayorCrecimientoActividades['cambio']){
                    $mayorCrecimientoActividades['cambio'] = $fila['cambio_category_'.$i];
                    $mayorCrecimientoActividades['id_categoria'] = $i;
                    $auxCantidad = $fila['category_'.$i];
                }
            }
            if($mayorCrecimientoActividades['id_categoria'] != 0){
                $query =   "SELECT subcategory 
                            FROM category 
                            WHERE id_category = ".$mayorCrecimientoActividades['id_categoria']."
                            ;";
                $que = mysql_query($query) or die(mysql_error());
                if($fila = mysql_fetch_assoc($que)){
                    $mayorCrecimientoActividades['nombre'] = $fila['subcategory'];
                    $mayorCrecimientoActividades['cambio'] = '<span class="owloo_trenp_up_porcent">'.number_format($mayorCrecimientoActividades['cambio'] * 100 / ($auxCantidad - $mayorCrecimientoActividades['cambio']), 2, '.', ' ').'%</span>';
                }
            }
            else{
                $mayorCrecimientoActividades = array('id_categoria' => 0, 'nombre' => 'sin cambio', 'cambio' => '');
            }
        }

    //FIN - Categoría: Actividades (1 - 5)
    
    //Categoría: Intereses (6 - 16)
        $categoriaMin = 6;
        $categoriaMax = 16;
        $auxCantidad = 0;
        $query = "SELECT id_country";
        for($i=$categoriaMin; $i<=$categoriaMax; $i++)
            $query .= ", rc1.category_".$i." category_".$i.", (rc1.category_".$i." - (
                            SELECT rc2.category_".$i." 
                            FROM record_country_for_user_preference rc2 
                            WHERE date = STR_TO_DATE('".$auxFechaMes."','%Y-%m-%d') 
                                AND rc2.id_country = ".COUNTRY_DATA_ID."
                          )) cambio_category_".$i;
        $query .= " FROM record_country_for_user_preference rc1 
                    WHERE date = '".get_last_date_for_user_preference()."' 
                        AND rc1.id_country = ".COUNTRY_DATA_ID."
                    ;";

        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            $mayorCrecimientoIntereses = array('id_categoria' => 0, 'nombre' => '', 'cambio' => 0);
            for($i=$categoriaMin; $i<=$categoriaMax; $i++){
                if($fila['cambio_category_'.$i] > $mayorCrecimientoIntereses['cambio']){
                    $mayorCrecimientoIntereses['cambio'] = $fila['cambio_category_'.$i];
                    $mayorCrecimientoIntereses['id_categoria'] = $i;
                    $auxCantidad = $fila['category_'.$i];
                }
            }
            if($mayorCrecimientoIntereses['id_categoria'] != 0){
                $query =   "SELECT subcategory 
                            FROM category 
                            WHERE id_category = ".$mayorCrecimientoIntereses['id_categoria']."
                            ;";
                $que = mysql_query($query) or die(mysql_error());
                if($fila = mysql_fetch_assoc($que)){
                    $mayorCrecimientoIntereses['nombre'] = $fila['subcategory'];
                    $mayorCrecimientoIntereses['cambio'] = '<span class="owloo_trenp_up_porcent">'.number_format($mayorCrecimientoIntereses['cambio'] * 100 / ($auxCantidad - $mayorCrecimientoIntereses['cambio']), 2, '.', ' ').'%</span>';
                }
            }
            else{
                $mayorCrecimientoIntereses = array('id_categoria' => 0, 'nombre' => 'sin cambio', 'cambio' => '');
            }
        }

    //FIN - Categoría: Intereses (6 - 16)
    
    //Categoría: Otros Móviles (22 - 23)
        $categoriaMin = 22;
        $categoriaMax = 23;
        $auxCantidad = 0;
        $query = "SELECT id_country";
        for($i=$categoriaMin; $i<=$categoriaMax; $i++)
            $query .= ", rc1.category_".$i." category_".$i.", (rc1.category_".$i." - (
                            SELECT rc2.category_".$i." 
                            FROM record_country_for_user_preference rc2 
                            WHERE date = STR_TO_DATE('".$auxFechaMes."','%Y-%m-%d') 
                                AND rc2.id_country = ".COUNTRY_DATA_ID."
                          )) cambio_category_".$i;
        $query .= " FROM record_country_for_user_preference rc1 
                    WHERE date = '".get_last_date_for_user_preference()."' 
                        AND rc1.id_country = ".COUNTRY_DATA_ID."
                    ;";

        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            $mayorCrecimientoOtrosMoviles = array('id_categoria' => 0, 'nombre' => '', 'cambio' => 0);
            for($i=$categoriaMin; $i<=$categoriaMax; $i++){
                if($fila['cambio_category_'.$i] > $mayorCrecimientoOtrosMoviles['cambio']){
                    $mayorCrecimientoOtrosMoviles['cambio'] = $fila['cambio_category_'.$i];
                    $mayorCrecimientoOtrosMoviles['id_categoria'] = $i;
                    $auxCantidad = $fila['category_'.$i];
                }
            }
            if($mayorCrecimientoOtrosMoviles['id_categoria'] != 0){
                $query =   "SELECT subcategory 
                            FROM category 
                            WHERE id_category = ".$mayorCrecimientoOtrosMoviles['id_categoria']."
                            ;";
                $que = mysql_query($query) or die(mysql_error());
                if($fila = mysql_fetch_assoc($que)){
                    $mayorCrecimientoOtrosMoviles['nombre'] = $fila['subcategory'];
                    $mayorCrecimientoOtrosMoviles['cambio'] = '<span class="owloo_trenp_up_porcent">'.number_format($mayorCrecimientoOtrosMoviles['cambio'] * 100 / ($auxCantidad - $mayorCrecimientoOtrosMoviles['cambio']), 2, '.', ' ').'%</span>';
                }
            }
            else{
                $mayorCrecimientoOtrosMoviles = array('id_categoria' => 0, 'nombre' => 'sin cambio', 'cambio' => '');
            }
        }

    //FIN - Categoría: Otros Móviles (22 - 23)
    
    
    //Obtenemos la fecha de hace 30 días    
        $auxFechaMes = "";
        $query =   "SELECT date 
                    FROM record_country_for_user_preference 
                    WHERE category_48 is not null AND DATE_SUB('".get_last_date_for_user_preference()."',INTERVAL 30 DAY) <= date 
                    GROUP BY date 
                    ORDER BY 1 ASC 
                    LIMIT 1;
                    ";
        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            $auxFechaMes = $fila['date'];
        }
    //FIN - Obtenemos la fecha de hace 30 días
    
    //Categoría: Android (48 - 53)
        $categoriaMin = 48;
        $categoriaMax = 53;
        $auxCantidad = 0;
        $query = "SELECT id_country";
        for($i=$categoriaMin; $i<=$categoriaMax; $i++)
            $query .= ", rc1.category_".$i." category_".$i.", (rc1.category_".$i." - (
                            SELECT rc2.category_".$i." 
                            FROM record_country_for_user_preference rc2 
                            WHERE date = STR_TO_DATE('".$auxFechaMes."','%Y-%m-%d') 
                                AND rc2.id_country = ".COUNTRY_DATA_ID."
                          )) cambio_category_".$i;
        $query .= " FROM record_country_for_user_preference rc1 
                    WHERE date = '".get_last_date_for_user_preference()."'  
                        AND rc1.id_country = ".COUNTRY_DATA_ID."
                    ;";

        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            $mayorCrecimientoAndroid = array('id_categoria' => 0, 'nombre' => '', 'cambio' => 0);
            for($i=$categoriaMin; $i<=$categoriaMax; $i++){
                if($fila['cambio_category_'.$i] > $mayorCrecimientoAndroid['cambio']){
                    $mayorCrecimientoAndroid['cambio'] = $fila['cambio_category_'.$i];
                    $mayorCrecimientoAndroid['id_categoria'] = $i;
                    $auxCantidad = $fila['category_'.$i];
                }
            }
            if($mayorCrecimientoAndroid['id_categoria'] != 0){
                $query =   "SELECT subcategory 
                            FROM category 
                            WHERE id_category = ".$mayorCrecimientoAndroid['id_categoria']."
                            ;";
                $que = mysql_query($query) or die(mysql_error());
                if($fila = mysql_fetch_assoc($que)){
                    $mayorCrecimientoAndroid['nombre'] = $fila['subcategory'];
                    $mayorCrecimientoAndroid['cambio'] = '<span class="owloo_trenp_up_porcent">'.number_format($mayorCrecimientoAndroid['cambio'] * 100 / ($auxCantidad - $mayorCrecimientoAndroid['cambio']), 2, '.', ' ').'%</span>';
                }
            }
            else{
                $mayorCrecimientoAndroid = array('id_categoria' => 0, 'nombre' => 'sin cambio', 'cambio' => '');
            }
        }

    //FIN - Categoría: Android (48 - 53)
    
    //Categoría: IOS (55 - 60)
        $categoriaMin = 55;
        $categoriaMax = 60;
        $auxCantidad = 0;
        $query = "SELECT id_country";
        for($i=$categoriaMin; $i<=$categoriaMax; $i++)
            $query .= ", rc1.category_".$i." category_".$i.", (rc1.category_".$i." - (
                            SELECT rc2.category_".$i." 
                            FROM record_country_for_user_preference rc2 
                            WHERE date = STR_TO_DATE('".$auxFechaMes."','%Y-%m-%d') 
                                AND rc2.id_country = ".COUNTRY_DATA_ID."
                          )) cambio_category_".$i;
        $query .= " FROM record_country_for_user_preference rc1 
                    WHERE date = '".get_last_date_for_user_preference()."'
                        AND rc1.id_country = ".COUNTRY_DATA_ID."
                    ;";

        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            $mayorCrecimientoIos = array('id_categoria' => 0, 'nombre' => '', 'cambio' => 0);
            for($i=$categoriaMin; $i<=$categoriaMax; $i++){
                if($fila['cambio_category_'.$i] > $mayorCrecimientoIos['cambio']){
                    $mayorCrecimientoIos['cambio'] = $fila['cambio_category_'.$i];
                    $mayorCrecimientoIos['id_categoria'] = $i;
                    $auxCantidad = $fila['category_'.$i];
                }
            }
            if($mayorCrecimientoIos['id_categoria'] != 0){
                $query =   "SELECT subcategory 
                            FROM category 
                            WHERE id_category = ".$mayorCrecimientoIos['id_categoria']."
                            ;";
                $que = mysql_query($query) or die(mysql_error());
                if($fila = mysql_fetch_assoc($que)){
                    $mayorCrecimientoIos['nombre'] = $fila['subcategory'];
                    $mayorCrecimientoIos['cambio'] = '<span class="owloo_trenp_up_porcent">'.number_format($mayorCrecimientoIos['cambio'] * 100 / ($auxCantidad - $mayorCrecimientoIos['cambio']), 2, '.', ' ').'%</span>';
                }
            }
            else{
                $mayorCrecimientoIos = array('id_categoria' => 0, 'nombre' => 'sin cambio', 'cambio' => '');
            }
        }
    //FIN - Categoría: IOS (55 - 60)

/************************ FIN - Mayor crecimiento en las categorías Deportivas, Actividades e Intereses *******************/
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="content-language" content="es" />
    <meta name="description" content="Datos de Facebook en <?=COUNTRY_DATA_NAME_ES?>. owloo puede monitorear y analizar el crecimiento, número de usuarios, intereses, además de datos geográficos y demográficos de Facebook <?=strtoupper(COUNTRY_DATA_CODE)?>." />
    <meta name="keywords" content="<?=COUNTRY_DATA_NAME_ES?>, Facebook, statistics, cantidad usuarios, penetración, estudio mercado, ranking, datos, social media, marketing" />
    <meta http-equiv="Cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" /> 

    <title>Estad&iacute;sticas y an&aacute;lisis de Facebook en <?=COUNTRY_DATA_NAME_ES?> - owloo</title>
    
    <meta property="og:image" content="<?=URL_IMAGES?>owloo_logo_redes_sociales.png" /> 
    
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
</head>
<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            <a class="owloo_navegator" href="<?=URL_ROOT?>facebook-stats/world-ranking/">Ranking mundial</a>
            <span class="owloo_separator">></span>
            <span class="owloo_navegator">Facebook en <?=COUNTRY_DATA_NAME_ES?></span>
        </div>
    </div>
    <div class="owloo_main owloo_main_content">
        <div class="owloo_tools_content">
            <div class="owloo_tools">
                <span class="owloo_msj_popup" data="<p>Presionando el botón puedes monitorear uno o varios perfiles de Twitter y las estadísticas de un país especifico.</p><p>Cada vez que accedas a Mi cuenta > Monitoreo, podrás ver rápidamente estas páginas, si quieres dejar de monitorearlas presiona nuevamente el botón.</p>"></span>
                <span class="owloo_text">Monitorea esta página</span>
                <div id="owloo_favorite" class="owloo_favorite_country_ajax owloo_favorite_icon" type="country" element="<?=COUNTRY_DATA_ID?>"></div>
            </div>
        </div>
        <div class="owloo_country_main_title">
            <img class="owloo_country_flag" src="<?=URL_IMAGES?>flags/64/<?=strtoupper(COUNTRY_DATA_CODE)?>.png" alt="<?=COUNTRY_DATA_CODE?>" title="" />
            <h1 class="owloo_main_title_h1 owloo_align_left">
                Estadísticas de Facebook en <?=COUNTRY_DATA_NAME_ES?>
            </h1>
        </div>
        
        <div class="owloo_country_section">
            <h2>
                Resumen de las estadísticas
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 1</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                 <div class="owloo_left">
                    <table>
                        <tbody>
                            <tr>
                                <td class="owloo_country_table_th">Total de usuarios en Facebook</td>
                                <td class="owloo_country_table_th owloo_country_table_1"><?=owloo_number_format($crecimiento['total_user'])?></td>
                            </tr>
                            <tr>
                                <td>Posición en el ranking</td>
                                <td><?=$ranking_position?></td>
                            </tr>
                            <tr>
                                <td>Crecimiento durante el último mes</td>
                                <td><?=$crecimiento['mes']['value']?></td>
                            </tr>
                            <tr>
                                <td>Idioma más hablado</td>
                                <td><?=$listIdiomas[0]['idioma']?></td>
                            </tr>
                            <tr>
                                <td>Cantidad de mujeres</td>
                                <td><?=round($crecimiento['total_female'] * 100 / $crecimiento['total_user'], 2)?>%</td>
                            </tr>
                            <tr>
                                <td>Cantidad de hombres</td>
                                <td><?=round($crecimiento['total_male'] * 100 / $crecimiento['total_user'], 2)?>%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="owloo_right">
                    <p>
                        Owloo te brinda datos de Facebook <?=COUNTRY_DATA_NAME_ES?> actualizados y detalles estadísticos con los cuales puedes calcular el crecimiento, el uso de los celulares conectados a Facebook y el los principales intereses de los usuarios en <?=COUNTRY_DATA_NAME_ES?>.
                    </p>
                    <p>
                        <strong><?=COUNTRY_DATA_NAME_ES?></strong> cuenta con <strong><?=owloo_number_format($crecimiento['total_user'])?> usuarios</strong> de los cuales el <?=round(($crecimiento['total_female'] * 100 / $crecimiento['total_user']), 2)?>% son mujeres y <?=round(($crecimiento['total_male'] * 100 / $crecimiento['total_user']), 2)?>% son hombres. Actualmente se encuentra en la <strong>posición <?=$ranking_position?></strong> en el ranking mundial de Facebook.
                    </p>
                </div>
            </div>
        </div>
        <div class="owloo_country_section">
            <h2>
                Crecimiento de usuarios en Facebook <?=COUNTRY_DATA_NAME_ES?>
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 2</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_left owloo_country_table_chart_audience">
                    <div id="owloo_chart_audiencia" class="owloo_chart_audiencie"></div>
                </div>
                <div class="owloo_right owloo_country_table_2_content">
                    <table>
                        <thead>
                            <tr>
                                <th>Periodo</th>
                                <th class="owloo_country_table_2">Audiencia</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Día</td>
                                <td><?=$crecimiento['dia']['value']?></td>
                                <td><?=$crecimiento['dia']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Semana</td>
                                <td><?=$crecimiento['semana']['value']?></td>
                                <td><?=$crecimiento['semana']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Mes</td>
                                <td><?=$crecimiento['mes']['value']?></td>
                                <td><?=$crecimiento['mes']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Dos meses</td>
                                <td><?=$crecimiento['dos_meses']['value']?></td>
                                <td><?=$crecimiento['dos_meses']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Tres meses</td>
                                <td><?=$crecimiento['tres_meses']['value']?></td>
                                <td><?=$crecimiento['tres_meses']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Seis meses</td>
                                <td><?=$crecimiento['seis_meses']['value']?></td>
                                <td><?=$crecimiento['seis_meses']['porcentaje']?></td>    
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="owloo_country_section">
            <h2>
                Estadísticas demográficas
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 3</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_left">
                    <table >
                        <thead>
                            <tr>                         
                                <th>Edades</th>
                                <th class="owloo_country_table_3">Audiencia</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($edades as $edad){ ?>
                            <tr>
                                <td><?=$edad['rango']?></td>
                                <td><?=owloo_number_format($edad['users'])?></td>
                                <td><?=owlooFormatPorcent($edad['users'], $crecimiento['total_user'])?>%</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="owloo_right">
                    <div class="owloo_audience_gender">
                        <div class="owloo_female">
                            <strong>Mujeres</strong>
                            <!--<i class="owloo_icon"></i>-->
                            <div class="owloo_gender_number"><?=owlooFormatPorcent($crecimiento['total_female'], $crecimiento['total_user'])?>%</div>
                        </div>
                        <div class="owloo_male">
                            <strong>Hombres</strong>
                            <!--<i class="owloo_icon"></i>-->
                            <div class="owloo_gender_number"><?=owlooFormatPorcent($crecimiento['total_male'], $crecimiento['total_user'])?>%</div>
                        </div>
                    </div>
                    <?php
                        //Ordenamos los idiomas de mayor a menor cantidad de hablantes
                        $sort_edades = $edades;
                        usort($sort_edades, function($a, $b){
                            return ($a['users'] < $b['users']) ? 1 : -1;
                        });
                    ?>
                    <p>
                        En <?=COUNTRY_DATA_NAME_ES?> hay <?=owloo_number_format($crecimiento['total_user'])?> usuarios registrados en Facebook de los cuales <?=owloo_number_format($crecimiento['total_female'])?> son mujeres y <?=owloo_number_format($crecimiento['total_male'])?> son hombres. La edad promedio del usuario es de <?=$sort_edades[0]['rango']?>, equivalente al <?=(owlooFormatPorcent($sort_edades[0]['users'], $crecimiento['total_user']))?>% de la cantidad total.
                    </p>
                </div>
            </div>
        </div>
        <div class="owloo_country_section">
            <h2>
                ¿Qué idiomas hablan en <?=COUNTRY_DATA_NAME_ES?>?
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 4</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_left">
                    <p>
                        En <?=COUNTRY_DATA_NAME_ES?> el <?=owlooFormatPorcent($listIdiomas[0]['users'], $crecimiento['total_user'])?>% de los usuarios en Facebook definen como su idioma principal al <?=$listIdiomas[0]['idioma']?>, en segundo lugar se encuentra el <?=$listIdiomas[1]['idioma']?> con el <?=owlooFormatPorcent($listIdiomas[1]['users'], $crecimiento['total_user'])?>% de usuarios, posteriormente el <?=$listIdiomas[2]['idioma']?> con <?=owlooFormatPorcent($listIdiomas[2]['users'], $crecimiento['total_user'])?>% de usuarios, el <?=owlooFormatPorcent($listIdiomas[3]['users'], $crecimiento['total_user'])?>% hablan <?=$listIdiomas[3]['idioma']?> y finalmente el <?=owlooFormatPorcent($listIdiomas[4]['users'], $crecimiento['total_user'])?>% el <?=$listIdiomas[4]['idioma']?>.
                    </p>
                </div>
                <div class="owloo_right owloo_country_width_40">
                    <table id="owloo_country_table_language">
                        <thead>
                            <tr>                         
                                <th class="owloo_country_table_4">Idioma</th>
                                <th>Audiencia</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; $count_element = count($listIdiomas); foreach ($listIdiomas as $idioma) { ?>
                            <tr<?=($count++>7?' class="owloo_tr_hidden"':'')?>>
                                <td><?=$idioma['idioma']?></td>
                                <td><?=owloo_number_format($idioma['users'])?></td>
                                <td><?=owlooFormatPorcent($idioma['users'], $crecimiento['total_user'])?>%</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php if($count_element > 7){ ?>
                    <div data="owloo_country_table_language" section="idiomas" class="owloo_country_more_info">Ver más idiomas</div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="owloo_country_section">
            <h2>
                Situación sentimental de los usuarios en <?=COUNTRY_DATA_NAME_ES?>
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 5</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_left owloo_country_width_34">
                    <div class="owloo_ads_300x250 owloo_ads_box"><?=get_owloo_ads('300x250')?></div>
                </div>
                <div class="owloo_right owloo_country_width_56">
                    <p>En <?=COUNTRY_DATA_NAME_ES?> hay <?=owloo_number_format(($relacion['casado']+$relacion['soltero']+$relacion['tiene_relacion']+$relacion['comprometido']))?> usuarios que señalan su situación sentimental en Facebook, de los cuales <?=owloo_number_format($relacion['casado'])?> afirman estar casados, <?=owloo_number_format($relacion['comprometido'])?> mencionan estar comprometidos, <?=owloo_number_format($relacion['soltero'])?> indican estar solteros y por  <?=owloo_number_format($relacion['tiene_relacion'])?> colocan tener una relación sentimental en este momento.</p>
                    <br/><br/>
                    <table class="owloo_table_no_border">
                        <thead>
                            <tr>                         
                                <th class="owloo_align_center">Casados</th>
                                <th class="owloo_align_center">Comprometidos</th>
                                <th class="owloo_align_center">Relación</th>
                                <th class="owloo_align_center">Solteros</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>                         
                                <td>
                                    <div class="owloo_country_chart_circle_item">
                                        <div class="circular-item tipB owloo_tooltip_charts" title="<?=owloo_number_format($relacion['casado'])?> usuarios casados">
                                            <div class="owloo_icon_title owloo_redCircle"><?=owlooFormatPorcent($relacion['casado'], $crecimiento['total_user'], 0)?>%</div>
                                            <input type="text" value="<?=owlooFormatPorcent($relacion['casado'], $crecimiento['total_user'], 0)?>" class="redCircle" />
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="owloo_country_chart_circle_item">
                                        <div class="circular-item tipB owloo_tooltip_charts" title="<?=owloo_number_format($relacion['comprometido'])?> usuarios están comprometidos">
                                            <div class="owloo_icon_title owloo_purpleCircle"><?=owlooFormatPorcent($relacion['comprometido'], $crecimiento['total_user'], 0)?>%</div>
                                            <input type="text" value="<?=owlooFormatPorcent($relacion['comprometido'], $crecimiento['total_user'], 0)?>" class="purpleCircle" />
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="owloo_country_chart_circle_item">
                                        <div class="circular-item tipB owloo_tooltip_charts" title="<?=owloo_number_format($relacion['tiene_relacion'])?> usuarios tienen relaciones">
                                            <div class="owloo_icon_title owloo_greenCircle"><?=owlooFormatPorcent($relacion['tiene_relacion'], $crecimiento['total_user'], 0)?>%</div>
                                            <input type="text" value="<?=owlooFormatPorcent($relacion['tiene_relacion'], $crecimiento['total_user'], 0)?>" class="greenCircle" />
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="owloo_country_chart_circle_item">
                                        <div class="circular-item tipB owloo_tooltip_charts_center" title="<?=owloo_number_format($relacion['soltero'])?> usuarios solteros">
                                            <div class="owloo_icon_title owloo_blueCircle"><?=owlooFormatPorcent($relacion['soltero'], $crecimiento['total_user'], 0)?>%</div>
                                            <input type="text" value="<?=owlooFormatPorcent($relacion['soltero'], $crecimiento['total_user'], 0)?>" class="blueCircle" />
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="owloo_country_section">
            <h2>
                Top 5 de ciudades de <?=COUNTRY_DATA_NAME_ES?>
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 6</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_left">
                    <table id="owloo_country_table_language">
                        <thead>
                            <tr>                         
                                <th class="owloo_country_table_4">Ciudad</th>
                                <th>Audiencia</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $cityMayorAudiencia = get_city_top(COUNTRY_DATA_CODE, 5);
                                foreach ($cityMayorAudiencia as $city) {
                            ?>
                            <tr>
                                <td><?=$city['nombre']?></td>
                                <td><?=owloo_number_format($city['total_user'])?></td>
                                <td><?=owlooFormatPorcent($city['total_user'], $crecimiento['total_user'])?>%</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="owloo_country_more_info_link"><a href="<?=URL_ROOT?>facebook-stats/cities/<?=convert_to_url_string(COUNTRY_DATA_NAME_EN)?>/" target="_blank">Ver más ciudades</a></div>
                </div>
                <div class="owloo_right owloo_country_width_40">
                    <p>
                        Con <?=owloo_number_format($cityMayorAudiencia[0]['total_user'])?> de usuarios, <?=$cityMayorAudiencia[0]['nombre']?> es la ciudad de <?=COUNTRY_DATA_NAME_ES?> con más cantidad de audiencia en Facebook, seguido de <?=$cityMayorAudiencia[1]['nombre']?> con <?=owloo_number_format($cityMayorAudiencia[1]['total_user'])?> usuarios y, en tercer lugar, con <?=owloo_number_format($cityMayorAudiencia[2]['total_user'])?> de usuarios está <?=$cityMayorAudiencia[2]['nombre']?>.
                    </p>
                </div>
            </div>
        </div>
        <div class="owloo_country_section">
            <h2>
                ¿Qué hacen los usuarios en <?=COUNTRY_DATA_NAME_ES?>?
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 7</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_ranking_content">
                    <div class="owloo_ranking_tab">
                        <ul>
                            <li><span id="deportes" class="owloo_active owloo_first owloo_country_options_1">Deportes</span></li>
                            <li><span id="actividades" class="owloo_country_options_1">Actividades</span></li>
                            <li><span id="intereses" class="owloo_last owloo_country_options_1">Intereses</span></li>
                        </ul>
                    </div>
                    <div class="owloo_ranking<?=(!$site->loggedin?' owloo_hide_data':'')?>">
                        <?php if(!$site->loggedin){ include(FOLDER_INCLUDE.'owloo_require_login.php'); } ?>
                        <div class="owloo_country_ranking_content owloo_active owloo_country_t_1" id="owloo_ranking_deportes">
                            <?php $count = 1; foreach ($intereses_deportes as $deporte){ ?>
                            <div class="owloo_ranking_item <?=($count==12?'owloo_ranking_resumen':'')?>">
                                <div class="owloo_rank owloo_no_margin">
                                    <?php if($count != 12){ ?>
                                    <?=str_pad($count++, 2, '0', STR_PAD_LEFT);?>
                                    <?php }else{ ?>
                                    &nbsp;
                                    <?php } ?>
                                </div>
                                <div class="owloo_text">
                                    <div class="owloo_title owloo_title_strong">
                                        <strong><?=$deporte['name']?></strong>
                                    </div>
                                    <div class="owloo_description">
                                        En <?=COUNTRY_DATA_NAME_ES?> cuenta con <strong><?=owloo_number_format($deporte['users'])?> de usuarios</strong> a quienes les gusta "<?=$deporte['name']?>". Representa el <?=owlooFormatPorcent($deporte['users'], $crecimiento['total_user'])?>% del total.
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="owloo_trend_up_content">
                                <div class="owloo_trend_up">
                                    <span>Trend Up</span>
                                    <span class="owloo_msj_popup" data="<strong>Tendencia del mes</strong>: Deporte con mayor crecimiento durante los últimos 30 días en <?=COUNTRY_DATA_NAME_ES?>."></span>
                                    <div class="owloo_trend_up_name"><?=$mayorCrecimientoDeportes['nombre']?> <?=$mayorCrecimientoDeportes['cambio']?></div>
                                </div>
                                <div class="owloo_trend_up">
                                    <span>Favorito</span>
                                    <div class="owloo_trend_up_name"><?=$intereses_deportes[0]['name']?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="owloo_country_ranking_content owloo_country_t_1" id="owloo_ranking_actividades">
                            <?php $count = 1; foreach ($intereses_actividades as $actividad){ ?>
                            <div class="owloo_ranking_item">
                                <div class="owloo_rank owloo_no_margin"><?=str_pad($count++, 2, '0', STR_PAD_LEFT);?></div>
                                <div class="owloo_text">
                                    <div class="owloo_title owloo_title_strong">
                                        <strong><?=$actividad['name']?></strong>
                                    </div>
                                    <div class="owloo_description">
                                        En <?=COUNTRY_DATA_NAME_ES?> cuenta con <strong><?=owloo_number_format($actividad['users'])?> de usuarios</strong> a quienes les gusta "<?=$actividad['name']?>". Representa el <?=owlooFormatPorcent($actividad['users'], $crecimiento['total_user'])?>% del total.
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="owloo_trend_up_content">
                                <div class="owloo_trend_up">
                                    <span>Trend Up</span>
                                    <span class="owloo_msj_popup" data="<strong>Tendencia del mes</strong>: Actividad con mayor crecimiento durante los últimos 30 días en <?=COUNTRY_DATA_NAME_ES?>."></span>
                                    <div class="owloo_trend_up_name"><?=$mayorCrecimientoIntereses['nombre']?> <?=$mayorCrecimientoIntereses['cambio']?></div>
                                </div>
                                <div class="owloo_trend_up">
                                    <span>Favorito</span>
                                    <div class="owloo_trend_up_name"><?=$intereses_actividades[0]['name']?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="owloo_country_ranking_content owloo_country_t_1" id="owloo_ranking_intereses">
                            <?php $count = 1; foreach ($intereses as $interes){ ?>
                            <div class="owloo_ranking_item">
                                <div class="owloo_rank owloo_no_margin"><?=str_pad($count++, 2, '0', STR_PAD_LEFT);?></div>
                                <div class="owloo_text">
                                    <div class="owloo_title owloo_title_strong">
                                        <strong><?=$interes['name']?></strong>
                                    </div>
                                    <div class="owloo_description">
                                        En <?=COUNTRY_DATA_NAME_ES?> cuenta con <strong><?=owloo_number_format($interes['users'])?> de usuarios</strong> a quienes les interesa "<?=$interes['name']?>". Representa el <?=owlooFormatPorcent($interes['users'], $crecimiento['total_user'])?>% del total.
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="owloo_trend_up_content">
                                <div class="owloo_trend_up">
                                    <span>Trend Up</span>
                                    <span class="owloo_msj_popup" data="<strong>Tendencia del mes</strong>: Interes con mayor crecimiento durante los últimos 30 días en <?=COUNTRY_DATA_NAME_ES?>."></span>
                                    <div class="owloo_trend_up_name"><?=$mayorCrecimientoActividades['nombre']?> <?=$mayorCrecimientoActividades['cambio']?></div>
                                </div>
                                <div class="owloo_trend_up">
                                    <span>Favorito</span>
                                    <div class="owloo_trend_up_name"><?=$intereses[0]['name']?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="owloo_country_section">
            <h2>
                Dispositivos móviles conectados a Facebook en <?=COUNTRY_DATA_NAME_ES?>
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 8</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_ranking_content">
                    <div class="owloo_ranking_tab">
                        <ul>
                            <li><span id="android" class="owloo_active owloo_first owloo_country_options_2">Android</span></li>
                            <li><span id="ios" class="owloo_country_options_2">IOS</span></li>
                            <li><span id="others_mobile_os" class="owloo_last owloo_country_options_2">Otros</span></li>
                        </ul>
                    </div>
                    <div class="owloo_ranking<?=(!$site->loggedin?' owloo_hide_data':'')?>">
                        <?php if(!$site->loggedin){ include(FOLDER_INCLUDE.'owloo_require_login.php'); } ?>
                        <div class="owloo_country_ranking_content owloo_active owloo_country_t_2" id="owloo_ranking_android">
                            <?php $count = 1; foreach ($intereses_android as $android){ ?>
                            <div class="owloo_ranking_item <?=($count==7?'owloo_ranking_resumen':'')?>">
                                <div class="owloo_rank owloo_no_margin">
                                    <?php if($count != 7){ ?>
                                    <?=str_pad($count++, 2, '0', STR_PAD_LEFT);?>
                                    <?php }else{ ?>
                                    &nbsp;
                                    <?php } ?>
                                </div>
                                <div class="owloo_text">
                                    <div class="owloo_title owloo_title_strong">
                                        <strong><?=$android['name']?></strong>
                                    </div>
                                    <div class="owloo_description">
                                        En <?=COUNTRY_DATA_NAME_ES?> hay <strong><?=owloo_number_format($android['users'])?> de usuarios</strong> que acceden con "<?=$android['name']?>". Representa el <?=owlooFormatPorcent($android['users'], $crecimiento['total_user'])?>% del total.
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="owloo_trend_up_content">
                                <div class="owloo_trend_up">
                                    <span>Trend Up</span>
                                    <span class="owloo_msj_popup" data="<strong>Tendencia del mes</strong>: Android con mayor crecimiento durante los últimos 30 días en <?=COUNTRY_DATA_NAME_ES?>."></span>
                                    <div class="owloo_trend_up_name"><?=$mayorCrecimientoAndroid['nombre']?> <?=$mayorCrecimientoAndroid['cambio']?></div>
                                </div>
                                <div class="owloo_trend_up">
                                    <span>Favorito</span>
                                    <div class="owloo_trend_up_name"><?=$intereses_android[0]['name']?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="owloo_country_ranking_content owloo_country_t_2" id="owloo_ranking_ios">
                            <?php $count = 1; foreach ($intereses_ios as $ios){ ?>
                            <div class="owloo_ranking_item <?=($count==8?'owloo_ranking_resumen':'')?>">
                                <div class="owloo_rank owloo_no_margin">
                                    <?php if($count != 8){ ?>
                                    <?=str_pad($count++, 2, '0', STR_PAD_LEFT);?>
                                    <?php }else{ ?>
                                    &nbsp;
                                    <?php } ?>
                                </div>
                                <div class="owloo_text">
                                    <div class="owloo_title owloo_title_strong">
                                        <strong><?=$ios['name']?></strong>
                                    </div>
                                    <div class="owloo_description">
                                        En <?=COUNTRY_DATA_NAME_ES?> hay <strong><?=owloo_number_format($ios['users'])?> de usuarios</strong> que acceden con "<?=$ios['name']?>". Representa el <?=owlooFormatPorcent($ios['users'], $crecimiento['total_user'])?>% del total.
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="owloo_trend_up_content">
                                <div class="owloo_trend_up">
                                    <span>Trend Up</span>
                                    <span class="owloo_msj_popup" data="<strong>Tendencia del mes</strong>: IOS con mayor crecimiento durante los últimos 30 días en <?=COUNTRY_DATA_NAME_ES?>."></span>
                                    <div class="owloo_trend_up_name"><?=$mayorCrecimientoIos['nombre']?> <?=$mayorCrecimientoIos['cambio']?></div>
                                </div>
                                <div class="owloo_trend_up">
                                    <span>Favorito</span>
                                    <div class="owloo_trend_up_name"><?=$intereses_ios[0]['name']?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="owloo_country_ranking_content owloo_country_t_2" id="owloo_ranking_others_mobile_os">
                            <?php $count = 1; foreach ($intereses_others_mobile_os as $others){ ?>
                            <div class="owloo_ranking_item">
                                <div class="owloo_rank owloo_no_margin"><?=str_pad($count++, 2, '0', STR_PAD_LEFT);?></div>
                                <div class="owloo_text">
                                    <div class="owloo_title owloo_title_strong">
                                        <strong><?=$others['name']?></strong>
                                    </div>
                                    <div class="owloo_description">
                                        En <?=COUNTRY_DATA_NAME_ES?> hay <strong><?=owloo_number_format($others['users'])?> de usuarios</strong> que acceden con "<?=$others['name']?>". Representa el <?=owlooFormatPorcent($others['users'], $crecimiento['total_user'])?>% del total.
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="owloo_trend_up_content">
                                <div class="owloo_trend_up">
                                    <span>Trend Up</span>
                                    <span class="owloo_msj_popup" data="<strong>Tendencia del mes</strong>: Otro Sistema Operativo Móvil con mayor crecimiento durante los últimos 30 días en <?=COUNTRY_DATA_NAME_ES?>."></span>
                                    <div class="owloo_trend_up_name"><?=$mayorCrecimientoOtrosMoviles['nombre']?> <?=$mayorCrecimientoOtrosMoviles['cambio']?></div>
                                </div>
                                <div class="owloo_trend_up">
                                    <span>Favorito</span>
                                    <div class="owloo_trend_up_name"><?=$intereses_others_mobile_os[0]['name']?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="owloo_country_section">
            <h2>
                Crecimiento de los dispositivos móviles conectados a Facebook en <?=COUNTRY_DATA_NAME_ES?>
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 9</strong>: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_left owloo_country_width_56">
                    <p>
                        En <?=COUNTRY_DATA_NAME_ES?> el <?=owlooFormatPorcent($total_intereses_ios, $crecimiento['total_user'])?>% de los usuarios ingresan a Facebook a través de la plataforma iOS, el <?=owlooFormatPorcent($total_intereses_android, $crecimiento['total_user'])?>% usan el sistema operativo Android, además del <?=owlooFormatPorcent($total_intereses_others_mobile_bb, $crecimiento['total_user'])?>% de usuarios tienen BlackBerry y el <?=owlooFormatPorcent($total_intereses_others_mobile_windows, $crecimiento['total_user'])?>% prefieren Windows.
                    </p>
                    <div class="owloo_ads_content_468x60">
                        <div class="owloo_ads_468x60 owloo_ads_box"></div>
                    </div>
                </div>
                <div class="owloo_right owloo_country_width_34">
                    <table>
                        <thead>
                            <tr>
                                <th class="owloo_country_table_th" colspan="2">Dispositivos móviles</th>
                                <th class="owloo_country_table_th"><?=owloo_number_format($intereses_total_mobile_os)?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Día</td>
                                <td><?=$crecimientoMobileUSers['dia']['value']?></td>
                                <td><?=$crecimientoMobileUSers['dia']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Semana</td>
                                <td><?=$crecimientoMobileUSers['semana']['value']?></td>
                                <td><?=$crecimientoMobileUSers['semana']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Mes</td>
                                <td><?=$crecimientoMobileUSers['mes']['value']?></td>
                                <td><?=$crecimientoMobileUSers['mes']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Dos meses</td>
                                <td><?=$crecimientoMobileUSers['dos_meses']['value']?></td>
                                <td><?=$crecimientoMobileUSers['dos_meses']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Tres meses</td>
                                <td><?=$crecimientoMobileUSers['tres_meses']['value']?></td>
                                <td><?=$crecimientoMobileUSers['tres_meses']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Seis meses</td>
                                <td><?=$crecimientoMobileUSers['seis_meses']['value']?></td>
                                <td><?=$crecimientoMobileUSers['seis_meses']['porcentaje']?></td>    
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="owloo_social_plugin" data="facebook-country/<?=convert_to_url_string(COUNTRY_DATA_NAME_EN)?>">
            <h3>¡Comparte los datos de <?=COUNTRY_DATA_NAME_ES?> en las redes sociales!</h3>
            <div class="owloo_social_plugin_content">
                <img alt="Cargando" title="Cargando" src="<?=URL_IMAGES?>loader-24x24.gif" />
            </div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
</body>
</html>
<?php include('cache/cache.end.php'); ?>