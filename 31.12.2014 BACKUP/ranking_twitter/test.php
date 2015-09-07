<?php
    require_once('../owloo_config.php');
    
    $data = get_url_content('http://api.klout.com/v2/identity.json/twitter?screenName=katyperry&key=gy4u8x32rvmeugcqpdun634b');
    
    $data = json_decode($data, true);
    
    if(isset($data['id'])){
        $klout_data = get_url_content('http://api.klout.com/v2/user.json/'.$data['id'].'/score?key=gy4u8x32rvmeugcqpdun634b');
        
        print_r(json_decode($klout_data, true));
    }