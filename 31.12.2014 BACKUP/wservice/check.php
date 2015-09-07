<?php
    require_once(__DIR__.'/config.php');
    
    function formatNum($num){
        return number_format($num, 0, ',', '.');
    }
    
    function get_total_items($table){
        $query = 'SELECT COUNT(*) cantidad FROM '.DB_FACEBOOK_PREFIX.$table.'_3_1 WHERE active_fb_get_data = 1;'; 
        $que = db_query($query, array());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cantidad'];
        }
    }
    
    function get_total_record($table){
        
        $date = 'DATE(NOW())';
        if(isset($_GET['date']) && !empty($_GET['date']))
            $date = $_GET['date'];
        
        $query = 'SELECT COUNT(*) cantidad FROM '.DB_FACEBOOK_PREFIX.'record_$1_3_1 WHERE date = '.($date=='DATE(NOW())'?'$2':"'$2'").';'; 
        $que = db_query($query, array($table, $date));
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cantidad'];
        }
    }
    
    function get_last_time_set_access_token(){
        $query = 'SELECT `date_add` FROM `'.DB_FACEBOOK_PREFIX.'access_token_3_1` ORDER BY 1 DESC LIMIT 1;'; 
        $que = db_query($query, array());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['date_add'];
        }
    }
    
    function get_count_facebook_pages(){
        $query = 'SELECT count(*) cantidad FROM `'.DB_FACEBOOK_PREFIX.'page` WHERE active = 1;'; 
        $que = db_query($query, array());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cantidad'];
        }
    }
    
    function get_count_insert_likes_talking_about(){
        $query = 'SELECT count(*) cantidad FROM '.DB_FACEBOOK_PREFIX.'pages_likes_talking_about WHERE date = date_format(NOW(), \'%Y-%m-%d\');'; 
        $que = db_query($query, array());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cantidad'];
        }
    }
    
    function get_count_twitter_profiles(){
        $query = 'SELECT count(*) cantidad FROM `'.DB_TWITTER_PREFIX.'user_master` WHERE `owloo_user_status` = 1;'; 
        $que = db_query_tw($query, array());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cantidad'];
        }
    }
    
    function get_count_twitter_profiles_followers(){
        $query = 'SELECT owloo_user_twitter_id, count(*) FROM `'.DB_TWITTER_PREFIX.'daily_track` WHERE `owloo_updated_on` = date_format(NOW(), \'%Y-%m-%d\') GROUP BY `owloo_user_twitter_id`;';
        $que = db_query_tw($query, array());
        return mysql_num_rows($que);
    }
    
    function get_count_twitter_klout(){
        $query = 'SELECT count(*) cantidad FROM `'.DB_TWITTER_PREFIX.'klout_tw_data`;'; 
        $que = db_query_tw($query, array());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cantidad'];
        }
    }
    
    function get_count_twitter_klout_score(){
        $query = 'SELECT count(*) cantidad FROM `'.DB_TWITTER_PREFIX.'klout_tw_data_record` WHERE `date` = date_format(NOW(), \'%Y-%m-%d\');';
        $que = db_query_tw($query, array());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cantidad'];
        }
    }
    
    function get_count_instagram_profiles(){
        $query = 'SELECT count(*) cantidad FROM `'.DB_INSTAGRAM_PREFIX.'profiles` WHERE `active` = 1;'; 
        $que = db_query($query, array());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cantidad'];
        }
    }
    
    function get_count_instagram_profiles_followers(){
        $query = 'SELECT count(*) cantidad FROM `'.DB_INSTAGRAM_PREFIX.'record` WHERE `date` = date_format(NOW(), \'%Y-%m-%d\');';
        $que = db_query($query, array());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cantidad'];
        }
    }
    
    $total_country = get_total_items('country');
    $total_age = get_total_items('age');
    $total_language = get_total_items('language');
    $total_generation = get_total_items('generation');
    $total_relationship = get_total_items('relationship');
    $total_comportamiento = get_total_items('comportamiento');
    $total_interest = get_total_items('interest');
    
    $total_region = get_total_items('region');
    
    $total_city = get_total_items('city');
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Owloo</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=100%; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=no;">
    <style>
        body, html{
            font-family: Arial;
        }
        td, th {
            padding: 5px;
            vertical-align: middle;
            border-left: 1px solid #ddd;
            border-top: 1px solid #ddd;
        }
        th {
            background-color: #fafafa;
        }
        table {
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            margin: 0 auto;
        }
        h3 {
            color: #007cc3;
            margin: 20px 0 5px;
            text-align: center;
            text-transform: uppercase;
        }
        .ok, .alert {
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
        }
        .ok {
            background-color: #5cb85c;
        }
        .alert {
            background-color: #f0ad4e;
            position: relative;
        }
        .alert a {
            color: #fff;
            display: block;
            height: 19px;
            left: 0;
            line-height: 19px;
            padding: 5px;
            position: absolute;
            text-decoration: none;
            top: 0;
            width: 57px;
        }
        .text-right{
            text-align: right;
        }
        .access_token {
            background-color: #f0ad4e;
            border: 1px solid #eea236;
            border-radius: 3px;
            color: #fff;
            display: block;
            font-weight: bold;
            margin: 20px auto 0;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            width: 200px;
        }
        @media only screen and (max-width: 370px) {
            td, th {
                font-size: 14px;
            }
        }
        @media only screen and (max-width: 340px) {
            td, th {
                font-size: 13px;
            }
        }
        @media only screen and (max-width: 310px) {
            td, th {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <a href="http://www.owloo.com/wservice/access_token_3_1/set_access_token.php" target="_blank" class="access_token"><?=get_last_time_set_access_token()?></a>
    <h3>Países</h3>
    <table cellspacing="0">
        <tr>
            <th>TYPE</th>
            <th>TOTAL</th>
            <th>INSERT</th>
            <th>CHECK</th>
        </tr>
        <tr>
            <td>Audiencias</td>
            <td class="text-right"><?=formatNum($total_country)?></td>
            <td class="text-right"><? $aux = get_total_record('country'); echo formatNum($aux); ?></td>
            <td class="<?=($total_country == $aux?'ok':'alert')?>"><?=($total_country == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/country_3_1/record_aux.php?type=country">alert</a>')?></td>
        </tr>
        <tr>
            <td>Edades</td>
            <td class="text-right"><?=formatNum($total_country*$total_age)?></td>
            <td class="text-right"><? $aux = get_total_record('country_age'); echo formatNum($aux); ?></td>
            <td class="<?=($total_country*$total_age == $aux?'ok':'alert')?>"><?=($total_country*$total_age == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/country_3_1/record_aux.php?type=country_age">alert</a>')?></td>
        </tr>
        <tr>
            <td>Lenguajes</td>
            <td class="text-right"><?=formatNum($total_country*$total_language)?></td>
            <td class="text-right"><? $aux = get_total_record('country_language'); echo formatNum($aux); ?></td>
            <td class="<?=($total_country*$total_language == $aux?'ok':'alert')?>"><?=($total_country*$total_language == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/country_3_1/record_aux.php?type=country_language">alert</a>')?></td>
        </tr>
        <tr>
            <td>Relaciones</td>
            <td class="text-right"><?=formatNum($total_country*$total_relationship)?></td>
            <td class="text-right"><? $aux = get_total_record('country_relationship'); echo formatNum($aux); ?></td>
            <td class="<?=($total_country*$total_relationship == $aux?'ok':'alert')?>"><?=($total_country*$total_relationship == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/country_3_1/record_aux.php?type=country_relationship">alert</a>')?></td>
        </tr>
        <tr>
            <td>Generaciones</td>
            <td class="text-right"><?=formatNum($total_country*$total_generation)?></td>
            <td class="text-right"><? $aux = get_total_record('country_generation'); echo formatNum($aux); ?></td>
            <td class="<?=($total_country*$total_generation == $aux?'ok':'alert')?>"><?=($total_country*$total_generation == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/country_3_1/record_aux.php?type=country_generation">alert</a>')?></td>
        </tr>
        <tr>
            <td>Comportamientos</td>
            <td class="text-right"><?=formatNum($total_country*$total_comportamiento)?></td>
            <td class="text-right"><? $aux = get_total_record('country_comportamiento'); echo formatNum($aux); ?></td>
            <td class="<?=($total_country*$total_comportamiento == $aux?'ok':'alert')?>"><?=($total_country*$total_comportamiento == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/country_3_1/record_aux.php?type=country_comportamiento">alert</a>')?></td>
        </tr>
        <tr>
            <td>Intereses</td>
            <td class="text-right"><?=formatNum($total_country*$total_interest)?></td>
            <td class="text-right"><? $aux = get_total_record('country_interest'); echo formatNum($aux); ?></td>
            <td class="<?=($total_country*$total_interest == $aux?'ok':'alert')?>"><?=($total_country*$total_interest == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/country_3_1/record_aux.php?type=country_interest">alert</a>')?></td>
        </tr>
    </table>
    
    <h3>Regiones</h3>
    <table cellspacing="0">
        <tr>
            <th>TYPE</th>
            <th>TOTAL</th>
            <th>INSERT</th>
            <th>CHECK</th>
        </tr>
        <tr>
            <td>Audiencias</td>
            <td class="text-right"><?=formatNum($total_region)?></td>
            <td class="text-right"><? $aux = get_total_record('region'); echo formatNum($aux); ?></td>
            <td class="<?=($total_region == $aux?'ok':'alert')?>"><?=($total_region == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/region_3_1/record_aux.php?type=region">alert</a>')?></td>
        </tr>
        <tr>
            <td>Edades</td>
            <td class="text-right"><?=formatNum($total_region*$total_age)?></td>
            <td class="text-right"><? $aux = get_total_record('region_age'); echo formatNum($aux); ?></td>
            <td class="<?=($total_region*$total_age == $aux?'ok':'alert')?>"><?=(($total_region*$total_age) == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/region_3_1/record_aux.php?type=region_age">alert</a>')?></td>
        </tr>
        <tr>
            <td>Relaciones</td>
            <td class="text-right"><?=formatNum($total_region*$total_relationship)?></td>
            <td class="text-right"><? $aux = get_total_record('region_relationship'); echo formatNum($aux); ?></td>
            <td class="<?=($total_region*$total_relationship == $aux?'ok':'alert')?>"><?=(($total_region*$total_relationship) == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/region_3_1/record_aux.php?type=region_relationship">alert</a>')?></td>
        </tr>
        <tr>
            <td>Comportamientos</td>
            <td class="text-right"><?=formatNum($total_region*5)?></td>
            <td class="text-right"><? $aux = get_total_record('region_comportamiento'); echo formatNum($aux); ?></td>
            <td class="<?=($total_region*5 == $aux?'ok':'alert')?>"><?=(($total_region*5) == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/region_3_1/record_aux.php?type=region_comportamiento">alert</a>')?></td>
        </tr>
        <tr>
            <td>Intereses</td>
            <td class="text-right"><?=formatNum($total_region*5)?></td>
            <td class="text-right"><? $aux = get_total_record('region_interest'); echo formatNum($aux); ?></td>
            <td class="<?=($total_region*5 == $aux?'ok':'alert')?>"><?=(($total_region*5) == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/region_3_1/record_aux.php?type=region_interest">alert</a>')?></td>
        </tr>
    </table>
    
    <h3>Ciudades</h3>
    <table cellspacing="0">
        <tr>
            <th>TYPE</th>
            <th>TOTAL</th>
            <th>INSERT</th>
            <th>CHECK</th>
        </tr>
        <tr>
            <td>Audiencias</td>
            <td class="text-right"><?=formatNum($total_city)?></td>
            <td class="text-right"><? $aux = get_total_record('city'); echo formatNum($aux); ?></td>
            <td class="<?=($total_city == $aux?'ok':'alert')?>"><?=($total_city == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/city_3_1/record_aux.php?type=city">alert</a>')?></td>
        </tr>
        <tr>
            <td>Edades</td>
            <td class="text-right"><?=formatNum($total_city*$total_age)?></td>
            <td class="text-right"><? $aux = get_total_record('city_age'); echo formatNum($aux); ?></td>
            <td class="<?=($total_city*$total_age == $aux?'ok':'alert')?>"><?=($total_city*$total_age == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/city_3_1/record_aux.php?type=city_age">alert</a>')?></td>
        </tr>
        <tr>
            <td>Relaciones</td>
            <td class="text-right"><?=formatNum($total_city*$total_relationship)?></td>
            <td class="text-right"><? $aux = get_total_record('city_relationship'); echo formatNum($aux); ?></td>
            <td class="<?=($total_city*$total_relationship == $aux?'ok':'alert')?>"><?=($total_city*$total_relationship == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/city_3_1/record_aux.php?type=city_relationship">alert</a>')?></td>
        </tr>
        <tr>
            <td>Comportamientos</td>
            <td class="text-right"><?=formatNum($total_city*5)?></td>
            <td class="text-right"><? $aux = get_total_record('city_comportamiento'); echo formatNum($aux); ?></td>
            <td class="<?=($total_city*5 == $aux?'ok':'alert')?>"><?=($total_city*5 == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/city_3_1/record_aux.php?type=city_comportamiento">alert</a>')?></td>
        </tr>
        <tr>
            <td>Intereses</td>
            <td class="text-right"><?=formatNum($total_city*5)?></td>
            <td class="text-right"><? $aux = get_total_record('city_interest'); echo formatNum($aux); ?></td>
            <td class="<?=($total_city*5 == $aux?'ok':'alert')?>"><?=($total_city*5 == $aux?'ok':'<a target="_blank" href="http://www.owloo.com/wservice/city_3_1/record_aux.php?type=city_interest">alert</a>')?></td>
        </tr>
    </table>
    
    <h3>Facebook / Twitter</h3>
    <table cellspacing="0">
        <tr>
            <th>TYPE</th>
            <th>TOTAL</th>
            <th>INSERT</th>
            <th>DIF</th>
        </tr>
        <tr>
            <td>Facebook Pages</td>
            <td class="text-right"><?=formatNum(get_count_facebook_pages())?></td>
            <td class="text-right"><?=formatNum(get_count_insert_likes_talking_about())?></td>
            <td class="text-right"><?=formatNum(get_count_facebook_pages()-get_count_insert_likes_talking_about())?></td>
        </tr>
        <tr>
            <td>Twitter Profiles</td>
            <td class="text-right"><?=formatNum(get_count_twitter_profiles())?></td>
            <td class="text-right"><?=formatNum(get_count_twitter_profiles_followers())?></td>
            <td class="text-right"><?=formatNum(get_count_twitter_profiles()-get_count_twitter_profiles_followers())?></td>
        </tr>
        <tr>
            <td>Twitter Klout</td>
            <td class="text-right"><?=formatNum(get_count_twitter_klout())?></td>
            <td class="text-right"><?=formatNum(get_count_twitter_klout_score())?></td>
            <td class="text-right"><?=formatNum(get_count_twitter_klout()-get_count_twitter_klout_score())?></td>
        </tr>
        <tr>
            <td>Instagram Profiles</td>
            <td class="text-right"><?=formatNum(get_count_instagram_profiles())?></td>
            <td class="text-right"><?=formatNum(get_count_instagram_profiles_followers())?></td>
            <td class="text-right"><?=formatNum(get_count_instagram_profiles()-get_count_instagram_profiles_followers())?></td>
        </tr>
    </table>
</body>
</html>