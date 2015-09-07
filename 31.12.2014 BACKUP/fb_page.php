<?php
    require_once('owloo_config.php');
    require_once('userMgmt/system/initiater.php');
    set_current_menu('facebook');
    set_current_page('page_profile');
    
    if(empty($_GET['username'])){
        header('Location: '.URL_ROOT.'facebook-stats/pages/hispanic/');
        exit();
    }
    
    $mensaje_new_fan_page = NULL;
    
/************************************ VARIABLES *************************************/
        
    //Datos generales del país en cuanto a números de usuarios
    $n_a = '<span class="owloo_not_change_audition"><em>n/a</em></span>';
    $crecimiento_fans = array(
                            'dia' => array('value' => $n_a, 'porcentaje' => $n_a),            //Cambio de usuarios en las ultimas 24 Horas
                            'semana' => array('value' => $n_a, 'porcentaje' => $n_a),         //Cambio de usuarios en los ultimos 7 dias
                            'quincena' => array('value' => $n_a, 'porcentaje' => $n_a),       //Cambio de usuarios en los ultimos 15 dias
                            'mes' => array('value' => $n_a, 'porcentaje' => $n_a),            //Cambio de usuarios en los ultimos 30 dias
                            'dos_meses' => array('value' => $n_a, 'porcentaje' => $n_a),      //Cambio de usuarios en los ultimos 60 dias
                            'tres_meses' => array('value' => $n_a, 'porcentaje' => $n_a),     //Cambio de usuarios en los ultimos 90 dias
                        ); 
                                                             
/************************************* FIN - VARIABLES **************************************/
    $_fb_username = str_replace('/', '', $_GET['username']);
    $_first_repeat = false;
    do{
        $need_repeat = false;
        $first_add_page = true;
        $_first_repeat = !$_first_repeat;
        $sql = "SELECT * FROM facebook_page WHERE username LIKE '".mysql_real_escape_string($_fb_username)."';";
        $res = mysql_query($sql) or die(mysql_error());
        if(!$page_data = mysql_fetch_assoc($res)){
            if($first_add_page){
                $first_add_page = false;
                require_once('ranking_fb_page/add_fb_page.php');
                if(empty($mensaje_new_fan_page))
                    $need_repeat = true;
            
            }else{
                $mensaje_new_fan_page = '<div>Lo sentimos, <strong>no hemos podido procesar su petición</strong>.</div>
                                         <div>Favor, intentelo más tarde...</div>';
            }
        }
    }while($_first_repeat && $need_repeat);
    
    if(!empty($page_data) && $page_data['active'] == 0){
        header('Location: '.URL_ROOT.'facebook-stats/pages/hispanic/');
        exit();
    }
    
    if(empty($mensaje_new_fan_page)){
        $local_fans_last_date = get_fb_page_local_fans_last_date($page_data['id_page']);
        $local_fans_country = NULL;
        if(!empty($local_fans_last_date)){
            $sql = "SELECT c.id_country id_country, name, nombre, likes FROM facebook_page_local_fans_country lf JOIN country c ON lf.id_country = c.id_country WHERE id_page = ".mysql_real_escape_string($page_data['id_page'])." AND date = '".mysql_real_escape_string($local_fans_last_date)."' ORDER BY likes DESC, nombre ASC;";
            $local_fans_country = mysql_query($sql) or die(mysql_error());
        }
    
        $fb_page_likes_last_update = get_fb_page_likes_last_update($page_data['id_page']);
        $fb_page_likes_nun_dates = get_fb_page_likes_nun_dates($page_data['id_page']);
        
        if($fb_page_likes_nun_dates > 1)
            $crecimiento_fans['dia'] = getCrecimientoFacebookFansPage($page_data['id_page'], $fb_page_likes_last_update, 1);
        if($fb_page_likes_nun_dates > 7)
            $crecimiento_fans['semana'] = getCrecimientoFacebookFansPage($page_data['id_page'], $fb_page_likes_last_update, 7);
        if($fb_page_likes_nun_dates > 15)
            $crecimiento_fans['quincena'] = getCrecimientoFacebookFansPage($page_data['id_page'], $fb_page_likes_last_update, 15);
        if($fb_page_likes_nun_dates > 30)
            $crecimiento_fans['mes'] = getCrecimientoFacebookFansPage($page_data['id_page'], $fb_page_likes_last_update, 30);
        if($fb_page_likes_nun_dates > 60)
            $crecimiento_fans['dos_meses'] = getCrecimientoFacebookFansPage($page_data['id_page'], $fb_page_likes_last_update, 60);
    }

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
                                <?php $ranking_position = get_fb_page_ranking($page_data['id_page'] ,(!empty($page_data['location'])?$page_data['location']:$page_data['first_local_fans_country']), get_fb_page_local_fans_general_last_date()); ?>
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
        <?php if(!empty($local_fans_country) && mysql_num_rows($local_fans_country) > 0){ ?>
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
                            $count_element = mysql_num_rows($local_fans_country);
                            $total_local_fans_country = 0;
							$temp_3_local_fans = array();
                            while($local_fan = mysql_fetch_assoc($local_fans_country)){ 
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
        <?php
            mysql_data_seek($local_fans_country, 0);
        ?>
        <div id="owloo_section_fb_local_fans" class="owloo_country_section owloo_country_content_tw_chart">
            <h2>
                Crecimiento de fans en
                <select id="owloo_fb_local_fans_select">
                    <?php
                    while ($local_fan = mysql_fetch_assoc($local_fans_country)) { ?>
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