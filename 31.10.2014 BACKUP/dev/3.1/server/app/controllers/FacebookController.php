<?php

class FacebookController extends BaseController {

    /*
    |
    | Get total amount of registered fanpages on owloo.
    |
    */
    public function getTotalPage($idiom)
    {
        switch ($idiom)
        {
            case 'world':
                if (!Cache::has('totalWorldFacebookPages')) {
                    $data['total'] = FacebookPage::count();
                    Cache::put('totalWorldFacebookPages', $data, 1440);
                }
                return Cache::get('totalWorldFacebookPages');
            break;
            case 'hispanic':
                if (!Cache::has('totalHispanicFacebookPages')) {
                    $data['total'] = FacebookPage::whereIdiom('es')->count();
                    Cache::put('totalHispanicFacebookPages', $data, 1440);
                }
                return Cache::get('totalHispanicFacebookPages');
            break;
        }
        return 'Invalid method';
    }
    
    private function formatGrow($grow, $current){
        
        $percent = 0;
        if($current - $grow > 0)
            $percent = $this->owlooFormatPorcent($grow, ($current - $grow));
        
        return array(
                    'value' => $this->owloo_number_format($grow),
                    'percent' => $percent,
                    'class' => (($grow > 0) ? 'up' : (($grow < 0) ? 'down' : 'zero')) 
                );
    }
    
    /*
    |
    | Get fanpage details.
    |
    */
    public function getPage($username){
        if (!Cache::has('facebookPage'.$username)) {
            $data = FacebookPage::whereUsername($username)->first();
            
            if($data){
                
                $page['username'] = strtolower($data['username']);
                
                $page['name'] = $data['name'];
                
                $page['about'] = $data['about'];
                
                $page['description'] = $data['description'];
                
                $page['link'] = $data['link'];
                
                $page['picture'] = 'http://graph.facebook.com/'.$data['fb_id'].'/picture?height=50&type=normal&width=50';
                
                $page['cover'] = $data['cover'];
                
                $page['is_verified'] = $data['is_verified'];
                
                $auxformat = explode("-", $data['in_owloo_from']);
                $year = $auxformat[0];
                $day = $auxformat[2];
                $month = strtolower($this->getMonth($auxformat[1], 'large'));
                $page['in_owloo_from'] = $day.' '.$month.' '.$year;
                
                $page['likes'] = $this->owloo_number_format($data['likes']);
                $page['likes_history_30'] = json_decode($data['likes_history_30'], true);
                $page['likes_grow']['grow_1'] = $this->formatGrow($data['likes_grow_1'], $data['likes']);
                $page['likes_grow']['grow_7'] = $this->formatGrow($data['likes_grow_7'], $data['likes']);
                $page['likes_grow']['grow_15'] = $this->formatGrow($data['likes_grow_15'], $data['likes']);
                $page['likes_grow']['grow_30'] = $this->formatGrow($data['likes_grow_30'], $data['likes']);
                $page['likes_grow']['grow_60'] = $this->formatGrow($data['likes_grow_60'], $data['likes']);
                
                $page['talking_about'] = $this->owloo_number_format($data['talking_about']);
                $page['talking_about_history_30'] = json_decode($data['talking_about_history_30'], true);
                $page['talking_about_grow']['grow_1'] = $this->formatGrow($data['talking_about_grow_1'], $data['talking_about']);
                $page['talking_about_grow']['grow_7'] = $this->formatGrow($data['talking_about_grow_7'], $data['talking_about']);
                $page['talking_about_grow']['grow_15'] = $this->formatGrow($data['talking_about_grow_15'], $data['talking_about']);
                $page['talking_about_grow']['grow_30'] = $this->formatGrow($data['talking_about_grow_30'], $data['talking_about']);
                $page['talking_about_grow']['grow_60'] = $this->formatGrow($data['talking_about_grow_60'], $data['talking_about']);
                
                $page['pta'] = 0;
                if($data['likes'] > 0)
                    $page['pta'] = $this->owlooFormatPorcent($data['talking_about'], $data['likes']);
                
                if(!empty($data['country_code'])){
                    $page['country']['location'] = true;
                    $page['country']['code'] = strtolower($data['country_code']);
                    $page['country']['name'] = $data['country_name'];
                    $page['country']['url_name'] = $this->convert_string_to_url($data['country_name_en']);
                    $page['country']['ranking'] = $data['country_ranking'];
                }else{
                    $page['country']['location'] = false;
                    $page['country']['code'] = strtolower($data['first_country_code']);
                    $page['country']['name'] = $data['first_country_name'];
                    $page['country']['url_name'] = $this->convert_string_to_url($data['first_country_name_en']);
                    $page['country']['ranking'] = $data['first_local_fans_country_ranking'];
                }
                
                $page['first_country']['code'] = strtolower($data['first_country_code']);
                $page['first_country']['name'] = $data['first_country_name'];
                $page['first_country']['url_name'] = $this->convert_string_to_url($data['first_country_name_en']);
                $page['first_country']['audience'] = $data['first_local_fans_country_audience'];
                $page['first_country']['ranking'] = $data['first_local_fans_country_ranking'];
                
                $page['category']['id'] = $data['category_id'];
                $page['category']['name'] = $data['category_name'];
                
                $page['sub_category']['id'] = $data['sub_category_id'];
                $page['sub_category']['name'] = $data['sub_category_name'];
                
                $page['general_ranking'] = $data['general_ranking'];
                
                $page['local_countries'] = array();
                
                $local_countries = FacebookPageLocalFans::where('id_page', $data['id_page'])->orderBy('likes_local_fans', 'DESC')->get(['country_code', 'country_name', 'country_name_en', 'likes_local_fans']);
                
                foreach ($local_countries as $local_country) {
                    
                    if($local_country['country_code'] == $data['first_country_code']){
                        $page['first_country']['market_share_percent'] = $this->owlooFormatPorcent($local_country['likes_local_fans'], $data['first_local_fans_country_audience']);
                    }
                    
                    $page['local_countries'][] = array(
                                                        'code' => strtolower($local_country['country_code']),
                                                        'name' => $local_country['country_name'],
                                                        'url_name' => $this->convert_string_to_url($local_country['country_name_en']),
                                                        'likes' => $this->owloo_number_format($local_country['likes_local_fans']),
                                                        'likes_percent' => $this->owlooFormatPorcent($local_country['likes_local_fans'], $data['likes'])
                                                );
                }
                
                Cache::put('facebookPage'.$username, $page, 1440);
            }
            else {
                return 'Invalid method';
            }
        }
        
        return Cache::get('facebookPage'.$username);

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

    /*
    |
    | Get ranking of fanpages.
    |
    */
    public function getPageLocalFansHistory($username, $country, $days = 30){
        
        if (!Cache::has('facebookPageLocalFansHistory'.$username)) {
            $data_page = FacebookPage::whereUsername($username)->first(['id_page', 'name']);
            $data_country = FacebookCountry::whereCode($country)->first(['id_country', 'name']);
            
            if($data_page && $data_country){
                
                $page['name'] = $data_page['name'];
                
                $data_local_country = FacebookPageLocalFansCountry::where('id_page', $data_page['id_page'])->where('id_country', $data_country['id_country'])->orderBy('date', 'DESC')->take(61)->get(['likes', 'date']);
                
                $series_data = ""; //Likes history
                $series_data_min = 0; //Min likes
                $series_data_max = 0; //Max likes
                $x_axis = ""; //Dates history
                $ban = 1; 
                $cont = 1;
                $_num_rango = 1;
                $_num_discard = count($data_local_country) - ($_num_rango * floor(count($data_local_country)/$_num_rango));
                
                $last_likes = null;
                $last_date = null;
                $nun_rows = count($data_local_country);
                
                foreach ($data_local_country as $local_country) {
                    if($_num_discard-- > 0) continue;
                    if($cont % $_num_rango == 0){
                        //Formatear fecha
                        $auxformat = explode("-", $local_country['date']);
                        $dia = $auxformat[2];
                        $mes = $this->getMonth($auxformat[1]);
                        if($ban == 1){
                            $series_data        .= $local_country['likes'];
                            $x_axis             .= "'".$dia." ".$mes."'";
                            $series_data_min    = $local_country['likes'];
                            $series_data_max    = $local_country['likes'];
                            $last_likes         = $local_country['likes'];
                            $last_date          = $local_country['date'];
                            $ban                = 0;
                        }
                        else{
                            $series_data .= ','.$local_country['likes'];
                            $x_axis .= ",'".$dia." ".$mes."'";
                            if($local_country['likes'] < $series_data_min)
                                $series_data_min = $local_country['likes'];
                            else
                            if($local_country['likes'] > $series_data_max)
                                $series_data_max = $local_country['likes'];
                        }
                    }
                    if($cont > 30) break;
                    $cont++;
                }

                $step = 1;
                if($cont-1 > 11)
                    $step = 2;
                if($cont-1 > 21)
                    $step = 3;
                
                $page['local_fans_history_30'] = array(
                                                            'series_data' =>  $series_data,
                                                            'series_data_min' => $series_data_min,
                                                            'series_data_max'=> $series_data_max,
                                                            'x_axis' =>  $x_axis,
                                                            'page_name' => $data_page['name'],
                                                            'country_name' => $data_country['name']
                                                    );
                                  
                $page['local_fans_grow']['grow_1'] = null;
                $page['local_fans_grow']['grow_7'] = null;
                $page['local_fans_grow']['grow_15'] = null;
                $page['local_fans_grow']['grow_30'] = null;
                $page['local_fans_grow']['grow_60'] = null;
                
                $array_days = array(1, 7, 15, 30, 60);
                
                foreach($array_days as $value){
                    if($nun_rows > $value){
                        $page['local_fans_grow']['grow_'.$value] = $this->formatGrow(($last_likes - $data_local_country[$value]['likes']), $data_local_country[$value]['likes']);
                        $page['local_fans_grow']['grow_'.$value]['date'] = $data_local_country[$value]['date'];
                    }
                }
                
                print_r($page);
                
                die();
                
                Cache::put('facebookPageLocalFansHistory'.$username, $page, 1440);
            }
            else {
                return 'Invalid method';
            }
        }
        
        return Cache::get('facebookPageLocalFansHistory'.$username);
        
    }

    /*
    |
    | Get ranking of fanpages.
    |
    */
    public function getRankingPage($where, $category, $page = 1)
    {
        //Cache::flush();

        $where = ucfirst($where);
        
        if (!Cache::has('rankingLikes' . $where . 'PageCat' . $category . '_' . (($page - 1) * 20))) {
            
            if($where != 'World' && $where != 'Hispanic'){
                $ranking = FacebookPageLocalFans::take(20)->skip(($page - 1) * 20)->orderBy('likes_local_fans','DESC')->orderBy('likes','DESC');
            }else {
                $ranking = FacebookPage::take(20)->skip(($page - 1) * 20)->orderBy('likes','DESC')->orderBy('talking_about', 'DESC');
            }

            switch ($where)
            {
                case 'World':
                    $ranking->whereParent(0);
                    break;

                case 'Hispanic':
                    $ranking->whereIdiom('es')->whereParent(0);
                    break;

                default:
                    if(FacebookCountry::whereCode(strtolower($where))->first()){
                        $ranking->where('country_code', strtoupper($where));
                    }
                    else {
                        return 'Invalid method';
                    }
            }
            
            if($category != 'all'){
                $ranking->where('category_id', $category);
            }
            
            if($where != 'World' && $where != 'Hispanic'){
                $ranking = $ranking->get(['fb_id', 'username', 'name', 'is_verified', 'likes', 'likes_grow_7', 'talking_about', 'likes_local_fans']);
            }else {
                $ranking = $ranking->get(['fb_id', 'username', 'name', 'is_verified', 'likes', 'likes_grow_7', 'talking_about', 'country_code', 'first_country_code']);
            }
            
            $pages = array();
            
            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['position'] = ((($page - 1) * 20) + $key) + 1;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = $value['name'];
                $cache['picture'] = 'http://graph.facebook.com/'.$value['fb_id'].'/picture?height=50&type=normal&width=50';
                $cache['is_verified'] = $value['is_verified'];
                $cache['likes'] = $this->owloo_number_format($value['likes']);
                $cache['grow_7'] = (!empty($value['likes_grow_7'])?$value['likes_grow_7']:0);
                $cache['pta'] = 0;
                if($value['likes'] > 0)
                    $cache['pta'] = $this->owlooFormatPorcent($value['talking_about'], $value['likes']);
                
                if($where != 'World' && $where != 'Hispanic'){
                    $cache['likes_local_fans'] = $this->owloo_number_format($value['likes_local_fans']);
                }else {
                    $cache['country'] = strtolower((!empty($value['country_code'])?$value['country_code']:$value['first_country_code']));
                }
                
                //$var = 'rankingLikes'.$where.'PageCat' . $category . '_' . ((($page - 1) * 20) + $key);
                
                $var = 'rankingLikes' . $where . 'PageCat' . $category . '_' . (($page - 1) * 20);
                
                $pages[] = $cache;
                
                //Cache::put($var, $cache, 1440);
            }

            $var = 'rankingLikes' . $where . 'PageCat' . $category . '_' . (($page - 1) * 20);
            
            Cache::put($var, $pages, 1440);
            
        }

       /* $from = ($page - 1) * 20;
        $to = ($page * 20) - 1;
        $foo = array();
        
        for ($from; $from <= $to; $from++)
        {
            $var = 'rankingLikes'.$where.'PageCat' . $category . '_' . $from;
            $foo[$from] = Cache::get($var);
        }*/

        //return $foo;
        $var = 'rankingLikes' . $where . 'PageCat' . $category . '_' . (($page - 1) * 20);
        return Cache::get($var);
        
    }

    /*
    |
    | Get ranking of fanpages.
    |
    */
    public function getRankingPageGrow($idiom){
        
        $where = ucfirst($idiom);
        
        if (!Cache::has('rankingGrowLikes' . $where . 'Page0')) {
            
            $ranking = FacebookPage::take(4)->orderBy('likes_grow_30','DESC');

            switch ($where)
            {
                case 'All':
                    $ranking->whereParent(0);
                    break;

                case 'Es':
                    $ranking->whereIdiom('es')->whereParent(0);
                    break;
                    
                /*case 'It':
                    $ranking->whereIdiom('it')->whereParent(0);
                    break;*/
                
                default:
                    return 'Invalid method';
            }
            
            $ranking = $ranking->get(['fb_id', 'username', 'name', 'likes', 'likes_grow_30']);

            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = $value['name'];
                $cache['picture'] = 'https://graph.facebook.com/'.$value['fb_id'].'/picture?type=large';
                $cache['percent'] = 0;
                if($value['likes'] - $value['likes_grow_30'] > 0)
                    $cache['percent'] = $this->owlooFormatPorcent($value['likes_grow_30'], ($value['likes'] - $value['likes_grow_30']));
                $cache['grow_30'] = $this->owloo_number_format($value['likes_grow_30']);
                $var = 'rankingGrowLikes'.$where.'Page' . $key;
                Cache::put($var, $cache, 1440);
            }
        }

        $from = 0;
        $to = 3;
        $foo = array();

        for ($from; $from <= $to; $from++)
        {
            $var = 'rankingGrowLikes'.$where.'Page' . $from;
            $foo[$from] = Cache::get($var);
        }

        return $foo;
    }

    /*
    |
    | Get ranking of countries.
    |
    */
    public function getRankingCountry($idiom, $page = 1){
        
        $idiom = ucfirst($idiom);
        
        $var_cache = 'rankingCountries' . $idiom . '_' . (($page - 1) * 20);
        
        if (!Cache::has($var_cache)) {
            
            $ranking = FacebookCountry::take(20)->skip(($page - 1) * 20)->orderBy('total_user','DESC')->orderBy('audience_grow_30', 'DESC');

            switch ($idiom)
            {
                case 'World':
                    break;

                case 'Hispanic':
                    $ranking->whereIdiom('es');
                    break;

                default:
                    return 'Invalid method';
            }
            
            $ranking = $ranking->get(['code', 'name', 'name_en', 'abbreviation', 'total_user', 'total_female', 'total_male', 'audience_grow_90']);
            
            $countries = array();
            
            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['position'] = ((($page - 1) * 20) + $key) + 1;
                $cache['code'] = strtolower($value['code']);
                $cache['name'] = $value['name'];
                $cache['abbreviation'] = $value['abbreviation'];
                $cache['link'] = $this->convert_string_to_url($value['name_en']);
                $cache['total_user'] = $this->owloo_number_format($value['total_user']);
                $cache['total_female'] = array(
                                                'value' => $this->owloo_number_format($value['total_female']),
                                                'percent' => $this->owlooFormatPorcent($value['total_female'], $value['total_user'])
                                         );
                $cache['total_male'] = array(
                                                'value' => $this->owloo_number_format($value['total_male']),
                                                'percent' => $this->owlooFormatPorcent($value['total_male'], $value['total_user'])
                                         );
                $cache['grow_90'] = $this->formatGrow($value['audience_grow_90'], $value['total_user']);
                
                $countries[] = $cache;
                
            }
            
            Cache::put($var_cache, $countries, 1440);
            
        }
        
        return Cache::get($var_cache);
    }

    /*
    |
    | Get country details.
    |
    */
    public function getCountry($code){
        
        $var_cache = 'facebookCountry'.$code;
        
        if (!Cache::has($var_cache)) {
            
            $data = FacebookCountry::whereCode($code)->first();
            
            if($data){
                
                $country['id_country'] = $data['id_country'];
                
                $country['code'] = strtolower($data['code']);
                
                $country['name'] = $data['name'];
                
                $country['abbreviation'] = $data['abbreviation'];
                
                $country['link'] = $this->convert_string_to_url($data['name_en']);
                
                $country['total_user'] = $this->owloo_number_format($data['total_user']);
                
                $country['total_female'] = array(
                                                'value' => $this->owloo_number_format($data['total_female']),
                                                'percent' => $this->owlooFormatPorcent($data['total_female'], $data['total_user'])
                                         );
                
                $country['total_male'] = array(
                                                'value' => $this->owloo_number_format($data['total_male']),
                                                'percent' => $this->owlooFormatPorcent($data['total_male'], $data['total_user'])
                                         );
                
                $country['audience_history'] = json_decode($data['audience_history'], true);
                
                $country['audience_grow']['grow_1'] = $this->formatGrow($data['audience_grow_1'], $data['total_user']);
                $country['audience_grow']['grow_7'] = $this->formatGrow($data['audience_grow_7'], $data['total_user']);
                $country['audience_grow']['grow_30'] = $this->formatGrow($data['audience_grow_30'], $data['total_user']);
                $country['audience_grow']['grow_60'] = $this->formatGrow($data['audience_grow_60'], $data['total_user']);
                $country['audience_grow']['grow_90'] = $this->formatGrow($data['audience_grow_90'], $data['total_user']);
                $country['audience_grow']['grow_180'] = $this->formatGrow($data['audience_grow_180'], $data['total_user']);
                
                $country['general_ranking'] = $data['general_ranking'];
                
                /***** Cities *****/
                $country['cities']['supports'] = $data['supports_city'];
                $country['cities']['items'] = array();
                
                $cities = FacebookCity::whereCountryCode($country['code'])->take(5)->orderBy('total_user','DESC')->get(['name', 'total_user']);
                foreach ($cities as $city) {
                    $country['cities']['items'][] = array(
                                                        'name' => $city['name'],
                                                        'total_user' => $this->owloo_number_format($city['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($city['total_user'], $data['total_user'])
                                                );
                }
                
                /***** Regions *****/
                $country['regions']['supports'] = $data['supports_region'];
                $country['regions']['items'] = array();
                
                $regions = FacebookRegion::whereCountryCode($country['code'])->take(5)->orderBy('total_user','DESC')->get(['name', 'total_user']);
                foreach ($regions as $region) {
                    $country['regions']['items'][] = array(
                                                        'name' => $region['name'],
                                                        'total_user' => $this->owloo_number_format($region['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($region['total_user'], $data['total_user'])
                                                );
                }
                
                /***** Ages *****/
                $country['ages']['items'] = array();
                
                $ages = FacebookCountryAge::whereCountryCode($country['code'])->orderBy('total_user','DESC')->get(['name', 'total_user', 'total_female', 'total_male', 'grow_30']);
                foreach ($ages as $age) {
                    $country['ages']['items'][] = array(
                                                        'name' => $age['name'],
                                                        'total_user' => $this->owloo_number_format($age['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($age['total_user'], $data['total_user'])
                                                );
                    
                }
                
                //Get max values
                foreach (array('user', 'female', 'male') as $value) {
                    $country['ages']['max_'.$value] = array();
                    $age_max_total = FacebookCountryAge::whereCountryCode($country['code'])->orderBy('total_'.$value,'DESC')->orderBy('id','ASC')->first(['name', 'total_'.$value]);
                    if(isset($age_max_total['name'])){
                        $country['ages']['max_'.$value] = array(
                                                                'name' => $age_max_total['name'],
                                                                'value' => $this->owloo_number_format($age_max_total['total_'.$value]),
                                                                'percent' => $this->owlooFormatPorcent($age_max_total['total_'.$value], $data['total_user'])
                                                          );
                    }
                }
                
                //Get Trend-up
                $country['ages']['trend_up'] = array();
                $age_grow = FacebookCountryAge::whereCountryCode($country['code'])->orderBy('grow_30','DESC')->orderBy('id','ASC')->first(['name', 'total_user', 'grow_30']);
                if(isset($age_grow['name']) && $age_grow['grow_30'] > 0){
                    $country['ages']['trend_up'] = array(
                                                          'name' => $age_grow['name'],
                                                          'grow' => $this->formatGrow($age_grow['grow_30'], $age_grow['total_user'])
                                                   );
                }
                
                /***** Languages *****/
                $country['languages']['items'] = array();
                
                $languages = FacebookCountryLanguage::whereCountryCode($country['code'])->orderBy('total_user','DESC')->get(['name', 'total_user', 'grow_30']);
                foreach ($languages as $language) {
                    $country['languages']['items'][] = array(
                                                        'name' => $language['name'],
                                                        'total_user' => $this->owloo_number_format($language['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($language['total_user'], $data['total_user'])
                                                );
                    
                }
                
                //Get the Trend-up
                $country['languages']['trend_up'] = array();
                $language_grow = FacebookCountryLanguage::whereCountryCode($country['code'])->orderBy('grow_30','DESC')->orderBy('total_user','DESC')->first(['name', 'total_user', 'grow_30']);
                if(isset($language_grow['name']) && $language_grow['grow_30'] > 0){
                    $country['languages']['trend_up'] = array(
                                                          'name' => $language_grow['name'],
                                                          'grow' => $this->formatGrow($language_grow['grow_30'], $language_grow['total_user'])
                                                   );
                }

                
                /***** Relationships *****/
                $country['relationships']['items'] = array();
                
                $relationships = FacebookCountryRelationship::whereCountryCode($country['code'])->orderBy('total_user','DESC')->get(['name', 'total_user', 'total_user_grow_30', 'total_female_grow_30', 'total_male_grow_30']);
                foreach ($relationships as $relationship) {
                    $country['relationships']['items'][] = array(
                                                        'name' => $relationship['name'],
                                                        'total_user' => $this->owloo_number_format($relationship['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($relationship['total_user'], $data['total_user'])
                                                );
                    
                }

                //Get Trend-up
                foreach (array('user', 'female', 'male') as $value) {
                    $country['relationships']['trend_up_'.$value] = array();
                    $relationship_grow = FacebookCountryRelationship::whereCountryCode($country['code'])->orderBy('total_'.$value.'_grow_30','DESC')->orderBy('total_user','DESC')->first(['name', 'total_'.$value, 'total_'.$value.'_grow_30']);
                    if(isset($relationship_grow['name']) && $relationship_grow['total_'.$value.'_grow_30'] > 0){
                        $country['relationships']['trend_up_'.$value] = array(
                                                              'name' => $relationship_grow['name'],
                                                              'grow' => $this->formatGrow($relationship_grow['total_'.$value.'_grow_30'], $relationship_grow['total_'.$value])
                                                       );
                    }
                }
                
                /***** Interests *****/
                $country['interests']['items'] = array();
                $interests = FacebookCountryInterest::whereCountryCode($country['code'])->whereNivel(1)->orderBy('total_user','DESC')->get(['id_interest', 'name', 'total_user', 'total_female', 'total_male', 'grow_30']);
                $count_nivel_1 = 0;
                foreach ($interests as $interest) {
                    $country['interests']['items'][$count_nivel_1] = array(
                                                        'name' => $interest['name'],
                                                        'total_user' => $this->owloo_number_format($interest['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($interest['total_user'], $data['total_user']),
                                                        'items' => array()
                                                );
                    
                    $interests_childrens_1 = FacebookCountryInterest::whereCountryCode($country['code'])->whereNivelSuperior($interest['id_interest'])->orderBy('total_user','DESC')->take(5)->get(['id_interest', 'name', 'total_user', 'total_female', 'total_male', 'grow_30']);
                    $count_nivel_2 = 0;
                    foreach ($interests_childrens_1 as $children_1) {
                        $country['interests']['items'][$count_nivel_1]['items'][$count_nivel_2] = array(
                                                        'name' => $children_1['name'],
                                                        'total_user' => $this->owloo_number_format($children_1['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($children_1['total_user'], $data['total_user']),
                                                        'items' => array()
                                                );
                                                
                        $interests_childrens_2 = FacebookCountryInterest::whereCountryCode($country['code'])->whereNivelSuperior($children_1['id_interest'])->orderBy('total_user','DESC')->take(5)->get(['id_interest', 'name', 'total_user', 'total_female', 'total_male', 'grow_30']);
                        foreach ($interests_childrens_2 as $children_2) {
                            $country['interests']['items'][$count_nivel_1]['items'][$count_nivel_2]['items'][] = array(
                                                            'name' => $children_2['name'],
                                                            'total_user' => $this->owloo_number_format($children_2['total_user']),
                                                            'percent' => $this->owlooFormatPorcent($children_2['total_user'], $data['total_user'])
                                                    );
                        }                        
                                                
                        $count_nivel_2++;
                    }
                    
                    $count_nivel_1++;
                }
                
                //Get the Trend-up
                $country['interests']['trend_up'] = array();
                $interest_grow = FacebookCountryInterest::whereCountryCode($country['code'])->whereNivel(1)->orderBy('grow_30','DESC')->orderBy('total_user','DESC')->first(['name', 'total_user', 'grow_30']);
                if(isset($interest_grow['name']) && $interest_grow['grow_30'] > 0){
                    $country['interests']['trend_up'] = array(
                                                          'name' => $interest_grow['name'],
                                                          'grow' => $this->formatGrow($interest_grow['grow_30'], $interest_grow['total_user'])
                                                   );
                }
                
                /***** Comportamientos *****/
                $country['comportamientos']['items'] = array();
                $comportamientos = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereNivel(1)->orderBy('total_user','DESC')->get(['id_comportamiento', 'name', 'total_user', 'total_female', 'total_male', 'grow_30']);
                $count_nivel_1 = 0;
                foreach ($comportamientos as $comportamiento) {
                    $country['comportamientos']['items'][$count_nivel_1] = array(
                                                        'name' => $comportamiento['name'],
                                                        'total_user' => $this->owloo_number_format($comportamiento['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($comportamiento['total_user'], $data['total_user']),
                                                        'items' => array()
                                                );
                    
                    $comportamientos_childrens_1 = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereNivelSuperior($comportamiento['id_comportamiento'])->orderBy('total_user','DESC')->take(5)->get(['id_comportamiento', 'name', 'total_user', 'total_female', 'total_male', 'grow_30']);
                    $count_nivel_2 = 0;
                    foreach ($comportamientos_childrens_1 as $children_1) {
                        $country['comportamientos']['items'][$count_nivel_1]['items'][$count_nivel_2] = array(
                                                        'name' => $children_1['name'],
                                                        'total_user' => $this->owloo_number_format($children_1['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($children_1['total_user'], $data['total_user']),
                                                        'items' => array()
                                                );
                                                
                        $comportamientos_childrens_2 = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereNivelSuperior($children_1['id_comportamiento'])->orderBy('total_user','DESC')->take(5)->get(['id_comportamiento', 'name', 'total_user', 'total_female', 'total_male', 'grow_30']);
                        $count_nivel_3 = 0;
                        foreach ($comportamientos_childrens_2 as $children_2) {
                            $country['comportamientos']['items'][$count_nivel_1]['items'][$count_nivel_2]['items'][$count_nivel_3] = array(
                                                            'name' => $children_2['name'],
                                                            'total_user' => $this->owloo_number_format($children_2['total_user']),
                                                            'percent' => $this->owlooFormatPorcent($children_2['total_user'], $data['total_user'])
                                                    );
                             
                            $comportamientos_childrens_3 = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereNivelSuperior($children_2['id_comportamiento'])->orderBy('total_user','DESC')->take(5)->get(['id_comportamiento', 'name', 'total_user', 'total_female', 'total_male', 'grow_30']);
                            foreach ($comportamientos_childrens_3 as $children_3) {
                                $country['comportamientos']['items'][$count_nivel_1]['items'][$count_nivel_2]['items'][$count_nivel_3]['items'][] = array(
                                                                'name' => $children_3['name'],
                                                                'total_user' => $this->owloo_number_format($children_3['total_user']),
                                                                'percent' => $this->owlooFormatPorcent($children_3['total_user'], $data['total_user'])
                                                        );
                            }
                             
                            $count_nivel_3++;
                        }                        
                                                
                        $count_nivel_2++;
                    }
                    
                    $count_nivel_1++;
                }
                
                //Get the Trend-up
                $country['comportamientos']['trend_up'] = array();
                $comportamiento_grow = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereNivel(1)->orderBy('grow_30','DESC')->orderBy('total_user','DESC')->first(['name', 'total_user', 'grow_30']);
                if(isset($comportamiento_grow['name']) && $comportamiento_grow['grow_30'] > 0){
                    $country['comportamientos']['trend_up'] = array(
                                                          'name' => $comportamiento_grow['name'],
                                                          'grow' => $this->formatGrow($comportamiento_grow['grow_30'], $comportamiento_grow['total_user'])
                                                   );
                }
                
                
                print_r($country); die();
                
                Cache::put($var_cache, $country, 1440);
            }
            else {
                return 'Invalid method';
            }
        }
        
        return Cache::get($var_cache);

    }

    private function owloo_number_format($value, $separador = '.', $decimal_separator = ',', $decimal_count = 0){
        return str_replace(' ', '&nbsp;', number_format($value, $decimal_count, $decimal_separator, $separador));
    }
    
    private function owlooFormatPorcent($number, $total, $decimal = 2, $sep_decimal = ',', $sep_miles = '.'){
        if($total == 0)
            return 0;
        
        $aux_result = ($number * 100 / $total);
        $aux_decimal = $decimal;
        
        if($aux_result < 0.001){
            $aux_decimal = 4;
        }
        elseif($aux_result < 0.01){
            $aux_decimal = 3;
        }
        
        return number_format(round(($number * 100 / $total), $aux_decimal), $aux_decimal, $sep_decimal, $sep_miles);
    }
    
    private function convert_string_to_url($text){
        $caracteresEspeciales = array('á', 'é', 'í', 'ó', 'ú', 'ñ', ' ', '?', ',', '.', '(', ')', 'Å');
        $caracteresReemplazo = array('a', 'e', 'i', 'o', 'u', 'n', '-', '', '', '', '', '', 'a');
        return str_ireplace($caracteresEspeciales, $caracteresReemplazo,  strtolower($text));
    }
    
    public function getRankingPageDev($where, $category, $page = 1)
    {
        $where = ucfirst($where);
        
        if (!Cache::has('rankingLikes' . $where . 'PageCat' . $category . '_' . (($page - 1) * 20))) {
            
            if($where != 'World' && $where != 'Hispanic'){
                $ranking = FacebookPageLocalFans::take(20)->skip(($page - 1) * 20)->orderBy('likes_local_fans','DESC')->orderBy('likes','DESC');
            }else {
                $ranking = FacebookPage::take(20)->skip(($page - 1) * 20)->orderBy('likes','DESC')->orderBy('talking_about', 'DESC');
            }

            switch ($where)
            {
                case 'World':
                    $ranking->whereParent(0);
                    break;

                case 'Hispanic':
                    $ranking->whereIdiom('es')->whereParent(0);
                    break;

                default:
                    if(FacebookPage::whereCode(strtolower($where))){
                        $ranking->where('country_code', strtoupper($where));
                    }
                    else {
                        return 'Invalid method';
                    }
            }
            
            if($category != 'all' && is_numeric($category)){
                $ranking->where('Category_id', $category);
            }
            
            if($where != 'World' && $where != 'Hispanic'){
                $ranking = $ranking->get(['fb_id', 'username', 'name', 'is_verified', 'likes', 'likes_grow_7', 'talking_about', 'likes_local_fans']);
            }else {
                $ranking = $ranking->get(['fb_id', 'username', 'name', 'is_verified', 'likes', 'likes_grow_7', 'talking_about', 'country_code', 'first_country_code']);
            }
            
            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['position'] = ((($page - 1) * 20) + $key) + 1;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = $value['name'];
                $cache['picture'] = 'http://graph.facebook.com/'.$value['fb_id'].'/picture?height=50&type=normal&width=50';
                $cache['is_verified'] = $value['is_verified'];
                $cache['likes'] = $this->owloo_number_format($value['likes']);
                $cache['grow_7'] = $value['likes_grow_7'];
                $cache['pta'] = 0;
                if($value['likes'] > 0)
                    $cache['pta'] = $this->owlooFormatPorcent($value['talking_about'], $value['likes']);
                
                if($where != 'World' && $where != 'Hispanic'){
                    $cache['likes_local_fans'] = $this->owloo_number_format($value['likes_local_fans']);
                }else {
                    $cache['country'] = strtolower((!empty($value['country_code'])?$value['country_code']:$value['first_country_code']));
                }
                
                $var = 'rankingLikes'.$where.'PageCat' . $category . '_' . ((($page - 1) * 20) + $key);
                Cache::put($var, $cache, 1440);
            }
        }

        $from = ($page - 1) * 20;
        $to = ($page * 20) - 1;
        $foo = array();

        for ($from; $from <= $to; $from++)
        {
            $var = 'rankingLikes'.$where.'PageCat' . $category . '_' . $from;
            $foo[$from] = Cache::get($var);
        }

        return $foo;
        
    }

    private function getTotalCountries()
    {
        if (!Cache::has('totalCountries')) {
            $data = FacebookCountry::count();
            Cache::put('totalCountries', $data, 1440);
        }
        return Cache::get('totalCountries');
    }

    public function getCountries()
    {

        // Cache::flush();

        if (!Cache::has('listCountries')) {

            $countries = FacebookCountry::orderBy('name','ASC')->get(['id_country', 'name', 'code', 'abbreviation']);
            $cache = array();

            foreach ($countries as $value)
            {
                $cache[strtolower($value['code'])]['id'] = $value['id_country'];
                $cache[strtolower($value['code'])]['name'] = $value['name'];
                $cache[strtolower($value['code'])]['abbreviation'] = $value['abbreviation'];
            }

            Cache::put('listCountries', $cache, 1440);

        }

        return Cache::get('listCountries');
    }

    private function getTotalCategory()
    {
        if (!Cache::has('totalFacebookCategories')) {
            $data = FacebookCategory::count();
            Cache::put('totalFacebookCategories', $data, 1440);
        }
        return Cache::get('totalFacebookCategories');
    }

    public function getCategories()
    {
        Cache::flush();
        if (!Cache::has('listFacebookCategories0')) {

            $ranking = FacebookCategory::orderBy('category','ASC')->get();

            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['id_category'] = $value['id_category'];
                $cache['name'] = $value['category'];
                $cache['tag'] =  str_replace([' ', 'á','é','í','ó','ú'], ['_','a','e','i','o','u'], strtolower($value['category']));
                $var = 'listFacebookCategories' . $key;
                Cache::put($var, $cache, 1440);
            }
        }
        
        $foo = array();
        $limit = $this->getTotalCategory();

        for($i = 0; $i < $limit; $i++)
        {
            $var = 'listFacebookCategories' . $i;
            $foo[$i] = Cache::get($var);
        }

        return $foo;
    }

}