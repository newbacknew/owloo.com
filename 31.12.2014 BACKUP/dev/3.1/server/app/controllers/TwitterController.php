<?php

class TwitterController extends BaseController {

    /*
    |
    | Total Profiles
    |
    */
    public function getTotalProfile($where)
    {
        $var_cache = 'total'.ucfirst($where).'TwitterProfiles';

        if (!Cache::has($var_cache)) {
            
            switch ($where)
            {
                case 'world':
                    $data['total'] = TwitterProfile::count();
                    break;

                case 'hispanic':
                    $data['total'] = TwitterProfile::whereIdiom('es')->count();
                    break;

                default:
                    $country_idiom = FacebookCountry::whereIdiom(strtolower($where))->first();
                    if ($country_idiom) {
                        $data['total'] = TwitterProfile::whereIdiom(strtolower($where))->count();
                    }
                    else {
                        return 'Invalid method';
                    }
            }

            Cache::put($var_cache, $data, 1440);
        }

        return Cache::get($var_cache);
    }
    
    /*
    |
    | Get Profile
    |
    */
    public function getProfile($screen_name){
        
        //if (!Cache::has('twitterProfile'.$screen_name)) {
            if (true) {
            
            $data = TwitterProfile::whereScreenName($screen_name)->first();
            
            if($data){
                
                $profile = NULL;
                
                $profile['screen_name'] = strtolower($data['screen_name']);
                
                $profile['name'] = $data['name'];
                
                $profile['description'] = $data['description'];
                
                $profile['picture'] = str_replace('_normal.', '.', $data['picture']);
                
                $profile['cover'] = $data['cover'];
                
                $profile['is_verified'] = $data['is_verified'];
                
                $profile['location'] = (!empty($data['location'])?$data['location']:'n/a');
                
                $profile['idiom'] = $this->getIdiom($data['idiom']);
                
                $auxformat = explode("-", $data['in_twitter_from']);
                $year = $auxformat[0];
                $day = $auxformat[2];
                $month = strtoupper($this->getMonth($auxformat[1], 'large'));
                $profile['in_twitter_from'] = substr($month,0,3).substr($year,2,2);
                
                $auxformat = explode("-", $data['in_owloo_from']);
                $year = $auxformat[0];
                $day = $auxformat[2];
                $month = strtoupper($this->getMonth($auxformat[1], 'large'));
                $profile['in_owloo_from'] = substr($month,0,3).substr($year,2,2);
                
                $profile['followers_count'] = $this->owloo_number_format($data['followers_count']);
                $profile['followers_count_str'] = $this->owloo_number_format_str($data['followers_count']);
                $profile['following_count'] = $this->owloo_number_format($data['following_count']);
                $profile['following_count_str'] = $this->owloo_number_format_str($data['following_count']);
                $profile['tweet_count'] = $this->owloo_number_format($data['tweet_count']);
                $profile['tweet_count_str'] = $this->owloo_number_format_str($data['tweet_count']);
                
                $profile['general_ranking'] = $this->owloo_number_format($data['general_ranking']);
                
                $charts = json_decode($data['charts'], true);
                
                $charts = array(
                                'followers' => $this->owloo_chart_data_format($charts['followers'], true),
                                'daily_followers_grow' => $this->owloo_chart_data_format($charts['daily_followers_grow'], true),
                                'daily_following_grow' => $this->owloo_chart_data_format($charts['daily_following_grow'], true),
                                'tweets_made_by_day' => $this->owloo_chart_data_format($charts['tweets_made_by_day'], true),
                                'tweets_made_by_day_of_the_week' => $this->owloo_chart_data_format($charts['tweets_made_by_day_of_the_week'], true)
                );
                
                for ($i = 0; $i < count($charts['tweets_made_by_day_of_the_week']['x_axis']); $i++) {
                    $charts['tweets_made_by_day_of_the_week']['data'][] = array(
                                                                                'name' => $charts['tweets_made_by_day_of_the_week']['x_axis'][$i],
                                                                                'value' => $this->owloo_number_format($charts['tweets_made_by_day_of_the_week']['series_data'][$i])
                                                                             );
                }
                
                $profile['charts'] = $charts;
                
                $cache['average_growth'] = $this->owloo_number_format($data['average_growth']);
                
                $profile['followers_accumulate_down_30'] = $this->owloo_number_format($data['accumulate_down_30']);
                $profile['followers_grow']['grow_1'] = $this->formatGrow($data['followers_grow_1'], $data['followers_count']);
                $profile['followers_grow']['grow_1'] = $this->formatGrow($data['followers_grow_1'], $data['followers_count']);
                $profile['followers_grow']['grow_7'] = $this->formatGrow($data['followers_grow_7'], $data['followers_count']);
                $profile['followers_grow']['grow_15'] = $this->formatGrow($data['followers_grow_15'], $data['followers_count']);
                $profile['followers_grow']['grow_30'] = $this->formatGrow($data['followers_grow_30'], $data['followers_count']);
                
                $most_mentions = json_decode($data['most_mentions'], true);
                $profile['most_hashtags'] = json_decode($data['most_hashtags'], true);
                
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
                
                $profile['klout'] = json_decode($data['klout'], true);
                
                $profile['ff_ratio']['value'] = 0;
                $profile['ff_ratio']['max_value'] = 100;
                if($data['following_count'] > 0){
                    $profile['ff_ratio']['value'] = $this->owlooFormatPorcent($data['followers_count'] / $data['following_count'], NULL, 2, '.', '');
                    if($profile['ff_ratio']['value'] > 100){
                        $profile['ff_ratio']['max_value'] = $profile['ff_ratio']['value'];
                    }
                }
                
                Cache::put('twitterProfile'.$screen_name, $profile, 1440);
            }
            else {
                return 'Invalid method';
            }
        }
        
        return Cache::get('twitterProfile'.$screen_name);

    }
    
    /*
    |
    | Ranking Profiles
    |
    */
    public function getRankingProfile($idiom, $page = 1)
    {

        $idiom = ucfirst($idiom);
        
        $var_cache = 'rankingTwitterProfiles' . $idiom . '_' . (($page - 1) * 20);
        
        //if (!Cache::has($var_cache)) {
        if (true) {
            
            $ranking = TwitterProfile::take(20)->skip(($page - 1) * 20)->orderBy('followers_count','DESC')->orderBy('followers_grow_30', 'DESC');
            
            switch ($idiom)
            {
                case 'World':
                    break;

                case 'Hispanic':
                    $ranking->whereIdiom('es');
                    break;

                default:
                    $country_idiom = FacebookCountry::whereIdiom(strtolower($idiom))->first();
                    if ($country_idiom) {
                        $ranking->whereIdiom(strtolower($idiom));
                    }
                    else {
                        return 'Invalid method';
                    }
            }
            
            $ranking = $ranking->get(['screen_name', 'name', 'picture', 'is_verified', 'followers_count', 'following_count', 'tweet_count', 'average_growth', 'followers_grow_30']);
            
            $profiles = array();
            
            foreach ($ranking as $key => $value)
            {
                
                $cache = NULL;
                $cache['position'] = ((($page - 1) * 20) + $key) + 1;
                $cache['username'] = strtolower($value['screen_name']);
                $cache['name'] = $value['name'];
                $cache['picture'] = str_replace('_normal.', '_bigger.', $value['picture']);
                $cache['is_verified'] = $value['is_verified'];
                
                //$cache['average_growth'] = $this->owloo_number_format($value['average_growth']);
                
                /*$ff_ratio = 0;
                if($value['following_count'] > 0)
                    $ff_ratio = $this->owlooFormatPorcent($value['followers_count'] / $value['following_count']);*/
                
                //Ratio
                //$cache['second_column'][] = array('value' => $ff_ratio.'%', 'class' => '');
                //Mes
                
                $aux_num = $this->formatGrow($value['followers_grow_30'], $value['followers_count']);
                $cache['second_column'][] = array('value' => $aux_num['value'], 'class' => $aux_num['class']);
                //Tweets
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['tweet_count']), 'class' => '');
                //Siguiendo
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['following_count']), 'class' => '');
                //Seguidores
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['followers_count']), 'class' => '');
                
                $profiles[] = $cache;
                
            }

            $second_column = array('Mes', 'Tweet', 'Siguiendo', 'Seguidores');
            $array_result = array(
                                    'type' => 'fb_page',
                                    'subtype' => 'tw_profile',
                                    'main_column' => 'Cuenta',
                                    'second_column' => $second_column,
                                    'large_column' => 3,
                                    'link' => 'twitter-analytics/profiles',
                                    'items' => $profiles
                            );
            
            Cache::put($var_cache, $array_result, 1440);
            
        }
        
        return Cache::get($var_cache);
        
    }

    /*
    |
    | Get ranking of fanpages grow.
    |
    */
    public function getRankingProfileGrow($idiom, $limit = 4){
        
        $idiom = ucfirst($idiom);
        
        $var_cache = 'rankingGrowTwitterProfile' . $idiom . $limit;
        
        if (!Cache::has($var_cache)) {
            
            $ranking = TwitterProfile::take($limit)->orderBy('followers_grow_30','DESC');

            switch ($idiom)
            {
                case 'All':
                    break;
                
                default:
                    $country_idiom = FacebookCountry::whereIdiom(strtolower($idiom))->first();
                    if ($country_idiom) {
                        $ranking->whereIdiom(strtolower($idiom));
                    }
                    else {
                        return 'Invalid method';
                    }
            }
            
            $ranking = $ranking->get(['screen_name', 'name', 'picture', 'followers_count', 'followers_grow_30']);
            
            $pages = array();
            
            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['screen_name'] = strtolower($value['screen_name']);
                $cache['name'] = $value['name'];
                $cache['picture'] = str_replace('_normal.', '.', $value['picture']);
                $cache['percent'] = 0;
                if($value['followers_count'] - $value['followers_grow_30'] > 0)
                    $cache['percent'] = $this->owlooFormatPorcent($value['followers_grow_30'], ($value['followers_count'] - $value['followers_grow_30']));
                $cache['grow_30'] = $this->owloo_number_format($value['followers_grow_30']);
                
                $pages[] = $cache;
            }
            
            Cache::put($var_cache, $pages, 1440);
            
        }

        return Cache::get($var_cache);
    }

    /*
    |
    | Get last profiles added.
    |
    */
    public function getLastProfileAdded($idiom, $limit = 4){
        
        $idiom = ucfirst($idiom);
        
        $var_cache = 'lastTwitterProfileAdded' . $idiom . $limit;
        
        if (!Cache::has($var_cache)) {
            
            $ranking = TwitterProfile::take($limit)->orderBy('id','DESC');

            switch ($idiom)
            {
                case 'All':
                    break;
                
                default:
                    $country_idiom = FacebookCountry::whereIdiom(strtolower($idiom))->first();
                    if ($country_idiom) {
                        $ranking->whereIdiom(strtolower($idiom));
                    }
                    else {
                        return 'Invalid method';
                    }
            }
            
            $ranking = $ranking->get(['screen_name', 'name', 'picture']);
            
            $pages = array();
            
            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['screen_name'] = strtolower($value['screen_name']);
                $cache['name'] = $value['name'];
                $cache['picture'] = str_replace('_normal.', '.', $value['picture']);
                
                $pages[] = $cache;
            }
            
            Cache::put($var_cache, $pages, 1440);
            
        }

        return Cache::get($var_cache);
    }

    private function owloo_number_format($value, $separador = '.', $decimal_separator = ',', $decimal_count = 0){
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
            return array(
                    'value' => 'n/a',
                    'percent' => NULL,
                    'class' => 'not-available' 
                );
        
        $percent = 0;
        if($current - $grow > 0)
            $percent = $this->owlooFormatPorcent($grow, ($current - $grow));
        
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
    
    private function get_url_content($url){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_URL, $url);
          $data = curl_exec($ch);
          curl_close($ch);
          return $data;
    }
    
    private function getIdiom($idiom){
        $original_idiom = $idiom;
        $aux_idiom = explode('-', $idiom);
        $idiom = $aux_idiom[0];
        
        $language_codes = array(
        'en' => 'Inglés' ,
        'aa' => 'Afar' ,
        'ab' => 'Abkhazian' ,
        'af' => 'Afrikaans' ,
        'am' => 'Amharic' ,
        'ar' => 'Árabe' ,
        'as' => 'Assamese' ,
        'ay' => 'Aymara' ,
        'az' => 'Azerbaijani' ,
        'ba' => 'Bashkir' ,
        'be' => 'Byelorussian' ,
        'bg' => 'Búlgaro' ,
        'bh' => 'Bihari' ,
        'bi' => 'Bislama' ,
        'bn' => 'Bengali/Bangla' ,
        'bo' => 'Tibetan' ,
        'br' => 'Breton' ,
        'ca' => 'Catalán' ,
        'co' => 'Corsican' ,
        'cs' => 'Checo' ,
        'cy' => 'Welsh' ,
        'da' => 'Danés' ,
        'de' => 'Alemán' ,
        'dz' => 'Bhutani' ,
        'el' => 'Greek' ,
        'eo' => 'Esperanto' ,
        'es' => 'Español' ,
        'et' => 'Estonian' ,
        'eu' => 'Basque' ,
        'fa' => 'Persian' ,
        'fi' => 'Finnish' ,
        'fj' => 'Fiji' ,
        'fo' => 'Faeroese' ,
        'fr' => 'Francés' ,
        'fy' => 'Frisian' ,
        'ga' => 'Irish' ,
        'gd' => 'Scots/Gaelic' ,
        'gl' => 'Gallego' ,
        'gn' => 'Guaraní' ,
        'gu' => 'Gujarati' ,
        'ha' => 'Hausa' ,
        'hi' => 'Hindi' ,
        'hr' => 'Croatian' ,
        'hu' => 'Hungarian' ,
        'hy' => 'Armenian' ,
        'ia' => 'Interlingua' ,
        'ie' => 'Interlingue' ,
        'ik' => 'Inupiak' ,
        'in' => 'Indonesio' ,
        'is' => 'Icelandic' ,
        'it' => 'Italiano' ,
        'iw' => 'Hebrew' ,
        'ja' => 'Japonés' ,
        'ji' => 'Yiddish' ,
        'jw' => 'Javanese' ,
        'ka' => 'Georgian' ,
        'kk' => 'Kazakh' ,
        'kl' => 'Greenlandic' ,
        'km' => 'Cambodian' ,
        'kn' => 'Kannada' ,
        'ko' => 'Coreano' ,
        'ks' => 'Kashmiri' ,
        'ku' => 'Kurdish' ,
        'ky' => 'Kirghiz' ,
        'la' => 'Latino' ,
        'ln' => 'Lingala' ,
        'lo' => 'Laothian' ,
        'lt' => 'Lithuanian' ,
        'lv' => 'Latvian/Lettish' ,
        'mg' => 'Malagasy' ,
        'mi' => 'Maori' ,
        'mk' => 'Macedonian' ,
        'ml' => 'Malayalam' ,
        'mn' => 'Mongolian' ,
        'mo' => 'Moldavian' ,
        'mr' => 'Marathi' ,
        'ms' => 'Malay' ,
        'mt' => 'Maltese' ,
        'my' => 'Burmese' ,
        'na' => 'Nauru' ,
        'ne' => 'Nepali' ,
        'nl' => 'Holandés' ,
        'no' => 'Norwegian' ,
        'oc' => 'Occitan' ,
        'om' => '(Afan)/Oromoor/Oriya' ,
        'pa' => 'Punjabi' ,
        'pl' => 'Polaco' ,
        'ps' => 'Pashto/Pushto' ,
        'pt' => 'Portugués' ,
        'qu' => 'Quechua' ,
        'rm' => 'Rhaeto-Romance' ,
        'rn' => 'Kirundi' ,
        'ro' => 'Romanian' ,
        'ru' => 'Ruso' ,
        'rw' => 'Kinyarwanda' ,
        'sa' => 'Sanskrit' ,
        'sd' => 'Sindhi' ,
        'sg' => 'Sangro' ,
        'sh' => 'Serbo-Croatian' ,
        'si' => 'Singhalese' ,
        'sk' => 'Slovak' ,
        'sl' => 'Slovenian' ,
        'sm' => 'Samoan' ,
        'sn' => 'Shona' ,
        'so' => 'Somali' ,
        'sq' => 'Albanian' ,
        'sr' => 'Serbian' ,
        'ss' => 'Siswati' ,
        'st' => 'Sesotho' ,
        'su' => 'Sundanese' ,
        'sv' => 'Sueco' ,
        'sw' => 'Swahili' ,
        'ta' => 'Tamil' ,
        'te' => 'Tegulu' ,
        'tg' => 'Tajik' ,
        'th' => 'Thai' ,
        'ti' => 'Tigrinya' ,
        'tk' => 'Turkmen' ,
        'tl' => 'Tagalog' ,
        'tn' => 'Setswana' ,
        'to' => 'Tonga' ,
        'tr' => 'Turco' ,
        'ts' => 'Tsonga' ,
        'tt' => 'Tatar' ,
        'tw' => 'Twi' ,
        'uk' => 'Ukrainian' ,
        'ur' => 'Urdu' ,
        'uz' => 'Uzbek' ,
        'vi' => 'Vietnamese' ,
        'vo' => 'Volapuk' ,
        'wo' => 'Wolof' ,
        'xh' => 'Xhosa' ,
        'yo' => 'Yoruba' ,
        'zh' => 'Chino' ,
        'zu' => 'Zulu' ,
        );
        
        if(isset($language_codes[$idiom])){
            return $language_codes[$idiom];
        }
        elseif(empty($idiom)){
            return 'n/a';
        }
        return $original_idiom;
        
    }

}