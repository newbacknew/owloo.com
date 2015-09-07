<?php
    error_reporting(0);
    function isHTTPS(){
        return (isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on");
    }
    
    $_owloo_config_defined = true;
    
    require_once(__DIR__.'/config_db.php');
    
    //define('HTTP_HTTPS', (isHTTPS())?'https':'http');
    define('HTTP_HTTPS', (isHTTPS())?'http':'http');
    define('DOMAIN', 'www.owloo.com/');
    define('URL_ROOT', HTTP_HTTPS.'://'.DOMAIN);
    define('URL_ROOT_HTTP', 'http://'.DOMAIN);
    //define('URL_ROOT_HTTPS', 'https://'.DOMAIN);
    define('URL_ROOT_HTTPS', 'http://'.DOMAIN);
    define('URL_IMAGES', HTTP_HTTPS.'://'.DOMAIN.'static/images/');
    define('URL_CSS', HTTP_HTTPS.'://'.DOMAIN.'static/css/');
    define('URL_JS', HTTP_HTTPS.'://'.DOMAIN.'static/js/');
    define('FOLDER_INCLUDE', 'static/include/');
    
    //Conexión a la base de datos
    $conn = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
    mysql_select_db(DB_NAME, $conn) or die('www.owloo.com');
    mysql_query('SET NAMES \'utf8\'');
    
    /***** Variables globales *****/
    $_current_menu = '';
    $_current_page = '';
    $_current_user_id = '';
    /***** FIN Variables globales *****/
    
    /***** Constantes para agilizar las consultas *****/
    function get_country_total_count($hispanic = false){
        $sql = 'SELECT count(*) cantidad FROM facebook_country_3_1 '.($hispanic?'WHERE idiom = \'es\'':'').';';
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['cantidad'];
        }
        return NULL;
    }
    
//    define('COUNTRY_TOTAL_COUNT', get_country_total_count());
//    define('COUNTRY_HISPANIC_TOTAL_COUNT', get_country_total_count(true));
    
    function get_country_date_last_update(){
        $sql = 'SELECT date 
                  FROM record_country 
                  GROUP BY date 
                  HAVING count(date) = '.COUNTRY_TOTAL_COUNT.'
                  ORDER BY 1 DESC 
                  LIMIT 1;
               ';
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
//    define('COUNTRY_DATE_LAST_UPDATE', get_country_date_last_update());
    
    function get_city_total_count(){
        $sql = 'SELECT count(*) cantidad FROM facebook_city;';
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['cantidad'];
        }
        return NULL;
    }
    
//    define('CITY_TOTAL_COUNT', get_city_total_count());
//    define('MAX_LIST_CITY_COUNT', 500);
    
    function get_city_date_last_update($numSemanas = 1){
        $sql =   'SELECT date 
                    FROM record_city 
                    GROUP BY date 
                    HAVING count(date) >= '.CITY_TOTAL_COUNT.'
                    ORDER BY 1 DESC 
                    LIMIT '.$numSemanas.';
                    ';
        $res = mysql_query($sql) or die(mysql_error());
        mysql_data_seek ($res, (mysql_num_rows($res) - 1));
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
//    define('CITY_DATE_LAST_UPDATE', get_city_date_last_update());
    
    function get_country_data_from_name($name){
        if($name == 'st-lucia'){//Excepción para el país Santa Lucía
            $name = 'St. Lucia';
        }
        $sql =  "SELECT id_country, code, name, nombre 
                    FROM facebook_country_3_1 
                    WHERE name LIKE '".mysql_real_escape_string(str_ireplace('-', ' ',  strtolower($name)))."';
                 ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila;
        }
        else{
            return NULL;
        }
    }
    
    if(isset($_GET['country']) && !empty($_GET['country'])){
        $_country_data = get_country_data_from_name($_GET['country']);
        define('COUNTRY_DATA_CODE', ($_country_data?$_country_data['code']:NULL));
        define('COUNTRY_DATA_ID', ($_country_data?$_country_data['id_country']:NULL));
        define('COUNTRY_DATA_NAME_ES', ($_country_data?$_country_data['nombre']:NULL));
        define('COUNTRY_DATA_NAME_EN', ($_country_data?$_country_data['name']:NULL));
    }
    
    /***** FIN Constantes para agilizar las consultas *****/
    
    /***** Paginador *****/
    define('PAGER_PP', 20); //Cantidad de elementos que se mostraran por pagina
    define('TWITTER_TOTAL_PROFILES', 1000);
    
    define('TWITTER_MENTIONS_TOTAL_PROFILES', 100);
    
    /***** FIN Paginador *****/
    
    function get_url_content($url){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_URL, $url);
          $data = curl_exec($ch);
          curl_close($ch);
          return $data;
    }
	
	function get_country_data_from_code($code){
        
		$sql =  "SELECT id_country, code, name, nombre 
                    FROM facebook_country_3_1 
                    WHERE code LIKE '".mysql_real_escape_string(strtolower($code))."';
                 ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila;
        }
        else{
            return NULL;
        }
    }
    
    function get_country_id_from_code($code){
        $sql =  "SELECT id_country 
                    FROM facebook_country_3_1 
                    WHERE code LIKE '".mysql_real_escape_string($code)."';
                 ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['id_country'];
        }
        else{
            return NULL;
        }
    }
    
    function requireHTTPS(){
        /*if($_SERVER['PHP_SELF'] == '/userMgmt/login.php' || $_SERVER['PHP_SELF'] == '/userMgmt/signup.php' || $_SERVER['PHP_SELF'] == '/userMgmt/forgotpass.php' || $_SERVER['PHP_SELF'] == '/userMgmt/profile.php' || $_SERVER['PHP_SELF'] == '/userMgmt/settings.php' || $_SERVER['PHP_SELF'] == '/userMgmt/admin.php'){
            return true;
        }*/
        return false;
    }
    
    function convert_to_url_string($text){
        $caracteresEspeciales = array('á', 'é', 'í', 'ó', 'ú', 'ñ', ' ', '?', ',', '.', '(', ')', 'Å');
        $caracteresReemplazo = array('a', 'e', 'i', 'o', 'u', 'n', '-', '', '', '', '', '', 'a');
        return str_ireplace($caracteresEspeciales, $caracteresReemplazo,  strtolower($text));
    }
    
    function get_country_date_last_x_days($last_day, $count){
        $sql =   'SELECT date 
                    FROM record_country 
                    WHERE DATE_SUB(STR_TO_DATE(\''.$last_day.'\', \'%Y-%m-%d\'), INTERVAL '.$count.' DAY) <= date 
                    GROUP BY date
                    HAVING count(date) = '.COUNTRY_TOTAL_COUNT.'
                    ORDER BY 1 ASC 
                    LIMIT 1;
                    ';
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function get_country_total_audience_for_date($id_country, $date){
        $sql =   "SELECT total_user, total_male, total_female
                        FROM record_country
                        WHERE id_country = ".$id_country."
                            AND date = STR_TO_DATE('".$date."','%Y-%m-%d')
                    ;
                    ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['total_user'];
        }
        return NULL;
    }
    
    function get_city_total_audience_for_date($id_city, $date){
        $sql =   "SELECT total_user 
                        FROM record_city 
                        WHERE id_city = ".$id_city."
                            AND date = STR_TO_DATE('".$date."','%Y-%m-%d')
                    ;
                    ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['total_user'];
        }
        return NULL;
    }
    
    function set_current_menu($current){
        global $_current_menu;
        $_current_menu = $current;
    }
    function get_current_menu(){
        global $_current_menu;
        return $_current_menu;
    }
    function set_current_page($current){
        global $_current_page;
        $_current_page = $current;
    }
    function get_current_page(){
        global $_current_page;
        return $_current_page;
    }
    function set_current_user_id($current){
        global $_current_user_id;
        $_current_user_id = $current;
    }
    function get_current_user_id(){
        global $_current_user_id;
        return $_current_user_id;
    }
    
    function owloo_number_format($value, $separador = '.', $decimal_separator = ','){
        return str_replace(' ', '&nbsp;', number_format($value, 0, $decimal_separator, $separador));
    }
    
    function getMes($mes, $format){ //Formateo de meses
        switch((int)$mes){
            case 1: if($format == 'short') return 'Ene'; else if($format == 'large') return 'Enero';
            case 2: if($format == 'short') return 'Feb'; else if($format == 'large') return 'Febrero';
            case 3: if($format == 'short') return 'Mar'; else if($format == 'large') return 'Marzo';
            case 4: if($format == 'short') return 'Abr'; else if($format == 'large') return 'Abril';
            case 5: if($format == 'short') return 'May'; else if($format == 'large') return 'Mayo';
            case 6: if($format == 'short') return 'Jun'; else if($format == 'large') return 'Junio';
            case 7: if($format == 'short') return 'Jul'; else if($format == 'large') return 'Julio';
            case 8: if($format == 'short') return 'Ago'; else if($format == 'large') return 'Agosto';
            case 9: if($format == 'short') return 'Set'; else if($format == 'large') return 'Setiembre';
            case 10: if($format == 'short') return 'Oct'; else if($format == 'large') return 'Octubre';
            case 11: if($format == 'short') return 'Nov'; else if($format == 'large') return 'Noviembre';
            case 12: if($format == 'short') return 'Dic'; else if($format == 'large') return 'Diciembre';
        }
    }

    function owloo_format_date($fecha){
        $auxformat = explode("-", $fecha);
        $dia = $auxformat[2];
        $mes = getMes($auxformat[1], 'short');
        $anho = $auxformat[0];
        
        return $dia.'-'.$mes.'-'.$anho;
    }

    function owlooFormatPorcent($number, $total, $decimal = 2, $sep_decimal = ',', $sep_miles = '.'){
        if($total == 0)
            return 0;
        if(($number * 100 / $total) > 0.10)
            return number_format(round(($number * 100 / $total), $decimal), $decimal, $sep_decimal, $sep_miles);
        else
            return number_format(round(($number * 100 / $total), 6), 6, $sep_decimal, $sep_miles);
    }
    
    function getCountryData($code){
        $sql =   "SELECT nombre, name, total_user, date, code, total_male, total_female
                        FROM record_country rc JOIN facebook_country_3_1 c ON rc.id_country = c.id_country 
                        WHERE code LIKE '".$code."' AND date = '".COUNTRY_DATE_LAST_UPDATE."';
                    ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return array ('nombre' => $fila['nombre'], 'name' => $fila['name'], 'total_user' => $fila['total_user'], 'total_male' => $fila['total_male'], 'total_female' => $fila['total_female']);
        }
        return NULL;
    }
    
    function get_city_data($id_city, $columna){
        $sql =   "SELECT ".$columna."
                        FROM facebook_city 
                        WHERE id_city = ".$id_city.";
                    ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila[$columna];
        }
        return NULL;
    }
    
    function get_country_data($id_country, $columna){
        $sql =   "SELECT ".$columna."
                        FROM facebook_country_3_1 
                        WHERE id_country = '".$id_country."';
                    ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila[$columna];
        }
        return NULL;
    }
    
    function getRanking($id_country){
        
        $sql =  "SELECT c.id_country, nombre, total_user
                FROM record_country r 
                    JOIN facebook_country_3_1 c 
                        ON r.id_country = c.id_country 
                WHERE date = STR_TO_DATE('".COUNTRY_DATE_LAST_UPDATE."','%Y-%m-%d')
                ORDER BY total_user DESC, nombre;";
        
        $que = mysql_query($sql) or die(mysql_error());
        $cont = 1;
        while($fila = mysql_fetch_assoc($que)){
            if($fila['id_country'] == $id_country)
                return $cont;
            $cont++;
        }
        return '-';
    }

    function get_user_count(){
        $qry = "";
        $qry = " Select COUNT(*) cantidad ";
        $qry = $qry . " from owloo_user_master;";
        $chk_oldpass = mysql_query($qry);
        if ($fetch_cntr = mysql_fetch_array($chk_oldpass)) {
            return number_format($fetch_cntr['cantidad'], 0, '.', ' ');
        }
        else
            return NULL;
    }
    
    function get_user_name($screenname){
        $qry = "";
        $qry = " Select owloo_user_name ";
        $qry = $qry . " from owloo_user_master";
        $qry = $qry . " Where owloo_screen_name = '" . $screenname . "'";
        $chk_oldpass = mysql_query($qry);
        if ($fetch_cntr = mysql_fetch_array($chk_oldpass)) {
            return $fetch_cntr['owloo_user_name'];
        }
        else
            return NULL;
    }
    
    function get_user_id($screenname){
        $qry = "";
        $qry = " Select owloo_user_twitter_id ";
        $qry = $qry . " from owloo_user_master";
        $qry = $qry . " Where owloo_screen_name = '" . $screenname . "'";
        $chk_oldpass = mysql_query($qry);
        if ($fetch_cntr = mysql_fetch_array($chk_oldpass)) {
            return $fetch_cntr['owloo_user_twitter_id'];
        }
        else
            return NULL;
    }
    
    function get_owloo_user_id_from_tw_id($tw_id){
        $qry = "";
        $qry = " Select owloo_user_id ";
        $qry = $qry . " from owloo_user_master";
        $qry = $qry . " Where owloo_user_twitter_id = '" . $tw_id . "'";
        $chk_oldpass = mysql_query($qry);
        if ($fetch_cntr = mysql_fetch_array($chk_oldpass)) {
            return $fetch_cntr['owloo_user_id'];
        }
        else
            return NULL;
    }
    
    function get_twitter_date_days($days){
        $query =   "SELECT owloo_updated_on 
                    FROM owloo_daily_track 
                    WHERE DATE_SUB(STR_TO_DATE(NOW(), '%Y-%m-%d'),INTERVAL ".$days." DAY) <= owloo_updated_on 
                    GROUP BY owloo_updated_on 
                    ORDER BY 1 ASC 
                    LIMIT 1;
                    ";
        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['owloo_updated_on'];
        }
        return NULL;
    }
    
    function get_twitter_date_30_day($update_id, $tabla, $col_id, $col_date){
        $query =   "SELECT ".$col_date." 
                    FROM ".$tabla." 
                    WHERE DATE_SUB(STR_TO_DATE(
                        (SELECT MAX(".$col_date.") FROM ".$tabla." WHERE ".$col_id." = '" . $update_id . "')
                    , '%Y-%m-%d'),INTERVAL 30 DAY) <= ".$col_date." 
                    AND ".$col_id." = '" . $update_id . "'
                    GROUP BY ".$col_date." 
                    ORDER BY 1 ASC 
                    LIMIT 1;
                    ";
        $que = mysql_query($query) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            return $fila[$col_date];
        }
        return NULL;
    }
    
    function get_city_top($countryCode, $count){
        $sql =   "SELECT fc.id_city id_city, fc.name nombre, total_user, total_female, total_male, c.id_country, c.code code, c.name name
                    FROM record_city rc 
                        JOIN facebook_city fc 
                            ON rc.id_city = fc.id_city 
                        JOIN facebook_country_3_1 c 
                            ON fc.id_country = c.id_country 
                    WHERE date = STR_TO_DATE('".CITY_DATE_LAST_UPDATE."','%Y-%m-%d') 
                        AND c.code LIKE '".$countryCode."'
                    ORDER BY 3 DESC lIMIT ".$count."
                    "; 
        $res = mysql_query($sql) or die(mysql_error());
        
        $cityMayorAudiencia = array();
        while($fila = mysql_fetch_assoc($res)){ 
            $cityMayorAudiencia[] = array('id_city' => $fila['id_city'], 'nombre' => substr($fila['nombre'], 0, strpos($fila['nombre'], ',')), 'total_user' => $fila['total_user'], 'total_female' => $fila['total_female'], 'total_male' => $fila['total_male']);
        }
        return $cityMayorAudiencia;
    }
    
    function get_owloo_ads($size){
        switch ($size) {
            case '728x90':
                return '
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- Owloo 728x90 -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:728px;height:90px"
                         data-ad-client="ca-pub-2178667345797931"
                         data-ad-slot="8959189419"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>';
                break;
            case '160x600':
                return '
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- Owloo 160x600 -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:160px;height:600px"
                         data-ad-client="ca-pub-2178667345797931"
                         data-ad-slot="4389389016"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>';
                break;
            case '300x250':
                return '
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- Owloo 300x250 -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:300px;height:250px"
                         data-ad-client="ca-pub-2178667345797931"
                         data-ad-slot="7342855416"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>';
                break;
            case '468x60':
                return '
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- Owloo 468x60 -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:468px;height:60px"
                         data-ad-client="ca-pub-2178667345797931"
                         data-ad-slot="8819588616"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>';
                break;
            default:
                return '';
                break;
        }
    }

    function reconnect_db($db_name){
        $conn = mysql_connect('localhost', DB_USER, DB_PASS) or die(mysql_error());
        mysql_select_db($db_name, $conn) or die(mysql_error());
        if($db_name == DB_NAME)
            mysql_query('SET NAMES \'utf8\'');
        else
            mysql_query('SET NAMES \'latin1\'');
    }

    function is_current_favorite($type, $id_element){
        $id_user = get_current_user_id();
        if(!empty($id_user) && !empty($type) && !empty($id_element)){
            $sql =   "SELECT *
                            FROM user_favorites 
                            WHERE id_user = ".mysql_real_escape_string($id_user)." AND type LIKE '".mysql_real_escape_string($type)."' AND id_element = ".mysql_real_escape_string($id_element)." AND active = 1
                        ";
                        
            $res = mysql_query($sql) or die(mysql_error());
            if($fila = mysql_fetch_assoc($res)){
                return true;
            }
        }
        return false;
    }
    
    function get_current_favorite_count($type){
        $id_user = get_current_user_id();
        if(!empty($id_user)){
            $sql =   "SELECT count(*) count
                            FROM user_favorites 
                            WHERE id_user = ".mysql_real_escape_string($id_user)." AND type LIKE '".mysql_real_escape_string($type)."' AND active = 1
                        ";
                        
            $res = mysql_query($sql) or die(mysql_error());
            if($fila = mysql_fetch_assoc($res)){
                return $fila['count'];
            }
        }
        return false;
    }
    
    function add_current_favorite($type, $id_element){
        $id_user = get_current_user_id();
        if(!empty($id_user) && !is_current_favorite($type, $id_element)){
            $sql = "INSERT INTO user_favorites VALUES(NULL, ".mysql_real_escape_string($id_user).", '".mysql_real_escape_string($type)."', ".mysql_real_escape_string($id_element).", 1, NOW(), NULL);";
            $res = mysql_query($sql) or die(mysql_error());
            if(mysql_affected_rows() == 1){
                return true;
            }
            return false;
        }
    }
    
    function down_current_favorite($type, $id_element){
        $id_user = get_current_user_id();
        if(!empty($id_user) && is_current_favorite($type, $id_element)){
            $sql = "UPDATE user_favorites SET active = 0, date_down = NOW() WHERE id_user = ".mysql_real_escape_string($id_user)." AND type LIKE '".mysql_real_escape_string($type)."' AND id_element = ".mysql_real_escape_string($id_element).";";
            $res = mysql_query($sql) or die(mysql_error());
            return true;
        }
        return false;
    }
    
    function get_current_favorite_country_data($id_country){
            
        $sql =   "SELECT id_country, nombre, name, code
                        FROM facebook_country_3_1 
                        WHERE id_country = ".mysql_real_escape_string($id_country).";
                    ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return array('id'=> $fila['id_country'], 'nombre'=> $fila['nombre'], 'name'=> $fila['name'], 'image'=> $fila['code']);
        }
        return NULL;
    }
	
	function get_current_favorite_page_data($id_page){
            
        $sql =   "SELECT id_page, name, picture, username
                        FROM facebook_page 
                        WHERE id_page = ".mysql_real_escape_string($id_page).";
                    ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return array('id'=> $fila['id_page'], 'nombre'=> $fila['name'], 'name'=> $fila['username'], 'image'=> $fila['picture']);
        }
        return NULL;
    }
    
    function get_current_favorite_twitter_data($id_user){
        
        $qry = "";
        $qry = " Select owloo_user_twitter_id, owloo_user_name, owloo_screen_name, owloo_user_photo ";
        $qry = $qry . " from owloo_user_master";
        $qry = $qry . " Where owloo_user_twitter_id = '" . mysql_real_escape_string($id_user) . "'";
        
        $chk_oldpass = mysql_query($qry) or die(mysql_error());
        if ($fetch_cntr = mysql_fetch_array($chk_oldpass)) {
            return array('id'=> $fetch_cntr['owloo_user_twitter_id'], 'nombre'=> $fetch_cntr['owloo_user_name'], 'name'=> $fetch_cntr['owloo_screen_name'], 'image'=> $fetch_cntr['owloo_user_photo']);
        }
        else
            return NULL;
    }
    
    function get_current_favorite_array(){
        $id_user = get_current_user_id();
        if(!empty($id_user)){
            
            $sql =   "SELECT *
                            FROM user_favorites 
                            WHERE id_user = ".mysql_real_escape_string($id_user)." AND active = 1
                        ";
                        
            $res = mysql_query($sql) or die(mysql_error());
            $country_favorites = array();
            $twitter_favorites = array();
            $page_favorites = array();
            while($fila = mysql_fetch_assoc($res)){
                if($fila['type'] == 'country')
                    $country_favorites[] = $fila['id_element'];
                elseif($fila['type'] == 'page')
                    $page_favorites[] = $fila['id_element'];
                elseif($fila['type'] == 'twitter')
                    $twitter_favorites[] = $fila['id_element'];
            }
            
            if(!empty($country_favorites) || !empty($twitter_favorites) || !empty($page_favorites))
                return array('country' => $country_favorites, 'twitter' => $twitter_favorites, 'page' => $page_favorites);
            else
                return NULL; 
            
        }
        return false;
    }

    function convert_imagen_to_https($url){
        if(isHTTPS())
            return str_replace('http://', 'https://', $url);
		return $url;
    }
    
    /***** FACEBOOK PAGES *****/
    
    function add_https_to_url($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        return $url;
    }
    
    function get_fb_page_id_from_url($url){
        
        if(strpos($url, 'facebook.com/') === false){}else{
            $url = add_https_to_url($url);
        }
        
        $datos = get_url_content('https://graph.facebook.com/'.$url);
		
		//$datos = @file_get_contents('http://23.88.103.193/~david/owl/get_fb_data.php?url='.urlencode('https://graph.facebook.com/'.$url));
		$datos = json_decode ($datos, true);
		if(isset($datos['id'])){
            return $datos['id'];
        }
		
        return NULL;
    }
    
    function get_owloo_fb_page_id_from_fb_id($fb_id){
        $sql = "SELECT id_page FROM facebook_page WHERE fb_id LIKE ".mysql_real_escape_string($fb_id).";";
        $res = mysql_query($sql) or die(mysql_error());
        
        if ($row = mysql_fetch_array($res)) {
            return $row['id_page'];
        }
        else
            return NULL;
    }
    
    function get_country_id_by_location($country){
        $sql = "SELECT id_country FROM facebook_country_3_1 WHERE name LIKE '".mysql_real_escape_string($country)."';";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['id_country'];
        }
        return NULL;
    }
    
    function is_exist_fb_page($fb_id){
        $sql = 'SELECT * FROM facebook_page WHERE fb_id = '.mysql_real_escape_string($fb_id).';';
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return true;
        }
        return false;
    }
    
    function get_fb_page_sub_category($sub_category){
        $sql = "SELECT * FROM facebook_page_sub_category WHERE sub_category LIKE '".mysql_real_escape_string($sub_category)."';";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['id_sub_category'];
        }
        return NULL;
    }
    
    function add_fb_page_sub_category($sub_category){
        if(!empty($sub_category)){
            $id_sub_category = get_fb_page_sub_category($sub_category);
            if(empty($id_sub_category)){
                $sub_category = mysql_real_escape_string($sub_category);
                $sql = "INSERT INTO facebook_page_sub_category VALUES(NULL, '$sub_category');";
                $res = mysql_query($sql) or die(mysql_error());
                if(mysql_affected_rows() > 0){
                    return mysql_insert_id();
                }
            }
            else {
                return $id_sub_category;
            }
        }
        return NULL;
    }
    
    function get_fb_pages_sub_categories($id_page, $id_sub_category){
        $sql = "SELECT * FROM facebook_pages_sub_categories WHERE id_page = ".mysql_real_escape_string($id_page)." AND id_sub_category = ".mysql_real_escape_string($id_sub_category).";";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['id'];
        }
        return NULL;
    }
    
    function add_fb_pages_sub_categories($id_page, $id_sub_category){
        $id = get_fb_pages_sub_categories($id_page, $id_sub_category);
        if(empty($id)){
            $id_page = mysql_real_escape_string($id_page);
            $id_sub_category = mysql_real_escape_string($id_sub_category);
            $sql = "INSERT INTO facebook_pages_sub_categories VALUES(NULL, $id_page, $id_sub_category);";
            $res = mysql_query($sql) or die(mysql_error());
            if(mysql_affected_rows() > 0){
                return mysql_insert_id();
            }
        }
        else {
            return $id;
        }
    }
    
    function is_hispanic_country($id_country){
        $sql = "SELECT idiom FROM facebook_country_3_1 WHERE id_country = ".mysql_real_escape_string($id_country)." AND idiom = 'es';";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return true;
        }
        return 0;
    }
    
    function add_fb_page($id, $username, $name, $about, $description, $link, $picture, $cover, $location, $is_verified, $likes, $talking_about, $category){
        
        $id = mysql_real_escape_string($id);
        $username = mysql_real_escape_string($username);
        $name = mysql_real_escape_string($name);
        $about = (!empty($about)?"'".mysql_real_escape_string($about)."'":'NULL');
        $description = (!empty($description)?"'".mysql_real_escape_string($description)."'":'NULL');
        $link = (!empty($link)?"'".mysql_real_escape_string($link)."'":'NULL');
        $picture = (!empty($picture)?"'".mysql_real_escape_string($picture)."'":'NULL');
        $cover = (!empty($cover)?"'".mysql_real_escape_string($cover)."'":'NULL');
        $id_country = get_country_id_by_location($location);
        $location = (!empty($id_country)?mysql_real_escape_string($id_country):'NULL');
        $is_verified = ($is_verified?1:0);
        $likes = (is_numeric($likes)?mysql_real_escape_string($likes):'NULL');
        $talking_about = (is_numeric($talking_about)?mysql_real_escape_string($talking_about):'NULL');
        
        $hispanic = 0;
        if(!empty($location))
            $hispanic = is_hispanic_country($location);

        $sql = "INSERT INTO facebook_page VALUES(NULL, $id, '$username', '$name', $about, $description, $link, $picture, $cover, $location, $is_verified, $likes, $talking_about, NULL, $hispanic, 1, NULL, NOW(), NOW());";
        $res = mysql_query($sql) or die(mysql_error());
        if(mysql_affected_rows() > 0){
            $id_page = mysql_insert_id();
            $id_sub_category = add_fb_page_sub_category($category);
            if(!empty($id_sub_category)){
                add_fb_pages_sub_categories($id_page, $id_sub_category);
                return $id_page;
            }
            return true;
        }
        return NULL;
    }

    function get_fb_page_access_token(){
        $sql = "SELECT * FROM facebook_access_token_3_1 ORDER BY date_add DESC LIMIT 1;";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['access_token'];
        }
        return NULL;
    }
    
    function is_exist_fb_page_local_fan_date($id_page, $date){
        $sql = "SELECT count(*) count FROM facebook_page_local_fans_country WHERE id_page = ".mysql_real_escape_string($id_page)." AND date LIKE '".mysql_real_escape_string($date)."';";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            if($fila['count'] > 0){
                return true;
            }
        }
        return false;
    }
    
    function add_lote_fb_page_local_fan($sql_insert){
        if(!empty($sql_insert)){
            $sql = "INSERT INTO facebook_page_local_fans_country(id, id_page, id_country, likes, date) VALUES".$sql_insert.";";
            $res = mysql_query($sql) or die(mysql_error());
            if(mysql_affected_rows() > 0){
                return true;
            }
        }
        return NULL;
    }
    
    function is_exist_today_fb_page_likes_talking_about($id_page){
        $sql = "SELECT count(*) count FROM facebook_pages_likes_talking_about WHERE id_page = ".$id_page." AND date = date_format(date, '%Y-%m-%d');";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            if($fila['count'] > 0){
                return true;
            }
        }
        return false;
    }
    
    function add_fb_page_likes_talking_about($id_page, $likes, $talking_about_count, $were_here_count){
        if(!empty($id_page) && is_numeric($likes) && is_numeric($talking_about_count)){
            $id_page = mysql_real_escape_string($id_page);
            $likes = mysql_real_escape_string($likes);
            $talking_about_count = mysql_real_escape_string($talking_about_count);
            $sql = "INSERT INTO facebook_pages_likes_talking_about VALUES($id_page, $likes, $talking_about_count, $were_here_count, NOW());";
            $res = mysql_query($sql) or die(mysql_error());
            if(mysql_affected_rows() > 0){
                return mysql_insert_id();
            }
        }
        return NULL;
    }
    
    function is_fb_page_has_location($id_page){
        $sql = "SELECT location FROM facebook_page WHERE id_page = ".mysql_real_escape_string($id_page).";";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            if(!empty($fila['location'])){
                return true;
            }
        }
        return false;
    }
    
    function update_fb_page_likes_talking_about_local_fans($id_page, $likes, $talking_about_count, $first_country_local_fans){
        if(!empty($id_page) && is_numeric($likes) && is_numeric($talking_about_count)){
            $id_page = mysql_real_escape_string($id_page);
            $likes = mysql_real_escape_string($likes);
            $talking_about_count = mysql_real_escape_string($talking_about_count);
            $first_country_local_fans = (!empty($first_country_local_fans)?$first_country_local_fans:'NULL');
            
            $hispanic = '';
            if(!is_fb_page_has_location($id_page) && !empty($first_country_local_fans)){
                $hispanic = ', hispanic = '.is_hispanic_country($first_country_local_fans).' ';
            }
            
            $sql = "UPDATE facebook_page SET likes = $likes, talking_about = $talking_about_count, first_local_fans_country = $first_country_local_fans $hispanic WHERE id_page = $id_page;";
            $res = mysql_query($sql) or die(mysql_error());
            if(mysql_affected_rows() > 0){
                return true;
            }
        }
        return NULL;
    }
    
    function get_first_country_local_fans($id_page){
        $id_page = mysql_real_escape_string($id_page);
        $sql = "SELECT id_country FROM facebook_page_local_fans_country WHERE id_page = $id_page ORDER BY date DESC, likes DESC LIMIT 1;";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['id_country'];
        }
        return NULL;
    }
    
    function get_fb_page_local_fans_general_last_date(){
        //$sql = "SELECT date, count(*) FROM (SELECT distinct date, id_page FROM facebook_page_local_fans_country) T GROUP BY 1 HAVING COUNT(*) >= (SELECT COUNT(*) FROM facebook_page WHERE active = 1) ORDER BY date DESC LIMIT 1;";
		
		//$sql = 'SELECT date, count(*) FROM facebook_page_local_fans_country WHERE date > DATE_SUB(DATE(NOW()), INTERVAL 6 DAY) GROUP BY 1 ORDER BY 2 DESC, 1 DESC';$sql = "SELECT date, count(*) FROM (SELECT distinct date, id_page FROM facebook_page_local_fans_country) T GROUP BY 1 ORDER BY 2 DESC, date DESC LIMIT 1 ";
        
        $sql = 'SELECT date, count(*) FROM facebook_page_local_fans_country WHERE date > DATE_SUB(DATE(NOW()), INTERVAL 6 DAY) GROUP BY 1 ORDER BY 2 DESC, 1 DESC';
		
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function get_fb_page_local_fans_last_date($id_page){
        $sql = "SELECT date FROM facebook_page_local_fans_country WHERE id_page = ".mysql_real_escape_string($id_page)." GROUP BY date ORDER BY date DESC LIMIT 1;";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function get_fb_page_likes_last_update($id_page){
        $sql =   "SELECT date FROM facebook_pages_likes_talking_about WHERE id_page = ".mysql_real_escape_string($id_page)." ORDER BY date DESC LIMIT 1;"; 
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['date'];
        }
        return NULL;
    }
    
    function get_fb_page_likes_nun_dates($id_page){
        $sql =   "SELECT count(*) count FROM facebook_pages_likes_talking_about WHERE id_page = ".mysql_real_escape_string($id_page).";"; 
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['count'];
        }
        return 0;
    }
    
    function get_fb_page_local_fans_likes_nun_dates($id_page, $id_country){
        $sql =   "SELECT count(*) count FROM facebook_page_local_fans_country WHERE id_page = ".mysql_real_escape_string($id_page)." AND id_country = ".mysql_real_escape_string($id_country).";"; 
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['count'];
        }
        return 0;
    }
    
    function get_fb_page_total_rows_count($hispanic = ''){
        $sql =   "SELECT count(*) count 
                   FROM facebook_page 
                   WHERE ".mysql_real_escape_string($hispanic)." active = 1 ;"; 
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['count'];
        }
        return 0;
    }
    
    function get_fb_page_country_total_rows_count($id_country, $local_fans_last_date){
        $sql =   "SELECT count(*) count 
               FROM facebook_page p JOIN facebook_page_local_fans_country plf ON p.id_page = plf.id_page 
               WHERE id_country = ".mysql_real_escape_string($id_country)." 
                   AND plf.date = '".mysql_real_escape_string($local_fans_last_date)."' 
                   AND active = 1;"; 
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return $fila['count'];
        }
        return 0;
    }
    
    function get_fb_page_data_from_username($username){
            
        $sql =   "SELECT id_page, name, picture, username
                        FROM facebook_page 
                        WHERE username LIKE '".mysql_real_escape_string($username)."';
                    ";
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            return array('id_page'=> $fila['id_page'], 'name'=> $fila['name'], 'username'=> $fila['username'], 'image'=> $fila['picture']);
        }
        return NULL;
    }
    
    function get_fb_page_ranking($id_page, $id_country, $local_fans_last_date){
        $sql = "SELECT p.id_page id_page, username, name, picture, location, is_verified, p.likes total_likes, plf.likes local_likes, talking_about, first_local_fans_country 
               FROM facebook_page p JOIN facebook_page_local_fans_country plf ON p.id_page = plf.id_page 
               WHERE id_country = ".mysql_real_escape_string($id_country)." 
                   AND plf.date = '".$local_fans_last_date."' 
                   AND active = 1 
               ORDER BY local_likes DESC, total_likes DESC, talking_about DESC;
                 ";
                 
                 
        $que = mysql_query($sql) or die(mysql_error());
        $cont = 1;
        while($fila = mysql_fetch_assoc($que)){
            if($fila['id_page'] == $id_page)
                return $cont;
            $cont++;
        }
        return '-';
    }
    
    function getCrecimientoFacebookFansPage($id_page, $last_update, $days){
        $crecimiento = array();
        $sql = "SELECT likes, (likes - (
                            SELECT likes 
                            FROM facebook_pages_likes_talking_about 
                            WHERE id_page = $id_page
                                AND date = (
                                        SELECT date 
                                        FROM facebook_pages_likes_talking_about 
                                        WHERE DATE_SUB('$last_update',INTERVAL $days DAY) <= date
                                            AND id_page = $id_page
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    )
                             LIMIT 1
                        )) cambio 
                FROM facebook_pages_likes_talking_about
                WHERE id_page = $id_page 
                    AND date = '$last_update';
                ";
        
        $que = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            if($fila['cambio'] > 0 ){
                $crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_up">'.owloo_number_format($fila['cambio']).'</span>';
                $crecimiento['porcentaje'] = '<span class="owloo_arrow_up_porcent">'.owlooFormatPorcent($fila['cambio'], $fila['likes']).'%</span>';
            }
            else if($fila['cambio'] == 0){
                $crecimiento['value'] = '<span class="owloo_not_change_audition"><em>sin cambio</em></span>';
                $crecimiento['porcentaje'] = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
            }
            else{
                $crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_down">'.owloo_number_format(($fila['cambio'] * -1)).'</span>';
                $crecimiento['porcentaje'] = '<span class="owloo_arrow_down_porcent">'.owlooFormatPorcent(($fila['cambio']*-1), $fila['likes']).'%</span>';
            }
        }
        return $crecimiento;
    }

    function getCrecimientoFacebookLocalFansPage($id_page, $id_country, $last_update, $days){
        $crecimiento = array();
        $sql = "SELECT likes, (likes - (
                            SELECT likes 
                            FROM facebook_page_local_fans_country 
                            WHERE id_page = $id_page
                                AND id_country = $id_country
                                AND date = (
                                        SELECT date 
                                        FROM facebook_page_local_fans_country 
                                        WHERE DATE_SUB('$last_update',INTERVAL $days DAY) <= date
                                            AND id_page = $id_page
                                        ORDER BY 1 ASC 
                                        LIMIT 1
                                    ) 
                        )) cambio 
                FROM facebook_page_local_fans_country
                WHERE id_page = $id_page 
                    AND id_country = $id_country
                    AND date = '$last_update';
                ";
        
        $que = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            if($fila['cambio'] > 0 ){
                $crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_up">'.owloo_number_format($fila['cambio']).'</span>';
                $crecimiento['porcentaje'] = '<span class="owloo_arrow_up_porcent">'.owlooFormatPorcent($fila['cambio'], $fila['likes']).'%</span>';
            }
            else if($fila['cambio'] == 0){
                $crecimiento['value'] = '<span class="owloo_not_change_audition"><em>sin cambio</em></span>';
                $crecimiento['porcentaje'] = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
            }
            else{
                $crecimiento['value'] = '<span class="owloo_change_audition owloo_arrow_down">'.owloo_number_format(($fila['cambio'] * -1)).'</span>';
                $crecimiento['porcentaje'] = '<span class="owloo_arrow_down_porcent">'.owlooFormatPorcent(($fila['cambio']*-1), $fila['likes']).'%</span>';
            }
        }
        return $crecimiento;
    }
    
    /***** END - FACEBOOK PAGES *****/
