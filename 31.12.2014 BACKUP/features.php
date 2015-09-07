<?php
    require_once('owloo_config.php');
    require_once('userMgmt/system/initiater.php');
    set_current_menu('features');
    
    $login_folder = 'notin/';
    include('cache/cache.start.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="content-language" content="es" />    
    <meta http-equiv="pragma" content="nocache" />
    <meta http-equiv="cache-control" content="no-cache, must-revalidate" />
    
    <title>Conoce Owloo</title>
    
    <meta property="og:image" content="<?=URL_IMAGES?>owloo_logo_redes_sociales.png" /> 
    
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            Acerca de Owloo
        </div>
    </div> 
    <div class="owloo_main owloo_main_content">
        <h1 class="owloo_features_title">
            Owloo para todos
        </h1>
        <p class="owloo_features_title_p">
            Owloo es una herramienta de análisis y estadísticas creada para diferentes sectores. Ayuda a las agencias de publicidad y medios de comunicación online y offline, empresas, profesionales y estudiantes que necesitan conocer los mercados, compararlos y optimizar los esfuerzos del marketing. Pueden conocer la penetración de Facebook en un determinado país, las estadísticas de una página o el crecimiento de una cuenta en Twitter, analizando cada detalle, ayudando así a optimizar los esfuerzos del marketing.
        </p>
        <h1 class="owloo_features_title">
            Investigaciones de mercados
        </h1>
        <p class="owloo_features_title_p">
            Analizar un mercado es un proceso fundamental para determinar el éxito de un producto o de una campaña de marketing digital. A través de Facebook y Twitter y los análisis de Owloo, es posible conocer los gustos e intereses de un mercado, enterarse de factores que pueden influenciar la actual estructura de una campaña publicitaria o estrategias comerciales de una empresa, las tendencias y situación demográficas y geográfica del mismo. Gracias a Facebook, Twitter y otras redes sociales que agrupan a más de 1.5 billones de personas en todo el mundo, Owloo te permite conocer precisos datos de un país específico.
        </p>
        <h1 class="owloo_features_title">
            Medir y analizar Facebook y Twitter
        </h1>
        <p class="owloo_features_title_p">
            Owloo analiza los principales datos de cada país, permitiendo conocer la penetración de usuarios, el crecimiento de Facebook durante los últimos meses, saber los intereses y gustos de las personas en un determinado país, las estadísticas de los dispositivos móviles y plataformas y un completo detalle demográfico y geográfico de los usuarios en Facebook. Además, es muy fácil monitorear el crecimiento de una cuenta de Twitter, los hashtags más utilizados, seguidores, menciones y la posibilidad de descargar datos históricos de cualquier cuenta existente en Twitter. Fundamental para cualquier empresa o agencia.
        </p>
        <h1 class="owloo_features_title">
            Facebook por páginas
        </h1>
        <p class="owloo_features_title_p">
            Actualmente Owloo permite analizar las estadísticas de las páginas de Facebook de cualquier empresa, grupo musical, famoso o marca brindando datos actualizados diariamente. Gracias a esta poderosa herramienta podemos medir el crecimiento de fans, ver la popularidad de una página y calcular la participación de los usuarios con la misma. Gracias a los miles de datos que recibimos diariamente y las nuevas páginas que se agregan a Owloo, se puede ver la clasificación de las páginas de Facebook más populares en la mayoría de los países del mundo.
        </p>
        <h1 class="owloo_features_title">
            Genera los reportes y descárgalos
        </h1>
        <p class="owloo_features_title_p">
            Con Owloo puedes descargar gratis los datos completos e históricos del crecimiento de una cuenta de Twitter en formato CSV.
        </p>
        <div class="owloo_social_plugin" data="features">
            <h3>¡Comparte con tus amigos!</h3>
            <div class="owloo_social_plugin_content">
                <img alt="Cargando" title="Cargando" src="<?=URL_IMAGES?>loader-24x24.gif" />
            </div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
</body>
</html>
<?php include('cache/cache.end.php'); ?>