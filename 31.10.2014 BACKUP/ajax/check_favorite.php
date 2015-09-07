<?php
    if(isset($_POST['type']) && isset($_POST['id_element']) && is_numeric($_POST['id_element'])){
        require_once('../owloo_config.php');
        require_once('../userMgmt/system/initiater.php');
        $user_profile = $site->get_profile();;
        if($site->loggedin)
            set_current_user_id($user_profile['user_id']);
        else {
            echo 0; //No login
            exit();
        }
        
        if(!is_current_favorite($_POST['type'], $_POST['id_element'])){
            
            if(get_current_favorite_count($_POST['type']) >= 6){
                echo 6; //Limit exceded
                exit();
            }
            
            if($_POST['type'] == 'country'){
                $_check = get_current_favorite_country_data($_POST['id_element']);
                if(empty($_check)){
                    echo 1; //No data
                    exit();
                }
            }
            elseif($_POST['type'] == 'page'){
                $_check = get_current_favorite_page_data($_POST['id_element']);
                if(empty($_check)){
                    echo 1; //No data
                    exit();
                }
            }
            else {
                reconnect_db('owloo_twitter');
                $_check = get_current_favorite_twitter_data($_POST['id_element']);
                if(empty($_check)){
                    echo 1; //No data
                    exit();
                }
                reconnect_db('owloo_owloo');
            }
            
            
            if(add_current_favorite($_POST['type'], $_POST['id_element']))
                echo 2; //Add ok
            else 
                echo 3; //Add error
            exit();
        }
        else {
            if(down_current_favorite($_POST['type'], $_POST['id_element']))
                echo 4; //Down Ok
            else 
                echo 5; //Down error
            exit();
        }
    }
    echo 1; //No data
    exit();
