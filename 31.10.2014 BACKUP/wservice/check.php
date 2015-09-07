<?php
    require_once(__DIR__.'/config.php');
    
    function formatNum($num){
        return number_format($num, 0, ',', '.');
    }
    
    function get_total_items($table){
        $query = 'SELECT COUNT(*) cantidad FROM facebook_'.$table.'_3_1 WHERE active_fb_get_data = 1;'; 
        $que = db_query($query, array());
        if($fila = mysql_fetch_assoc($que)){
            return $fila['cantidad'];
        }
    }
    
    function get_total_record($table){
        
        $date = 'DATE(NOW())';
        if(isset($_GET['date']) && !empty($_GET['date']))
            $date = $_GET['date'];
        
        $query = 'SELECT COUNT(*) cantidad FROM facebook_record_$1_3_1 WHERE date = '.($date=='DATE(NOW())'?'$2':"'$2'").';'; 
        $que = db_query($query, array($table, $date));
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
    
/*
 * 
 * SELECT * FROM `facebook_record_city_3_1` WHERE date = '2014-10-27' AND id_city NOT IN (SELECT id_city FROM `facebook_record_city_3_1` WHERE date = '2014-10-26')
 * 
 * SELECT id_city FROM `facebook_record_city_relationship_3_1` WHERE date = '2014-10-26' GROUP BY id_city HAVING count(*) < 12
 * 
 * SELECT id_city FROM `facebook_record_city_comportamiento_3_1` WHERE date = '2014-10-26' GROUP BY id_city HAVING count(*) < 5
 * 
 * SELECT id_city FROM `facebook_record_city_interest_3_1` WHERE date = '2014-10-26' GROUP BY id_city HAVING count(*) < 5
 * 
 * */
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
</body>
</html>