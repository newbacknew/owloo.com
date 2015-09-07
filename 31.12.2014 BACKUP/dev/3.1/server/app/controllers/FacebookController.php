<?php

class FacebookController extends BaseController {

    public function getTotalUser($who) {
        
        $who = strtolower($who);

        $var_cache = 'total' . ucfirst($who) . 'FacebookUserCountries';

        if (!Cache::has($var_cache)) {
            switch ($who) {
                
                case 'all':
                        $data['total'] = FacebookCountry::sum('total_user');
                        break;
                
                case 'women':
                        $data['total'] = FacebookCountry::sum('total_female');
                        break;
                
                case 'men':
                        $data['total'] = FacebookCountry::sum('total_male');
                        break;
                        
                case 'grow':
                        $total_user = FacebookCountry::sum('total_user');
                        $audience_grow_90 = FacebookCountry::sum('audience_grow_90');
                        $data['grow'] = $this->formatGrow($audience_grow_90, $total_user);
                        break;
                        
                default:
                    return 'Invalid method';
            }

            if($who != 'grow') {
                $data['total'] = $this->owloo_number_format($data['total']);
            }
            
            Cache::put($var_cache, $data, 1440);
            
        }
        
        return Cache::get($var_cache);
    }
    
    public function getAverageCPC() {
        return '4.44 - 5.55';
    }

    /*
    |
    | Get total amount of registered fanpages on owloo.
    |
    */
    public function getTotalPage($where, $category = 'all') {
        
        $where = strtolower($where);
        
        $var_cache = 'total' . ucfirst($where) . ucfirst($category) . 'FacebookPages';
        
        if (!Cache::has($var_cache)) {
            
            $is_have_category = false;
            $id_category = NULL;
            
            if ($category != 'all') {
                $category_search = FacebookCategory::whereCategory($category)->first();
                if($category_search){
                    $id_category = $category_search->id_category;
                    $is_have_category = true;
                }else{
                    return 'Invalid method';
                }
            }
            
            
            switch ($where) {
                
                case 'world':
                    
                    if($is_have_category){
                        $data['total'] = FacebookPage::
                            whereCategoryId($id_category)
                            ->where(function($query){
                                $query->whereParent(0)->orWhereRaw('id_page = parent');
                            })->count();
                    }
                    else{
                        $data['total'] = FacebookPage::whereParent(0)->orWhereRaw('id_page = parent')->count();
                    }
                    
                    break;

                case 'hispanic':
                    
                    if($is_have_category){
                        $data['total'] = FacebookPage::
                            whereIdiom('es')
                            ->whereCategoryId($id_category)
                            ->where(function($query){
                                $query->whereParent(0)->orWhereRaw('id_page = parent');
                            })->count();
                    }
                    else{
                        $data['total'] = FacebookPage::
                            whereIdiom('es')
                            ->where(function($query){
                                $query->whereParent(0)->orWhereRaw('id_page = parent');
                            })->count();
                    }
                    
                    
                    break;

                default:
                    $country_idiom = FacebookCountry::whereIdiom($where)->first();
                    $country = FacebookCountry::whereSlug($where)->first();
                    if ($country) {
                        if($is_have_category){
                            $data['total'] = FacebookPageLocalFans::
                                where('country_code', strtoupper($country->code))
                                ->whereCategoryId($id_category)
                                ->where(function($query){
                                    $query->whereParent(0)->orWhereRaw('id_page = parent');
                                })->count();
                        }
                        else{
                            $data['total'] = FacebookPageLocalFans::
                                where('country_code', strtoupper($country->code))
                                ->where(function($query){
                                    $query->whereParent(0)->orWhereRaw('id_page = parent');
                                })->count();
                        }
                    }
                    elseif ($country_idiom) {
                        if($is_have_category){
                            $data['total'] = FacebookPage::
                                whereIdiom($where)
                                ->whereCategoryId($id_category)
                                ->where(function($query){
                                    $query->whereParent(0)->orWhereRaw('id_page = parent');
                                })->count();
                        }
                        else{
                            $data['total'] = FacebookPage::
                                whereIdiom($where)
                                ->where(function($query){
                                    $query->whereParent(0)->orWhereRaw('id_page = parent');
                                })->count();
                        }   
                    }
                    else {
                        return 'Invalid method';
                    }
            }

            Cache::put($var_cache, $data, 1440);
            
        }
        
        return Cache::get($var_cache);
        
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
                    'value_str' => $this->owloo_number_format_str($grow),
                    'percent' => $percent,
                    'class' => (($grow > 0) ? 'plus' : (($grow < 0) ? 'minus' : 'equal')) 
                );
    }
    
    /*
    |
    | Get fanpage details.
    |
    */
    public function getPage($username) {
        
        $var_cache = 'facebookPage' . ucfirst($username);
            
//        if (!Cache::has($var_cache)) {
        if (true) {
            
            $data = FacebookPage::whereUsername($username)->first();
            
            if ($data) {
                
                $page['username'] = strtolower($data['username']);
                
                $page['name'] = $data['name'];
                
                $page['about'] = $data['about'];
                
                $page['description'] = $data['description'];
                
                $page['link'] = $data['link'];
                
                $page['picture'] = 'http://graph.facebook.com/'.$data['fb_id'].'/picture?height=200&type=normal&width=200';
                
                $page['cover'] = $data['cover'];
                
                $page['is_verified'] = $data['is_verified'];
                
                $auxformat = explode("-", $data['in_owloo_from']);
                $year = $auxformat[0];
                $day = $auxformat[2];
                $month = strtolower($this->getMonth($auxformat[1], 'large'));
                $page['in_owloo_from'] = $day.' '.$month.' '.$year;
                
                $page['likes'] = $this->owloo_number_format($data['likes']);
                $page['likes_str'] = $this->owloo_number_format_str($data['likes']);
                
                $charts = json_decode($data['charts'], true);
                
                $charts = array(
                                'likes' => $this->owloo_chart_data_format($charts['likes'], true),
                                'daily_likes_grow' => $this->owloo_chart_data_format($charts['daily_likes_grow'], true),
                                'talking_about' => $this->owloo_chart_data_format($charts['talking_about'], true)
                );
                
                $accumulate_down_30 = $charts['likes']['accumulate_down'];
                unset($charts['likes']['accumulate_down']);
                
                $page['charts'] = $charts;
                
                $page['likes_grow']['grow_1'] = $this->formatGrow($data['likes_grow_1'], $data['likes']);
                $page['likes_grow']['grow_7'] = $this->formatGrow($data['likes_grow_7'], $data['likes']);
                $page['likes_grow']['grow_15'] = $this->formatGrow($data['likes_grow_15'], $data['likes']);
                $page['likes_grow']['grow_30'] = $this->formatGrow($data['likes_grow_30'], $data['likes']);
                //$page['likes_grow']['grow_60'] = $this->formatGrow($data['likes_grow_60'], $data['likes']);
                
                $page['accumulate_down_30'] = $this->formatGrow($accumulate_down_30, $data['likes']);
                
                $page['talking_about'] = $this->owloo_number_format($data['talking_about']);
                //$page['talking_about_grow']['grow_1'] = $this->formatGrow($data['talking_about_grow_1'], $data['talking_about']);
                $page['talking_about_grow']['grow_7'] = $this->formatGrow($data['talking_about_grow_7'], $data['talking_about']);
                //$page['talking_about_grow']['grow_15'] = $this->formatGrow($data['talking_about_grow_15'], $data['talking_about']);
                $page['talking_about_grow']['grow_30'] = $this->formatGrow($data['talking_about_grow_30'], $data['talking_about']);
                //$page['talking_about_grow']['grow_60'] = $this->formatGrow($data['talking_about_grow_60'], $data['talking_about']);
                
                $page['pta'] = 0;
                if ($data['likes'] > 0) {
                    $page['pta'] = $this->owlooFormatPorcent($data['talking_about'], $data['likes']);
                }
                
                if (!empty($data['country_code'])) {
                    
                    $page['location'] = true;
                    $page['country']['code'] = strtolower($data['country_code']);
                    $page['country']['name'] = $data['country_name'];
                    //$page['country']['slug'] = $data['slug'];
                    $page['country']['ranking'] = $data['country_ranking'];
                    $page['country']['audience'] = $this->owloo_number_format($data['country_audience']);
                    
                }else {
                    
                    $page['location'] = false;
                    $page['country']['code'] = strtolower($data['first_country_code']);
                    $page['country']['name'] = $data['first_country_name'];
                    //$page['country']['slug'] = $data['first_country_slug'];
                    $page['country']['ranking'] = $data['first_local_fans_country_ranking'];
                    $page['country']['audience'] = $this->owloo_number_format($data['first_local_fans_country_audience']);
                    
                }
                
                $page['first_country']['code'] = strtolower($data['first_country_code']);
                $page['first_country']['name'] = $data['first_country_name'];
                //$page['first_country']['slug'] = $data['first_country_slug'];
                $page['first_country']['audience'] = $this->owloo_number_format($data['first_local_fans_country_audience']);
                $page['first_country']['ranking'] = $data['first_local_fans_country_ranking'];
                
                $page['category']['id'] = $data['category_id'];
                $page['category']['name'] = $data['category_name'];
                
                $page['sub_category']['id'] = $data['sub_category_id'];
                $page['sub_category']['name'] = $data['sub_category_name'];
                
                $page['general_ranking'] = $this->owloo_number_format($data['general_ranking']);
                
                $page['local_countries'] = array();
                
                $local_countries = FacebookPageLocalFans::where('id_page', $data['id_page'])->orderBy('likes_local_fans', 'DESC')->get(['country_code', 'country_name', 'country_slug', 'likes_local_fans']);
                
                foreach ($local_countries as $local_country) {
                    
                    if ($local_country['country_code'] == $data['first_country_code']) {
                        $page['first_country']['likes'] = $this->owloo_number_format($local_country['likes_local_fans']);
                        $page['first_country']['market_share_percent'] = $this->owlooFormatPorcent($local_country['likes_local_fans'], $data['first_local_fans_country_audience']);
                    }
                    
                    if (strtolower($local_country['country_code']) == $page['country']['code']) {
                        $page['country']['likes'] = $this->owloo_number_format($local_country['likes_local_fans']);
                        $page['country']['market_share_percent'] = $this->owlooFormatPorcent($local_country['likes_local_fans'], $data['country_audience']);
                    }
                    
                    $page['local_countries'][] = array(
                            'code' => strtolower($local_country['country_code']),
                            'name' => $local_country['country_name'],
                            //'slug' => $local_country['country_slug'],
                            'likes' => $this->owloo_number_format($local_country['likes_local_fans']),
                            'likes_percent' => $this->owlooFormatPorcent($local_country['likes_local_fans'], $data['likes'])
                    );
                        
                }
                
                Cache::put($var_cache, $page, 1440);
            }
            else {
                return 'Invalid method';
            }
        }
        
        return Cache::get($var_cache);

    }

    private function getMonth($mes, $format = 'short') { //Formateo de meses
        
        switch((int)$mes) {
            case 1: if ($format == 'short') return 'Ene'; else return 'Enero';
            case 2: if ($format == 'short') return 'Feb'; else return 'Febrero';
            case 3: if ($format == 'short') return 'Mar'; else return 'Marzo';
            case 4: if ($format == 'short') return 'Abr'; else return 'Abril';
            case 5: if ($format == 'short') return 'May'; else return 'Mayo';
            case 6: if ($format == 'short') return 'Jun'; else return 'Junio';
            case 7: if ($format == 'short') return 'Jul'; else return 'Julio';
            case 8: if ($format == 'short') return 'Ago'; else return 'Agosto';
            case 9: if ($format == 'short') return 'Set'; else return 'Setiembre';
            case 10: if ($format == 'short') return 'Oct'; else return 'Octubre';
            case 11: if ($format == 'short') return 'Nov'; else return 'Noviembre';
            case 12: if ($format == 'short') return 'Dic'; else return 'Diciembre';
        }
    }

    /*
    |
    | Get Local Fans history of fanpages.
    |
    */
    public function getPageLocalFansHistory($username, $country, $days = 30) {
        
        $var_cache = 'facebookPageLocalFansHistory' . ucfirst($username) . ucfirst($country) . $days;
        
        if (!Cache::has($var_cache)) {

            $data_page = FacebookPage::whereUsername($username)->first(['id_page', 'name']);
            $data_country = FacebookCountry::whereCode($country)->first(['id_country', 'name']);

            if ($data_page && $data_country) {

                $page['name'] = $data_page['name'];
                $data_local_country = FacebookPageLocalFansCountry::where('id_page', $data_page['id_page'])->where('id_country', $data_country['id_country'])->orderBy('date', 'DESC')->take(61)->get(['likes', 'date']);

                $series_data = array(); //Likes history
                $series_data_min = 0; //Min likes
                $series_data_max = 0; //Max likes
                $x_axis = array(); //Dates history
                $ban = 1; 
                $cont = 1;
                $_num_rango = 1;
                $_num_discard = count($data_local_country) - ($_num_rango * floor(count($data_local_country)/$_num_rango));

                $last_likes = null;
                $last_date = null;
                $nun_rows = count($data_local_country);

                foreach ($data_local_country as $local_country){
                    
                    if ($_num_discard-- > 0){ continue; }
                    
                    if ($cont % $_num_rango == 0) {
                        //Formatear fecha
                        $auxformat = explode("-", $local_country['date']);
                        $dia = $auxformat[2];
                        $mes = $this->getMonth($auxformat[1]);
                        $series_data[] = $local_country['likes'];
                        $x_axis[]      = "'".$dia." ".$mes."'";
                        if ($ban == 1) {
                            $series_data_min    = $local_country['likes'];
                            $series_data_max    = $local_country['likes'];
                            $last_likes         = $local_country['likes'];
                            $last_date          = $local_country['date'];
                            $ban                = 0;
                        }
                        else {
                            if ($local_country['likes'] < $series_data_min) {
                                $series_data_min = $local_country['likes'];
                            }
                            elseif ($local_country['likes'] > $series_data_max) {
                                $series_data_max = $local_country['likes'];
                            }
                        }
                    }
                    if ($cont > 30){ break; }
                    $cont++;
                }

                $step = 1;
                if ($cont-1 > 11){ $step = 2; }
                if ($cont-1 > 21){ $step = 3; }
                
                $page['local_fans_history_30'] = array(
                    'series_data' =>  implode(',', $series_data),
                    'series_data_min' => $series_data_min,
                    'series_data_max'=> $series_data_max,
                    'x_axis' =>  implode(',', $x_axis),
                    'page_name' => $data_page['name'],
                    'country_name' => $data_country['name']
                );

                $page['local_fans_grow']['grow_1'] = null;
                $page['local_fans_grow']['grow_7'] = null;
                $page['local_fans_grow']['grow_15'] = null;
                $page['local_fans_grow']['grow_30'] = null;
                $page['local_fans_grow']['grow_60'] = null;

                $array_days = array(1, 7, 15, 30, 60);

                foreach ($array_days as $value) {
                    if ($nun_rows > $value) {
                        $page['local_fans_grow']['grow_'.$value] = $this->formatGrow(($last_likes - $data_local_country[$value]['likes']), $data_local_country[$value]['likes']);
                        $page['local_fans_grow']['grow_'.$value]['date'] = $data_local_country[$value]['date'];
                    }
                }

                Cache::put($var_cache, $page, 1440);

            } else {
                return 'Invalid method';
            }
        }

        return Cache::get($var_cache);

    }

    /*
    |
    | Get ranking of fanpages.
    |
    */
    public function getRankingPage($idiom, $category, $page = 1) {
        
        $where = strtolower($idiom);
        
        $var_cache = 'rankingLikes' . ucfirst($where) . 'PageCat' . ucfirst($category) . '_' . (($page - 1) * 20);
        
        if (!Cache::has($var_cache)) {
        //if(true){
            
            $is_ranking_by_country = false;
            
            $country_idiom = FacebookCountry::whereIdiom($where)->first();
            
            if ($where != 'world' && $where != 'hispanic' && !$country_idiom) {
                $ranking = FacebookPageLocalFans::take(20)->skip(($page - 1) * 20)->orderBy('likes_local_fans','DESC')->orderBy('likes','DESC');
                $is_ranking_by_country = true;
            } else {
                $ranking = FacebookPage::take(20)->skip(($page - 1) * 20)->orderBy('likes','DESC')->orderBy('talking_about', 'DESC');
            }
            
            
            $is_have_category = false;
            $id_category = NULL;
            if ($category != 'all') {
                $category_search = FacebookCategory::whereCategory($category)->first();
                if($category_search){
                    $id_category = $category_search->id_category;
                    $is_have_category = true;
                }else{
                    return 'Invalid method';
                }
            }

            switch ($where) {
                
                case 'world':
                    
                    if($is_have_category) {
                        $ranking
                            ->whereCategoryId($id_category)
                            ->where(function($query){
                                $query->whereParent(0)->orWhereRaw('id_page = parent');
                            });
                    }
                    else{
                        $ranking->whereParent(0)->orWhereRaw('id_page = parent');
                    }
                    
                    break;

                case 'hispanic':
                        
                    if($is_have_category) {
                        $ranking
                            ->whereIdiom('es')
                            ->whereCategoryId($id_category)
                            ->where(function($query){
                                $query->whereParent(0)->orWhereRaw('id_page = parent');
                            });
                    }
                    else{
                        $ranking
                            ->whereIdiom('es')
                            ->where(function($query){
                                $query->whereParent(0)->orWhereRaw('id_page = parent');
                            });
                    }
                    
                    break;

                default:
                    
                    $country = FacebookCountry::whereSlug($where)->first();
                    if ($country) {
                        if($is_have_category) {
                            $ranking
                                ->where('country_code', strtoupper($country->code))
                                ->whereCategoryId($id_category)
                                ->where(function($query){
                                    $query->whereParent(0)->orWhereRaw('id_page = parent');
                                });
                        }
                        else {
                            $ranking
                                ->where('country_code', strtoupper($country->code))
                                ->where(function($query){
                                    $query->whereParent(0)->orWhereRaw('id_page = parent');
                                });
                        }   
                    }
                    elseif ($country_idiom) {
                        if($is_have_category) {
                            $ranking
                                ->whereIdiom($where)
                                ->whereCategoryId($id_category)
                                ->where(function($query){
                                    $query->whereParent(0)->orWhereRaw('id_page = parent');
                                });
                        }
                        else {
                            $ranking
                                ->whereIdiom($where)
                                ->where(function($query){
                                    $query->whereParent(0)->orWhereRaw('id_page = parent');
                                });
                        } 
                    }
                    else {
                        return 'Invalid method';
                    }
                    
            }

            if ($is_ranking_by_country) {
                $ranking = $ranking->get(['category_id', 'fb_id', 'username', 'name', 'is_verified', 'likes', 'likes_grow_7', 'talking_about', 'likes_local_fans']);
            } else {
                $ranking = $ranking->get(['category_id', 'fb_id', 'username', 'name', 'is_verified', 'likes', 'likes_grow_7', 'talking_about', 'country_code', 'country_slug', 'first_country_code', 'first_country_slug']);
            }

            $pages = array();

            foreach ($ranking as $key => $value) {
                $cache = NULL;
                $cache['category_id'] = $value['category_id'];
                $cache['position'] = ((($page - 1) * 20) + $key) + 1;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = $value['name'];
                $cache['picture'] = 'http://graph.facebook.com/'.$value['fb_id'].'/picture?height=50&type=normal&width=50';
                $cache['is_verified'] = $value['is_verified'];

                if (!$is_ranking_by_country) {
                    $cache['country']['code'] = strtolower((!empty($value['country_code']) ? $value['country_code'] : $value['first_country_code']));
                    $cache['country']['slug'] = (!empty($value['country_slug']) ? $value['country_slug'] : $value['first_country_slug']);
                }

                $pta = 0;
                if ($value['likes'] > 0) $pta = $this->owlooFormatPorcent($value['talking_about'], $value['likes']);
                //PTA
                $cache['second_column'][] = array('value' => $pta.'%', 'class' => '');
                //Semana
                $aux_num = $this->formatGrow($value['likes_grow_7'], $value['likes']);
                $cache['second_column'][] = array('value' => $aux_num['value'], 'class' => $aux_num['class']);
                //Fans totales
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['likes']), 'class' => '');
                if ($is_ranking_by_country) {
                    //Fans locales
                    $cache['second_column'][] = array('value' => $this->owloo_number_format($value['likes_local_fans']), 'class' => '');
                }
                
                $pages[] = $cache;
            }

            $second_column = array();
            if ($is_ranking_by_country) {
                $second_column = array('PTA', 'Semana', 'Fans totales', 'Fans locales');
            } else {
                $second_column = array('País', 'PTA', 'Semana', 'Fans totales');
            }
            
            $array_result = array(
                                    'type' => 'fb_page',
                                    'subtype' => (($is_ranking_by_country)?'fb_country_page':'fb_all_pages'),
                                    'main_column' => 'Página',
                                    'second_column' => $second_column,
                                    'large_column' => 3,
                                    'link' => 'facebook-analytics/pages',
                                    'items' => $pages
                            );
            
            Cache::put($var_cache, $array_result, 1440);
            
        }

        return Cache::get($var_cache);
        
    }

    /*
    |
    | Get ranking of fanpages.
    |
    */
    public function getRankingPageGrow($idiom, $limit = 4) {
        
        $where = strtolower($idiom);
        
        $var_cache = 'rankingGrowPageLikes' . ucfirst($where) . $limit;
        
//        if (!Cache::has($var_cache)) {
            if (true) {
            
            $ranking = FacebookPage::take($limit)->orderBy('likes_grow_30','DESC');

            switch ($where) {
                
                case 'all':
                    $ranking->whereParent(0)->orWhereRaw('id_page = parent');
                    break;
                
                default:
                    $country_idiom = FacebookCountry::whereIdiom($where)->first();
                    if ($country_idiom) {
                        $ranking
                            ->whereIdiom($where)
                            ->where(function($query){
                                $query->whereParent(0)->orWhereRaw('id_page = parent');
                            });
                    } else {
                        return 'Invalid method';
                    }
            }
            
            $ranking = $ranking->get(['fb_id', 'username', 'name', 'likes', 'likes_grow_30']);
            
            $pages = array();
            
            foreach ($ranking as $key => $value) {
                $cache = NULL;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = $value['name'];
                $cache['picture'] = 'https://graph.facebook.com/'.$value['fb_id'].'/picture?type=large';
                $cache['percent'] = 0;
                if ($value['likes'] - $value['likes_grow_30'] > 0)
                    $cache['percent'] = $this->owlooFormatPorcent($value['likes_grow_30'], ($value['likes'] - $value['likes_grow_30']));
                $cache['grow_30'] = $this->owloo_number_format($value['likes_grow_30']);
                
                $pages[] = $cache;
            }
            
            Cache::put($var_cache, $pages, 1440);
            
        }

        return Cache::get($var_cache);
    }

    /*
    |
    | Get last pages added.
    |
    */
    public function getLastPageAdded($idiom, $limit = 4) {
        
        $where = strtolower($idiom);
        
        $var_cache = 'lastFacebookPageAdded' . ucfirst($where) . $limit;
        
        if (!Cache::has($var_cache)) {
            
            $ranking = FacebookPage::take($limit)->orderBy('id_page','DESC');

            switch ($where) {
                
                case 'all':
                    $ranking->whereParent(0)->orWhereRaw('id_page = parent');
                    break;
                
                default:
                    $country_idiom = FacebookCountry::whereIdiom($where)->first();
                    if ($country_idiom) {
                        $ranking
                            ->whereIdiom($where)
                            ->where(function($query){
                                $query->whereParent(0)->orWhereRaw('id_page = parent');
                            });
                    } else {
                        return 'Invalid method';
                    }
            }
            
            $ranking = $ranking->get(['fb_id', 'username', 'name']);
            
            $pages = array();
            
            foreach ($ranking as $key => $value) {
                $cache = NULL;
                $cache['username'] = strtolower($value['username']);
                $cache['name'] = $value['name'];
                $cache['picture'] = 'https://graph.facebook.com/'.$value['fb_id'].'/picture?type=large';
                
                $pages[] = $cache;
            }
            
            Cache::put($var_cache, $pages, 1440);
            
        }

        return Cache::get($var_cache);
    }

    /*
    |
    | Get ranking of countries.
    |
    */
    public function getRankingCountry($idiom, $page = 1) {
        
        $idiom = strtolower($idiom);
        
        $var_cache = 'rankingCountries' . ucfirst($idiom) . '_' . (($page - 1) * 20);
        
        if (!Cache::has($var_cache)) {
        //if(true){
            
            $ranking = FacebookCountry::take(20)->skip(($page - 1) * 20)->orderBy('total_user','DESC')->orderBy('audience_grow_90', 'DESC');

            switch ($idiom) {
                
                case 'world':
                    break;

                case 'hispanic':
                    $ranking->whereIdiom('es');
                    break;

                default:
                    $country_idiom = FacebookCountry::whereIdiom($idiom)->first();
                    if ($country_idiom) {
                        $ranking->whereIdiom($idiom);
                    } else {
                        return 'Invalid method';
                    }
            }
            
            $ranking = $ranking->get(['code', 'name', 'slug', 'abbreviation', 'total_user', 'total_female', 'total_male', 'audience_grow_90']);
            
            $countries = array();
            
            foreach ($ranking as $key => $value) {
                
                $cache = NULL;
                $cache['position'] = ((($page - 1) * 20) + $key) + 1;
                $cache['code'] = strtolower($value['code']);
                $cache['name'] = $value['name'];
                $cache['abbreviation'] = $value['abbreviation'];
                $cache['slug'] = $value['slug'];
                 
                //Mes
                $aux_num = $this->formatGrow($value['audience_grow_90'], $value['total_user']);
                $cache['second_column'][] = array('value' => $aux_num['value'], 'class' => $aux_num['class']);
                //Mujeres
                $cache['second_column'][] = array('value' => $this->owlooFormatPorcent($value['total_female'], $value['total_user']).'%', 'class' => '');
                //Hombres
                $cache['second_column'][] = array('value' => $this->owlooFormatPorcent($value['total_male'], $value['total_user']).'%', 'class' => '');
                //Total usuarios
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['total_user']), 'class' => '');
                
                $countries[] = $cache;
                
            }

            $second_column = array('Mes', 'Mujeres', 'Hombres', 'Total usuarios');
            $array_result = array(
                                    'type' => 'fb_country',
                                    'subtype' => 'fb_country',
                                    'main_column' => 'País',
                                    'second_column' => $second_column,
                                    'large_column' => 3,
                                    'link' => 'facebook-stats',
                                    'items' => $countries
                            );
            
            Cache::put($var_cache, $array_result, 1440);
            
        }
        
        return Cache::get($var_cache);
        
    }

    /*
    |
    | Get grow of countries.
    |
    */
    public function getRankingCountryGrow($idiom) {
        
        $where = strtolower($idiom);
        
        $var_cache = 'rankingGrowCountry' . ucfirst($where);
        
        //if (!Cache::has($var_cache)) {
        if(true) {
          
            $ranking = FacebookCountry::take(4)->orderBy('audience_grow_90','DESC')->orderBy('total_user','DESC');

            switch ($where) {
                
                case 'all':
                    break;
                
                default:
                    $country_idiom = FacebookCountry::whereIdiom($where)->first();
                    if ($country_idiom) {
                        $ranking->whereIdiom($where);
                    } else {
                        return 'Invalid method';
                    }
                    
            }
            
            $ranking = $ranking->get(['code', 'name', 'slug', 'abbreviation', 'total_user', 'audience_grow_90']);
            
            $countries = array();
            
            foreach ($ranking as $key => $value) {
                
                $cache = NULL;
                $cache['name'] = $value['name'];
                $cache['abbreviation'] = $value['abbreviation'];
                $cache['slug'] = $value['slug'];
                $cache['code'] = strtolower($value['code']);
                $cache['grow_90'] = $this->formatGrow($value['audience_grow_90'], $value['total_user']);
                
                $countries[] = $cache;
            }
            
            Cache::put($var_cache, $countries, 1440);
            
        }

        return Cache::get($var_cache);
    }
    
    /*
    |
    | Get city's short details.
    |
    */
    private function formatCityShortDetailsDataTableRow($name, $name_top_element, $total, $value) {
        return array(
                        'name'=> $name,
                        'name_top_element' => $name_top_element,
                        'value' => array(
                                            'value' => $this->owloo_number_format($value),
                                            'percent' => $this->owlooFormatPorcent($value, $total)
                                        )
                 );
    }    
    
    public function getCityShortDetails($id_city){
        
        $var_cache = 'facebookCityShortDetails' . $id_city;
        
        //if (!Cache::has($var_cache)) {
        if (true) {
        
            $value = FacebookCity::whereIdCity($id_city)->first();
            
            if (!$value) {
                return 'Invalid method';
            }
            
            $cache['name'] = $value['name'];
            
            $country = FacebookCountry::whereCode($value['country_code'])->first(['name', 'slug']);
            $cache['country']['name'] = $country['name'];
            $cache['country']['slug'] = $country['slug'];
            $cache['country']['code'] = strtolower($value['country_code']);
            
            $cache['chart_history'] = json_decode($value['chart_history'], true);
            
            $cache['key_metrics'] = array();
            
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Total', '', $value['total_user'], $value['total_user']);
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Mujeres', '', $value['total_user'], $value['total_female']);
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Hombres', '', $value['total_user'], $value['total_male']);
            
            //Get max Age value
            $age_max_total = FacebookCityAge::whereIdCity($value['id_city'])->orderBy('total_user','DESC')->orderBy('id','ASC')->first(['name', 'total_user']);
            if (isset($age_max_total['name'])) {
                $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Edad', $age_max_total['name'], $value['total_user'], $age_max_total['total_user']);
            }
            
            //Get max Relationship value
            $relationship_max_total = FacebookCityRelationship::whereIdCity($value['id_city'])->orderBy('total_user','DESC')->orderBy('id','ASC')->first(['name', 'total_user']);
            if (isset($relationship_max_total['name'])) {
                $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Relación', $relationship_max_total['name'], $value['total_user'], $relationship_max_total['total_user']);
            }
            
            //Get max Interests value
            $interests_max_total = FacebookCityInterest::whereIdCity($value['id_city'])->orderBy('total_user','DESC')->orderBy('id','ASC')->first(['name', 'total_user']);
            if (isset($interests_max_total['name'])) {
                $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Interés', $interests_max_total['name'], $value['total_user'], $interests_max_total['total_user']);
            }
            
            //Get All Mobile Device value
            $mobile_device = FacebookCityComportamiento::whereIdCity($value['id_city'])->whereIdComportamiento(96)->first(['total_user']);
            if (isset($mobile_device['total_user'])) {
                $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Dispositivos móviles', '', $value['total_user'], $mobile_device['total_user']);
            }
    
            //Get max Mobile OS value
            $mobile_os[63] = 'Blackberry';
            $mobile_os[92] = 'Android';
            $mobile_os[93] = 'Windows Phones';
            $mobile_os[94] = 'IOS';
            $comportamiento_max_total = FacebookCityComportamiento::whereIdCity($value['id_city'])->where('id_comportamiento', '!=', 96)->orderBy('total_user','DESC')->orderBy('id','ASC')->first(['id_comportamiento', 'total_user']);
            if (isset($comportamiento_max_total['id_comportamiento'])) {
                $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Interés', $mobile_os[$comportamiento_max_total['id_comportamiento']], $value['total_user'], $comportamiento_max_total['total_user']);
            }
            
            Cache::put($var_cache, $cache, 1440);
            
        }

        return Cache::get($var_cache);
    }
    
    /*
    |
    | Get region's short details.
    |
    */
    public function getRegionShortDetails($id_region){
        
        $var_cache = 'facebookRegionShortDetails'.$id_region;
        
        //if (!Cache::has($var_cache)) {
        if (true) {
        
            $value = FacebookRegion::whereIdRegion($id_region)->first();
            
            if (!$value) {
                return 'Invalid method';
            }
            
            $cache['name'] = $value['name'];
            
            $country = FacebookCountry::whereCode($value['country_code'])->first(['name', 'slug']);
            $cache['country']['name'] = $country['name'];
            $cache['country']['slug'] = $country['slug'];
            $cache['country']['code'] = strtolower($value['country_code']);
            
            $cache['chart_history'] = json_decode($value['chart_history'], true);
            
            $cache['key_metrics'] = array();
            
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Total', '', $value['total_user'], $value['total_user']);
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Mujeres', '', $value['total_user'], $value['total_female']);
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Hombres', '', $value['total_user'], $value['total_male']);
            
            //Get max Age value
            $age_max_total = FacebookRegionAge::whereIdRegion($value['id_region'])->orderBy('total_user','DESC')->orderBy('id','ASC')->first(['name', 'total_user']);
            if (isset($age_max_total['name'])) {
                $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Edad', $age_max_total['name'], $value['total_user'], $age_max_total['total_user']);
            }
            
            //Get max Relationship value
            $relationship_max_total = FacebookRegionRelationship::whereIdRegion($value['id_region'])->orderBy('total_user','DESC')->orderBy('id','ASC')->first(['name', 'total_user']);
            if (isset($relationship_max_total['name'])) {
                $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Relación', $relationship_max_total['name'], $value['total_user'], $relationship_max_total['total_user']);
            }
            
            //Get max Interests value
            $interests_max_total = FacebookRegionInterest::whereIdRegion($value['id_region'])->orderBy('total_user','DESC')->orderBy('id','ASC')->first(['name', 'total_user']);
            if (isset($interests_max_total['name'])) {
                $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Interés', $interests_max_total['name'], $value['total_user'], $interests_max_total['total_user']);
            }
            
            //Get All Mobile Device value
            $mobile_device = FacebookRegionComportamiento::whereIdRegion($value['id_region'])->whereIdComportamiento(96)->first(['total_user']);
            if (isset($mobile_device['total_user'])) {
                $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Dispositivos móviles', '', $value['total_user'], $mobile_device['total_user']);
            }
    
            //Get max Mobile OS value
            $mobile_os[63] = 'Blackberry';
            $mobile_os[92] = 'Android';
            $mobile_os[93] = 'Windows Phones';
            $mobile_os[94] = 'IOS';
            $comportamiento_max_total = FacebookRegionComportamiento::whereIdRegion($value['id_region'])->where('id_comportamiento', '!=', 96)->orderBy('total_user','DESC')->orderBy('id','ASC')->first(['id_comportamiento', 'total_user']);
            if (isset($comportamiento_max_total['id_comportamiento'])) {
                $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Interés', $mobile_os[$comportamiento_max_total['id_comportamiento']], $value['total_user'], $comportamiento_max_total['total_user']);
            }
            
            Cache::put($var_cache, $cache, 1440);
            
        }

        return Cache::get($var_cache);
    }

    public function getCountryInterestDetails($id){
        
        $var_cache = 'facebookCountryInterestDetails'.$id;
        
        //if (!Cache::has($var_cache)) {
        if (true) {
        
            $value = FacebookCountryInterest::whereId($id)->whereNivel(1)->first();
            
            if (!$value) {
                return 'Invalid method';
            }
            
            $id_interest = $value['id_interest'];
            
            $cache['name'] = $value['name'];
            
            $country = FacebookCountry::whereCode($value['country_code'])->first(['name', 'slug']);
            $cache['country']['name'] = $country['name'];
            $cache['country']['slug'] = $country['slug'];
            $cache['country']['code'] = strtolower($value['country_code']);
            
            $cache['chart_history'] = json_decode($value['chart_history'], true);
            
            $cache['key_metrics'] = array();
            
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Total', '', $value['total_user'], $value['total_user']);
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Mujeres', '', $value['total_user'], $value['total_female']);
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Hombres', '', $value['total_user'], $value['total_male']);
            
            /***** Interests *****/
            $cache['list'] = array();
            $interests = FacebookCountryInterest::whereCountryCode($value['country_code'])->whereNivelSuperior($id_interest)->orderBy('total_user','DESC')->get(['name', 'total_user', 'grow_7']);
            foreach ($interests as $interest) {
                $cache['list'][] = array(
                                                'name' => $interest['name'],
                                                'grow_7' => $this->formatGrow($interest['grow_7'], $interest['total_user']),
                                                'total_user' => $this->owloo_number_format($interest['total_user'])
                                        );
            }
            
            Cache::put($var_cache, $cache, 1440);
            
        }

        return Cache::get($var_cache);
    }

    public function getCountryComportamientoDetails($id){
        
        $var_cache = 'facebookCountryComportamientoDetails'.$id;
        
        //if (!Cache::has($var_cache)) {
        if (true) {
        
            $value = FacebookCountryComportamiento::whereId($id)->whereNivel(2)->whereMobileDevice(0)->first(['id_comportamiento', 'name', 'country_code', 'total_user']);
            
            if (!$value || $value['total_user'] != '') {
                return 'Invalid method';
            }
            
            $id_comportamiento = $value['id_comportamiento'];
            
            $cache['name'] = $value['name'];
            
            $country = FacebookCountry::whereCode($value['country_code'])->first(['name', 'slug']);
            $cache['country']['name'] = $country['name'];
            $cache['country']['slug'] = $country['slug'];
            $cache['country']['code'] = strtolower($value['country_code']);
            
            /***** Comportamientos *****/
            $cache['list'] = array();
            $comportamientos = FacebookCountryComportamiento::whereCountryCode($value['country_code'])->whereNivelSuperior($id_comportamiento)->orderBy('total_user','DESC')->get(['name', 'total_user', 'grow_7']);
            foreach ($comportamientos as $comportamiento) {
                $cache['list'][] = array(
                                                'name' => $comportamiento['name'],
                                                'grow_7' => $this->formatGrow($comportamiento['grow_7'], $comportamiento['total_user']),
                                                'total_user' => $this->owloo_number_format($comportamiento['total_user'])
                                        );
            }
            
            Cache::put($var_cache, $cache, 1440);
            
        }

        return Cache::get($var_cache);
    }

    public function getCountryMobileDeviceDetails($id){
        
        $var_cache = 'facebookCountryMobileDeviceDetails'.$id;
        
        //if (!Cache::has($var_cache)) {
        if (true) {
        
            $value = FacebookCountryComportamiento::whereId($id)->whereNivel(3)->whereMobileDevice(1)->first(['id_comportamiento', 'name', 'country_code', 'total_user']);
            
            if (!$value || !$this->mobile_device_has_more_device($value['id_comportamiento'])) {
                return 'Invalid method';
            }
            
            $id_comportamiento = $value['id_comportamiento'];
            
            $cache['name'] = $value['name'];
            
            $country = FacebookCountry::whereCode($value['country_code'])->first(['name', 'slug']);
            $cache['country']['name'] = $country['name'];
            $cache['country']['slug'] = $country['slug'];
            $cache['country']['code'] = strtolower($value['country_code']);
            
            $cache['chart_history'] = json_decode($value['chart_history'], true);
            
            $cache['key_metrics'] = array();
            
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Total', '', $value['total_user'], $value['total_user']);
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Mujeres', '', $value['total_user'], $value['total_female']);
            $cache['key_metrics'][] = $this->formatCityShortDetailsDataTableRow('Hombres', '', $value['total_user'], $value['total_male']);
            
            /***** Comportamientos *****/
            $cache['list'] = array();
            $comportamientos = FacebookCountryComportamiento::whereCountryCode($value['country_code'])->whereNivelSuperior($id_comportamiento)->orderBy('total_user','DESC')->get(['name', 'total_user', 'grow_7']);
            foreach ($comportamientos as $comportamiento) {
                $cache['list'][] = array(
                                                'name' => $comportamiento['name'],
                                                'grow_7' => $this->formatGrow($comportamiento['grow_7'], $comportamiento['total_user']),
                                                'total_user' => $this->owloo_number_format($comportamiento['total_user'])
                                        );
            }
            
            Cache::put($var_cache, $cache, 1440);
            
        }

        return Cache::get($var_cache);
    }

    private function mobile_device_has_more_device($id_mobile_device){
        $not_has_more_device = array(62, 77, 90, 154, 156, 157, 188, 189);
        return !in_array($id_mobile_device, $not_has_more_device);
    }

    /*
    |
    | Get country details.
    |
    */
    public function getCountry($country_name) {
        
        $var_cache = 'facebookCountry'.str_replace('-', '_', $country_name);
        
        //if (!Cache::has($var_cache)) {
        if (true) {
            
            $data = FacebookCountry::whereSlug($country_name)->first();
            
            if ($data) {
                
                $country['id_country'] = $data['id_country'];
                
                $country['code'] = strtolower($data['code']);
                
                $country['name'] = $data['name'];
                
                $country['abbreviation'] = $data['abbreviation'];
                
                $country['slug'] = $data['slug'];
                
                $country['total_user'] = $this->owloo_number_format($data['total_user']);
                
                $country['total_user_str'] = $this->owloo_number_format_str($data['total_user']);
                
                $country['total_female'] = array(
                                                'value' => $this->owloo_number_format($data['total_female']),
                                                'value_str' => $this->owloo_number_format_str($data['total_female']),
                                                'percent' => $this->owlooFormatPorcent($data['total_female'], $data['total_user'])
                                         );
                
                $country['total_male'] = array(
                                                'value' => $this->owloo_number_format($data['total_male']),
                                                'value_str' => $this->owloo_number_format_str($data['total_male']),
                                                'percent' => $this->owlooFormatPorcent($data['total_male'], $data['total_user'])
                                         );
                
                $country['audience_history'] = $this->owloo_chart_data_format($data['audience_history']);

                $country['audience_grow']['grow_90'] = $this->formatGrow($data['audience_grow_90'], $data['total_user']);
                $country['audience_grow']['grow_180'] = $this->formatGrow($data['audience_grow_180'], $data['total_user']);
                $country['audience_grow']['grow_270'] = $this->formatGrow($data['audience_grow_270'], $data['total_user']);
                $country['audience_grow']['grow_360'] = $this->formatGrow($data['audience_grow_360'], $data['total_user']);
                $country['audience_down_360'] = $this->formatGrow($data['audience_down_360'], $data['total_user']);
                
                $country['general_ranking'] = $data['general_ranking'];
                
                $country['trends'] = array();

                /***** Cities *****/
                $country['cities']['supports'] = ($data['supports_city']?true:false);
                $country['cities']['items'] = array();
                
                $cities = FacebookCity::whereCountryCode($country['code'])->take(5)->orderBy('total_user','DESC')->get(['id_city', 'name', 'total_user']);
                foreach ($cities as $city) {
                    $country['cities']['items'][] = array(
                                                        'id' => $city['id_city'],
                                                        'name' => $city['name'],
                                                        'total_user' => $this->owloo_number_format($city['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($city['total_user'], $data['total_user'])
                                                );
                }

                /***** Regions *****/
                $country['regions']['supports'] = ($data['supports_region']?true:false);
                $country['regions']['items'] = array();
                
                $regions = FacebookRegion::whereCountryCode($country['code'])->take(5)->orderBy('total_user','DESC')->get(['id_region', 'name', 'total_user']);
                foreach ($regions as $region) {
                    $country['regions']['items'][] = array(
                                                        'id' => $region['id_region'],
                                                        'name' => $region['name'],
                                                        'total_user' => $this->owloo_number_format($region['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($region['total_user'], $data['total_user'])
                                                );
                }

                /***** Ages *****/
                $country['ages']['has_more'] = true;
                $country['ages']['items'] = array();
                
                $ages = FacebookCountryAge::whereCountryCode($country['code'])->orderBy('total_user','DESC')->get(['name', 'total_user', 'total_female', 'total_male', 'grow_30']);
                foreach ($ages as $age) {
                    $country['ages']['items'][] = array(
                                                        'name' => $age['name'],
                                                        'total_user' => $this->owloo_number_format($age['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($age['total_user'], $data['total_user']),
                                                        'more' => array(
                                                                        'female' => $this->owloo_number_format($age['total_female']),
                                                                        'male' => $this->owloo_number_format($age['total_male']),
                                                                        'grow_30' => $this->formatGrow($age['grow_30'], $age['total_user'])
                                                                  )
                                                );
                    
                }
                
                //Get max values
                foreach (array('user'/*, 'female', 'male'*/) as $value) {
                    $country['ages']['max_'.$value] = array();
                    $age_max_total = FacebookCountryAge::whereCountryCode($country['code'])->orderBy('total_'.$value,'DESC')->orderBy('id','ASC')->first(['name', 'total_'.$value]);
                    if (isset($age_max_total['name'])) {
                        $country['ages']['max_'.$value] = array(
                                                                'name' => $age_max_total['name'],
                                                                'value' => $this->owloo_number_format($age_max_total['total_'.$value]),
                                                                'percent' => $this->owlooFormatPorcent($age_max_total['total_'.$value], $data['total_user'])
                                                          );
                    }
                }
                
                //Get Trend-up
                $trend_up = array();
                $age_grow = FacebookCountryAge::whereCountryCode($country['code'])->orderBy('grow_30','DESC')->orderBy('id','ASC')->first(['name', 'total_user', 'grow_30']);
                if (isset($age_grow['name']) && $age_grow['grow_30'] > 0) {
                    $trend_up = array(
                                          'name' => $age_grow['name'],
                                          'grow' => $this->formatGrow($age_grow['grow_30'], $age_grow['total_user'])
                                   );
                }
                $country['trends']['age'] = $trend_up;

                /***** Languages *****/
                $country['languages']['has_more'] = false;
                $country['languages']['items'] = array();
                
                $languages = FacebookCountryLanguage::whereCountryCode($country['code'])->orderBy('total_user','DESC')->get(['name', 'total_user', 'grow_30']);
                foreach ($languages as $language) {
                    $country['languages']['items'][] = array(
                                                        'name' => $language['name'],
                                                        'total_user' => $this->owloo_number_format($language['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($language['total_user'], $data['total_user'])
                                                );
                    
                }
                
                //Get max values
                foreach (array('user'/*, 'female', 'male'*/) as $value) {
                    $country['languages']['max_'.$value] = array();
                    $language_max_total = FacebookCountryLanguage::whereCountryCode($country['code'])->orderBy('total_'.$value,'DESC')->orderBy('id','ASC')->first(['name', 'total_'.$value]);
                    if (isset($language_max_total['name'])) {
                        $country['languages']['max_'.$value] = array(
                                                                'name' => $language_max_total['name'],
                                                                'value' => $this->owloo_number_format($language_max_total['total_'.$value]),
                                                                'percent' => $this->owlooFormatPorcent($language_max_total['total_'.$value], $data['total_user'])
                                                          );
                    }
                }
                
                //Get the Trend-up
                $trend_up = array();
                $language_grow = FacebookCountryLanguage::whereCountryCode($country['code'])->orderBy('grow_30','DESC')->orderBy('total_user','DESC')->first(['name', 'total_user', 'grow_30']);
                if (isset($language_grow['name']) && $language_grow['grow_30'] > 0) {
                    $trend_up = array(
                                          'name' => $language_grow['name'],
                                          'grow' => $this->formatGrow($language_grow['grow_30'], $language_grow['total_user'])
                                   );
                }
                $country['trends']['language'] = $trend_up;
                
                /***** Gender trend *****/
                $trend_up = array();
                if($data['audience_female_grow_30'] > 0 || $data['audience_male_grow_30'] > 0){
                    if($data['audience_male_grow_30'] > $data['audience_female_grow_30']){
                        $trend_up = array(
                                            'name' => 'Hombres',
                                            'grow' => $this->formatGrow($data['audience_male_grow_30'], $data['total_male'])
                                         );
                    }else{
                        $trend_up = array(
                                            'name' => 'Mujeres',
                                            'grow' => $this->formatGrow($data['audience_female_grow_30'], $data['total_female'])
                                         );
                    }
                }
                $country['trends']['gender'] = $trend_up;

                /***** Relationships *****/
                $country['relationships']['has_more'] = true;
                $country['relationships']['items'] = array();
                
                $relationships = FacebookCountryRelationship::whereCountryCode($country['code'])->orderBy('total_user','DESC')->get(['name', 'total_user', 'total_female', 'total_male', 'total_user_grow_30']);
                foreach ($relationships as $relationship) {
                    $country['relationships']['items'][] = array(
                                                        'name' => $relationship['name'],
                                                        'total_user' => $this->owloo_number_format($relationship['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($relationship['total_user'], $data['total_user']),
                                                        'more' => array(
                                                                        'female' => $this->owloo_number_format($relationship['total_female']),
                                                                        'male' => $this->owloo_number_format($relationship['total_male']),
                                                                        'grow_30' => $this->formatGrow($relationship['total_user_grow_30'], $relationship['total_user'])
                                                                  )
                                                );
                    
                }

                //Get Trend-up
                foreach (array('user', 'female', 'male') as $value) {
                    $trend_relationships[$value] = array();
                    $relationship_grow = FacebookCountryRelationship::whereCountryCode($country['code'])->orderBy('total_'.$value.'_grow_30','DESC')->orderBy('total_user','DESC')->first(['name', 'total_'.$value, 'total_'.$value.'_grow_30']);
                    if (isset($relationship_grow['name']) && $relationship_grow['total_'.$value.'_grow_30'] > 0) {
                        $trend_relationships[$value] = array(
                                                              'name' => $relationship_grow['name'],
                                                              'grow' => $this->formatGrow($relationship_grow['total_'.$value.'_grow_30'], $relationship_grow['total_'.$value])
                                                       );
                    }
                }
                
                $country['trends']['female_relationship'] = $trend_relationships['female'];
                $country['trends']['male_relationship'] = $trend_relationships['male'];
                
                /***** Interests *****/
                $country['interests']['items'] = array();
                $interests = FacebookCountryInterest::whereCountryCode($country['code'])->whereNivel(1)->orderBy('total_user','DESC')->get(['id', 'name', 'total_user', 'grow_7']);
                foreach ($interests as $interest) {
                    $country['interests']['items'][] = array(
                                                        'id' => $interest['id'],
                                                        'name' => $interest['name'],
                                                        'grow_7' => $this->formatGrow($interest['grow_7'], $interest['total_user']),
                                                        'total_user' => $this->owloo_number_format($interest['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($interest['total_user'], $data['total_user'])
                                                );
                }
                
                //Get the Trend-up
                $country['interests']['trend_up'] = array();
                foreach (array(1, 3, 7, 15, 30) as $days) {
                    $country['interests']['trend_up']['grow_'.$days] = array();
                    $interest_grow = FacebookCountryInterest::whereCountryCode($country['code'])->orderBy('grow_'.$days,'DESC')->orderBy('total_user','DESC')->first(['name', 'total_user', 'grow_'.$days]);
                    
                    $time = NULL;
                    switch ($days) {
                        case 1:
                            $time = '24 hours';
                            break;
                        case 3:
                            $time = '72 hours';
                            break;
                        default:
                            $time = $days.' days';
                            break;
                    }
                    
                    if (isset($interest_grow['name']) && $interest_grow['grow_'.$days] > 0) {
                        
                        $country['interests']['trend_up']['grow_'.$days] = array(
                                                              'name' => $interest_grow['name'],
                                                              'grow' => $this->formatGrow($interest_grow['grow_'.$days], $interest_grow['total_user']),
                                                              'time' => $time
                                                       );
                    }else {
                        $country['interests']['trend_up']['grow_'.$days] = array(
                                                              'name' => '',
                                                              'grow' => '',
                                                              'time' => $time
                                                       );
                    }
                }

                /***** Comportamientos *****/
                $country['comportamientos']['items'] = array();
                $items_has_more = array();
                $comportamientos = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereNivel(2)->whereMobileDevice(0)->orderBy('total_user','DESC')->get(['id', 'name', 'total_user', 'grow_7']);
                foreach ($comportamientos as $comportamiento) {
                    
                    if($comportamiento['total_user'] != ''){
                        $country['comportamientos']['items'][] = array(
                                                                        'has_more' => false,
                                                                        'id' => $comportamiento['id'],
                                                                        'name' => $comportamiento['name'],
                                                                        'grow_7' => $this->formatGrow($comportamiento['grow_7'], $comportamiento['total_user']),
                                                                        'total_user' => $this->owloo_number_format($comportamiento['total_user']),
                                                                        'percent' => $this->owlooFormatPorcent($comportamiento['total_user'], $data['total_user'])
                                                                );
                    }else{
                        $items_has_more[] = array(
                                                    'has_more' => true,
                                                    'id' => $comportamiento['id_comportamiento'],
                                                    'name' => $comportamiento['name']
                                            );
                    }
                    
                        
                    
                }
                foreach ($items_has_more as $more) {
                    $country['comportamientos']['items'][] = $more;
                }

                //Get the Trend-up
                $country['comportamientos']['trend_up'] = array();
                foreach (array(1, 3, 7, 15, 30) as $days) {
                    $country['comportamientos']['trend_up']['grow_'.$days] = array();
                    $comportamiento_grow = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereMobileDevice(0)->orderBy('grow_'.$days,'DESC')->orderBy('total_user','DESC')->first(['name', 'total_user', 'grow_'.$days]);
                    
                    $time = NULL;
                    switch ($days) {
                        case 1:
                            $time = '24 hours';
                            break;
                        case 3:
                            $time = '72 hours';
                            break;
                        default:
                            $time = $days.' days';
                            break;
                    }
                    
                    if (isset($comportamiento_grow['name']) && $comportamiento_grow['grow_'.$days] > 0) {
                        
                        $country['comportamientos']['trend_up']['grow_'.$days] = array(
                                                              'name' => $comportamiento_grow['name'],
                                                              'grow' => $this->formatGrow($comportamiento_grow['grow_'.$days], $comportamiento_grow['total_user']),
                                                              'time' => $time
                                                       );
                    }else {
                        $country['comportamientos']['trend_up']['grow_'.$days] = array(
                                                              'name' => '',
                                                              'grow' => '',
                                                              'time' => $time
                                                       );
                    }
                }

                /***** Mobile device *****/
                
                //Top values
                $country['mobile_devices']['top'] = array();
                $top_elements = array(
                                    array('id' => '92', 'name' => 'Android'),
                                    array('id' => '94', 'name' => 'IOS'),
                                    array('id' => '93', 'name' => 'Windows Phones'),
                                    array('id' => '96', 'name' => 'Todos los dispositivos móviles'),
                                    array('id' => '100', 'name' => 'Propietarios de tabletas')
                                    
                            );
                            
                foreach ($top_elements as $top_comportamiento) {
                    $mobile_device = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereIdComportamiento($top_comportamiento['id'])->get(['total_user'])->first();
                    if($mobile_device){
                         $country['mobile_devices']['top'][] = array(
                                                                    'name' => $top_comportamiento['name'],
                                                                    'total_user' => $this->owloo_number_format($mobile_device['total_user'])
                                                            );
                    }
                }
                
                //Table
                $country['mobile_devices']['brands'] = array();
                foreach (array('android', 'ios', 'others') as $operating_system) {
                    $mobile_devices = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereNivel(($operating_system=='ios'?4:3))->whereMobileOs($operating_system)->orderBy('total_user','DESC')->get(['id', 'id_comportamiento', 'name', 'total_user', 'grow_7']);
                    foreach ($mobile_devices as $mobile_device) {
                        $country['mobile_devices']['brands'][$operating_system][] = array(
                                                            'has_more' => ($operating_system!='ios' && $this->mobile_device_has_more_device($mobile_device['id_comportamiento'])?true:false), 
                                                            'id' => $mobile_device['id'],
                                                            'name' => $mobile_device['name'],
                                                            'grow_7' => $this->formatGrow($mobile_device['grow_7'], $mobile_device['total_user']),
                                                            'total_user' => $this->owloo_number_format($mobile_device['total_user']),
                                                            'percent' => $this->owlooFormatPorcent($mobile_device['total_user'], $data['total_user'])
                                                    );
                    }
                }

                //Trend ups
                $country['mobile_devices']['trend_up'] = array();
                foreach (array('android', 'ios', 'others') as $operating_system) {
                    foreach (array(1, 3, 7, 15, 30) as $days) {
                        $country['mobile_devices']['trend_up'][$operating_system]['grow_'.$days] = array();
                        $comportamiento_grow = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereMobileOs($operating_system)->orderBy('grow_'.$days,'DESC')->orderBy('total_user','DESC')->first(['name', 'total_user', 'grow_'.$days]);
                        
                        $time = NULL;
                        switch ($days) {
                            case 1:
                                $time = '24 hours';
                                break;
                            case 3:
                                $time = '72 hours';
                                break;
                            default:
                                $time = $days.' days';
                                break;
                        }
                        
                        if (isset($comportamiento_grow['name']) && $comportamiento_grow['grow_'.$days] > 0) {
                            
                            $country['mobile_devices']['trend_up'][$operating_system]['grow_'.$days] = array(
                                                                  'name' => $comportamiento_grow['name'],
                                                                  'grow' => $this->formatGrow($comportamiento_grow['grow_'.$days], $comportamiento_grow['total_user']),
                                                                  'time' => $time
                                                           );
                        }else {
                            $country['mobile_devices']['trend_up'][$operating_system]['grow_'.$days] = array(
                                                                  'name' => '',
                                                                  'grow' => '',
                                                                  'time' => $time
                                                           );
                        }
                    }
                }

                //Android vs. IOS
                //Top values
                $country['mobile_devices']['vs'] = array();
                $vs_elements = array(
                                    array('id' => '92', 'name' => 'Android'),
                                    array('id' => '94', 'name' => 'IOS')
                            );
                            
                foreach ($vs_elements as $vs_comportamiento) {
                    $mobile_device = FacebookCountryComportamiento::whereCountryCode($country['code'])->whereIdComportamiento($vs_comportamiento['id'])->get(['chart_history'])->first();
                    if($mobile_device){ 
                         $country['mobile_devices']['vs'][strtolower($vs_comportamiento['name'])] = array(
                                                                        'name' => $vs_comportamiento['name'],
                                                                        'history' => $this->owloo_chart_data_format($mobile_device['chart_history'])
                                                                );
                    }
                }
                

                Cache::put($var_cache, $country, 1440);
                
            }
            else {
                return 'Invalid method';
            }
        }
        return Cache::get($var_cache);

    }

    /*
    |
    | Get ranking of cities.
    |
    */
    public function getRankingCity ($country, $page) {
        
        $country = strtolower($country);
        
        $var_cache = 'rankingCities_' . $country . '_' . (($page - 1) * 20);

        if (!Cache::has($var_cache)) {
        //if (true) {
            
            $ranking = FacebookCity::take(20)->skip(($page - 1) * 20)->orderBy('total_user','DESC');
            
            switch ($country)
            {
                case 'world':
                    break;

                case 'hispanic':
                    $ranking->whereIdiom('es');
                    break;

                default:
                    $country_idiom = FacebookCountry::whereIdiom($country)->first();
                    if ($country_idiom) {
                        $ranking->whereIdiom($country);
                    }
                    else {
                        $country_search = FacebookCountry::whereSlug($country)->first(['code']);
                        if ($country_search) {
                            $ranking->whereCountryCode($country_search->code);
                        }else{
                            return 'Invalid method';
                        }
                    }
            }

            $ranking = $ranking->get(['id_city', 'name', 'country_code', 'total_user', 'total_female', 'total_male', 'grow_90']);

            $cities = array();

            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['position'] = ((($page - 1) * 20) + $key) + 1;
                $cache['id'] = $value['id_city'];
                $array_name = explode(',', $value['name']);
                $cache['name'] = $array_name[0];
                
                $data_country = FacebookCountry::whereCode($value['country_code'])->first(['slug']);
                $cache['country_code'] = strtolower($value['country_code']);
                $cache['country_slug'] = $data_country['slug'];
                
                //Mes
                $aux_num = $this->formatGrow($value['grow_90'], $value['total_user']);
                $cache['second_column'][] = array('value' => $aux_num['value'], 'class' => $aux_num['class']);
                //Total usuarios
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['total_user']), 'class' => '');
                
                $cities[] = $cache;                
            }
            
            $second_column = array('País', 'Mes', 'Total usuarios');
            $array_result = array(
                                    'type' => 'fb_country',
                                    'subtype' => 'fb_city',
                                    'main_column' => 'Ciudad',
                                    'second_column' => $second_column,
                                    'large_column' => 4,
                                    'link' => 'facebook-stats/cities',
                                    'items' => $cities
                            );
            
            Cache::put($var_cache, $array_result, 1440);
            
        }

        return Cache::get($var_cache);
    }

    public function getCityDetails($id_city){
        
        $var_cache = 'facebookCityDetails'.$id_city;
        
        if (!Cache::has($var_cache)) {
        
            $value = FacebookCity::whereIdCity($id_city)->first();
            
            if (!$value) {
                return 'Invalid method';
            }
            
            $cache['total_female'] = array(
                                            'value' => $this->owloo_number_format($value['total_female']),
                                            'percent' => $this->owlooFormatPorcent($value['total_female'], $value['total_user'])
                                     );
            $cache['total_male'] = array(
                                            'value' => $this->owloo_number_format($value['total_male']),
                                            'percent' => $this->owlooFormatPorcent($value['total_male'], $value['total_user'])
                                     );
            
            //Ages
            $cache['ages']['items'] = array();
    
            $ages = FacebookCityAge::whereIdCity($value['id_city'])->orderBy('total_user','DESC')->take(5)->get(['name', 'total_user']);
            foreach ($ages as $age) {
                $cache['ages']['items'][] = array(
                    'name' => $age['name'],
                                                    'total_user' => $this->owloo_number_format($age['total_user'])
                                            );
                
            }
    
            //Get max value
            $cache['ages']['max_user'] = array();
            $age_max_total = FacebookCityAge::whereIdCity($value['id_city'])->orderBy('total_user','DESC')->orderBy('id','ASC')->first(['name', 'total_user']);
            if (isset($age_max_total['name'])) {
                $cache['ages']['max_user'] = array(
                                                        'name' => $age_max_total['name'],
                                                        'value' => $this->owloo_number_format($age_max_total['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($age_max_total['total_user'], $value['total_user'])
                                                  );
            }
    
            //Relationships
            $cache['relationships']['items'] = array();
    
            $relationships = FacebookCityRelationship::whereIdCity($value['id_city'])->orderBy('total_user','DESC')->take(5)->get(['name', 'total_user']);
            foreach ($relationships as $relationship) {
                $cache['relationships']['items'][] = array(
                                                    'name' => $relationship['name'],
                                                    'total_user' => $this->owloo_number_format($relationship['total_user'])
                                            );
                
            }
    
            //Interests
            $cache['interests']['items'] = array();
    
            $interests = FacebookCityInterest::whereIdCity($value['id_city'])->orderBy('total_user','DESC')->take(5)->get(['name', 'total_user']);
            foreach ($interests as $interest) {
                $cache['interests']['items'][] = array(
                                                    'name' => $interest['name'],
                                                    'total_user' => $this->owloo_number_format($interest['total_user'])
                                            );
                
            }
    
            //Get the Trend-up
            $cache['interests']['trend_up'] = array();
            $interest_grow = FacebookCityInterest::whereIdCity($value['id_city'])->orderBy('grow_30','DESC')->orderBy('total_user','DESC')->first(['name', 'total_user', 'grow_30']);
            if (isset($interest_grow['name']) && $interest_grow['grow_30'] > 0) {
                $cache['interests']['trend_up'] = array(
                                                      'name' => $interest_grow['name'],
                                                      'grow' => $this->formatGrow($interest_grow['grow_30'], $interest_grow['total_user'])
                                               );
            }
    
            //Comportamientos
            $cache['comportamientos']['items'] = array();
    
            $comportamientos = FacebookCityComportamiento::whereIdCity($value['id_city'])->orderBy('total_user','DESC')->take(5)->get(['name', 'total_user']);
            foreach ($comportamientos as $comportamiento) {
                $cache['comportamientos']['items'][] = array(
                                                    'name' => $comportamiento['name'],
                                                    'percent' => $this->owlooFormatPorcent($comportamiento['total_user'], $value['total_user'])
                                            );
                
            }
            
            Cache::put($var_cache, $cache, 1440);
            
        }

        return Cache::get($var_cache);
    }

    /*
    |
    | Get ranking of regions.
    |
    */
    public function getRankingRegion ($country, $page) {
        
        $country = ucfirst($country);

        $var_cache = 'rankingRegions_' . $country . '_' . (($page - 1) * 20);

        if (!Cache::has($var_cache)) {
        
            $ranking = FacebookRegion::take(20)->skip(($page - 1) * 20)->orderBy('total_user','DESC');
            
            
            switch ($country)
            {
                case 'World':
                    break;

                case 'Hispanic':
                    $ranking->whereIdiom('es');
                    break;

                default:
                    $country_idiom = FacebookCountry::whereIdiom(strtolower($country))->first();
                    if ($country_idiom) {
                        $ranking->whereIdiom(strtolower($country));
                    }
                    else {
                        $country_search = FacebookCountry::whereCode(FacebookCountry::whereSlug($country)->first(['code'])->code)->first();
                        if ($country_search) {
                            $ranking->whereCountryCode($country_search->code);
                        }else{
                            return 'Invalid method';
                        }
                    }
            }

            $ranking = $ranking->get(['id_region', 'name', 'country_code', 'total_user', 'total_female', 'total_male', 'grow_90']);

            $regions = array();

            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['position'] = ((($page - 1) * 20) + $key) + 1;
                $cache['id'] = $value['id_region'];
                $cache['name'] = $value['name'];
                
                $data_country = FacebookCountry::whereCode($value['country_code'])->first(['slug']);
                $cache['country_code'] = strtolower($value['country_code']);
                $cache['country_slug'] = $data_country['slug'];
                
                //Mes
                $aux_num = $this->formatGrow($value['grow_90'], $value['total_user']);
                $cache['second_column'][] = array('value' => $aux_num['value'], 'class' => $aux_num['class']);
                //Total usuarios
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['total_user']), 'class' => '');
                
                $regions[] = $cache;                
            }

            $second_column = array('País', 'Mes', 'Total usuarios');
            $array_result = array(
                                    'type' => 'fb_country',
                                    'subtype' => 'fb_region',
                                    'main_column' => 'Region',
                                    'second_column' => $second_column,
                                    'large_column' => 4,
                                    'link' => 'facebook-stats/regions',
                                    'items' => $regions
                            );

            Cache::put($var_cache, $array_result, 1440);
        }

        return Cache::get($var_cache);
    }
    
    /*
    |
    | Get region details.
    |
    */
    public function getRegionDetails($id_region){
        
        $var_cache = 'facebookRegionDetails'.$id_region;
        
        if (!Cache::has($var_cache)) {
        
            $value = FacebookRegion::whereIdRegion($id_region)->first();
            
            if (!$value) {
                return 'Invalid method';
            }
            
            /***** Ages *****/
            $cache['ages']['items'] = array();

            $ages = FacebookRegionAge::whereIdRegion($value['id_region'])->orderBy('total_user','DESC')->take(5)->get(['name', 'total_user']);
            foreach ($ages as $age) {
                $cache['ages']['items'][] = array(
                    'name' => $age['name'],
                                                    'total_user' => $this->owloo_number_format($age['total_user'])
                                            );
                
            }

            //Get max value
            $cache['ages']['max_user'] = array();
            $age_max_total = FacebookRegionAge::whereIdRegion($value['id_region'])->orderBy('total_user','DESC')->orderBy('id','ASC')->first(['name', 'total_user']);
            if (isset($age_max_total['name'])) {
                $cache['ages']['max_user'] = array(
                                                        'name' => $age_max_total['name'],
                                                        'value' => $this->owloo_number_format($age_max_total['total_user']),
                                                        'percent' => $this->owlooFormatPorcent($age_max_total['total_user'], $value['total_user'])
                                                  );
            }

            /***** Relationships *****/
            $cache['relationships']['items'] = array();

            $relationships = FacebookRegionRelationship::whereIdRegion($value['id_region'])->orderBy('total_user','DESC')->take(5)->get(['name', 'total_user']);
            foreach ($relationships as $relationship) {
                $cache['relationships']['items'][] = array(
                                                    'name' => $relationship['name'],
                                                    'total_user' => $this->owloo_number_format($relationship['total_user'])
                                            );
                
            }

            /***** Interests *****/
            $cache['interests']['items'] = array();

            $interests = FacebookRegionInterest::whereIdRegion($value['id_region'])->orderBy('total_user','DESC')->take(5)->get(['name', 'total_user']);
            foreach ($interests as $interest) {
                $cache['interests']['items'][] = array(
                                                    'name' => $interest['name'],
                                                    'total_user' => $this->owloo_number_format($interest['total_user'])
                                            );
                
            }

            //Get the Trend-up
            $cache['interests']['trend_up'] = array();
            $interest_grow = FacebookRegionInterest::whereIdRegion($value['id_region'])->orderBy('grow_30','DESC')->orderBy('total_user','DESC')->first(['name', 'total_user', 'grow_30']);
            if (isset($interest_grow['name']) && $interest_grow['grow_30'] > 0) {
                $cache['interests']['trend_up'] = array(
                                                      'name' => $interest_grow['name'],
                                                      'grow' => $this->formatGrow($interest_grow['grow_30'], $interest_grow['total_user'])
                                               );
            }

            /***** Comportamientos *****/
            $cache['comportamientos']['items'] = array();

            $comportamientos = FacebookRegionComportamiento::whereIdRegion($value['id_region'])->orderBy('total_user','DESC')->take(5)->get(['name', 'total_user']);
            foreach ($comportamientos as $comportamiento) {
                $cache['comportamientos']['items'][] = array(
                                                    'name' => $comportamiento['name'],
                                                    'percent' => $this->owlooFormatPorcent($comportamiento['total_user'], $value['total_user'])
                                            );
                
            }
            
            Cache::put($var_cache, $cache, 1440);
            
        }

        return Cache::get($var_cache);
    }

    /*
    |
    | Get ranking of continents.
    |
    */
    public function getRankingContinent() {
            
        $var_cache = 'rankingContinents';
        
        if (!Cache::has($var_cache)) {
            
            $ranking = FacebookContinent::orderBy('total_user', 'DESC')->get(['name', 'total_user', 'total_female', 'total_male', 'grow_30']);
            
            $continents = array();
            
            foreach ($ranking as $key => $value)
            {
                $cache = NULL;
                $cache['position'] = $key + 1;
                $cache['name'] = $value['name'];
                
                //Mes
                $aux_num = $this->formatGrow($value['grow_30'], $value['total_user']);
                $cache['second_column'][] = array('value' => $aux_num['value'], 'class' => $aux_num['class']);
                //Mujeres
                $cache['second_column'][] = array('value' => $this->owlooFormatPorcent($value['total_female'], $value['total_user']).'%', 'class' => '');
                //Hombres
                $cache['second_column'][] = array('value' => $this->owlooFormatPorcent($value['total_male'], $value['total_user']).'%', 'class' => '');
                //Total usuarios
                $cache['second_column'][] = array('value' => $this->owloo_number_format($value['total_user']), 'class' => '');
                
                $continents[] = $cache;
                
            }
            
            $second_column = array('Mes', 'Mujeres', 'Hombres', 'Total usuarios');
            $array_result = array(
                                    'type' => 'fb_country',
                                    'subtype' => 'fb_continent',
                                    'main_column' => 'Continente',
                                    'second_column' => $second_column,
                                    'large_column' => 3,
                                    'link' => NULL,
                                    'items' => $continents
                            );
            
            Cache::put($var_cache, $array_result, 1440);
            
        }
        
        return Cache::get($var_cache);
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
    
    private function owlooFormatPorcent($number, $total = NULL, $decimal = 2, $sep_decimal = ',', $sep_miles = '.') {
        if ($total === 0)
            return 0;
        
        if (!empty($total))
            $aux_result = ($number * 100 / $total);
        else
            $aux_result = $number;
        
        $aux_decimal = $decimal;
        
        if ($aux_result < 0.001) {
            $aux_decimal = 4;
        }
        elseif ($aux_result < 0.01) {
            $aux_decimal = 3;
        }
        
        if (!empty($total))
            return number_format(round(($number * 100 / $total), $aux_decimal), $aux_decimal, $sep_decimal, $sep_miles);
        else
            return number_format(round(($number), $aux_decimal), $aux_decimal, $sep_decimal, $sep_miles);
    }
    
    private function convert_string_to_url($text) {
        $caracteresEspeciales = array('á', 'é', 'í', 'ó', 'ú', 'ñ', ' ', '?', ',', '.', '(', ')', 'Å');
        $caracteresReemplazo = array('a', 'e', 'i', 'o', 'u', 'n', '-', '', '', '', '', '', 'a');
        return str_ireplace($caracteresEspeciales, $caracteresReemplazo,  strtolower($text));
    }
    
    /*
    |
    | Get total amount of Facebook cities on owloo.
    |
    */
    public function getTotalCountries($where)
    {
        
        $where = strtolower($where);
        
        $var_cache = 'total' . ucfirst($where) . 'FacebookCountries';
        
        if (!Cache::has($var_cache)) {
            
            switch ($where)
            {
                case 'world':
                    $data['total'] = FacebookCountry::count();
                    break;
    
                case 'hispanic':
                    $data['total'] = FacebookCountry::whereIdiom('es')->count();
                    break;
    
                default:
                    $country_idiom = FacebookCountry::whereIdiom($where)->first();
                    if ($country_idiom) {
                        $data['total'] = FacebookCountry::whereIdiom($where)->count();
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
    | Get total amount of Facebook cities on owloo.
    |
    */
    public function getTotalCities($where)
    {
        
        $where = strtolower($where);
        
        $var_cache = 'total' . ucfirst($where) . 'FacebookCities';
        
        if (!Cache::has($var_cache)) {
            
            switch ($where)
            {
                case 'world':
                    $data['total'] = FacebookCity::count();
                    break;

                case 'hispanic':
                    $data['total'] = FacebookCity::whereIdiom('es')->count();
                    break;

                default:
                    $country_idiom = FacebookCountry::whereIdiom($where)->first();
                    if ($country_idiom) {
                        $data['total'] = FacebookCity::whereIdiom($where)->count();
                    }
                    else {
                        $country_search = FacebookCountry::whereCode(FacebookCountry::whereSlug($where)->first(['code'])->code)->first();
                        if ($country_search) {
                            $data['total'] = FacebookCity::whereCountryCode($country_search->code)->count();
                        }else{
                            return 'Invalid method';
                        }
                    }
            }
            
            Cache::put($var_cache, $data, 1440);
            
        }
        
        return Cache::get($var_cache);
    }
    
    /*
    |
    | Get total amount of Facebook cities on owloo.
    |
    */
    public function getTotalRegions($where)
    {
        
        $where = strtolower($where);
        
        $var_cache = 'total' . ucfirst($where) . 'FacebookRegions';
        
        if (!Cache::has($var_cache)) {
            
            switch ($where)
            {
                case 'world':
                    $data['total'] = FacebookRegion::count();
                    break;

                case 'hispanic':
                    $data['total'] = FacebookRegion::whereIdiom('es')->count();
                    break;

                default:
                    $country_idiom = FacebookCountry::whereIdiom($where)->first();
                    if ($country_idiom) {
                        $data['total'] = FacebookRegion::whereIdiom($where)->count();
                    }
                    else {
                        $country_search = FacebookCountry::whereCode(FacebookCountry::whereSlug($where)->first(['code'])->code)->first();
                        if ($country_search) {
                            $data['total'] = FacebookRegion::whereCountryCode($country_search->code)->count();
                        }else{
                            return 'Invalid method';
                        }
                    }
            }
            
            
            Cache::put($var_cache, $data, 1440);
            
        }
        
        return Cache::get($var_cache);
    }
    
    public function getCountries()
    {
        if (!Cache::has('listCountries')) {

            $countries = FacebookCountry::orderBy('name','ASC')->get(['id_country', 'name', 'slug', 'code', 'abbreviation']);
            $cache = array();

            foreach ($countries as $value)
            {
                $cache[$value['slug']]['id'] = $value['id_country'];
                $cache[$value['slug']]['code'] = $value['code'];
                $cache[$value['slug']]['name'] = $value['name'];
                $cache[$value['slug']]['link'] = $value['slug'];
                $cache[$value['slug']]['abbreviation'] = $value['abbreviation'];
            }

            Cache::put('listCountries', $cache, 1440);

        }

        return Cache::get('listCountries');
    }
    
    /*
    |
    | Get list of facebook countries/cities.
    |
    */
    public function getCityCountries()
    {
        
        $var_cache = 'listCountriesCities';
        
        if (!Cache::has($var_cache)) {
        
            $countries = FacebookCountry::whereSupportsCity('1')->orderBy('name','ASC')->get(['id_country', 'name', 'slug', 'code', 'abbreviation']);
            
            $cache = array();
            foreach ($countries as $value)
            {
                
                $index = $value['slug'];
                $cache[$index]['id'] = $value['id_country'];
                $cache[$index]['code'] = $value['code'];
                $cache[$index]['name'] = $value['name'];
                $cache[$index]['abbreviation'] = $value['abbreviation'];
            }

            Cache::put($var_cache, $cache, 1440);

        }

        return Cache::get($var_cache);
    }

    /*
    |
    | Get list of facebook countries/regions.
    |
    */
    public function getRegionCountries(){
        
        $var_cache = 'listCountriesRegions';
        
        if (!Cache::has($var_cache)) {
        
            $countries = FacebookCountry::whereSupportsRegion('1')->orderBy('name','ASC')->get(['id_country', 'name', 'slug', 'code', 'abbreviation']);
            $cache = array();

            foreach ($countries as $value) {
                
                $index = $value['slug'];
                $cache[$index]['id'] = $value['id_country'];
                $cache[$index]['code'] = $value['code'];
                $cache[$index]['name'] = $value['name'];
                $cache[$index]['abbreviation'] = $value['abbreviation'];
                
            }

            Cache::put($var_cache, $cache, 1440);

        }

        return Cache::get($var_cache);
        
    }

    private function getTotalCategory() {
        
        if (!Cache::has('totalFacebookCategories')) {
            $data = FacebookCategory::count();
            Cache::put('totalFacebookCategories', $data, 1440);
        }
        
        return Cache::get('totalFacebookCategories');
        
    }

    public function getCategories() {
        
        $var_cache = 'listFacebookCategories';
        
        if (!Cache::has($var_cache)) {
        
            $ranking = FacebookCategory::orderBy('category','ASC')->get();
            
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

}