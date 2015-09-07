<?php
    //error_reporting(1);
    function isHTTPS(){
        return (isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on");
    }
    
    define('HTTP_HTTPS', (isHTTPS())?'https':'http');
    define('DOMAIN', 'www.owloo.com/dev/owloo_ruben/');
    define('URL_ROOT', HTTP_HTTPS.'://'.DOMAIN);
    define('URL_ROOT_HTTP', 'http://'.DOMAIN);
    define('URL_ROOT_HTTPS', 'https://'.DOMAIN);
    define('URL_IMAGES', HTTP_HTTPS.'://'.DOMAIN.'static/images/');
    define('URL_CSS', HTTP_HTTPS.'://'.DOMAIN.'static/css/');
    define('URL_JS', HTTP_HTTPS.'://'.DOMAIN.'static/js/');
    define('FOLDER_INCLUDE', 'static/include/');
    
    /***** Variables globales *****/
    $_current_menu = '';
    $_current_page = '';
    $_current_user_id = '';
    /***** FIN Variables globales *****/
    
    /***** Constantes para agilizar las consultas *****/
    function get_country_total_count($hispanic = false){
        return ($hispanic?20:212);
    }
    
    define('COUNTRY_TOTAL_COUNT', get_country_total_count());
    define('COUNTRY_HISPANIC_TOTAL_COUNT', get_country_total_count(true));
    
    function get_city_total_count(){
        return 19232;
    }
    
    define('CITY_TOTAL_COUNT', get_city_total_count());
    define('MAX_LIST_CITY_COUNT', 500);
    
    function get_country_data_from_name($name){
        return json_decode('{"id_country":"21","code":"IN","name":"India","nombre":"India"}', true);
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
	
	function get_country_data_from_code($code){
        return json_decode('{"id_country":"80","code":"PY","name":"Paraguay","nombre":"Paraguay"}', true);
    }
    
    function get_country_id_from_code($code){
        return 80;
    }
    
    function convert_to_url_string($text){
        $caracteresEspeciales = array('á', 'é', 'í', 'ó', 'ú', 'ñ', ' ', '?', ',', '.', '(', ')', 'Å');
        $caracteresReemplazo = array('a', 'e', 'i', 'o', 'u', 'n', '-', '', '', '', '', '', 'a');
        return str_ireplace($caracteresEspeciales, $caracteresReemplazo,  strtolower($text));
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
        return json_decode('{"nombre":"India","name":"India","total_user":"112000000","total_male":"84000000","total_female":"26000000"}', true);
    }
    
    function get_user_name($screenname){
        return 'KATY PERRY';
    }
    
    function get_fb_page_local_fans_likes_nun_dates($id_page, $id_country){
        $sql =   "SELECT count(*) count FROM facebook_page_local_fans_country WHERE id_page = ".mysql_real_escape_string($id_page)." AND id_country = ".mysql_real_escape_string($id_country).";"; 
        $res = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($res)){
            echo $fila['count']; die;
            return $fila['count'];
        }
        return 0;
    }
    
    function get_fb_page_likes_nun_dates($id_page){
        return 106;
    }
    
    function get_fb_page_ranking(){
        return 2;
    }
    
    function get_fb_page_likes_last_update($id_page){
        return '2014-09-10';
    }
    
    function get_fb_page_local_fans_last_date($id_page){
        return '2014-09-08';
    }
    
    function getRanking(){
        return 71;
    }
    
    function get_fb_page_total_rows_count($hispanic = ''){
        return 1738;
    }
    
    function get_country_data($id_country, $columna){
        return 'MX';
    }
    
    
    function get_city_date_last_update($numSemanas = 1){
        return '2014-03-17';
    }
    
    define('CITY_DATE_LAST_UPDATE', get_city_date_last_update());
    
    function get_city_top($countryCode, $count){
        return json_decode('
[{"id_city":"2001","nombre":"New Delhi","total_user":"6200000","total_female":"1700000","total_male":"4600000"},{"id_city":"2002","nombre":"Mumbai","total_user":"6000000","total_female":"1760000","total_male":"4200000"},{"id_city":"2003","nombre":"Bangalore","total_user":"4400000","total_female":"1240000","total_male":"3200000"},{"id_city":"2004","nombre":"Hyderabad","total_user":"4000000","total_female":"1000000","total_male":"3000000"},{"id_city":"2005","nombre":"Chennai","total_user":"3600000","total_female":"940000","total_male":"2800000"}]', true);
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

    function convert_imagen_to_https($url){
        if(isHTTPS())
            return str_replace('http://', 'https://', $url);
		return $url;
    }
    
    /***** FACEBOOK PAGES *****/
    
    function get_fb_page_country_total_rows_count($id_country, $local_fans_last_date){
        return 1534;
    }
    
    /***** END - FACEBOOK PAGES *****/
