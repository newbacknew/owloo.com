<?php
include('owloo_config.php');

echo '<h1>Generando el sitemap...</h1>';

$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset
      xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
      xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
      xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">";

$url = array(
'http://www.owloo.com/', 
'http://www.owloo.com/facebook-stats/world-ranking/',
'http://www.owloo.com/facebook-stats/hispanic-ranking/',
'http://www.owloo.com/facebook-stats/cities/',
'http://www.owloo.com/facebook-stats/continents/',
'http://www.owloo.com/twitter-stats/',
'http://www.owloo.com/twitter-stats/hispanic/',
'http://www.owloo.com/features/',
'http://www.owloo.com/privacy/',
'http://www.owloo.com/facebook-stats/',
'http://www.owloo.com/facebook-stats/pages/hispanic/',
'http://www.owloo.com/facebook-stats/pages/world/'
);


foreach($url as $link){
$xml .= "
<url>
  <loc>".$link."</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Countries
$query =   "SELECT nombre, code, name 
			FROM country 
			ORDER BY 1 ASC;
			";
$que = mysql_query($query) or die(mysql_error());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>http://www.owloo.com/facebook-stats/".convert_to_url_string($fila['name'])."/</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Cities
$query =   "SELECT nombre, code, name 
			FROM country 
			ORDER BY 1 ASC;
			";
$que = mysql_query($query) or die(mysql_error());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>http://www.owloo.com/facebook-stats/cities/".convert_to_url_string($fila['name'])."/</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Countries page
$query =   "SELECT nombre, code, name 
			FROM country 
			ORDER BY 1 ASC;
			";
$que = mysql_query($query) or die(mysql_error());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>http://www.owloo.com/facebook-stats/pages/country/".convert_to_url_string($fila['code'])."/</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Facebook page
$query =   "SELECT id_page, username 
			FROM facebook_page
			WHERE active = 1 
			ORDER BY 1 ASC;
			";
$que = mysql_query($query) or die(mysql_error());
while($fila = mysql_fetch_assoc($que)){
$xml .= "
<url>
  <loc>http://www.owloo.com/facebook-stats/pages/".convert_to_url_string($fila['username'])."/</loc>
  <changefreq>daily</changefreq>
</url>";
}

//Twitter's profiles
reconnect_db('owloo_twitter');

$qry = "";
$qry = " SELECT owloo_screen_name FROM owloo_user_master";
$qry = $qry . " Order By owloo_followers_count DESC";
$qrydata = mysql_query($qry);
while ($fetchdata = mysql_fetch_array($qrydata)){
$xml .= "
<url>
  <loc>http://www.owloo.com/twitter-stats/userpage/".$fetchdata['owloo_screen_name']."</loc>
  <changefreq>daily</changefreq>
</url>";
}

$xml .= "
</urlset>";

$f = fopen(__DIR__.'/sitemap.xml', 'w+');
fwrite($f, $xml);
fclose($f);

echo '<p>Sitemap generado!</p>';