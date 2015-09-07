<?php
    function get_url_content($url){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_URL, $url);
          $data = curl_exec($ch);
          curl_close($ch);
          return $data;
    }
    
    $user = 'BMW';
    $user_id = 253310177; //52762453
    $media_id = '817378653615841268_43109246';
    $client_id = '04e770ce699e44eb80bcf26cf929aa5a';
    $count_data = 33;
    
    echo '<h1>LATAMCLICK:</h1>';
    $data = get_url_content("https://api.instagram.com/v1/users/search?q=$user&client_id=$client_id");
    $data = json_decode($data, true);
    //print_r($data);
    
    //echo '<h1>Datos del usuario '.$user.':</h1>';
    $data = get_url_content("https://api.instagram.com/v1/users/$user_id/?client_id=$client_id");
    $data = json_decode($data, true);
    //print_r($data);
    
    //echo '<h1>Posteos:</h1>';
    $data = get_url_content("https://api.instagram.com/v1/users/$user_id/media/recent/?client_id=$client_id&count=$count_data");
    $data = json_decode($data, true);
    //print_r($data);
    
    //echo '<h1>Posteos:</h1>';
    $data = get_url_content("https://api.instagram.com/v1/users/$user_id/media/recent?count=$count_data&max_id=328924686631226068_253310177&client_id=$client_id");
    $data = json_decode($data, true);
    //print_r($data);
    
    /*foreach ($data['data'] as $value) {
        echo ($count++).') '.$value['link'].'<br/>';
    }*/
    
    echo '<h1>Siguiendo a:</h1>';
    $data = get_url_content("https://api.instagram.com/v1/users/$user_id/follows?client_id=$client_id");
    $data = json_decode($data, true);
    //print_r($data);
    
    foreach ($data['data'] as $value) {
        echo $value[full_name].'<br/>';
    }
    
    echo '<h1>Seguidores:</h1>';
    $data = get_url_content("https://api.instagram.com/v1/users/$user_id/followed-by?client_id=$client_id");
    $data = json_decode($data, true);
    //print_r($data);
    
    foreach ($data['data'] as $value) {
        echo $value[full_name].'<br/>';
    }
    
    echo '<h1>Media:</h1>';
    $data = get_url_content("https://api.instagram.com/v1/media/$media_id?client_id=$client_id");
    $data = json_decode($data, true);
    //print_r($data);
