<?php
    function get_url_content($url){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_URL, $url);
          $data = curl_exec($ch);
          curl_close($ch);
          return $data;
    }
    
    $tag = $_GET['tag'];
    $client_id = '04e770ce699e44eb80bcf26cf929aa5a';
    $count_data = 100;
    
    echo '<h1>Cantidad de posteos:</h1>';
    $data = get_url_content("https://api.instagram.com/v1/tags/$tag?client_id=$client_id");
    $data = json_decode($data, true);
    echo $data['data']['media_count'];
    
    echo '<h1>Últimos posteos:</h1>';
    $data = get_url_content("https://api.instagram.com/v1/tags/$tag/media/recent?client_id=$client_id&count=$count_data");
    $data = json_decode($data, true);
    ?>
    <table><tr><th>Tags</th><th>Comments</th><th>Likes</th><th>Post by</th><th>Link</th></tr>
    <?
    foreach ($data['data'] as $value) {
        echo '<tr><td>'.count($value[tags]).'</td><td>'.$value[comments][count].'</td><td>'.$value[likes][count].'</td><td>'.$value[user][full_name].'</td><td>'.$value[link].'</td></tr>';
    }
    ?>
    </table><br><br><br>
    <?
    print_r($data);