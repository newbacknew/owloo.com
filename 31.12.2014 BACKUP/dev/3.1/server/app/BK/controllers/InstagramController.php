<?php

class InstagramController extends BaseController {

    /*
    |
    | Total Profiles
    |
    */
    public function getTotalProfile()
    {
        
        if (!Cache::has('totalInstagramProfiles')) {
            $data['total'] = InstagramProfile::count();
            Cache::put('totalInstagramProfiles', $data, 1440);
        }
        return Cache::get('totalInstagramProfiles');
        
    }

    /*
    |
    | Ranking Profiles
    |
    */
    public function getRankingProfile($page = 1)
    {
        
        $var_cache = 'rankingInstagramProfiles_' . (($page - 1) * 20);
        
        if (!Cache::has($var_cache)) {
            
            $ranking = InstagramProfile::take(20)->skip(($page - 1) * 20)->orderBy('followed_by_count','DESC')->orderBy('followed_by_grow_30', 'DESC');
            
            $ranking = $ranking->get(['username', 'name', 'picture', 'followed_by_count', 'follows_count', 'media_count', 'followed_by_grow_30']);
            
            $profiles = array();
            
            foreach ($ranking as $key => $value)
            {
                
                $cache = NULL;
                $cache['position'] = ((($page - 1) * 20) + $key) + 1;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = (!empty($value['name'])?$value['name']:$value['username']);
                $cache['picture'] = $value['picture'];
                $cache['followed_by_count'] = $this->owloo_number_format($value['followed_by_count']);
                $cache['follows_count'] = $this->owloo_number_format($value['follows_count']);
                $cache['media_count'] = $this->owloo_number_format($value['media_count']);
                $cache['followed_by_grow_30'] = (!empty($value['followed_by_grow_30'])?$this->formatGrow($value['followed_by_grow_30'], $value['followed_by_count']):'null');
                
                $profiles[] = $cache;
                
            }
            
            Cache::put($var_cache, $profiles, 1440);
            
        }
        
        return Cache::get($var_cache);
        
    }
    
    /*
    |
    | Get grow of fanpages.
    |
    */
    public function getRankingProfileGrow() {
        
        $var_cache = 'rankingInstagramGrow';
        
        if (!Cache::has($var_cache)) {
            
            $ranking = InstagramProfile::take(4)->orderBy('followed_by_grow_30','DESC');
            
            $ranking = $ranking->get(['username', 'name', 'picture', 'followed_by_count', 'followed_by_grow_30']);
            
            $profiles = array();
            
            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = $value['name'];
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
    | Get Profile
    |
    */
    public function getProfile($username){
        
        $var_cache = 'instagramProfile'.$username;
        
        if (!Cache::has($var_cache)) {
            
            $data = InstagramProfile::whereUsername($username)->first();
            
            if($data){
                
                $profile = NULL;
                
                $profile['username'] = strtolower($data['username']);
                
                $profile['name'] = $data['name'];
                
                $profile['bio'] = $data['bio'];
                
                $profile['website'] = $data['website'];
                
                $profile['picture'] = str_replace('_normal.', '.', $data['picture']);
                
                $auxformat = explode("-", $data['in_owloo_from']);
                $year = $auxformat[0];
                $day = $auxformat[2];
                $month = strtolower($this->getMonth($auxformat[1], 'large'));
                $profile['in_owloo_from'] = $day.' '.$month.' '.$year;
                
                $profile['followed_by_count'] = $this->owloo_number_format($data['followed_by_count']);
                $profile['follows_count'] = $this->owloo_number_format($data['follows_count']);
                $profile['media_count'] = $this->owloo_number_format($data['media_count']);
                
                $profile['followed_by_history_30'] = json_decode($data['followed_by_history_30'], true);
                
                $profile['followed_by_grow']['grow_1'] = $this->formatGrow($data['followed_by_grow_1'], $data['followed_by_count']);
                $profile['followed_by_grow']['grow_7'] = $this->formatGrow($data['followed_by_grow_7'], $data['followed_by_count']);
                $profile['followed_by_grow']['grow_15'] = $this->formatGrow($data['followed_by_grow_15'], $data['followed_by_count']);
                $profile['followed_by_grow']['grow_30'] = $this->formatGrow($data['followed_by_grow_30'], $data['followed_by_count']);
                
                $profile['most_mentions'] = json_decode($data['most_mentions'], true);
                $profile['most_hashtags'] = json_decode($data['most_hashtags'], true);
                
                $profile['last_post'] = json_decode($data['last_post'], true);
                
                Cache::put($var_cache, $profile, 1440);
                
            }
            else {
                return 'Invalid method';
            }
        }
        
        return Cache::get($var_cache);

    }
    
    


    /***** Funciones comunes *****/
    private function owloo_number_format($value, $separador = '.', $decimal_separator = ',', $decimal_count = 0){
        return str_replace(' ', '&nbsp;', number_format($value, $decimal_count, $decimal_separator, $separador));
    }

    private function owlooFormatPorcent($number, $total = NULL, $decimal = 2, $sep_decimal = ',', $sep_miles = '.'){
        if($total === 0)
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
    
    private function getMonth($mes, $format = 'short'){ //Formateo de meses
        switch((int)$mes){
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

    private function formatGrow($grow, $current){
        
        if($grow === NULL)
            return array();
        
        $percent = 0;
        if($current - $grow > 0)
            $percent = $this->owlooFormatPorcent($grow, ($current - $grow));
        
        return array(
                    'value' => $this->owloo_number_format($grow),
                    'percent' => $percent,
                    'class' => (($grow > 0) ? 'up' : (($grow < 0) ? 'down' : 'zero')) 
                );
    }

    
    
}