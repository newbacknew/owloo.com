<?php
include("config/config.php");
if ($_GET["pts"]) {
    $uid = $_GET["uid"];
    $pts = $_GET["pts"];
    $ip = $_SERVER['REMOTE_ADDR'];
    $qry = "";
    $qry = "INSERT INTO owloo_user_rating ";
    $qry = $qry . " (owloo_user_id, owloo_rating_points, owloo_rating_ip, owloo_rating_date)  VALUES (";
    $qry = $qry . " '" . $uid . "',";
    $qry = $qry . " '" . $pts . "',";
    $qry = $qry . " '" . $ip . "',";
    $qry = $qry . " '" . Date("Y-m-d") . "')";
    $result = mysql_query($qry);
    if ($result == 1) {
        echo "1";
    } else {
        echo "0";
    }
} else {
    echo "<script>window.location='" . SITE_URL . "'</script>";
}
?>