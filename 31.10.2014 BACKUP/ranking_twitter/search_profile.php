<?php
	require_once('../owloo_config.php');
    require_once("config/config.php");
	$_GET['term'] = trim($_GET['term']);
	$filter = '';
	if(strlen($_GET['term']) < 2)
			exit();
    
	//SI SOLO HAY UNA PALABRA DE BUSQUEDA SE ESTABLECE UNA INSTRUCION CON LIKE
	$query = "SELECT DISTINCT *  FROM ((SELECT owloo_user_id, owloo_screen_name, owloo_user_photo FROM owloo_user_master WHERE owloo_screen_name LIKE '".mysql_real_escape_string($_GET['term'])."%' ORDER BY 1 ASC) UNION (SELECT owloo_user_id, owloo_screen_name, owloo_user_photo FROM owloo_user_master WHERE owloo_screen_name LIKE '%".mysql_real_escape_string($_GET['term'])."%' ORDER BY 1 ASC)) T LIMIT 10;";		
    
    $queEmp = mysql_query($query) or die(mysql_error());
    $_arreglo[] = array();
    $_arreglo = NULL;
	
	
	$first = true;
    $profiles = '';
    
	if(mysql_num_rows($queEmp) == 0)
		$profiles = '{ "label" : "sin resultado...", "value" : 0 }';
	while($resEmp = mysql_fetch_assoc($queEmp)){
		
		$profiles .= ((!$first)?',':'').'{ "label" : "'.$resEmp["owloo_screen_name"].'", "value" : '.$resEmp["owloo_user_id"].', "img_profile": "'.$resEmp["owloo_user_photo"].'" }';
		$first=false;
		
    }
	print '['.$profiles.']';