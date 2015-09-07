<?php
    require_once('owloo_config.php');
    set_current_menu('facebook');
    set_current_page('country');
    
        
$ranking_position = 2;

$crecimiento = '{"total_user":"112000000","total_female":"26000000","total_male":"84000000","dia":{"value":"<span class=\"owloo_not_change_audition\"><em>sin cambio<\/em><\/span>","porcentaje":"<span class=\"owloo_not_change_audition\"><em>n\/a<\/em><\/span>"},"semana":{"value":"<span class=\"owloo_not_change_audition\"><em>sin cambio<\/em><\/span>","porcentaje":"<span class=\"owloo_not_change_audition\"><em>n\/a<\/em><\/span>"},"mes":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">2.000.000<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">1,79%<\/span>"},"dos_meses":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">4.000.000<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">3,57%<\/span>"},"tres_meses":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">6.000.000<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">5,36%<\/span>"},"seis_meses":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">14.000.000<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">12,50%<\/span>"},"porcentaje":""}';
$crecimiento = json_decode($crecimiento, true);

$relacion = '{"soltero":"34000000","tiene_relacion":"2600000","casado":"10400000","0":"comprometido","comprometido":"860000"}';
$relacion = json_decode($relacion, true);

$edades = '[{"rango":"13 - 15","users":"4000000"},{"rango":"16 - 17","users":"9400000"},{"rango":"18 - 28","users":"72000000"},{"rango":"29 - 34","users":"13200000"},{"rango":"35 - 44","users":"8400000"},{"rango":"45 - 54","users":"2800000"},{"rango":"55 - 64","users":"1100000"},{"rango":"65+","users":"680000"}]';
$edades = json_decode($edades, true);

$listIdiomas = '[{"idioma":"Ingl\u00e9s","users":"110000000"},{"idioma":"Hindi","users":"34000000"},{"idioma":"Bengal\u00ed","users":"3600000"},{"idioma":"\u00c1rabe","users":"700000"},{"idioma":"Franc\u00e9s","users":"700000"},{"idioma":"Espa\u00f1ol","users":"300000"},{"idioma":"Alem\u00e1n","users":"200000"},{"idioma":"Chino","users":"100000"},{"idioma":"Japon\u00e9s","users":"98000"},{"idioma":"Ruso","users":"64000"},{"idioma":"Italiano","users":"62000"},{"idioma":"Coreano","users":"60000"},{"idioma":"Portugu\u00e9s","users":"48000"},{"idioma":"Indonesio","users":"28000"},{"idioma":"Malaya","users":"22000"},{"idioma":"Tailand\u00e9s","users":"17600"},{"idioma":"Holand\u00e9s","users":"16800"},{"idioma":"Turco","users":"12000"},{"idioma":"Filipino","users":"7000"},{"idioma":"Polaco","users":"5000"},{"idioma":"Vietnamita","users":"4000"}]';
$listIdiomas = json_decode($listIdiomas, true);

$intereses = '[{"name":"Comida \/ Restaurantes","users":"20000000"},{"name":"Fotograf\u00eda","users":"19400000"},{"name":"Literatura \/ Lectura","users":"18400000"},{"name":"Viajes","users":"15800000"},{"name":"Baile","users":"10400000"},{"name":"Juegos (Consola)","users":"9600000"},{"name":"Juegos (Sociales \/ Online)","users":"7800000"},{"name":"Cocina","users":"4600000"},{"name":"Jardiner\u00eda","users":"4000000"},{"name":"Actividades al aire libre","users":"1300000"},{"name":"Carga de fotos","users":"1040000"},{"name":"Bricolaje \/ Manualidades","users":"360000"},{"name":"Planificaci\u00f3n de eventos","users":"78000"}]';
$intereses = json_decode($intereses, true);

$intereses_actividades = '[{"name":"Cultura POP","users":"34000000"},{"name":"Entretenimiento (TV)","users":"19200000"},{"name":"Autom\u00f3viles","users":"14400000"},{"name":"Medio ambiente","users":"6800000"},{"name":"Noticias","users":"5600000"},{"name":"Organizaciones ben\u00e9ficas \/ Causas","users":"4800000"},{"name":"Salud y bienestar","users":"4200000"},{"name":"Hogar y jardiner\u00eda","users":"4000000"},{"name":"Cerveza \/ Vino \/ Licores","users":"3400000"},{"name":"Mascotas (todas)","users":"3400000"},{"name":"Educaci\u00f3n \/ Ense\u00f1anza","users":"1960000"}]';
$intereses_actividades = json_decode($intereses_actividades, true);

$intereses_android = '[{"name":"Samsung","users":"28000000"},{"name":"Sony","users":"4000000"},{"name":"HTC","users":"1780000"},{"name":"LG","users":"1120000"},{"name":"Motorola","users":"90000"},{"name":"Android (otro)","users":"17200000"},{"name":"Android (todos)","users":"52000000"}]';
$intereses_android = json_decode($intereses_android, true);

$intereses_ios = '[{"name":"iPhone 4","users":"720000"},{"name":"iPhone 5","users":"660000"},{"name":"iPhone 4S","users":"600000"},{"name":"iPad 2","users":"320000"},{"name":"iPad 3","users":"188000"},{"name":"iPod Touch","users":"92000"},{"name":"iPad 1","users":"34000"},{"name":"IOS\/Apple (todos)","users":"4200000"}]';
$intereses_ios = json_decode($intereses_ios, true);

$intereses_others_mobile_os = '[{"name":"Windows","users":"3400000"},{"name":"RIM \/ BlackBerry","users":"980000"}]';
$intereses_others_mobile_os = json_decode($intereses_others_mobile_os, true);

$intereses_deportes = '[{"name":"Cricket","users":"32000000"},{"name":"F\u00fatbol","users":"14600000"},{"name":"Deportes motor \/ Nascar","users":"6600000"},{"name":"Baloncesto","users":"5600000"},{"name":"Tenis","users":"4400000"},{"name":"B\u00e9isbol","users":"3200000"},{"name":"F\u00fatbol americano","users":"2600000"},{"name":"Hockey sobre hielo","users":"1060000"},{"name":"Golf","users":"660000"},{"name":"Deportes extremos","users":"300000"},{"name":"Deportes de fantas\u00eda","users":"104000"},{"name":"Todos los deportes","users":"38000000"}]';
$intereses_deportes = json_decode($intereses_deportes, true);

$crecimientoMobileUSers = '{"dia":{"value":"<span class=\"owloo_change_audition owloo_arrow_down\">22.000<\/span>","porcentaje":"<span class=\"owloo_arrow_down_porcent\">0,019643%<\/span>"},"semana":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">4.322.000<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">3,86%<\/span>"},"mes":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">4.284.000<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">3,83%<\/span>"},"dos_meses":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">6.496.000<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">5,80%<\/span>"},"tres_meses":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">10.450.000<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">9,33%<\/span>"},"seis_meses":{"value":"<span class=\"owloo_change_audition owloo_arrow_up\">24.288.000<\/span>","porcentaje":"<span class=\"owloo_arrow_up_porcent\">21,69%<\/span>"},"porcentaje":""}';
$crecimientoMobileUSers = json_decode($crecimientoMobileUSers, true);

$mayorCrecimientoDeportes = '{"id_categoria":42,"nombre":"Cricket","cambio":"<span class=\"owloo_trenp_up_porcent\">14.29%<\/span>"}';
$mayorCrecimientoDeportes = json_decode($mayorCrecimientoDeportes, true);

$mayorCrecimientoActividades = '{"id_categoria":41,"nombre":"Fotograf\u00eda","cambio":"<span class=\"owloo_trenp_up_porcent\">4.30%<\/span>"}';
$mayorCrecimientoActividades = json_decode($mayorCrecimientoActividades, true);

$mayorCrecimientoIntereses = '{"id_categoria":16,"nombre":"Cultura pop","cambio":"<span class=\"owloo_trenp_up_porcent\">6.25%<\/span>"}';
$mayorCrecimientoIntereses = json_decode($mayorCrecimientoIntereses, true);

$mayorCrecimientoAndroid = '{"id_categoria":51,"nombre":"Samsung","cambio":"<span class=\"owloo_trenp_up_porcent\">7.69%<\/span>"}';
$mayorCrecimientoAndroid = json_decode($mayorCrecimientoAndroid, true);

$mayorCrecimientoIos = '{"id_categoria":59,"nombre":"iPhone 4S","cambio":"<span class=\"owloo_trenp_up_porcent\">7.14%<\/span>"}';
$mayorCrecimientoIos = json_decode($mayorCrecimientoIos, true);

$mayorCrecimientoOtrosMoviles = '{"id_categoria":23,"nombre":"Windows","cambio":"<span class=\"owloo_trenp_up_porcent\">6.25%<\/span>"}';
$mayorCrecimientoOtrosMoviles = json_decode($mayorCrecimientoOtrosMoviles, true);

$intereses_total_mobile_os = 63074000;

$total_intereses_ios = 4200000;

$total_intereses_android = 52000000;

$total_intereses_others_mobile_bb = 980000;

$total_intereses_others_mobile_windows = 3400000;


/*print_r(json_encode($mayorCrecimientoOtrosMoviles)); die();

$xxx = '';
$xxx = json_decode($xxx, true);*/













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
                    <div class="owloo_ranking<?=(FALSE?' owloo_hide_data':'')?>">
                        <?php if(FALSE){ include(FOLDER_INCLUDE.'owloo_require_login.php'); } ?>
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