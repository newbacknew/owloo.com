<?php
//session_start();
$_SESSION['time_new'] = time();
$db_host = "localhost";
$db_username = DB_USER;
$db_password = DB_PASS;
$db_name = DB_NAME_TW;
$conn = mysql_connect($db_host, $db_username, $db_password) or die("not connected");
mysql_select_db($db_name, $conn);
mysql_query('SET NAMES \'latin1\'');
define("SITE_URL", URL_ROOT."ranking_twitter/");
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
?>