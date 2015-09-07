<?php
    require_once('../../owloo_config.php');
    require_once('../../ranking_twitter/config/config.php');
?>
<?php
    if(!isset($_GET['screenname']) || empty($_GET['screenname'])){
        header('Location: '.URL_ROOT);
        exit();
    }
    
    $qry = "";
    $qry = "SELECT owloo_user_id ";
    $qry = $qry . " from owloo_user_master";
    $qry = $qry . " Where owloo_screen_name LIKE '" . mysql_real_escape_string($_GET['screenname']) . "';";
    
    $res = mysql_query($qry);
    $fila = mysql_fetch_array($res);
    
    if(isset($fila['owloo_user_id'])){
        $update_id = $fila['owloo_user_id'];
        $screen_name = $_GET['screenname'];
    }
    else{
        header('Location: '.URL_ROOT);
        exit();
    }
    
?>
/***** TWITTER CHARTS *****/

<?php if(isset($_GET['require_jquery'])) require_once('jquery.min.js'); ?>

<?php require_once('statistics/highcharts.js'); ?>

<?php include("../../ranking_twitter/chart/followergrowth.php"); ?>

<?php include("../../ranking_twitter/chart/tweetperhour.php"); ?>

<?php include("../../ranking_twitter/chart/tweetperday.php"); ?>

<?php include("../../ranking_twitter/chart/mostmentions.php"); ?>

<?php include("../../ranking_twitter/chart/mostusedhashtag.php"); ?>

/***** END TWITTER CHARTS *****/