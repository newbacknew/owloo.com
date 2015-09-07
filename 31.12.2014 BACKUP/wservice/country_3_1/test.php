<?php
    $text = '{"error":{"message":"Error validating access token: This may be because the user logged out or may be due to a system error.","type":"OAuthException","code":190,"error_subcode":467}}';
    $datos = json_decode($text, TRUE);
    
    print_r($datos);
    