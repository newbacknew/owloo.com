<?php
include("config/config.php");
include("../config_content.php");
$update_id = NULL;
$screen_name = NULL;
if(isset($_POST['id'])){
	$update_id = mysql_real_escape_string($_POST['id']);
	$screen_name = mysql_real_escape_string($_POST['user']);
?>
<script type="text/javascript" src="<?=URL_JS?>jquery.js"></script>
<script src="<?=URL_ROOT?>ranking_twitter/js/highcharts.js"></script>
<div class="chartDiv">
    <?php include("chart/followergrowth.php"); ?>
</div>
<div class="chartDiv">
    <?php include("chart/tweetperhour.php"); ?>
</div>
<div class="chartDiv">
    <?php include("chart/tweetperday.php"); ?>
</div>
<?php if(false){ ?>
<div class="chartDiv">
    <?php include("chart/curseword.php"); ?>
</div>
<?php }  ?>
<div class="chartDiv">
    <?php include("chart/mostmentions.php"); ?>
</div>
<div class="chartDiv">
    <?php include("chart/mostusedhashtag.php"); ?>
</div>
<?php }
else{
	echo 'Usuario no registrado.';
}?>