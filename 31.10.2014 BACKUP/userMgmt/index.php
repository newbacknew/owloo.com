<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.4.0 																		 *
 *****************************************************************************************/
 	header('Location: profile.php');
	exit();
	require_once('system/initiater.php');
	$site->restricted_page('login.php');
	
	$site->template_show_header();
?>
<div class="row show-grid">
    <div class="span12">
		<h1>Solid PHP User Management System</h1>
		<p>Many features in the administration panel has been disabled in the demo, this is to prevent mischievousness</p>
	</div>
</div>
<?php
$site->template_show_footer();