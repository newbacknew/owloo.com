<?php
    set_time_limit(0);
    
    define('DB_USER', 'owloo_admin');
    define('DB_PASS', 'fblatamx244');
    define('DB_NAME', 'owloo_owloo');
    //Conexión a la base de datos
    $conn = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
    mysql_select_db(DB_NAME, $conn) or die(mysql_error());
    mysql_query('SET NAMES \'utf8\'');
    
    function get_url_content($url){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_URL, $url);
          $data = curl_exec($ch);
          curl_close($ch);
          return $data;
    }
    
    function get_fb_page_access_token(){
        $sql = "SELECT * FROM facebook_page_access_token ORDER BY date_add DESC LIMIT 1;";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['access_token'];
        }
        return NULL;
    }
    
    function get_page($id_page){
        $sql = "SELECT id_page, fb_id, username
                FROM facebook_page
                WHERE id_page = ".mysql_real_escape_string($id_page).";";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return array('fb_id' => $fila['fb_id'], 'username' => $fila['username']);
        }
        return NULL;
    }
    
    
    $access_token = get_fb_page_access_token();
    
    $query =   "SELECT id_page, fb_id, username, parent
                FROM facebook_page
                ORDER BY 1 Limit 1000, 2000;";
    $que = mysql_query($query) or die(mysql_error());
    
    echo '<table>';
    
    while($fila = mysql_fetch_assoc($que)){
        $global_brand = get_url_content('https://graph.facebook.com/'.$fila['fb_id'].'?fields=global_brand_parent_page.username&access_token='.$access_token);
        $global_brand = json_decode ($global_brand, true);
        
        if(isset($global_brand['global_brand_parent_page'])){
            
            echo '<tr>';
                echo '<td>';
                    echo $fila['username'];
                echo '</td>';        
                echo '<td>';
                    echo $global_brand['global_brand_parent_page']['username'];
                echo '</td>'; 
                echo '<td>';
                    echo $global_brand['global_brand_parent_page']['id'];
                echo '</td>';
                
                if(!empty($fila['parent'])){
                    $parent = get_page($fila['parent']);
                    
                    echo '<td>';
                        echo $parent['username'];
                    echo '</td>'; 
                    echo '<td>';
                        echo $parent['fb_id'];
                    echo '</td>';
                }
                else {
                    echo '<td>';
                    echo '</td>'; 
                    echo '<td>';
                    echo '</td>';
                }
            echo '<tr>';
            
        }
    }
    
    echo '</table>';