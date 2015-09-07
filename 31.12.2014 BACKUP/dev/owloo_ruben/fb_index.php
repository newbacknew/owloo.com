<?php
    require_once('owloo_config.php');
    set_current_menu('facebook');
    set_current_page('fb_index');
    
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Con Owloo puedes analizar los datos de una página de Facebook o las estadísticas por países y ciudades." />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="content-language" content="es" />    
    <meta http-equiv="pragma" content="nocache" />
    <meta http-equiv="cache-control" content="no-cache, must-revalidate" />
    
    <title>Estadísticas de páginas y países en Facebook</title>
    
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>

</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            Analiza Facebook por páginas o países
        </div>
    </div> 
    <div class="owloo_main owloo_main_content">
    	<div class="owloo_fb_index_title">
        	<h1>Ahora puedes ver las estadísticas de las páginas de Facebook, ver datos de cada país, ciudad y mucho más en un mismo lugar</h1>
        </div>
        <div class="owloo_fb_index">
            <div class="owloo_fb_index_item owloo_first">
                <h2 class="oeloo_title">Analiza los datos de Facebook por país y ciudad</h2>
                <a href="<?=URL_ROOT?>facebook-stats/world-ranking/" class="owloo_btn owloo_btn_blue owloo_btn_big">Facebook por país</a>
            </div>
            <div class="owloo_fb_index_item">
                <h2 class="oeloo_title">Estadísticas de las páginas de Facebook</h2>
                <a href="<?=URL_ROOT?>facebook-stats/pages/hispanic/" class="owloo_btn owloo_btn_blue owloo_btn_big">Ranking de páginas</a>
            </div>
            <div class="owloo_clear"></div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
</body>
</html>