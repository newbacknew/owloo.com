<?php
    require_once('owloo_config.php');
    require_once('userMgmt/system/initiater.php');
    
    $user_profile = $site->get_profile();;
    if(!empty($user_profile))
        set_current_user_id($user_profile['user_id']);
    
    require_once("ranking_twitter/config/config.php");
    set_current_menu('twitter');
    set_current_page('twitter');
    
    if(isset($_GET['page']) && $_GET['page'] == 'userpageadd'){
        header('Location: '.URL_ROOT.'twitter-stats/userpage/'.$_POST["txttwittername"]);
        exit();
    }
    
    $meta_title = 'Analizar Twitter con Owloo';
    $meta_descripction = 'Analiza gratis una cuenta de Twitter y descarga las estadísticas de las cuentas más populares';
    $title = 'Cuentas de Twitter más influyentes';
    if(isset($_GET['page'])){
        if($_GET['page'] == 'mostmentionedaccounts'){ 
            $meta_title = 'Perfiles más mencionados';
            $meta_descripction = 'Los perfiles de Twitter más mencionados según owloo';
            $title = 'Perfiles más mencionados';
        }
        elseif($_GET['page'] == 'hispanic'){
            $meta_title = 'Twitter ranking en español';
            $title = 'Cuentas de Twitter más influyentes en español';
        }
    }
    $fb_url = URL_ROOT.'twitter-stats/';
    $fb_title = 'Analizar Twitter con Owloo';
    $fb_desc = 'Analiza gratis una cuenta de Twitter y descarga las estadísticas de las cuentas más populares.';
    
    $tw_screen_name = NULL;
    $tw_user_link = NULL;
    $tw_user_original_link = NULL;
    $tw_user_name = NULL;
    if(isset($_GET['page']) && $_GET['page'] == 'userpage'){
        $tw_screen_name = (isset($_REQUEST["txttwittername"])?$_REQUEST["txttwittername"]:$_GET["twittername"]);
        $tw_user_name = get_user_name($tw_screen_name);
        
        if($tw_user_name){
            $meta_title = 'Estadísticas de '.$tw_user_name.' en Twitter';
            $meta_descripction = 'Analiza la cuenta de '.$tw_user_name.' en Twitter. Estadísticas de seguidores, hashtags y crecimiento diario de '.$tw_user_name.'.';
            
            $tw_user_original_link = URL_ROOT."twitter-stats/userpage/".$tw_screen_name;
            $fb_url = $tw_user_original_link;
            
            //$content=@file_get_contents("http://snix.co/api/?url=".$tw_user_original_link."&api=ZIqTwfUjRLfw");
            
            $content='';
            
            
            $url=json_decode($content,TRUE);//Decodes json into an array
            
            if(!$url["error"]){  // If there is no error
                $tw_user_link =  $url["short"]; //Outputs the short url
            }
            
            $fb_desc = 'Analiza la cuenta de '.$tw_user_name.' en Twitter. Estadísticas de seguidores, hashtags y crecimiento diario de '.$tw_user_name.'.';
            
        }
    }
?>
<!DOCTYPE HTML>
<html<?php if($tw_user_name){ ?> itemscope itemtype="http://schema.org/Other"<?php } ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="content-language" content="es" />    
    <meta name="description" content="<?=$meta_descripction?>." />
    <?php if($meta_title=='Analizar y descargar estadísticas de Twitter - Owloo'){ ?><meta name="keywords" content="ranking,twitter,stats,datos,estadisticas,analytics,tweets" /><?php } ?>
    <meta http-equiv="pragma" content="nocache" />
    <meta http-equiv="cache-control" content="no-cache, must-revalidate" />
    <?php if($tw_user_name){ ?><meta itemprop="name" content="<?=$meta_title?>"><?php } ?>
    
    <title><?=$meta_title?></title>
    
    <meta property="og:image" content="<?=URL_IMAGES?>owloo_logo_redes_sociales.png" /> 
    
    <?php require_once(FOLDER_INCLUDE.'include_in_header.php'); ?>
</head>

<body>
    <?php require_once(FOLDER_INCLUDE.'header.php'); ?>
    <div class="owloo_product_title">
        <div class="owloo_main">
            <a class="owloo_navegator" href="<?=URL_ROOT?>twitter-stats/">Ranking de Twitter</a>
            <span class="owloo_separator">></span>
            <span class="owloo_navegator"><?=((isset($_GET['page'])&&$_GET['page']=='mostmentionedaccounts')?'Perfiles más mencionados':((isset($_GET['page'])&&$_GET['page']=='hispanic')?'Ranking español':(isset($_GET['page'])&&$_GET['page']=='userpage'?$tw_user_name:'Ranking mundial')))?></span>
        </div>
    </div> 
    <div class="owloo_main owloo_main_content">
        <?php if(!empty($tw_user_name)){ ?>
        <div class="owloo_tools_content">
            <div class="owloo_tools">
                <span class="owloo_msj_popup" data="<p>Presionando el botón puedes monitorear uno o varios perfiles de Twitter y las estadísticas de un país especifico.</p><p>Cada vez que accedas a Mi cuenta > Monitoreo, podrás ver rápidamente estas páginas, si quieres dejar de monitorearlas presiona nuevamente el botón.</p>"></span>
                <?php
                    $tw_user_id = get_user_id($tw_screen_name);
                    reconnect_db('owloo_owloo');
                    $is_current_favorite = is_current_favorite('twitter', $tw_user_id);
                    reconnect_db('owloo_twitter');
                ?>
                <span class="owloo_text">Monitorea esta página</span>
                <div id="owloo_favorite" class="owloo_favorite_icon<?=($is_current_favorite?' owloo_active':'')?>" type="twitter" element="<?=$tw_user_id?>"></div>
            </div>
        </div>
        <?php } ?>
        <div class="owloo_tw_title_content">
            <h1 class="owloo_main_title_h1 owloo_align_left">
            <?php if($tw_user_name){ ?>
                Datos y estadísticas de <?=$tw_user_name?> en Twitter
            <?php }elseif(isset($_GET['page']) && $_GET['page'] == 'userpage'){ ?>
                Datos y estadísticas de Twitter
            <?php }else{ ?>
                <?=$title?>
            <?php } ?>
            </h1>
        </div>
        <?php
        if(isset($_GET['page']) && $_GET['page'] == 'userpage'){
            set_current_page('twitter_profile');
            include('ranking_twitter/twitter_user_page.php');
        }else{
        ?>
        <?php if(!isset($_GET['page']) || $_GET['page'] == 'hispanic'){ ?>
         <div class="owloo_main owloo_qmark_country_content">
            <?php if(isset($_GET['page']) && $_GET['page'] == 'hispanic'){ ?>
            <span class="owloo_msj_popup" data="<p>El ranking hispano se genera midiendo la cantidad de seguidores que aumentaron o disminuyeron en relación al último mes entre todos los perfiles en español de Twitter presentes en Owloo.</p><p>Si no visualiza un determinado perfil, puedes añadirlo a través de campo presente al final de la página.</p><p>Así mismo podemos analizar cuántos usuarios están siguiendo o dejaron de seguir al perfil. Puedes acceder a las estadísticas completas del perfil a través del enlace.</p>"></span>
            <?php }else{ ?>
                <span class="owloo_msj_popup" data="<p>El ranking se genera midiendo la cantidad de seguidores que aumentaron o disminuyeron en relación al último mes entre todos los perfiles de Twitter presentes en Owloo.</p><p>Si no visualiza un determinado perfil, puedes añadirlo a través de campo presente al final de la página.</p><p>Así mismo podemos analizar cuántos usuarios están siguiendo o dejaron de seguir al perfil. Puedes acceder a las estadísticas completas del perfil a través del enlace.</p>"></span>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="owloo_ranking_content">
            <div class="owloo_ranking_tab">
                <ul>
                    <?php $_pager_title_aux = 'global'; if(isset($_GET['page'])&&$_GET['page']=='hispanic'){ ?>
                    <li><a class="owloo_first" href="<?=URL_ROOT?>twitter-stats/">Ranking mundial</a></li>
                    <li><span class="owloo_active">Ranking español</span></li>
                    <?php if(false){ ?><li><a class="owloo_last" href="<?=URL_ROOT?>twitter-stats/mostmentionedaccounts/">Perfiles más mencionados</a></li><?php } ?>
                    <?php }elseif(isset($_GET['page'])&&$_GET['page'] == 'mostmentionedaccounts'){ $_pager_title_aux = 'mentions'; ?>
                    <li><a class="owloo_first" href="<?=URL_ROOT?>twitter-stats/">Ranking mundial</a></li>
                    <li><a href="<?=URL_ROOT?>twitter-stats/hispanic/">Ranking español</a></li>
                    <?php if(false){ ?><li><span class="owloo_last owloo_active">Perfiles más mencionados</span></li><?php } ?>
                    <?php }else{ ?>
                    <li><span class="owloo_active">Ranking mundial</span></li>
                    <li><a href="<?=URL_ROOT?>twitter-stats/hispanic/">Ranking español</a></li>
                    <?php if(false){ ?><li><a class="owloo_last" href="<?=URL_ROOT?>twitter-stats/mostmentionedaccounts/">Perfiles más mencionados</a></li><?php } ?>
                    <?php } ?>
                </ul>
                <div class="owloo_ads_content_160x600">
                    <div class="owloo_ads_box owloo_ads_160x600"><?=get_owloo_ads('160x600')?></div>
                </div>
            </div>
            <div class="owloo_ranking">
                <?php
                    if(!empty($_GET['page'])){
                        if($_GET['page'] == 'mostmentionedaccounts'){
                            include('ranking_twitter/list_most_mentioned_accounts.php');
                        }
                        else{
                            $is_hispanic = '';
                            if($_GET['page'] == 'hispanic')
                                $is_hispanic = " WHERE owloo_user_language LIKE 'es' ";
                            include('ranking_twitter/list_most_followed_accounts.php');
                        }
                    }
                    else{
                      include('ranking_twitter/list_most_followed_accounts.php');
                    }
                ?>
            </div>
        </div>
        <?php } ?>
        <div class="owloo_tw_search_content">
            <?php include(FOLDER_INCLUDE.'owloo_tw_search.php'); ?>
        </div>
        <?php
            $aux_page_sp = null;
            if(!(isset($_GET['page'])&&$_GET['page']=='userpage')){
                if((isset($_GET['page'])&&$_GET['page']=='hispanic'))
                    $aux_page_sp = 'hispanic-';
                else
                    $aux_page_sp = 'global-';
            }
        ?>
        <?php if(!empty($aux_page_sp) || !empty($tw_user_name)){ ?>
        <div class="owloo_social_plugin" data="twitter-<?=$aux_page_sp?>stats<?=(empty($aux_page_sp)?'/'.$tw_screen_name:'')?>">
            <h3>La plataforma en español más completa para el análisis de Twitter</h3>
            <div class="owloo_social_plugin_content">
                <img alt="Cargando" title="Cargando" src="<?=URL_IMAGES?>loader-24x24.gif" />
            </div>
        </div>
        <?php } ?>
    </div>
    <?php require_once(FOLDER_INCLUDE.'footer.php'); ?>
    <?php require_once(FOLDER_INCLUDE.'include_in_footer.php'); ?>
    <?php if(get_current_page()!='twitter_profile'){ ?>
    <script>if(location.hash){var page = location.hash.replace('#stats-', '');next(page, <?=TWITTER_TOTAL_PROFILES?>, <?=PAGER_PP?>);load_page(page, '<?=$_pager_title_aux?>_twitter', true, '');}</script>
    <?php } ?>
    <?php if(get_current_page()=='twitter_profile' && !$user_not_udate){ ?>
    <input type="hidden" id="votcnt" value="0"/>
    <input type="hidden" id="hfuidid" value="<?php echo $update_id; ?>"/>
    <input type="hidden" id="hfflag" value="<?php echo $hfflag; ?>"/>
    <input type="hidden" id="hftweetcnt" value="<?php echo $tweetcount; ?>"/>
    <input type="hidden" id="hfscreen" value="<?php echo $screen_name; ?>"/>
    <script>
        $(document).ready(function()
        {
            $.ajax({
                type: "GET",
                url: "<?=URL_ROOT?>ranking_twitter/savelog.php",
                data: "uid=" + $("#hfuidid").val(),
                cache: false,
                success: function(html){}
            });
            qrystr = "";
            qrystr = qrystr + "uid=" + $("#hfuidid").val();
            qrystr = qrystr + "&screen=" + $("#hfscreen").val();
            qrystr = qrystr + "&twcnt=" + $("#hftweetcnt").val();
            qrystr = qrystr + "&flg=" + $("#hfflag").val();
            $.ajax({
                type: "GET",
                url: "<?=URL_ROOT?>ranking_twitter/savehashmention.php",
                data: qrystr,
                cache: false,
                success: function(html){
                    <?php if($hfflag == 1) { ?>
                    $.getScript('<?=URL_JS?>owloo_twitter_charts.js?screenname=<?=$screen_name?>&require_jquery=true?v=1.0',
                        function(){$('.owloo_country_content').removeClass('owloo_country_content_tw_chart');
                        $('#owloo_tw_charts_content').addClass('owloo_hide');
                        $('#owloo_content_charts_preup').removeClass('owloo_hide');
                    });
                    <?php } ?>
                }
            });
        });
    </script>
    <?php } ?>
    <div class="owloo_tw_download_content">
        <table>
            <tr>
                <td>
                    <div class="owloo_tw_download">
                        <div class="owloo_close">X</div>
                        <div class="owloo_tw_download_sl"></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>