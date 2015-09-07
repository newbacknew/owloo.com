<?php
    require_once('owloo_config.php');
    require_once('userMgmt/system/initiater.php');
    set_current_menu('facebook');
    set_current_page('fb_ranking');
    
    if(!isset($_GET['ranking']) || !($_GET['ranking'] == 'world' || $_GET['ranking'] == 'hispanic')){
        header('Location: '.URL_ROOT);
        exit();
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="<?=($_GET['ranking']=='hispanic'?'Ranking hispano de las páginas más populares en Facebook':'Ranking de las páginas más populares en Facebook de todo el mundo')?>. Con Owloo mide la cantidad de fans y el PTA de una página de Facebook." />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="content-language" content="es" />    
    <meta http-equiv="pragma" content="nocache" />
    <meta http-equiv="cache-control" content="no-cache, must-revalidate" />
    
    <title><?=($_GET['ranking']=='hispanic'?'Ranking de las páginas de Facebook en español - Owloo':'Ranking mundial de las mejores páginas de Facebook - Owloo')?></title>
    
    <meta property="og:image" content="<?=URL_IMAGES?>owloo_logo_redes_sociales.png" /> 
       
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
    
</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            Páginas de Facebook más populares
        </div>
    </div> 
    <div class="owloo_main owloo_main_content">
    <div class="owloo_fb_index">
            <h3>Analiza gratis una página, agrégala en el campo de abajo</h3>
            <?php include(FOLDER_INCLUDE.'owloo_fb_search.php'); ?>
        </div>
        <h1 class="owloo_main_title_h1 owloo_align_left">
            Ranking <?=($_GET['ranking']=='hispanic'?'hispano':'mundial')?> de páginas más populares en Facebook
        </h1>
        <div class="owloo_main owloo_qmark_country_content">
            <span class="owloo_msj_popup" data="<p>El ranking <?=($_GET['ranking']=='hispanic'?'hispano':'mundial')?> de las páginas más populares en Facebook se genera midiendo la cantidad de fans totales (en todo el mundo) que la página tiene.</p>
<p>Además se mide el crecimiento semanal de fans y su PTA (People Talking About).</p>"></span>
        </div>
        <div class="owloo_ranking_content">
            <div class="owloo_ranking_tab">
                <ul>
                    <?php if($_GET['ranking']=='hispanic'){ ?>
                    <li><span class="owloo_first owloo_active">Ranking hispano</span></li>
                    <li><a class="owloo_last" href="<?=URL_ROOT?>facebook-stats/pages/world/">Ranking mundial</a></li>
                    <?php }else{ ?>
                    <li><a class="owloo_first" href="<?=URL_ROOT?>facebook-stats/pages/hispanic/">Ranking hispano</a></li>
                    <li><span class="owloo_last owloo_active">Ranking mundial</span></li>
                    <?php } ?>
                </ul>
                <div class="owloo_ads_content_160x600">
                    <div class="owloo_ads_box owloo_ads_160x600"><?=get_owloo_ads('160x600')?></div>
                </div>
            </div>
            <div class="owloo_ranking">
                <?php include('ranking_fb_page/list_page.php'); ?>
            </div>
        </div>
        <div class="owloo_social_plugin" data="ranking-pages">
            <h3>La plataforma en español más completa para el análisis de Facebook</h3>
            <div class="owloo_social_plugin_content">
                <img alt="Cargando" title="Cargando" src="<?=URL_IMAGES?>loader-24x24.gif" />
            </div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
    <script>if(location.hash){var page = location.hash.replace('#stats-', '');next(page, <?=($_GET['ranking']=='hispanic'?get_fb_page_total_rows_count(' hispanic = 1 AND '):get_fb_page_total_rows_count())?>, <?=PAGER_PP?>);load_page(page, '<?=($_GET['ranking']=='hispanic'?'hispanic':'global')?>_page', true, '');}</script>
</body>
</html>