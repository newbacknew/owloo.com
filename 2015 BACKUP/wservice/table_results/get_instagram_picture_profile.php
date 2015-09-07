<?php

    include(__DIR__.'/mentions_picture/simple_html_dom.php');
    
    function get_url_content($url){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_URL, $url);
          $data = curl_exec($ch);
          curl_close($ch);
          return $data;
    }
    
    function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

   
    function get_current_picture_profile($username){
        
        $html =  get_url_content('http://instagram.com/'.$username);
    
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        
        foreach( $doc->getElementsByTagName('meta') as $meta ) {
           if($meta->getAttribute('property') == 'og:image'){
               if($meta->getAttribute('content') != ''){
                   return  $meta->getAttribute('content');
               }
           }
        }
        
        return 'http://a0.twimg.com/sticky/default_profile_images/default_profile_5_200x200.png';
        
    }
    
    $time_start = microtime_float();
    
    get_current_picture_profile('cristiano');
    
    echo 'Time: '.(microtime_float() - $time_start);
    
?>
