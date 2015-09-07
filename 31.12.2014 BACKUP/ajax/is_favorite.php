<?php
    if(isset($_POST['type']) && isset($_POST['id_element']) && is_numeric($_POST['id_element'])){
        require_once('../owloo_config.php');
        require_once('../userMgmt/system/initiater.php');
        $user_profile = $site->get_profile();;
        if($site->loggedin)
            set_current_user_id($user_profile['user_id']);
        else {
            echo 0; //is not favorite
            exit();
        }
        
        if(is_current_favorite($_POST['type'], $_POST['id_element'])){
            echo 1; //is favorite
            exit();
        }
    }
    echo 0; //is not favorite
    exit();
