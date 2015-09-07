<?php
    include(__DIR__.'/twitter/twitterconfig.php');
    
    $cont_access_token = 0;
    
    function content_error($result, $parameters){
        
        $twdatas = json_decode($result, true);
        
        foreach ($twdatas as $twdata) {
            if (isset($twdata[0]["message"]) && $twdata[0]["message"]) {
                if($twdata[0]["code"] != 34){
                    next_access_token();
                    return true;
                }
                else{
                    return false;
                }
                    
            }
        }
        return false;
    }
    
    function getsignature($url, $parameters, $twitter_access_tokens) {
        $oauth_hash = '';
        $oauth_hash .= 'oauth_consumer_key=' . $twitter_access_tokens['Consumer_Key'] . '&';
        $oauth_hash .= 'oauth_nonce=' . time() . '&';
        $oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
        $oauth_hash .= 'oauth_timestamp=' . time() . '&';
        $oauth_hash .= 'oauth_token=' . $twitter_access_tokens['Access_Token'] . '&';
        $oauth_hash .= 'oauth_version=1.0&';
        $oauth_hash .= $parameters;
        $base = '';
        $base .= 'GET';
        $base .= '&';
        $base .= rawurlencode($url);
        $base .= '&';
        $base .= rawurlencode($oauth_hash);
        $key = '';
        $key .= rawurlencode($twitter_access_tokens['Consumer_Secret']);
        $key .= '&';
        $key .= rawurlencode($twitter_access_tokens['Access_Token_Secret']);
        $signature = base64_encode(hash_hmac('sha1', $base, $key, true));
        $signature = rawurlencode($signature);
        return $signature;
    }
    
    function getdata($url, $parameters) {
        
        $opTwitterAccessTokens = New TwitterAccessTokens();
        
        do{
            
            $twitter_access_tokens = $opTwitterAccessTokens->getDataAccessToken();
            
            $signature = getsignature($url, $parameters, $twitter_access_tokens);
            $oauth_header = '';
            $oauth_header .= 'oauth_consumer_key="' . $twitter_access_tokens['Consumer_Key'] . '",';
            $oauth_header .= 'oauth_nonce="' . time() . '", ';
            $oauth_header .= 'oauth_signature="' . $signature . '", ';
            $oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
            $oauth_header .= 'oauth_timestamp="' . time() . '", ';
            $oauth_header .= 'oauth_token="' . $twitter_access_tokens['Access_Token'] . '", ';
            $oauth_header .= 'oauth_version="1.0", ';
            
            $curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');
            $curl_request = curl_init();
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
            curl_setopt($curl_request, CURLOPT_HEADER, false);
            curl_setopt($curl_request, CURLOPT_URL, $url . '?' . $parameters);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
            $json = curl_exec($curl_request);
            curl_close($curl_request);
            
            //echo '<br>'.$oauth_header.'<br>';
        
        }while(content_error($json, $parameters));
        
        return $json;
    }
    
    function gettwitterdata($maxid, $param){
        
        $opTwitterAccessTokens = New TwitterAccessTokens();
        
        do{
            
            $twitter_access_tokens = $opTwitterAccessTokens->getDataAccessToken();
            
            $oauth_hash .= 'count=200&';
            if ($maxid > 0) {
                $oauth_hash .= 'max_id=' . $maxid . '&';
            }
            $oauth_hash .= 'oauth_consumer_key=' . $twitter_access_tokens['Consumer_Key'] . '&';
            $oauth_hash .= 'oauth_nonce=' . time() . '&';
            $oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
            $oauth_hash .= 'oauth_timestamp=' . time() . '&';
            $oauth_hash .= 'oauth_token=' . $twitter_access_tokens['Access_Token'] . '&';
            $oauth_hash .= 'oauth_version=1.0&';
            $oauth_hash .= $param;
            $base = '';
            $base .= 'GET';
            $base .= '&';
            $base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');
            $base .= '&';
            $base .= rawurlencode($oauth_hash);
            $key = '';
            $key .= rawurlencode($twitter_access_tokens['Consumer_Secret']);
            $key .= '&';
            $key .= rawurlencode($twitter_access_tokens['Access_Token_Secret']);
            $signature = base64_encode(hash_hmac('sha1', $base, $key, true));
            $signature = rawurlencode($signature);
            $oauth_header = '';
            $oauth_header .= 'oauth_consumer_key="' . $twitter_access_tokens['Consumer_Key'] . '",';
            $oauth_header .= 'oauth_nonce="' . time() . '", ';
            $oauth_header .= 'oauth_signature="' . $signature . '", ';
            $oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
            $oauth_header .= 'oauth_timestamp="' . time() . '", ';
            $oauth_header .= 'oauth_token="' . $twitter_access_tokens['Access_Token'] . '", ';
            $oauth_header .= 'oauth_version="1.0", ';
            $curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');
            $curl_request = curl_init();
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
            curl_setopt($curl_request, CURLOPT_HEADER, false);
            $turl = 'https://api.twitter.com/1.1/statuses/user_timeline.json?count=200';
            if ($maxid > 0) {
                $turl = $turl . '&max_id=' . $maxid . "&";
                $turl = $turl . $param;
            } else {
                $turl = $turl . "&" . $param;
            }
            curl_setopt($curl_request, CURLOPT_URL, $turl);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
            $json = curl_exec($curl_request);
            curl_close($curl_request);
        }while(content_error($json, $param));
        return $json;
    }
    
    function getsincetwitterdata($maxid,$param) {
        
        $opTwitterAccessTokens = New TwitterAccessTokens();
        
        do{
            
            $twitter_access_tokens = $opTwitterAccessTokens->getDataAccessToken();
            
            $oauth_hash .= 'count=200&';
            if ($maxid > 0) {
                $oauth_hash .= 'max_id=' . $maxid . '&';
            }
            $oauth_hash .= 'oauth_consumer_key=' . $twitter_access_tokens['Consumer_Key'] . '&';
            $oauth_hash .= 'oauth_nonce=' . time() . '&';
            $oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
            $oauth_hash .= 'oauth_timestamp=' . time() . '&';
            $oauth_hash .= 'oauth_token=' . $twitter_access_tokens['Access_Token'] . '&';
            $oauth_hash .= 'oauth_version=1.0&';
            $oauth_hash .= $param;
            $base = '';
            $base .= 'GET';
            $base .= '&';
            $base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');
            $base .= '&';
            $base .= rawurlencode($oauth_hash);
            $key = '';
            $key .= rawurlencode($twitter_access_tokens['Consumer_Secret']);
            $key .= '&';
            $key .= rawurlencode($twitter_access_tokens['Access_Token_Secret']);
            $signature = base64_encode(hash_hmac('sha1', $base, $key, true));
            $signature = rawurlencode($signature);
            $oauth_header = '';
            $oauth_header .= 'oauth_consumer_key="' . $twitter_access_tokens['Consumer_Key'] . '",';
            $oauth_header .= 'oauth_nonce="' . time() . '", ';
            $oauth_header .= 'oauth_signature="' . $signature . '", ';
            $oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
            $oauth_header .= 'oauth_timestamp="' . time() . '", ';
            $oauth_header .= 'oauth_token="' . $twitter_access_tokens['Access_Token'] . '", ';
            $oauth_header .= 'oauth_version="1.0", ';
            $curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');
            $curl_request = curl_init();
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
            curl_setopt($curl_request, CURLOPT_HEADER, false);
            $turl = 'https://api.twitter.com/1.1/statuses/user_timeline.json?count=200';
            if ($maxid > 0) {
                $turl = $turl . '&max_id=' . $maxid . "&";
                $turl = $turl . $param;
            } else {
                $turl = $turl . "&" . $param;
            }
            curl_setopt($curl_request, CURLOPT_URL, $turl);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
            $json = curl_exec($curl_request);
            curl_close($curl_request);
        }while(content_error($json, $param));
        return $json;
    }