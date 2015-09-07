<?php
    require_once('owloo_config.php');
    set_current_menu('facebook');
    
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="content-language" content="es" />    
    <meta name="description" content="Cantidad de usuarios, crecimiento y datos de Facebook en América Latina, Europa y todos los continentes." />
    <meta name="keywords" content="Facebook, statistics, stats, continentes, estadisticas" />
    <meta http-equiv="pragma" content="nocache" />
    <meta http-equiv="cache-control" content="no-cache, must-revalidate" />
    
    <title>Datos de Facebook por continentes</title>
    
    <meta property="og:image" content="<?=URL_IMAGES?>owloo_logo_redes_sociales.png" /> 
    
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            <span class="owloo_navegator">Ranking de continentes</span>
        </div>
    </div> 
    <div class="owloo_main owloo_main_content">
        <h1 class="owloo_main_title_h1 owloo_align_left">
            Datos de Facebook por continentes
        </h1>
        <div class="owloo_main owloo_qmark_country_content">
            <span class="owloo_msj_popup" data="En este ranking se clasifican los continentes con mayor cantidad de usuarios registrados en Facebook durante los últimos 3 meses. Así mismo podemos analizar el porcentaje del género femenino y masculino registrado en Facebook en un continente específico."></span>
        </div>
        <div class="owloo_ranking_content">
            <div class="owloo_ranking_tab">
                <ul>
                    <li><a class="owloo_first" href="<?=URL_ROOT?>facebook-stats/world-ranking/">Ranking mundial</a></li>
                    <li><a href="<?=URL_ROOT?>facebook-stats/hispanic-ranking/">Ranking hispano</a></li>
                    <li><a href="<?=URL_ROOT?>facebook-stats/cities/">Ranking de ciudades</a></li>
                    <li><span class="owloo_active owloo_last">Continentes</span></li>
                </ul>
                <div class="owloo_ads_content_160x600">
                    <div class="owloo_ads_box owloo_ads_160x600"><?=get_owloo_ads('160x600')?></div>
                </div>
            </div>
            <div class="owloo_ranking">
                <?php include('ranking_continent/list_continents.php'); ?>
            </div>
        </div>
        <div class="owloo_social_plugin" data="facebook-continents-ranking">
            <h3>¡Comparte estos datos en las redes sociales!</h3>
            <div class="owloo_social_plugin_content">
                <img alt="Cargando" title="Cargando" src="<?=URL_IMAGES?>loader-24x24.gif" />
            </div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
</body>
</html>