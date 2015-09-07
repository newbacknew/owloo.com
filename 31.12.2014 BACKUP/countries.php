<?php
    require_once('owloo_config.php');
    require_once('userMgmt/system/initiater.php');
    set_current_menu('facebook');
    
    $login_folder = 'notin/';
    include('cache/cache.start.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index,follow" />
	<meta http-equiv="content-language" content="es" />    
    <meta name="description" content="Ranking de Facebook por país. Datos geográficos, demográficos de las redes sociales. owloo, la herramienta en español más completa para el análisis de Facebook." />
    <meta name="keywords" content="Facebook, statistics, stats, owloo, owlo, ranking, estudio mercado, medir, análisis, crecimiento, analytics" />
    <meta http-equiv="pragma" content="nocache" />
	<meta http-equiv="cache-control" content="no-cache, must-revalidate" />
    
    <title>Datos y estadísticas de Facebook - owloo</title>
    
    <meta property="og:image" content="<?=URL_IMAGES?>owloo_logo_redes_sociales.png" /> 
    
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            <span class="owloo_navegator"><?=(isset($_GET['hispanic'])?'Ranking hispano':'Ranking mundial')?></span>
        </div>
    </div> 
    <div class="owloo_main owloo_main_content">
        <h1 class="owloo_main_title_h1 owloo_align_left">
            Datos de Facebook por país
        </h1>
        <div class="owloo_main owloo_qmark_country_content">
            <?php if(isset($_GET['hispanic'])){ ?>
                <span class="owloo_msj_popup" data="El ranking se genera midiendo, por cada país de habla hispana, la cantidad de usuarios que aumentaron o disminuyeron en relación a los últimos 3 meses. Así mismo podemos analizar el porcentaje del género femenino y masculino registrado en Facebook en un determinado país hispano. Puedes acceder a las estadísticas completas de cada país dando a través del enlace."></span>
            <?php }else{ ?>
            <span class="owloo_msj_popup" data="El ranking se genera midiendo, por cada país, la cantidad de usuarios que aumentaron o disminuyeron en relación a los últimos 3 meses. Así mismo podemos analizar el porcentaje del género femenino y masculino registrado en Facebook en un determinado país. Puedes acceder a las estadísticas completas de cada país a través del enlace."></span>
            <?php } ?>
        </div>
        <div class="owloo_ranking_content">
            <div class="owloo_ranking_tab">
                <ul>
                    <?php if(isset($_GET['hispanic'])){ ?>
                    <li><a class="owloo_first" href="<?=URL_ROOT?>facebook-stats/world-ranking/">Ranking mundial</a></li>
                    <li><span class="owloo_active">Ranking hispano</span></li>
                    <?php }else{ ?>
                    <li><span class="owloo_active owloo_first">Ranking mundial</span></li>
                    <li><a href="<?=URL_ROOT?>facebook-stats/hispanic-ranking/">Ranking hispano</a></li>
                    <?php } ?>
                    <li><a href="<?=URL_ROOT?>facebook-stats/cities/">Ranking de ciudades</a></li>
                    <li><a class="owloo_last" href="<?=URL_ROOT?>facebook-stats/continents/">Continentes</a></li>
                </ul>
                <div class="owloo_ads_content_160x600">
                    <div class="owloo_ads_box owloo_ads_160x600"><?=get_owloo_ads('160x600')?></div>
                </div>
            </div>
            <div class="owloo_ranking">
                <?php include('ranking_country/list_country.php'); ?>
            </div>
        </div>
        <div class="owloo_social_plugin" data="facebook-<?=(isset($_GET['hispanic'])?'hispanic':'world')?>-ranking">
            <h3>La plataforma en español más completa para el análisis de las redes sociales</h3>
            <div class="owloo_social_plugin_content">
                <img alt="Cargando" title="Cargando" src="<?=URL_IMAGES?>loader-24x24.gif" />
            </div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
    <script>if(location.hash){var page = location.hash.replace('#stats-', '');next(page, <?=COUNTRY_TOTAL_COUNT?>, <?=PAGER_PP?>);load_page(page, '<?=(isset($_GET['hispanic'])?'hispanic':'global')?>_country', true, '');}</script>
</body>
</html>
<?php include('cache/cache.end.php'); ?>