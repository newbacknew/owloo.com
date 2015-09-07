<?php
    set_time_limit(0);
        
    require_once('../config.php');
    
    //Conexión a la base de datos
    $conn = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
    mysql_select_db(DB_NAME, $conn) or die(mysql_error());
    mysql_query('SET NAMES \'utf8\'');
    
	$array_fb_id_to_id_page = array();
    
    function enviar_informe($actual, $actualizados){
        //Enviar aviso por email
        $para = 'mmolinas@latamclick.com';
        $titulo = 'Owloo - Pages';
        $mensaje = 'Actual: '.$actual.'<br>Actualizados: '.$actualizados;
        $cabeceras = 'From: dev@owloo.com' . "\r\n";
        mail($para, $titulo, $mensaje, $cabeceras);
    }
    
    function cron_update_fb_page_likes_talking($id_page, $likes, $talking_about_count){
        if(!empty($id_page) && is_numeric($likes) && is_numeric($talking_about_count)){
            $id_page = mysql_real_escape_string($id_page);
            $likes = mysql_real_escape_string($likes);
            $talking_about_count = mysql_real_escape_string($talking_about_count);
            $sql = "UPDATE facebook_page SET likes = $likes, talking_about = $talking_about_count WHERE id_page = $id_page;";
            $res = mysql_query($sql) or die(mysql_error());
            if(mysql_affected_rows() > 0){
                return true;
            }
        }
        return NULL;
    }
    
    function cron_get_likes_talking_about_from_fb($ids){
        $datos = get_url_content('https://graph.facebook.com/fql?q=SELECT+page_id,fan_count,talking_about_count,were_here_count+FROM+page+WHERE+page_id+IN('.$ids.')');
		//$datos = get_url_content('http://23.88.103.193/~david/owl/gat_fb_data_like.php?ids='.$ids);
		$datosarray =  json_decode ($datos, true);
		
        if(isset($datosarray['data']) && count($datosarray['data']) > 0){
            return $datosarray['data'];
        }
        return NULL;
    }
    
    function cron_insert_likes_talking_about_from_fb($ids){
        global $array_fb_id_to_id_page;
        $datosarray =  cron_get_likes_talking_about_from_fb($ids);
        if(!empty($datosarray)){
            $sql_insert = '';
            $aux_first = true;
            foreach($datosarray as $page){
                if(!$aux_first) $sql_insert .= ','; else $aux_first = false;
                $sql_insert .= "(".mysql_real_escape_string($array_fb_id_to_id_page[$page['page_id']]).",".mysql_real_escape_string($page['fan_count']).",".mysql_real_escape_string($page['talking_about_count']).",".mysql_real_escape_string($page['were_here_count']).",NOW())";
                
                cron_update_fb_page_likes_talking($array_fb_id_to_id_page[$page['page_id']], $page['fan_count'], $page['talking_about_count']);
                
            }
            $sql = "INSERT INTO facebook_pages_likes_talking_about(id_page, likes, talking_about, were_here_count, date) VALUES".$sql_insert.";";
            $res = mysql_query($sql) or die(mysql_error());
        }
    }
    
    $query =   "SELECT id_page, fb_id 
                FROM  facebook_page
                WHERE id_page NOT IN(
                    SELECT DISTINCT id_page
                    FROM facebook_pages_likes_talking_about
                    WHERE date = date_format(NOW(), '%Y-%m-%d')
                )
                ORDER BY 1
                ;";
    $que = mysql_query($query) or die(mysql_error());
    $cont = 0;
    $_aux_count = 0;
    $sql_in = '';
    $sql_insert = '';
    while($fila = mysql_fetch_assoc($que)){
        
        $_aux_count++;
        
        $array_fb_id_to_id_page[$fila['fb_id']] = $fila['id_page'];
        
        $cont++;
        if($cont != 1)
            $sql_in .= ',';
        $sql_in .= $fila['fb_id'];
        
        if($cont == 500){//500
            cron_insert_likes_talking_about_from_fb($sql_in);
            $cont = 0;
            $sql_in = '';
        }
    }
    if($cont > 0){
        cron_insert_likes_talking_about_from_fb($sql_in);
    }
    
    $query =   "SELECT count(*) count 
                FROM  facebook_page
                WHERE id_page NOT IN(
                    SELECT DISTINCT id_page
                    FROM facebook_pages_likes_talking_about
                    WHERE date = date_format(NOW(), '%Y-%m-%d')
                )
                ORDER BY 1
                ;";
    $que = mysql_query($query) or die(mysql_error());
    if($fila = mysql_fetch_assoc($que)){
        enviar_informe($fila['count'], $_aux_count);
    }