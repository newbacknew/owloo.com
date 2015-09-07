<?php
//session_start();
$_SESSION['time_new'] = time();
$db_host = "localhost";
$db_username = "owloo_admin";
$db_password = "fblatamx244";
$db_name = "owloo_twitter";
mysql_connect($db_host, $db_username, $db_password) or die("not connected");
mysql_select_db($db_name);
mysql_query('SET NAMES \'latin1\'');
define("SITE_URL", "http://www.owloo.com/ranking_twitter/");
define("DOMAIN_NAME", "owloo.com");
define("ADMIN_EMAIL", "chintan.sarda@portalsolutions.in");
define("NOTIFY_EMAIL", "info@portalsolutions.in");
define("SITE_INCLUDE", SITE_URL . "include/");
define("SITE_JS", SITE_URL . "js/");
define("SITE_IMAGE", SITE_URL . "images/");
define("USER_IMAGE", SITE_URL . "userimg/");
define("HOMEPAGE_SMALL", "26x26");
define("RECENTBRAND_BIG", "123x125");
define("PRICEPAGE_THUMB", "95x79");
define("TEMPORALPAGE_THUMB", "89x85");
include("../lang/en_us.php");
?>