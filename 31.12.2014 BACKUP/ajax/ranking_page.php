<?php
    require_once('../owloo_config.php');
    if(!(isset($_POST['page']) && is_numeric($_POST['page'])))
        $_POST['page'] = 1;
    if(isset($_POST['from_page'])){
        switch ($_POST['from_page']) {
            case 'global_country':
                require_once('../ranking_country/list_country_page.php');
                break;
            case 'hispanic_country':
                require_once('../ranking_country/list_country_page.php');
                break;
            case 'global_city':
                require_once('../ranking_city/list_city_page.php');
                break;
            case 'hispanic_city':
                require_once('../ranking_city/list_city_page.php');
                break;
            case 'country_city':
                if(isset($_POST['country'])){
                    $countryCode = "";  
                    $countryName = "";  
                    $countryNameEn = "";    
                    $countryCodeName = $_POST['country'];
                    
                    if(empty($_POST['country'])){
                        die('Acceso restringido!');
                    }
                    else{ //Comprobamos que el nombre pertenezca a un país
                        $_country_temp = get_country_data_from_name($countryCodeName);
                        if(!empty($_country_temp)){
                            $countryCode = $_country_temp['code'];
                            $countryName = $_country_temp['nombre'];
                            $countryNameEn = $_country_temp['name'];
                        }
                        else{
                            die('Acceso restringido!'); 
                        }
                    }
                }
                require_once('../ranking_city/list_city_country_page.php');
                break;
            case 'global_twitter':
                require_once("../ranking_twitter/config/config.php");
                require_once('../ranking_twitter/list_most_followed_accounts_page.php');
                break;
            case 'hispanic_twitter':
                require_once("../ranking_twitter/config/config.php");
                require_once('../ranking_twitter/list_most_followed_accounts_page.php');
                break;
            case 'mentions_twitter':
                require_once("../ranking_twitter/config/config.php");
                require_once('../ranking_twitter/list_most_mentioned_accounts_page.php');
                break;
            case 'global_page':
                require_once('../ranking_fb_page/list_page_page.php');
                break;
            case 'hispanic_page':
                require_once('../ranking_fb_page/list_page_page.php');
                break;
            case 'country_page':
                if(isset($_POST['country'])){
                    $countryCode = "";  
                    $countryName = "";  
                    $countryNameEn = "";    
                    $countryCodeName = $_POST['country'];
                    
                    if(empty($_POST['country'])){
                        die('Acceso restringido!');
                    }
                    else{ //Comprobamos que el nombre pertenezca a un país
                        $_country_temp = get_country_data_from_name($_POST['country']);
                        if(!empty($_country_temp)){
                            $id_country = $_country_temp['id_country'];
                        }
                        else{
                            die('Acceso restringido!'); 
                        }
                    }
                }
                require_once('../ranking_fb_page/list_page_country_page.php');
                break;
            default:
                die('Acceso restringido!');
                break;
        }
    }
    else {
        die('Acceso restringido!');
    }