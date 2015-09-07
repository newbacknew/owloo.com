<?php
    require_once('../config.php');
    
    $datos = file_get_contents('https://graph.facebook.com/search?access_token=CAACZBzNhafycBADQMMLbVmnXxd6BQRhFjwUO89NO4kGgEXFlDxZCVPB6FdUdGZCh43YNHGyIjlVF1bAoU5kYM1U7p7qDeOlaTm04wCy9hND3DdVpPAGMwtc9QQo7RUiSfGUZAbjqykeh3LzNJdzmHNMJLB7iO3e9cEeZB6oljPFVQyYdfGucKwNpZCyTwYpxTQ90GMwYpbnm9QL6SNj34yZCorvsSHVf88ZD&endpoint=/search&limit=500&locale=es_LA&method=get&pretty=0&type=adlocale');
        
    $datosarray = json_decode ($datos, true);
    
    //print_r($datosarray);
    
    /*for($i = 0; $i < count($datosarray['data']); $i++) {
        $nombre = $datosarray['data'][$i]['name'];
        $name = NULL;
        $key = $datosarray['data'][$i]['key'];
        if(isset($datosarray['data'][$i+1])){
            if($datosarray['data'][$i+1]['key'] == $key){
                $nombre = $datosarray['data'][$i+1]['name'];
                $name = $datosarray['data'][$i]['name'];
                $i++;
            }
        }
        
        $sql = "INSERT INTO facebook_language_3_1 VALUES(null, $key, '".mysql_real_escape_string($nombre)."', ".(!empty($name)?"'".$name."'":"NULL").", 1, 1);";
        $res = mysql_query($sql) or die(mysql_error());
        
    }*/
    
    echo 'FIN';
