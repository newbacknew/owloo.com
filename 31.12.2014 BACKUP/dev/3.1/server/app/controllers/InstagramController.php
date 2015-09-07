<?php

class InstagramController extends BaseController {
    
    public function getCategories() {
        
        $var_cache = 'listInstagramCategories';
        
        if (!Cache::has($var_cache)) {
        
            $ranking = InstagramCategory::orderBy('category','ASC')->get();
            
            $categories = array();
            
            foreach ($ranking as $key => $value) {
                    
                $cache = NULL;
                $index = $value['category'];
                $cache['id'] = $value['id_category'];
                $cache['name'] = $value['category'];
                $categories[$index] = $cache;
                
            }
            Cache::put($var_cache, $categories, 1440);
            
        }

        return Cache::get($var_cache);
    }

    /*
    |
    | Total Profiles
    |
    */
    public function getTotalProfile($category = 'all') {
        
        if (!Cache::has('total'.ucfirst($category).'InstagramProfiles')) {
            
            $is_have_category = false;
            $id_category = NULL;
            if ($category != 'all') {
                $category_search = InstagramCategory::whereCategory($category)->first();
                if($category_search) {
                    $id_category = $category_search->id_category;
                    $is_have_category = true;
                }else {
                    return 'Invalid method';
                }
            }
            
            if($is_have_category) {
                $data['total'] = InstagramProfile::whereCategory($category)->count();
            }else {
                $data['total'] = InstagramProfile::count();
            }
            
            Cache::put('totalInstagramProfiles', $data, 1440);
        }
        return Cache::get('totalInstagramProfiles');
        
    }

    /*
    |
    | Ranking Profiles
    |
    */
    public function getRankingProfile($category, $page = 1) {
        
        $var_cache = 'ranking'.ucfirst($category).'InstagramProfiles_' . (($page - 1) * 20);
        
        if (!Cache::has($var_cache)) {
                
            $is_have_category = false;
            $id_category = NULL;
            if ($category != 'all') {
                $category_search = InstagramCategory::whereCategory($category)->first();
                if($category_search) {
                    $id_category = $category_search->id_category;
                    $is_have_category = true;
                }else {
                    return 'Invalid method';
                }
            }
            
            $ranking = InstagramProfile::take(20)->skip(($page - 1) * 20)->orderBy('followed_by_count','DESC')->orderBy('followed_by_grow_30', 'DESC');
            
            if($is_have_category) {
                $ranking->whereCategory($category);
            }
            
            $ranking = $ranking->get(['username', 'name', 'picture', 'followed_by_count', 'follows_count', 'media_count', 'followed_by_grow_30']);
            
            $profiles = array();
            
            foreach ($ranking as $key => $value) {
                
                $cache = NULL;
                $cache['position'] = ((($page - 1) * 20) + $key) + 1;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = (!empty($value['name'])?$value['name']:$value['username']);
                $cache['picture'] = $value['picture'];
                
                //Mes
                $aux_num = $this->formatGrow($value['followed_by_grow_30'], $value['followed_by_count']);
                $cache['second_column'][] = array('value' => $aux_num['value'], 'class' => $aux_num['class']);
                //Post
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['media_count']), 'class' => '');
                //Siguiendo
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['follows_count']), 'class' => '');
                //Seguidores
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['followed_by_count']), 'class' => '');
                
                
                $profiles[] = $cache;
                
            }

            $second_column = array('Mes', 'Post', 'Siguiendo', 'Seguidores');
            $array_result = array(
                                    'type' => 'fb_page',
                                    'subtype' => 'in_profile',
                                    'main_column' => 'Cuenta',
                                    'second_column' => $second_column,
                                    'large_column' => 3,
                                    'link' => 'instagram-analytics/accounts',
                                    'items' => $profiles
                            );
            
            Cache::put($var_cache, $array_result, 1440);
            
        }
        
        return Cache::get($var_cache);
        
    }
    
    /*
    |
    | Get grow of fanpages.
    |
    */
    public function getRankingProfileGrow($limit = 4) {
        
        $var_cache = 'rankingInstagramGrow' . $limit;
        
        if (!Cache::has($var_cache)) {
            
            $ranking = InstagramProfile::take($limit)->orderBy('followed_by_grow_30','DESC');
            
            $ranking = $ranking->get(['username', 'name', 'picture', 'followed_by_count', 'followed_by_grow_30']);
            
            $profiles = array();
            
            foreach ($ranking as $key => $value) {
                $cache = NULL;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = (!empty($value['name'])?$value['name']:$value['username']);
                $cache['picture'] = $value['picture'];
                $cache['percent'] = 0;
                if ($value['followed_by_count'] - $value['followed_by_grow_30'] > 0)
                    $cache['percent'] = $this->owlooFormatPorcent($value['followed_by_grow_30'], ($value['followed_by_count'] - $value['followed_by_grow_30']));
                $cache['grow_30'] = $this->owloo_number_format($value['followed_by_grow_30']);
                
                $profiles[] = $cache;
            }
            
            Cache::put($var_cache, $profiles, 1440);
            
        }

        return Cache::get($var_cache);
    }

    /*
    |
    | Get last profiles added.
    |
    */
    public function getLastProfileAdded($limit = 4) {
        
        $var_cache = 'lastInstagramProfileAdded' . $limit;
        
        if (!Cache::has($var_cache)) {
            
            $ranking = InstagramProfile::take($limit)->orderBy('id','DESC');
            
            $ranking = $ranking->get(['username', 'name', 'picture']);
            
            $profiles = array();
            
            foreach ($ranking as $key => $value) {
                $cache = NULL;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = (!empty($value['name'])?$value['name']:$value['username']);
                $cache['picture'] = $value['picture'];
                
                $profiles[] = $cache;
            }
            
            Cache::put($var_cache, $profiles, 1440);
            
        }

        return Cache::get($var_cache);
    }
    
    /*
    |
    | Get Profile
    |
    */
    public function getProfile($username){
        
        $var_cache = 'instagramProfile'.$username;
        
        //if (!Cache::has($var_cache)) {
        if (true) {
            
            $data = InstagramProfile::whereUsername($username)->first();
            
            if($data) {
                
                $profile = NULL;
                
                $profile['username'] = strtolower($data['username']);
                
                $profile['name'] = (!empty($data['name'])?$data['name']:$data['username']);
                
                $profile['bio'] = $data['bio'];
                
                $profile['website'] = $data['website'];
                
                $profile['picture'] = str_replace('_normal.', '.', $data['picture']);
                
                $auxformat = explode("-", $data['in_owloo_from']);
                $year = $auxformat[0];
                $day = $auxformat[2];
                $month = strtoupper($this->getMonth($auxformat[1], 'large'));
                $profile['in_owloo_from'] = substr($month,0,3).substr($year,2,2);
                
                $profile['followed_by_count'] = $this->owloo_number_format($data['followed_by_count']);
                $profile['followed_by_count_str'] = $this->owloo_number_format_str($data['followed_by_count']);
                $profile['follows_count'] = $this->owloo_number_format($data['follows_count']);
                $profile['follows_count_str'] = $this->owloo_number_format_str($data['follows_count']);
                $profile['media_count'] = $this->owloo_number_format($data['media_count']);
                $profile['media_count_str'] = $this->owloo_number_format_str($data['media_count']);
                
                $profile['general_ranking'] = $this->owloo_number_format($data['general_ranking']);
                                
                $charts = json_decode($data['charts'], true);
                
                $charts = array(
                                'followers' => $this->owloo_chart_data_format($charts['followers'], true),
                                'daily_followers_grow' => $this->owloo_chart_data_format($charts['daily_followers_grow'], true),
                                'daily_following_grow' => $this->owloo_chart_data_format($charts['daily_following_grow'], true)
                );
                
                $profile['charts'] = $charts;
                
                $profile['followers_accumulate_down_30'] = $this->owloo_number_format($data['accumulate_down_30']);
                $profile['followed_by_grow']['grow_1'] = $this->formatGrow($data['followed_by_grow_1'], $data['followed_by_count']);
                $profile['followed_by_grow']['grow_7'] = $this->formatGrow($data['followed_by_grow_7'], $data['followed_by_count']);
                $profile['followed_by_grow']['grow_15'] = $this->formatGrow($data['followed_by_grow_15'], $data['followed_by_count']);
                $profile['followed_by_grow']['grow_30'] = $this->formatGrow($data['followed_by_grow_30'], $data['followed_by_count']);
                
                $most_mentions = json_decode($data['most_mentions'], true);
                $profile['most_hashtags'] = json_decode($data['most_hashtags'], true);
                
                $limit = count($profile['most_hashtags']);
                $first_hashtag = NULL;
                $hashtags = array();
                for($i=0; $i < $limit; $i++){
                    $profile['most_hashtags'][$i]['count'] = $this->owloo_number_format($profile['most_hashtags'][$i]['count']);
                    $profile['most_hashtags'][$i]['class'] = '';
                    if($i == 0){
                        $profile['most_hashtags'][$i]['class'] = 'big';
                        $first_hashtag = $profile['most_hashtags'][$i];
                    }else{
                        $hashtags[] = $profile['most_hashtags'][$i];
                        if($i == 3){
                            $hashtags[] = $first_hashtag;
                        }
                    }
                }
                
                $profile['most_hashtags'] = $hashtags;
                
                $limit = count($most_mentions);
                for ($i=0; $i < $limit; $i++) {
                    $most_mentions[$i]['count'] = $this->owloo_number_format($most_mentions[$i]['count']);
                    if($i < 2){
                        $most_mentions['big'][] = $most_mentions[$i];
                    }else{
                        $most_mentions['normal'][] = $most_mentions[$i];
                    }
                }
                
                $profile['most_mentions'] = array('big' => $most_mentions['big'], 'normal' => $most_mentions['normal']);
                
                
                $profile['last_post'] = json_decode($data['last_post'], true);
                
                $profile['post_by_engagement_rate'] = json_decode($data['post_by_engagement_rate'], true);
                
                Cache::put($var_cache, $profile, 1440);
                
            }
            else {
                return 'Invalid method';
            }
        }
        
        return Cache::get($var_cache);

    }
    
    /***** Funciones comunes *****/
    private function owloo_number_format($value, $separador = '.', $decimal_separator = ',', $decimal_count = 0) {
        return str_replace(' ', '&nbsp;', number_format($value, $decimal_count, $decimal_separator, $separador));
    }
    
    private function owloo_number_format_str($n) {
        $n = (0+str_replace(",","",$n));

        if(!is_numeric($n)) return false;

        if($n>1000000000000) return round(($n/1000000000000),1).'MMM';
        else if($n>1000000000) return round(($n/1000000000),1).'MM';
        else if($n>1000000) return round(($n/1000000),1).'M';
        else if($n>1000) return round(($n/1000),1).'K';

        return number_format($n);
    }

    private function owlooFormatPorcent($number, $total = NULL, $decimal = 2, $sep_decimal = ',', $sep_miles = '.'){
        if($total === 0 || $number === 0)
            return 0;
        
        if(!empty($total))
            $aux_result = ($number * 100 / $total);
        else
            $aux_result = $number;
        
        $aux_decimal = $decimal;
        
        if($aux_result < 0.001){
            $aux_decimal = 4;
        }
        elseif($aux_result < 0.01){
            $aux_decimal = 3;
        }
        
        if(!empty($total))
            return number_format(round(($number * 100 / $total), $aux_decimal), $aux_decimal, $sep_decimal, $sep_miles);
        else
            return number_format(round(($number), $aux_decimal), $aux_decimal, $sep_decimal, $sep_miles);
    }
    
    private function getMonth($mes, $format = 'short') { //Formateo de meses
        switch((int)$mes) {
            case 1: if($format == 'short') return 'Ene'; else return 'Enero';
            case 2: if($format == 'short') return 'Feb'; else return 'Febrero';
            case 3: if($format == 'short') return 'Mar'; else return 'Marzo';
            case 4: if($format == 'short') return 'Abr'; else return 'Abril';
            case 5: if($format == 'short') return 'May'; else return 'Mayo';
            case 6: if($format == 'short') return 'Jun'; else return 'Junio';
            case 7: if($format == 'short') return 'Jul'; else return 'Julio';
            case 8: if($format == 'short') return 'Ago'; else return 'Agosto';
            case 9: if($format == 'short') return 'Set'; else return 'Setiembre';
            case 10: if($format == 'short') return 'Oct'; else return 'Octubre';
            case 11: if($format == 'short') return 'Nov'; else return 'Noviembre';
            case 12: if($format == 'short') return 'Dic'; else return 'Diciembre';
        }
    }

    private function formatGrow($grow, $current) {
        
        if($grow === NULL) {
            return array(
                    'value' => 'n/a',
                    'percent' => NULL,
                    'class' => 'not-available' 
                );
        }
        
        $percent = 0;
        if($current - $grow > 0) {
            $percent = $this->owlooFormatPorcent($grow, ($current - $grow));
        }
        
        return array(
                    'value' => $this->owloo_number_format($grow),
                    'percent' => $percent,
                    'class' => (($grow > 0) ? 'plus' : (($grow < 0) ? 'minus' : 'equal')) 
                );
    }
    
    private function owloo_chart_data_format($data, $is_array = false) {
        if(!$is_array)
            $data = json_decode($data, true);
        if(isset($data['series_data'])){
            $data['series_data'] =  array_map(function($n){ return intval($n); }, explode(",", $data['series_data']));
        }
        if(isset($data['x_axis'])){
            $data['x_axis'] = array_map(function($n){ return str_replace("'", "", $n); }, explode(",", $data['x_axis']));
        }
        return $data;
    }

    
    
}