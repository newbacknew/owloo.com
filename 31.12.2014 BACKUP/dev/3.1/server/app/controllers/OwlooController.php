<?php

class OwlooController extends BaseController {

	/*
	|
	| Total Users:
	|
	*/
	public function getTotalUsers()
	{
		if (!Cache::has('totalOwlooUsers'))
		{
			// $data = OwlooUser::count();
			$data = '4.100';
			Cache::put('totalOwlooUsers', $data, 1440);
		}
		return Cache::get('totalOwlooUsers');
	}
    
    /*
    |
    | ADD new accounts
    |
    */
    
    private function searchAccountInDB($username, $social_network) {
        
        switch ($social_network) {
            case 'facebook':
                $data = FacebookPage::whereUsername($username)->first(['fb_id', 'username', 'name']);
                break;
            
            case 'twitter':
                $data = TwitterProfile::whereScreenName($username)->first(['screen_name', 'name', 'picture']);
                break;
                
            case 'instagram':
                $data = InstagramProfile::whereUsername($username)->first(['username', 'name', 'picture']);
                break;
                
            default:
                return 'Invalid method';
                break;
        }
        
        if ($data) {
            $page['username'] = strtolower(isset($data['username'])?$data['username']:$data['screen_name']);
            $page['name'] = (!empty($data['name'])?$data['name']:$page['username']);
            $page['picture'] = ($social_network=='facebook'?'http://graph.facebook.com/'.$data['fb_id'].'/picture?height=200&type=normal&width=200':str_replace('_normal.', '_200x200.', $data['picture']));
            return $page;
        }
        else {
            return NULL;
        }

    }
    
    private function getUrlContent($url){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_URL, $url);
          $data = curl_exec($ch);
          curl_close($ch);
          $data = json_decode ($data, true);
          return $data;
    }
    
    private function addHttpsToUrl($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "https://" . $url;
        }
        return $url;
    }
    
    private function urlCleanUsername($username, $type){
        $username = urldecode($username);
        $search = array('https', 'http', '://', 'www.', $type.'.com/');
        $replace = array('');
        $username = str_replace($search, $replace, $username);
        $chart_position = strpos( $username, '/');
        if($chart_position){
            $username = substr($username, 0, $chart_position);
        }
        return strtolower($username);
    }
    
    private function instagramSearchUsername($username, $instagram_client_id){
        $username = $this->urlCleanUsername($username, 'instagram');
        $data = $this->getUrlContent("https://api.instagram.com/v1/users/search?q=$username&client_id=".$instagram_client_id);
        
        if($data['meta']['code'] == 200){
            foreach ($data['data'] as $user) {
                if($user['username'] == $username){
                    return array('username' => $user['username'], 'name' => (!empty($user['full_name'])?$user['full_name']:$user['username']), 'picture' => $user['profile_picture']);
                }
            }
        }
        
        return NULL;
    }
    
    private function twitterSearchUsername($username){
        
        require_once(__DIR__.'/social_api/twitter.php');
        
        $username = $this->urlCleanUsername($username, 'twitter');
        
        $retdata = getdata('https://api.twitter.com/1.1/users/lookup.json', 'screen_name=' . $username);
        $twdatas = json_decode($retdata, true);
        
        if(isset($twdatas[0]['id'])){
            $user = $twdatas[0];
            return array('username' => $user['screen_name'], 'name' => $user['name'], 'picture' => str_replace('_normal.', '_200x200.', $user['profile_image_url']));
        }
        
        return NULL;
    }
    
    private function searchAccountFromUrl($url, $social_network){
        
        $found_user = NULL;
        
        switch ($social_network) {
            case 'facebook':
                
                if(strpos($url, 'facebook.com/') === false){}else{
                    $url = $this->addHttpsToUrl($url);
                }
                $datos = $this->getUrlContent('https://graph.facebook.com/'.$url);
                if(isset($datos['id']) && isset($datos['likes'])){
                    $found_user = array('username' => (isset($datos['username'])?$datos['username']:$datos['id']), 'name' => $datos['name'], 'picture' => 'http://graph.facebook.com/'.$datos['id'].'/picture?height=200&type=normal&width=200');
                }
                
                break;
                
            case 'twitter':
                $datos = $this->twitterSearchUsername($url);
                if(!empty($datos)){
                    $found_user = $datos;
                }
                
                break;
            
            case 'instagram':
                require_once(__DIR__.'/social_api/instagram.php');
                $datos = $this->instagramSearchUsername($url, $instagram_client_id);
                if(!empty($datos)){
                    $found_user = $datos;
                }
                
                break;
            
            default:
                
                break;
        }
        
        return $found_user;
    }
    
    private function formatFoundAccount($account, $source){
        $social_account['found'] = 'true';
        $social_account['username'] = $account['username'];
        $social_account['name'] = $account['name'];
        $social_account['picture'] = $account['picture'];
        $social_account['surce'] = $source;
        return $social_account;
    }
    
    public function searchSocialAccountsFunction($account, $social_network){
        
        $social_account = array('found' => 'false');
        
        $search = $this->searchAccountInDB($account, $social_network);
        if(!empty($search)){
            $social_account = $this->formatFoundAccount($search, 'owloo');
        }
        else {
            $search = $this->searchAccountFromUrl($account, $social_network);
            if(!empty($search)){
                $search_aux = $this->searchAccountInDB($search['username'], $social_network);
                if(!empty($search_aux)){
                    $social_account = $this->formatFoundAccount($search_aux, 'owloo');
                }else {
                    $social_account = $this->formatFoundAccount($search, 'new');
                }
            }
        }
        
        return $social_account;
        
    }

    public function formatUsername($account){
        
        $account = str_replace('*', '/', $account);

        /*** if is from Instagram or Twitter url ***/
        if(strpos($account, 'twitter.com') !== false) {
            $account = $this->urlCleanUsername($account, 'twitter');
        }elseif (strpos($account, 'instagram.com') !== false) {
             $account = $this->urlCleanUsername($account, 'instagram');
        }
        
        return $account;
        
    }
    
    public function searchSocialAccounts($account){
        
        $account = $this->formatUsername($account);
        
        $facebook = $this->searchSocialAccountsFunction($account, 'facebook');
        
        if($facebook['found'] && strpos($account, 'facebook.com') !== false) {
            $account = $facebook['username'];
        }
        
        $twitter = $this->searchSocialAccountsFunction($account, 'twitter');
        
        $instagram = $this->searchSocialAccountsFunction($account, 'instagram');
        
        return array(
                        'facebook' => $facebook,
                        'twitter' => $twitter,
                        'instagram' => $instagram
               );
    }
    
    public function searchSocialAccountsFacebook($account){
        
        $facebook = $this->searchSocialAccountsFunction($account, 'facebook');
        
        return array(
                        'facebook' => $facebook
               );
    }
    
    public function searchSocialAccountsTwitter($account){
        
        $twitter = $this->searchSocialAccountsFunction($account, 'twitter');
        
        return array(
                        'twitter' => $twitter
               );
    }
    
    public function searchSocialAccountsInstagram($account){
        
        $instagram = $this->searchSocialAccountsFunction($account, 'instagram');
        
        return array(
                        'instagram' => $instagram
               );
    }

}