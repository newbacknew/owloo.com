<?php

include("config/config.php");
session_start();
if ($_GET['uid']) {
    $uid = $_GET['uid'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $qry = "";
    $qry = $qry . "INSERT INTO owloo_profile_log (owloo_user_id, owloo_client_ip, owloo_entry_date) VALUES (";
    $qry = $qry . " '" . $uid . "',";
    $qry = $qry . " '" . $ip . "',";
    $qry = $qry . " '" . Date("Y-m-d") . "')";
    $result = mysql_query($qry);
    echo $result;
} else {
    "<script>window.location='" . SITE_URL . "'</script>";
}
?>