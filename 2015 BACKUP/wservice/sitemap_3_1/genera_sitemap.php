<?php

set_time_limit(0);

require_once(__DIR__.'/../config.php');

echo '<h1>Generando el sitemap...</h1>';

$domain_prefig = array(
                        array('name' => 'sitemap_http_www', 'link' => 'http://www.owloo.com'),
                        array('name' => 'sitemap_https_www', 'link' => 'https://www.owloo.com'),
                        array('name' => 'sitemap_http', 'link' => 'http://owloo.com'),
                        array('name' => 'sitemap_https', 'link' => 'https://owloo.com')
                      );

foreach ($domain_prefig as $prefig) {
        
    $domain = $prefig['link'];
        
$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";

$url = array(
    '', 
    '/features', 
    '/privacy',
    '/facebook-statistics',
    '/facebook-research',
    '/facebook-stats/continents',
    '/facebook-stats/regions',
    '/facebook-stats/cities',
    '/facebook-stats/countries',
    '/facebook-analytics',
    '/facebook-analytics/pages',
    '/twitter-analytics',
    '/twitter-analytics/profiles',
    '/instagram-analytics',
    '/instagram-analytics/accounts'
);

foreach($url as $link){
$xml .= "
<url>
  <loc>".$domain.$link."</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Countries
$query =   "SELECT id_country, slug 
            FROM ".DB_RESULTS_PREFIX."facebook_countries 
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/facebook-stats/".$fila['slug']."</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Ciudades por país
$query =   "SELECT id_country, slug 
            FROM ".DB_RESULTS_PREFIX."facebook_countries
            WHERE code IN (SELECT country_code FROM ".DB_RESULTS_PREFIX."facebook_cities GROUP BY 1)
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/facebook-stats/cities/country/".$fila['slug']."</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Detalle de ciudades por país
$query =   "SELECT id_country, code, slug
            FROM ".DB_RESULTS_PREFIX."facebook_countries
            WHERE code IN (SELECT country_code FROM ".DB_RESULTS_PREFIX."facebook_cities GROUP BY 1)
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
    
    $query_city =  "SELECT id_city, slug 
                    FROM ".DB_RESULTS_PREFIX."facebook_cities
                    WHERE country_code = '$10'
                    ORDER BY 1 ASC;
                    ";
    $que_city = db_query_table_results($query_city, array($fila['code']));
    while($fila_city = mysql_fetch_assoc($que_city)){
$xml .= "
<url>
  <loc>".$domain."/facebook-stats/cities/country/".$fila['slug']."#".$fila_city['slug']."</loc>
  <changefreq>daily</changefreq>
</url>";
    }
}

//Regiones por país
$query =   "SELECT id_country, slug 
            FROM ".DB_RESULTS_PREFIX."facebook_countries
            WHERE code IN (SELECT country_code FROM ".DB_RESULTS_PREFIX."facebook_regions GROUP BY 1)
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/facebook-stats/regions/country/".$fila['slug']."</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Detalle de regiones por país
$query =   "SELECT id_country, code, slug
            FROM ".DB_RESULTS_PREFIX."facebook_countries
            WHERE code IN (SELECT country_code FROM ".DB_RESULTS_PREFIX."facebook_regions GROUP BY 1)
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
    
    $query_region =  "SELECT id_region, slug 
                    FROM ".DB_RESULTS_PREFIX."facebook_regions
                    WHERE country_code = '$10'
                    ORDER BY 1 ASC;
                    ";
    $que_region = db_query_table_results($query_region, array($fila['code']));
    while($fila_region = mysql_fetch_assoc($que_region)){
$xml .= "
<url>
  <loc>".$domain."/facebook-stats/regions/country/".$fila['slug']."#".$fila_region['slug']."</loc>
  <changefreq>daily</changefreq>
</url>";
    }
}

//Detalle de regiones
$query =   "SELECT id_region, slug 
            FROM ".DB_RESULTS_PREFIX."facebook_regions
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/facebook-stats/regions#".$fila['slug']."</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Detalle de ciudades
$query =   "SELECT id_city, slug 
            FROM ".DB_RESULTS_PREFIX."facebook_cities
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/facebook-stats/cities#".$fila['slug']."</loc>
  <changefreq>daily</changefreq>
</url>";
}

/***** FACEBOOK PAGES *****/

//Páginas del Facebook
$query =   "SELECT id_page, username 
            FROM ".DB_RESULTS_PREFIX."facebook_pages 
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/facebook-analytics/pages/".strtolower($fila['username'])."</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Ranking filtrado por tags
$query =   "SELECT id_category, category 
            FROM ".DB_RESULTS_PREFIX."facebook_pages_categories 
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/facebook-analytics/pages/tag/".strtolower($fila['category'])."</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Ranking filtrado por países
$query =   "SELECT id_country, slug 
            FROM ".DB_RESULTS_PREFIX."facebook_countries 
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/facebook-analytics/pages/country/".$fila['slug']."</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Ranking filtrado por países y tags
$query =   "SELECT id_country, slug 
            FROM ".DB_RESULTS_PREFIX."facebook_countries 
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
    $query_cat =   "SELECT id_category, category 
                FROM ".DB_RESULTS_PREFIX."facebook_pages_categories 
                ORDER BY 1 ASC;
                ";
    $que_cat = db_query_table_results($query_cat, array());
    while($fila_cat = mysql_fetch_assoc($que_cat)){
$xml .= "
<url>
  <loc>".$domain."/facebook-analytics/pages/country/".$fila['slug']."/tag/".$fila_cat['category']."</loc>
  <changefreq>daily</changefreq>
</url>";
    }
}


/***** TWITTER PROFILES *****/
//Perfiles del Twitter
$query =   "SELECT id, screen_name 
            FROM ".DB_RESULTS_PREFIX."twitter_profiles 
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/twitter-analytics/profiles/".strtolower($fila['screen_name'])."</loc>
  <changefreq>daily</changefreq>
</url>";
}


/***** INSTAGRAM PROFILES *****/

//Perfiles del Twitter
$query =   "SELECT id, username 
            FROM ".DB_RESULTS_PREFIX."instagram_profiles
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/instagram-analytics/accounts/".strtolower($fila['username'])."</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Perfiles del Twitter
$query =   "SELECT id_category, category 
            FROM ".DB_RESULTS_PREFIX."instagram_categories
            ORDER BY 1 ASC;
            ";
$que = db_query_table_results($query, array());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>".$domain."/instagram-analytics/accounts/tag/".strtolower($fila['category'])."</loc>
  <changefreq>daily</changefreq>
</url>";
}


$xml .= "
</urlset>";

$f = fopen(__DIR__.'/'.$prefig['name'].'.xml', 'w+');
fwrite($f, $xml);
fclose($f);

}

echo '<p>Sitemap generado!</p>';