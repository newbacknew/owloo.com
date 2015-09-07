<?php 
    require_once('owloo_config.php');
    require_once('userMgmt/system/initiater.php');
    set_current_menu('facebook');
    
    $login_folder = 'notin/';
    if($site->loggedin){
        $login_folder = 'in/';
    }
    include("cache/cache.start.php");
    
    $countryCode = "";  
    $countryName = "";  
    $countryNameEn = "";    
    $countryCodeName = $_GET['country'];
    if(empty($_GET['country'])){
        header('location: '.URL_ROOT.'facebook-stats/cities/');
        exit(); 
    }
    else{ //Comprobamos que el nombre pertenezca a un país
        if($countryCodeName == 'st-lucia'){//Excepción para el país Santa Lucía
            $countryCodeName = 'St. Lucia';
        }
        $sql =  "SELECT code, nombre, name
                    FROM country 
                    WHERE name LIKE '".mysql_real_escape_string(str_ireplace('-', ' ',  strtolower($countryCodeName)))."';
                 ";
        $que = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            $countryCode = $fila['code'];
            $countryName = $fila['nombre'];
            $countryNameEn = $fila['name'];
        }
        else{
            header('location: '.URL_ROOT.'facebook-stats/cities/');
            exit(); 
        }
    }
    
    include('ranking_city/list_city_country_part1.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="content-language" content="es" />        
    <meta name="description" content="Análisis estadístico de las ciudades de <?=$countryName?>. Usuarios, géneros y crecimiento de Facebook en <?php for($i=0, $limit = count($cityMayorAudiencia); $i < $limit; $i++){ if($i != 0){ if($i == $limit-1) echo ' y '; else echo ', ';} echo $cityMayorAudiencia[$i]['nombre']; } ?>." />
    <meta name="keywords" content="Facebook, statistics, stats, <?=$countryName?>, estadisticas, ciudades" />
    <meta http-equiv="pragma" content="nocache" />
    <meta http-equiv="cache-control" content="no-cache, must-revalidate" />
    
    <title>Datos de Facebook por ciudad en <?=$countryName?></title>
    
    <meta property="og:image" content="<?=URL_IMAGES?>owloo_logo_redes_sociales.png" /> 
    
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            <a class="owloo_navegator" href="<?=URL_ROOT?>">Ranking de Facebook</a>
            <span class="owloo_separator">></span>
            <span class="owloo_navegator">Ciudades de <?=$countryName?></span>
        </div>
    </div> 
    <div class="owloo_main owloo_main_content">
        <h1 class="owloo_main_title_h1 owloo_align_left">
            Ciudades de <?=$countryName?> en Facebook
        </h1>
        <div class="owloo_main owloo_qmark_country_content">
            <span class="owloo_msj_popup" data="El ranking se genera midiendo la cantidad de usuarios que aumentaron o disminuyeron en relación a los últimos 3 meses de cada ciudad de <?=$countryName?>. Así mismo podemos analizar el porcentaje del género femenino y masculino registrado en Facebook en una determinada ciudad. Puedes acceder a las estadísticas de otras ciudades de un determinado país a través del enlace."></span>
        </div>
        <div class="owloo_ranking_content">
            <div class="owloo_ranking_tab">
                <ul>
                   <li><a class="owloo_first" href="<?=URL_ROOT?>facebook-stats/world-ranking/">Ranking mundial</a></li>
                    <li><a href="<?=URL_ROOT?>facebook-stats/hispanic-ranking/">Ranking hispano</a></li>
                    <li><a href="<?=URL_ROOT?>facebook-stats/cities/">Ranking de ciudades</a></li>
                    <li><span class="owloo_active_city owloo_active"><i>&gt;</i><?=$countryName?></span></li>
                    <li><a class="owloo_last" href="<?=URL_ROOT?>facebook-stats/continents/">Continentes</a></li>
                </ul>
                <div class="owloo_ads_content_160x600">
                    <div class="owloo_ads_box owloo_ads_160x600"><?=get_owloo_ads('160x600')?></div>
                </div>
            </div>
            <div class="owloo_ranking<?=(!$site->loggedin?' owloo_hide_data':'')?>">
                <?php if(!$site->loggedin){ include(FOLDER_INCLUDE.'owloo_require_login.php'); } ?>
                <?php include('ranking_city/list_city_country_part2.php'); ?>
            </div>
        </div>
        <div class="owloo_social_plugin" data="facebook-country-cities-ranking/<?=convert_to_url_string($countryNameEn)?>">
            <h3>¡Comparte los datos de <?=$countryName?> en las redes sociales!</h3>
            <div class="owloo_social_plugin_content">
                <img alt="Cargando" title="Cargando" src="<?=URL_IMAGES?>loader-24x24.gif" />
            </div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
    <script>if(location.hash){var page = location.hash.replace('#stats-', '');next(page, <?=COUNTRY_TOTAL_COUNT?>, <?=PAGER_PP?>);load_page(page, 'country_city', true, '<?=$countryCodeName?>');}</script>
</body>
</html>
<?php include('cache/cache.end.php'); ?>