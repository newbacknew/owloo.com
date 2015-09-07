<?php
    session_start();
    require_once('../../owloo_config.php');
    include('../config/config.php');
    
    if(!isset($_SESSION['owloo_tw_download']) || empty($_SESSION['owloo_tw_download'])){
        die('Acceso restringido!');
    }
    
    $qry = "";
    $qry = "SELECT owloo_user_id ";
    $qry = $qry . " from owloo_user_master";
    $qry = $qry . " Where owloo_screen_name LIKE '" . mysql_real_escape_string($_SESSION['owloo_tw_download']) . "';";
    
    $res = mysql_query($qry);
    $fila = mysql_fetch_array($res);
    
    if(isset($fila['owloo_user_id'])){
        $user_id = $fila['owloo_user_id'];
        $screen_name = $_SESSION['owloo_tw_download'];
    }
    else{
        die('Acceso restringido!');
    }

    $last_id = 0;
    $csv_end = "
    ";
    $csv_sep = ",";
    $csv_file = "owloo_twitter_stats_".$screen_name."_".Date("Y_m_d").".csv";
    $csv="";
    
    $sql="";
    $sql="SELECT owloo_followers_count, owloo_following_count, owloo_tweetcount, owloo_listed_count, owloo_updated_on, DATE_FORMAT(owloo_updated_on, '%d-%m-%Y') date FROM owloo_daily_track WHERE owloo_user_twitter_id = ".mysql_real_escape_string($user_id)." ORDER BY owloo_updated_on;";
    $res=mysql_query($sql);
    
    $csv.='"Fecha"'.$csv_sep.'"Seguidores"'.$csv_sep.'"Siguiendo"'.$csv_sep.'"Cantidad de Tweets"'.$csv_end;
    
    while($row=mysql_fetch_array($res)){
        $csv.= $row['date'].$csv_sep.$row['owloo_followers_count'].$csv_sep.$row['owloo_following_count'].$csv_sep.$row['owloo_tweetcount'].$csv_end;
    }
    
    $csv.=$csv_end;$csv.=$csv_end;
    //Hashtag
    $csv.='Hashtag'.$csv_sep.'"Cantidad de uso"'.$csv_end;
    
    $qry = "";
    $qry = $qry . " SELECT owloo_user_id, owloo_hashword, count(owloo_hashword) AS count";
    $qry = $qry . " FROM owloo_hashtag";
    $qry = $qry . " WHERE owloo_user_id = ".mysql_real_escape_string($user_id);
    $qry = $qry . " GROUP BY owloo_hashword, owloo_user_id";
    $qry = $qry . " ORDER BY count DESC";
    $qrydata = mysql_query($qry);
    
    while ($fetchdata = mysql_fetch_array($qrydata)){
        $csv.= '#'.trim($fetchdata["owloo_hashword"]).$csv_sep.$fetchdata["count"].$csv_end;
    }
    
    $csv.=$csv_end;$csv.=$csv_end;
    //Menciones
    $csv.='Menciones'.$csv_sep.'"Cantidad de menciones"'.$csv_end;
    
    $qry = "";
    $qry = $qry . " SELECT owloo_user_id, owloo_screenanme , count( owloo_screenanme ) AS count";
    $qry = $qry . " FROM owloo_mentions";
    $qry = $qry . " WHERE owloo_user_id = ".mysql_real_escape_string($user_id);
    $qry = $qry . " GROUP BY owloo_screenanme, owloo_user_id";
    $qry = $qry . " ORDER BY count DESC";
    $qrydata = mysql_query($qry);
    while ($fetchdata = mysql_fetch_array($qrydata)){
        $csv.= '@'.trim($fetchdata["owloo_screenanme"]).$csv_sep.$fetchdata["count"].$csv_end;
    }
    
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename='.$csv_file);
    header('Pragma: no-cache');
    print utf8_decode($csv);
    exit();
    
    /*$_response = 0; 
    if (!$handle = fopen('csv/'.$csv_file, "w")) {  
        echo $_response;  
        exit;  
    }  
    if (fwrite($handle, utf8_decode($csv)) === FALSE) {  
        echo $_response;  
        exit;   
    }  
    fclose($handle);
    
    echo '<a class="owloo_btn owloo_btn_blue" href="'.URL_ROOT.'ranking_twitter/genera_csv/get_csv.php?filename='.$csv_file.'">Ya puedes descargarlo!</a>';
    exit();*/
    
    
    
    /*if(!isset($_GET['filename'])){
        die('Acceso denegado!');
    }
    
    if(!file_exists('csv/'.$_GET['filename'])){
        die('El archivo no existe!');
    }
    
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename='.$_GET['filename']);
    header('Pragma: no-cache');
    readfile('csv/'.$_GET['filename']);*/