<?php
    require_once('../owloo_config.php');
    //$url = DOMAIN;
    $url = 'www.owloo.com/';
    $tw_description = '';
    
    function get_country_data_sp($country_name){   
        $countryCodeName = $country_name;
        //Comprobamos que el nombre pertenezca a un país
        if($countryCodeName == 'st-lucia'){ //Excepción para el país Santa Lucía
            $countryCodeName = 'St. Lucia';
        }
        $sql =  "SELECT code, nombre, name
                    FROM country 
                    WHERE name LIKE '".mysql_real_escape_string(str_ireplace('-', ' ',  strtolower($countryCodeName)))."';
                 ";
        $que = mysql_query($sql) or die(mysql_error());
        if($fila = mysql_fetch_assoc($que)){
            return array('code' => $fila['code'], 'nombre' => $fila['nombre'], 'name' => $fila['name']);
        }
        else {
            return NULL;
        }
    }
    
    if(!isset($_GET['page'])){
        exit();
    }
    
    switch ($_GET['page']) {
        case 'index':
            $url = DOMAIN;
            $tw_description = 'La plataforma en español para el análisis de las redes sociales. Facebook ranking y estadísticas por país http://'.str_replace('www.', '', $url);
            break;
        case 'facebook-world-ranking':
            $url = DOMAIN.'facebook-stats/world-ranking/';
            $tw_description = 'Ranking mundial de los países en Facebook http://'.str_replace('www.', '', $url);
            break;
        case 'facebook-hispanic-ranking':
            $url = DOMAIN.'facebook-stats/hispanic-ranking/';
            $tw_description = 'Ranking de los países hispanos en Facebook http://'.str_replace('www.', '', $url);
            break;
        case 'facebook-cities-ranking':
            $url = DOMAIN.'facebook-stats/cities/';
            $tw_description = 'Ranking de ciudades en Facebook http://'.str_replace('www.', '', $url);
            break;
        case 'facebook-continents-ranking':
            $url = DOMAIN.'facebook-stats/continents/';
            $tw_description = 'Datos de Facebook por continentes http://'.str_replace('www.', '', $url);
            break;
        case 'features':
            $url = DOMAIN.'features/';
            $tw_description = 'Conoce owloo http://'.str_replace('www.', '', $url);
            break;
        case 'facebook-country-cities-ranking':
            if(!isset($_GET['value']))
                exit();
            $country = get_country_data_sp($_GET['value']);
            if(empty($country)){
                exit(); 
            }
            $url = DOMAIN.'facebook-stats/cities/'.convert_to_url_string($country['name']).'/';
            $tw_description = 'Datos de Facebook en '.$country['nombre'].' por ciudad http://'.str_replace('www.', '', $url);
            break;
       case 'facebook-country':
            if(!isset($_GET['value']))
                exit();
            $country = get_country_data_sp($_GET['value']);
            if(empty($country)){
                exit(); 
            }
            $url = DOMAIN.'facebook-stats/'.convert_to_url_string($country['name']).'/';
            $tw_description = 'Estadísticas y análisis de Facebook en '.$country['nombre'].' http://'.str_replace('www.', '', $url);
            break;
        case 'twitter-global-stats':
            $url = DOMAIN.'twitter-stats/';
            $tw_description = 'Analiza gratis un perfil de Twitter con Owloo. Ranking mundial http://'.str_replace('www.', '', $url);
            break;
        case 'twitter-hispanic-stats':
            $url = DOMAIN.'twitter-stats/hispanic/';
            $tw_description = 'Analiza gratis un perfil de Twitter con Owloo. Ranking hispano http://'.str_replace('www.', '', $url);
            break;
        case 'twitter-stats':
            require_once("../ranking_twitter/config/config.php");
            $name = get_user_name($_GET['value']);
            if(empty($name)){
                exit(); 
            }
            $url = DOMAIN.'twitter-stats/userpage/'.$_GET['value'];
            $tw_description = 'Estadísticas de @'.$_GET['value'].' en Twitter http://'.str_replace('www.', '', $url);
            break;
        case 'facebook-page':
            $page = get_fb_page_data_from_username($_GET['value']);
            if(empty($page)){
                exit(); 
            }
            $url = DOMAIN.'facebook-stats/pages/'.$_GET['value'].'/';
            $tw_description = 'Estadísticas de '.$page['name'].' en Facebook, fans PTA y crecimiento en http://'.str_replace('www.', '', $url);
            break;
        case 'ranking-pages':
            $url = DOMAIN.'facebook-stats/pages/hispanic/';
            $tw_description = 'Las páginas de Facebook más populares, estadísticas gratuitas en http://'.str_replace('www.', '', $url);
            break;
        case 'facebook-page-country':
            if(!isset($_GET['value']))
                exit();
            $country = get_country_data_sp($_GET['value']);
            if(empty($country)){
                exit(); 
            }
            $url = DOMAIN.'facebook-stats/pages/country/'.convert_to_url_string($country['code']).'/';
            $tw_description = 'Páginas de Facebook más populares en '.$country['nombre'].', estadísticas gratuitas en http://'.str_replace('www.', '', $url);
            break;
        default:
            
            break;
    }

?>
        <script>(function(d, s, id) {
        	 var js, fjs = d.getElementsByTagName(s)[0];
        	 if (d.getElementById(id)) return;
        	 js = d.createElement(s); js.id = id;
        	 js.src = "https://connect.facebook.net/es_LA/all.js#xfbml=1&appId=314006765369588";
        	 fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <div class="owloo_social_plugin_item owloo_social_plugin_fb">
            <div class="fb-like" data-href="http://<?=$url?>" data-send="false" data-layout="button_count" data-show-faces="false"></div>
        </div>
        <div class="owloo_social_plugin_item owloo_social_plugin_twitter">
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$url?>" data-text="<?=$tw_description?>" data-lang="es" data-hashtags="owloo">Twittear</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
        <div class="owloo_social_plugin_item owloo_social_plugin_gplus">
            <div class="g-plusone" data-size="medium" data-href="http://<?=$url?>"></div>
            <script type="text/javascript">
              window.___gcfg = {lang: 'es-419'};
              (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.google.com/js/plusone.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
              })();
            </script>
        </div>
        
