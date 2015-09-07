<?php
    require_once('owloo_config.php');
    set_current_menu('facebook');
    set_current_page('fb_ranking_country');
    
    $country_data = get_country_data_from_code($_GET['country_code']);

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Ranking de las mejores páginas de Facebook en <?=$country_data['nombre']?>. Estadísticas actualizadas de los fan pages más populares en <?=$country_data['nombre']?>." />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="content-language" content="es" />    
    <meta http-equiv="pragma" content="nocache" />
    <meta http-equiv="cache-control" content="no-cache, must-revalidate" />
    
    <title>Top páginas de Facebook en <?=$country_data['nombre']?> - Owloo</title>
    
    <meta property="og:image" content="<?=URL_IMAGES?>owloo_logo_redes_sociales.png" /> 
       
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
    
</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            <a class="owloo_navegator" href="<?=URL_ROOT?>facebook-stats/pages/hispanic/">Páginas de Facebook más populares</a>
            <span class="owloo_separator">&gt;</span>
            <span class="owloo_navegator"><?=$country_data['nombre']?></span>
        </div>
    </div> 
    <div class="owloo_main owloo_main_content">
        <h1 class="owloo_main_title_h1 owloo_align_left">
            Páginas de Facebook más populares en <?=$country_data['nombre']?>
        </h1>
        <div class="owloo_main owloo_qmark_country_content">
            <span class="owloo_msj_popup" data="<p>El ranking de las páginas de Facebook más populares en <?=$country_data['nombre']?> se genera midiendo la cantidad de fans locales que la página dispone en el país.</p>
<p>Además se mide el crecimiento semanal de fans, la cantidad de fans totales
(en todo el mundo) que la página tiene y su PTA (People Talking About).</p>"></span>
        </div>
        <div class="owloo_ranking_content">
            <div class="owloo_ranking_tab">
                <ul>
                    <li><span class="owloo_first owloo_active"><?=$country_data['nombre']?></span></li>
                </ul>
            </div>
            <div class="owloo_ranking">
                <?php include('ranking_fb_page/list_page_country.php'); ?>
            </div>
        </div>
        <h1 class="owloo_main_title_h1 owloo_align_left owloo_fb_engagement_title">
            Páginas de Facebook en <?=$country_data['nombre']?> con el PTA más alto
        </h1>
        <?php if(false){ ?>
        <div class="owloo_main owloo_qmark_country_content">
            <span class="owloo_msj_popup" data="Descripción..."></span>
        </div>
        <?php } ?>
        <div class="owloo_ranking_content">
            <div class="owloo_ranking_tab">
                <ul>
                    <li><span class="owloo_first owloo_active"><?=$country_data['nombre']?></span></li>
                </ul>
            </div>
            <div class="owloo_ranking">
                <?php include('ranking_fb_page/list_page_country_engagement.php'); ?>
            </div>
        </div>
        <h1 class="owloo_main_title_h1 owloo_align_center owloo_fb_engagement_title">
            Datos de <?=$country_data['nombre']?> en Facebook
        </h1>
        <div class="owloo_fb_fan_page_country_description">
            <?php $country_data_num = getCountryData($country_data['code']); ?>
            <?=$country_data['nombre']?> cuenta con <?=owloo_number_format($country_data_num['total_user'])?> usuarios, de los cuáles el <?=owlooFormatPorcent($country_data_num['total_female'], $country_data_num['total_user'])?>% son mujeres y <?=owlooFormatPorcent($country_data_num['total_male'], $country_data_num['total_user'])?> son hombres.<br/>
            Actualmente se encuentra en la posición <?=getRanking($country_data['id_country'])?> en el ranking mundial de Facebook.
            <hr>
            <a href="<?=URL_ROOT?>facebook-stats/<?=convert_to_url_string($country_data['name'])?>/" title="Estadísticas de Facebook en <?=$country_data['nombre']?>" target="_blank">Ver datos completos de <?=$country_data['nombre']?> en Facebook &gt;</a>
        </div>
        <h1 class="owloo_main_title_h1">
            Últimas páginas de Facebook agregadas a Owloo
        </h1>
        <div class="owloo_fb_add_last_page_content">
            <?php require_once('ranking_fb_page/list_last_add_acounts.php'); ?>
        </div>
        <div class="owloo_tw_search_content">
            <?php include(FOLDER_INCLUDE.'owloo_fb_search.php')?>
        </div>
        <div class="owloo_social_plugin" data="facebook-page-country/<?=convert_to_url_string($country_data['name'])?>">
            <h3>La plataforma en español más completa para el análisis de Facebook</h3>
            <div class="owloo_social_plugin_content">
                <img alt="Cargando" title="Cargando" src="<?=URL_IMAGES?>loader-24x24.gif" />
            </div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
    <script>if(location.hash){var page = location.hash.replace('#stats-', '');next(page, <?=$page_total_count?>, <?=PAGER_PP?>);load_page(page, 'country_page', true, '<?=convert_to_url_string($country_data['name'])?>');}</script>
</body>
</html>