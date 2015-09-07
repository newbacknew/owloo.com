<?php 
    require_once('owloo_config.php');
    set_current_menu('index');
    set_current_page('index');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index,follow" />
	<meta http-equiv="content-language" content="es" />    
    <meta name="description" content="Owloo ofrece el monitoreo y seguimiento gratis de Facebook y Twitter, permitiendo el análisis y la comparación de las estadísticas e indicadores de los medios sociales." />
    <meta name="keywords" content="Facebook, statistics, stats, owloo, owlo, ranking, estudio mercado, medir, análisis, crecimiento, analytics" />
    <meta http-equiv="pragma" content="nocache" />
	<meta http-equiv="cache-control" content="no-cache, must-revalidate" />
    
    <title>Estadísticas de Facebook y Twitter</title>
    
    <meta property="og:image" content="<?=URL_IMAGES?>owloo_logo_redes_sociales.png" /> 
    
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_index_zone_1">
        <div class="owloo_main">
            <h1>Analiza Facebook y Twitter. Ahora más potente que nunca. </h1>
        </div>
    </div>
    <div class="owloo_index_zone_2">
        <div class="owloo_main">
            <h3>Más de <span id="owloo_user_count"><?php echo 9999; ?></span> profesionales, medios y agencias registradas</h3>
        </div>
    </div>
    <div class="owloo_create_account">
        <div class="owloo_main">
            <div class="owloo_text">
                Crea una cuenta gratuita<br/>
                Descrube todas las funcionalidades de Owloo
            </div>
            <div class="owloo_button">
                <a class="owloo_btn owloo_btn_orange" href="<?=URL_ROOT_HTTPS?>userMgmt/signup.php">Regístrate hoy - GRATIS</a>
            </div>
        </div>
    </div>
    <div class="owloo_main owloo_main_content">
        <?php require_once('ranking_country/statistics_change_user.php'); ?>
        <h1 class="owloo_main_title_h1 owloo_index">
            Tasa de crecimiento de Facebook por país
            <span class="owloo_msj_popup" data="Se mide el país que obtuvo un mayor crecimiento durante el último mes. Se entiende como crecimiento el registro de cuentas nuevas en Facebook."></span>
        </h1>
        <div class="owloo_index_charts">
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 30 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$g_mayor_crec_mes['pais']?>
                </div>
                <?php if($g_mayor_crec_mes['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($g_mayor_crec_mes['cambio_number'], 112000000)?>%</div>
                <div class="owloo_index_charts_number <?=($g_mayor_crec_mes['cambio']!=0?'owloo_index_charts_up':'')?>"><?=$g_mayor_crec_mes['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 90 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$g_mayor_crec_3_meses['pais']?>
                </div>
                <?php if($g_mayor_crec_3_meses['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($g_mayor_crec_3_meses['cambio_number'], 112000000)?>%</div>
                <div class="owloo_index_charts_number <?=($g_mayor_crec_3_meses['cambio']!=0?'owloo_index_charts_up':'')?>"><?=$g_mayor_crec_3_meses['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 30 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$g_mayor_decrec_mes['pais']?>
                </div>
                <?php if($g_mayor_decrec_mes['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($g_mayor_decrec_mes['cambio_number'], 1040000)?>%</div>
                <div class="owloo_index_charts_number <?=($g_mayor_decrec_mes['cambio']!=0?'owloo_index_charts_down':'')?>"><?=$g_mayor_decrec_mes['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 90 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$g_mayor_decrec_3_meses['pais']?>
                </div>
                <?php if($g_mayor_decrec_3_meses['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($g_mayor_decrec_3_meses['cambio_number'], 1240000)?>%</div>
                <div class="owloo_index_charts_number <?=($g_mayor_decrec_3_meses['cambio']!=0?'owloo_index_charts_down':'')?>"><?=$g_mayor_decrec_3_meses['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
        </div>
        <h1 class="owloo_main_title_h1 owloo_index">
            Tasa de crecimiento de Facebook en Hispanoamérica por país
            <span class="owloo_msj_popup" data="Se mide el país hispano que obtuvo un mayor crecimiento durante el último mes. Se entiende como crecimiento el registro de cuentas nuevas en Facebook."></span>
        </h1>
        <div class="owloo_index_charts">
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 30 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$hh_mayor_crec_mes['pais']?>
                </div>
                <?php if($hh_mayor_crec_mes['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($hh_mayor_crec_mes['cambio_number'], 11600000)?>%</div>
                <div class="owloo_index_charts_number <?=($hh_mayor_crec_mes['cambio']!=0?'owloo_index_charts_up':'')?>"><?=$hh_mayor_crec_mes['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 90 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$hh_mayor_crec_3_meses['pais']?>
                </div>
                <?php if($hh_mayor_crec_3_meses['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($hh_mayor_crec_3_meses['cambio_number'], 56000000)?>%</div>
                <div class="owloo_index_charts_number <?=($hh_mayor_crec_3_meses['cambio']!=0?'owloo_index_charts_up':'')?>"><?=$hh_mayor_crec_3_meses['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 30 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$hh_mayor_decrec_mes['pais']?>
                </div>
                <?php if($hh_mayor_decrec_mes['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($hh_mayor_decrec_mes['cambio_number'], 200000000)?>%</div>
                <div class="owloo_index_charts_number <?=($hh_mayor_decrec_mes['cambio']!=0?'owloo_index_charts_down':'')?>"><?=$hh_mayor_decrec_mes['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 90 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$hh_mayor_decrec_3_meses['pais']?>
                </div>
                <?php if($hh_mayor_decrec_3_meses['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($hh_mayor_decrec_3_meses['cambio_number'], 200000000)?>%</div>
                <div class="owloo_index_charts_number <?=($hh_mayor_decrec_3_meses['cambio']!=0?'owloo_index_charts_down':'')?>"><?=$hh_mayor_decrec_3_meses['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
        </div>
        <?php require_once('ranking_city/statistics_change_user.php'); ?>
        <h1 class="owloo_main_title_h1 owloo_index">
            Tasa de crecimiento de Facebook en Hispanoamérica por ciudad
            <span class="owloo_msj_popup" data="Se mide la ciudad hispana que obtuvo un mayor crecimiento durante el último mes. Se entiende como crecimiento el registro de cuentas nuevas en Facebook."></span>
        </h1>
        <div class="owloo_index_charts">
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 30 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$hh_city_mayor_crec_mes['ciudad']?>
                </div>
                <?php if($hh_city_mayor_crec_mes['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($hh_city_mayor_crec_mes['cambio_number'], $hh_city_mayor_crec_mes['total_user'])?>%</div>
                <div class="owloo_index_charts_number <?=($hh_city_mayor_crec_mes['cambio']!=0?'owloo_index_charts_up':'')?>"><?=$hh_city_mayor_crec_mes['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 90 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$hh_city_mayor_crec_3_meses['ciudad']?>
                </div>
                <?php if($hh_city_mayor_crec_3_meses['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($hh_city_mayor_crec_3_meses['cambio_number'], $hh_city_mayor_crec_3_meses['total_user'])?>%</div>
                <div class="owloo_index_charts_number <?=($hh_city_mayor_crec_3_meses['cambio']!=0?'owloo_index_charts_up':'')?>"><?=$hh_city_mayor_crec_3_meses['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 30 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$hh_city_mayor_decrec_mes['ciudad']?>
                </div>
                <?php if($hh_city_mayor_decrec_mes['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($hh_city_mayor_decrec_mes['cambio_number'], $hh_city_mayor_decrec_mes['total_user'])?>%</div>
                <div class="owloo_index_charts_number <?=($hh_city_mayor_decrec_mes['cambio']!=0?'owloo_index_charts_down':'')?>"><?=$hh_city_mayor_decrec_mes['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
            <div class="owloo_index_charts_item">
                <h3 class="owloo_index_charts_title">Últimos 90 días</h3>
                <div class="owloo_index_charts_country">
                    <?=$hh_city_mayor_decrec_3_meses['ciudad']?>
                </div>
                <?php if($hh_city_mayor_decrec_3_meses['cambio_number'] > 0){ ?>
                <div class="owloo_index_charts_porcent"><?=owlooFormatPorcent($hh_city_mayor_decrec_3_meses['cambio_number'], $hh_city_mayor_decrec_3_meses['total_user'])?>%</div>
                <div class="owloo_index_charts_number <?=($hh_city_mayor_decrec_3_meses['cambio']!=0?'owloo_index_charts_down':'')?>"><?=$hh_city_mayor_decrec_3_meses['cambio']?></div>
                <?php }else{ ?>
                    <div class="owloo_index_charts_number owloo_index_charts_no_change">0</div>
                <?php } ?>
            </div>
        </div>
        
        <div class="owloo_index_ads_728x90">
            <div class="owloo_ads_728x90 owloo_ads_box"><?=get_owloo_ads('728x90')?></div>
        </div>
        <h1 class="owloo_main_title_h1">
            Últimas cuentas de Twitter agregadas en Owloo
        </h1>
        <div class="owloo_wrap_tw_last_add_content">
        </div>
        <div class="owloo_tw_search_content">
            <?php include(FOLDER_INCLUDE.'owloo_tw_search.php'); ?>
        </div>
        <div class="owloo_social_plugin" data="index">
            <h3>La plataforma en español más completa para el análisis de las redes sociales</h3>
            <div class="owloo_social_plugin_content">
                <img alt="Cargando" title="Cargando" src="<?=URL_IMAGES?>loader-24x24.gif" />
            </div>
        </div>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
</body>
</html>