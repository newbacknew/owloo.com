<?php
    set_time_limit(0);
    
    require_once('../config.php');
    
    $conn = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
    mysql_select_db(DB_NAME, $conn) or die(mysql_error());
    mysql_query('SET NAMES \'utf8\'');
    
    $array_fb_id_to_id_page = array();
    
    function enviar_informe($actual, $actualizados){
        //Enviar aviso por email
        $para = 'mmolinas@latamclick.com';
        $titulo = 'Owloo - Pages update details';
        $mensaje = 'Actual: '.$actual.'<br>Pages update details: '.$actualizados;
        $cabeceras = 'From: dev@owloo.com' . "\r\n";
        mail($para, $titulo, $mensaje, $cabeceras);
    }
    
    function cron_get_page_details_from_fb($ids){
        $datos = get_url_content('https://graph.facebook.com/fql?q=SELECT+page_id,username,name,about,description,pic_small,pic_cover,is_verified+FROM+page+WHERE+page_id+IN('.$ids.')');
        $datosarray =  json_decode ($datos, true);
        
        if(isset($datosarray['data']) && count($datosarray['data']) > 0){
            return $datosarray['data'];
        }
        return NULL;
    }
    
    function cron_update_page_details_from_fb($ids){
        global $array_fb_id_to_id_page;
        $datosarray =  cron_get_page_details_from_fb($ids);
        
        if(!empty($datosarray)){
            $sql_update = '';
            foreach($datosarray as $page){
                $sql_update = "UPDATE facebook_page SET name = '".mysql_real_escape_string($page['name'])."', about = '".mysql_real_escape_string($page['about'])."', description = '".mysql_real_escape_string($page['description'])."', picture = '".mysql_real_escape_string($page['pic_small'])."', cover = '".mysql_real_escape_string($page['pic_cover']['source'])."', is_verified = ".mysql_real_escape_string(($page['is_verified']?1:0)).", date_update = NOW() WHERE id_page = ".mysql_real_escape_string($array_fb_id_to_id_page[$page['page_id']]).";";
                $res = mysql_query($sql_update) or die(mysql_error());
            }
        }
    }
    
    $query =   "SELECT id_page, fb_id 
                FROM  facebook_page
                WHERE date_update < date_format(NOW(), '%Y-%m-%d')
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
        
        if($cont == 500){
            cron_update_page_details_from_fb($sql_in);
            $cont = 0;
            $sql_in = '';
        }
    }
    if($cont > 0){
        cron_update_page_details_from_fb($sql_in);
    }
    
    
    $query =   "SELECT count(*) count 
                FROM  facebook_page
                WHERE date_update < date_format(NOW(), '%Y-%m-%d')
                ORDER BY 1
                ;";
    $que = mysql_query($query) or die(mysql_error());
    if($fila = mysql_fetch_assoc($que)){
        enviar_informe($fila['count'], $_aux_count);
    }