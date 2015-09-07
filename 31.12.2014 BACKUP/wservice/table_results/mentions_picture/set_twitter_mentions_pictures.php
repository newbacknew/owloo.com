<?php
    require_once(__DIR__.'/../../config.php');
    include(__DIR__.'/simple_html_dom.php');
    
    $picture = str_get_html(get_url_content('https://twitter.com/latamclick'))->find('img.ProfileAvatar-image');
    
    echo ($picture[0]->attr['src']);