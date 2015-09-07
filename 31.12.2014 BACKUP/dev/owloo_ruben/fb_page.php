<?php
    require_once('owloo_config.php');
    set_current_menu('facebook');
    set_current_page('page_profile');
    
    $mensaje_new_fan_page = NULL;
        
    $n_a = '<span class="owloo_not_change_audition"><em>n/a</em></span>';

    $_fb_username = 'cristiano';
    
    $page_data = '{"id_page":"7","fb_id":"81221197163","username":"Cristiano","name":"Cristiano Ronaldo","about":"Welcome to the OFFICIAL Facebook page of Cristiano Ronaldo.","description":"","link":"https:\/\/www.facebook.com\/Cristiano","picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-xfp1\/v\/t1.0-1\/p50x50\/10363769_10152518873712164_4951848658666973254_n.jpg?oh=a58b55ef5e822b30902b977a7ae2ae84&oe=549A1A5F&__gda__=1419871796_c17e96d5391735170849fab441a859b4","cover":"https:\/\/fbcdn-sphotos-g-a.akamaihd.net\/hphotos-ak-xaf1\/v\/t1.0-9\/s720x720\/10441268_10152518873232164_3307823841159892259_n.png?oh=3bd65e113bba9c1e1e76485d9302de78&oe=549E56E5&__gda__=1419091984_8bd025bd1060a7ebb3ad1541f710ab14","location":"47","is_verified":"1","likes":"97627263","talking_about":"2985798","first_local_fans_country":"22","hispanic":"1","active":"1","date_add":"2014-05-29","date_update":"2014-09-10"}';
    $page_data = json_decode($page_data, true);
    
    $local_fans_country = '[{"id_country":"22","name":"Indonesia","nombre":"Indonesia","likes":"8357817"},{"id_country":"8","name":"Brazil","nombre":"Brasil","likes":"7177000"},{"id_country":"21","name":"India","nombre":"India","likes":"6080822"},{"id_country":"31","name":"Mexico","nombre":"M\u00e9xico","likes":"5689106"},{"id_country":"1","name":"United States","nombre":"Estados Unidos","likes":"4680022"},{"id_country":"52","name":"Turkey","nombre":"Turqu\u00eda","likes":"4433141"},{"id_country":"15","name":"Egypt","nombre":"Egipto","likes":"3292566"},{"id_country":"11","name":"Colombia","nombre":"Colombia","likes":"2796091"},{"id_country":"87","name":"Vietnam","nombre":"Vietnam","likes":"2723131"},{"id_country":"4","name":"Argentina","nombre":"Argentina","likes":"2288461"},{"id_country":"17","name":"France","nombre":"Francia","likes":"2266088"},{"id_country":"25","name":"Italy","nombre":"Italia","likes":"2203166"},{"id_country":"30","name":"Malaysia","nombre":"Malasia","likes":"2122036"},{"id_country":"3","name":"United Kingdom","nombre":"Reino Unido","likes":"1823604"},{"id_country":"18","name":"Germany","nombre":"Alemania","likes":"1585499"},{"id_country":"38","name":"Peru","nombre":"Per\u00fa","likes":"1516871"},{"id_country":"54","name":"Venezuela","nombre":"Venezuela","likes":"1499757"},{"id_country":"51","name":"Thailand","nombre":"Tailandia","likes":"1477349"},{"id_country":"47","name":"Spain","nombre":"Espa\u00f1a","likes":"1379104"},{"id_country":"99","name":"Algeria","nombre":"Argelia","likes":"1376823"},{"id_country":"68","name":"Morocco","nombre":"Marruecos","likes":"1295661"},{"id_country":"55","name":"Portugal","nombre":"Portugal","likes":"1281755"},{"id_country":"9","name":"Chile","nombre":"Chile","likes":"1263186"},{"id_country":"36","name":"Pakistan","nombre":"Pakist\u00e1n","likes":"1185498"},{"id_country":"64","name":"Bangladesh","nombre":"Banglad\u00e9s","likes":"1006795"},{"id_country":"34","name":"Nigeria","nombre":"Nigeria","likes":"892073"},{"id_country":"98","name":"Iraq","nombre":"Irak","likes":"863865"},{"id_country":"71","name":"Ecuador","nombre":"Ecuador","likes":"859998"},{"id_country":"40","name":"Poland","nombre":"Polonia","likes":"808457"},{"id_country":"85","name":"Tunisia","nombre":"T\u00fanez","likes":"769310"},{"id_country":"72","name":"Romania","nombre":"Rumania","likes":"739203"},{"id_country":"2","name":"Canada","nombre":"Canad\u00e1","likes":"598353"},{"id_country":"43","name":"Serbia","nombre":"Serbia","likes":"563042"},{"id_country":"39","name":"Philippines","nombre":"Filipinas","likes":"550309"},{"id_country":"45","name":"South Africa","nombre":"Sud\u00e1frica","likes":"530012"},{"id_country":"101","name":"Nepal","nombre":"Nepal","likes":"512611"},{"id_country":"27","name":"Jordan","nombre":"Jordania","likes":"492231"},{"id_country":"5","name":"Australia","nombre":"Australia","likes":"470337"},{"id_country":"42","name":"Saudi Arabia","nombre":"Arabia Saudita","likes":"461104"},{"id_country":"19","name":"Greece","nombre":"Grecia","likes":"446604"},{"id_country":"46","name":"South Korea","nombre":"Corea del Sur","likes":"432198"},{"id_country":"74","name":"Guatemala","nombre":"Guatemala","likes":"412232"},{"id_country":"7","name":"Belgium","nombre":"B\u00e9lgica","likes":"372458"},{"id_country":"75","name":"Costa Rica","nombre":"Costa Rica","likes":"367545"}]';
    $local_fans_country = json_decode($local_fans_country, true);

    $crecimiento_fans = '{"dia":{"value":"71.406<\/span>","porcentaje":"0,073141%<\/span>"},"semana":{"value":"548.804<\/span>","porcentaje":"0,56%<\/span>"},"quincena":{"value":"1.387.725<\/span>","porcentaje":"1,42%<\/span>"},"mes":{"value":"2.957.132<\/span>","porcentaje":"3,03%<\/span>"},"dos_meses":{"value":"6.572.403<\/span>","porcentaje":"6,73%<\/span>"},"tres_meses":{"value":"n\/a<\/em><\/span>","porcentaje":"n\/a<\/em><\/span>"}}';
    $crecimiento_fans = json_decode($crecimiento_fans, true);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Analiza el crecimiento de fans, el PTA y otras estadísticas de la página de <?=$page_data['name']?> en Facebook." />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="content-language" content="es" />    
    <meta http-equiv="pragma" content="nocache" />
    <meta http-equiv="cache-control" content="no-cache, must-revalidate" />
    
    <title>Estadísticas de <?=$page_data['name']?> en Facebook - Owloo</title>
    
    <meta property="og:image" content="<?=URL_IMAGES?>owloo_logo_redes_sociales.png" /> 
    
    <link rel="stylesheet" type="text/css" href="<?=URL_CSS?>selectize.default.css?v=1.1" />
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
    
</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            <a class="owloo_navegator" href="<?=URL_ROOT?>facebook-stats/pages/hispanic/">Páginas de Facebook más populares</a>
            <span class="owloo_separator">&gt;</span>
            <span class="owloo_navegator"><?=$page_data['name']?></span>
        </div>
    </div> 
    <div class="owloo_main owloo_main_content">
        <?php if(!empty($mensaje_new_fan_page)){ ?>
        <div class="owloo_msj_alert owloo_fb_alert_msj">
            <?=$mensaje_new_fan_page?>           
        </div>
        <?php }else{ ?>
        <div class="owloo_tools_content">
            <div class="owloo_tools">
                <span class="owloo_msj_popup" data="<p>Presionando el botón puedes monitorear una o varias páginas de Facebook.</p><p>Cada vez que accedas a Mi cuenta > Monitoreo, podrás ver rápidamente estas páginas, si quieres dejar de monitorearlas presiona nuevamente el botón.</p>"></span>
                <span class="owloo_text">Monitorea esta página</span>
                <div id="owloo_favorite" class="owloo_favorite_country_ajax owloo_favorite_icon" type="page" element="<?=$page_data['id_page']?>"></div>
            </div>
        </div>
        <div class="owloo_country_main_title">
            <?php if(!empty($page_data['picture'])){ ?><img class="owloo_country_flag owloo_fb_page_picture" src="<?=$page_data['picture']?>" title="" /><?php } ?>
            <h1 class="owloo_main_title_h1 owloo_align_left">
                <?=$page_data['name']?> en Facebook
            </h1>
        </div>
        <div class="owloo_country_section">
            <h2>
                Resumen de las estadísticas
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 1</strong>: Lorem Ipsum is simply dummy text."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                 <div class="owloo_left">
                    <table>
                        <tbody>
                            <tr>
                                <td class="owloo_country_table_th">Cantidad total de fans</td>
                                <td class="owloo_country_table_th owloo_country_table_1"><?=owloo_number_format($page_data['likes'])?></td>
                            </tr>
                            <tr>
                                <td>PTA</td>
                                <td><?=owloo_number_format($page_data['talking_about'])?></td>
                            </tr>
                            <tr>
                                <td>Personas hablando de esto</td>
                                <td><?=owlooFormatPorcent($page_data['talking_about'], $page_data['likes'])?>%</td>
                            </tr>
                            <tr>
                                <td>Página verificada</td>
                                <td><?php
                                        if($page_data['is_verified']){
                                            $verimg = '<img src="'.URL_IMAGES.'owloo_fb_page_verified_17x17.png" class="owloo_tooltip" title="Cuenta verificada" alt="Si"/>';
                                        }else{
                                            $verimg = '<img src="'.URL_IMAGES.'owloo_tw_not_verified.png" class="owloo_tooltip" title="Cuenta no verificada" alt="No"/>';
                                        }
                                        echo $verimg;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>En Owloo desde</td>
                                <td><?php
                                        $date = new DateTime($page_data['date_add']);
                                        echo $date->format('d-M-Y');
                                    ?>
                                </td>
                            </tr>
                            <?php if(!empty($page_data['location']) || !empty($page_data['first_local_fans_country'])){ ?>
                            <tr>
                                <td><?=(!empty($page_data['location'])?'Localización':'Popular en')?></td>
                                <td>
                                    <a href="<?=URL_ROOT?>facebook-stats/pages/country/<?=convert_to_url_string(get_country_data((!empty($page_data['location'])?$page_data['location']:$page_data['first_local_fans_country']), 'code'))?>/" title="Páginas más populares de Facebook en <?=get_country_data((!empty($page_data['location'])?$page_data['location']:$page_data['first_local_fans_country']), 'nombre')?>">
                                        <span class="owloo_country_flag_fb_page owloo_country_flag" style="background-position:0 <?=(-20 * ((!empty($page_data['location'])?$page_data['location']:$page_data['first_local_fans_country'])-1))?>px"></span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <?php $ranking_position = get_fb_page_ranking(); ?>
                                <td>Posición en el ranking de <?=get_country_data((!empty($page_data['location'])?$page_data['location']:$page_data['first_local_fans_country']), 'nombre')?></td>
                                <td><?=$ranking_position?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="owloo_right">
                    <p>
                       <strong><?=$page_data['name']?></strong> cuenta con <strong><?=owloo_number_format($page_data['likes'])?> fans</strong> en Facebook y actualmente <strong>el PTA</strong> es de <strong><?=owloo_number_format($page_data['talking_about'])?></strong>.
                    </p>
                    <p>
                        El país con más fans de <?=$page_data['name']?> es <?=get_country_data($page_data['first_local_fans_country'], 'nombre')?> y se posiciona al puesto <?=$ranking_position?> en el ranking de páginas de <?=get_country_data((!empty($page_data['location'])?$page_data['location']:$page_data['first_local_fans_country']), 'nombre')?>.
                    </p>
                    <?php if(!empty($page_data['about'])){ ?>
                    <p class="owloo_fb_page_about">
                        <em><?=$page_data['about']?></em>
                    </p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="owloo_country_section">
            <h2>
                Crecimiento de fans durante los últimos 30 días
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 2</strong>: Lorem Ipsum is simply dummy text."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_left owloo_country_table_chart_audience">
                    <div id="owloo_chart_likes" class="owloo_chart_audiencie"></div>
                </div>
                <div class="owloo_right owloo_country_table_2_content">
                    <table>
                        <thead>
                            <tr>
                                <th>Periodo</th>
                                <th class="owloo_country_table_2">Fans</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Día</td>
                                <td><?=$crecimiento_fans['dia']['value']?></td>
                                <td><?=$crecimiento_fans['dia']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Semana</td>
                                <td><?=$crecimiento_fans['semana']['value']?></td>
                                <td><?=$crecimiento_fans['semana']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Dos semanas</td>
                                <td><?=$crecimiento_fans['quincena']['value']?></td>
                                <td><?=$crecimiento_fans['quincena']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Mes</td>
                                <td><?=$crecimiento_fans['mes']['value']?></td>
                                <td><?=$crecimiento_fans['mes']['porcentaje']?></td>
                            </tr>
                            <tr>
                                <td>Dos meses</td>
                                <td><?=$crecimiento_fans['dos_meses']['value']?></td>
                                <td><?=$crecimiento_fans['dos_meses']['porcentaje']?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="owloo_country_section">
            <h2>
                Monitoreo del PTA durante los últimos 30 días
                <span class="owloo_msj_popup" data="<p>El PTA de una página es una métrica que incluye diferentes mediciones, como los post de usuarios en el muro, los comentarios, compartir post, me gusta de fotos y vídeos, etiquetas, regístros a un evento de la página entre otros datos.</p>
                Owloo clasifica óptima una página de Facebook en base a los siguientes porcentajes de su PTA: 6%+ óptimo, 5%-3% excelente, 2%-1% regular, -1% negativo.</p>"></span>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_left owloo_country_table_2_content">
                    <div class="owloo_chart_pta_content">
                        <div class="owloo_title">Owloo PTA Monitor</div>
                        <div id="owloo_chart_pta"></div>
                        <span class="owloo_gauge_chart_min">0</span>
                        <span class="owloo_gauge_chart_max"><?=(owlooFormatPorcent($page_data['talking_about'], $page_data['likes'], 2, '.', '')>6?'+':'')?>6</span>
                    </div>
                </div>
                <div class="owloo_right owloo_country_table_chart_audience">
                    <div id="owloo_chart_likes_talking_about" class="owloo_chart_audiencie"></div>
                </div>
            </div>
        </div>
        <?php if(!empty($local_fans_country) && count($local_fans_country) > 0){ ?>
        <div class="owloo_country_section">
            <h2>
                Fans de <?=$page_data['name']?> por país
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 4</strong>: Lorem Ipsum is simply dummy text."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_left owloo_country_width_40">
                    <table id="owloo_fb_table_local_fans">
                        <thead>
                            <tr>
                                <th class="owloo_fb_page_table_1">País</th>
                                <th>Fans</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1; 
                            $count_element = count($local_fans_country);
                            $total_local_fans_country = 0;
							$temp_3_local_fans = array();
                            
                            $datos = array();
                            
                            foreach($local_fans_country as $local_fan){
                                
                                $datos[] = $local_fan;
                                 
								$total_local_fans_country += $local_fan['likes'];
								if($count < 4)
									$temp_3_local_fans[] = array('country' => $local_fan['nombre'], 'fans' => $local_fan['likes']);
							?>
                            <tr<?=($count++>7?' class="owloo_tr_hidden"':'')?>>
                                <td>
                                    <span class="owloo_country_flag_fb_local_fans owloo_country_flag" style="background-position:0 <?=(-20 * ($local_fan['id_country']-1))?>px"></span> <?=$local_fan['nombre']?>
                                </td>
                                <td><?=owloo_number_format($local_fan['likes'])?></td>
                                <td><?=owlooFormatPorcent($local_fan['likes'], $page_data['likes'])?>%</td>
                            </tr>
                            <?php } ?>
                            <?php if($total_local_fans_country != $page_data['likes']){ ?>
                            <tr<?=($count++>7?' class="owloo_tr_hidden"':'')?>>
                                <td><span class="owloo_fb_others_countries">Otros países</span></td>
                                <td><?=owloo_number_format($page_data['likes'] - $total_local_fans_country)?></td>
                                <td><?=owlooFormatPorcent(($page_data['likes'] - $total_local_fans_country), $page_data['likes'])?>%</td>
                            </tr> 
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php if($count_element > 7){ ?>
                    <div data="owloo_fb_table_local_fans" section="países" class="owloo_country_more_info">Ver más países</div>
                    <?php } ?>
                </div>
                <div class="owloo_right">
                    <p>
                        Con <?=owloo_number_format($temp_3_local_fans[0]['fans'])?> <?=$temp_3_local_fans[0]['country']?> es el país con mayor cantidad de fans que siguen a <?=$page_data['name']?> en Facebook, seguido por <?=$temp_3_local_fans[1]['country']?> con <?=owloo_number_format($temp_3_local_fans[1]['fans'])?> y <?=$temp_3_local_fans[2]['country']?> con <?=owloo_number_format($temp_3_local_fans[2]['fans'])?>.
                    </p>
                    <div class="owloo_ads_content_468x60">
                        <div class="owloo_ads_468x60 owloo_ads_box"><?=get_owloo_ads('468x60')?></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="owloo_section_fb_local_fans" class="owloo_country_section owloo_country_content_tw_chart">
            <h2>
                Crecimiento de fans en
                <select id="owloo_fb_local_fans_select">
                    <?php
                    foreach ($local_fans_country as $local_fan) { ?>
                    <option value="<?=convert_to_url_string($local_fan['name'])?>"><?=$local_fan['nombre']?></option>
                    <?php } ?>
                </select>
                <span class="owloo_fb_page_select_text">durante los últimos 30 días</span>
                <?php if(false){ ?><span class="owloo_msj_popup" data="<strong>Descripción 2</strong>: Lorem Ipsum is simply dummy text."></span><?php } ?>
            </h2>
            <div class="owloo_country_content">
                <div class="owloo_left owloo_country_table_chart_audience">
                    <div id="owloo_chart_local_fans" class="owloo_chart_audiencie"></div>
                </div>
                <div id="owloo_chart_local_fans_table" class="owloo_right owloo_country_table_2_content">
                </div>
            </div>
        </div>
        <?php } ?>
        <?php } ?>
        <h1 class="owloo_main_title_h1">
            Últimas páginas de Facebook agregadas a Owloo
        </h1>
        <div class="owloo_fb_add_last_page_content">
            <?php require_once('ranking_fb_page/list_last_add_acounts.php'); ?>
        </div>
        <div class="owloo_tw_search_content">
            <?php include(FOLDER_INCLUDE.'owloo_fb_search.php')?>
        </div>
        <div class="owloo_social_plugin" data="facebook-page/<?=convert_to_url_string($page_data['username'])?>">
            <h3>La plataforma en español más completa para el análisis de Facebook</h3>
            <div class="owloo_social_plugin_content">
                <img alt="Cargando" title="Cargando" src="<?=URL_IMAGES?>loader-24x24.gif" />
            </div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
</body>
</html>