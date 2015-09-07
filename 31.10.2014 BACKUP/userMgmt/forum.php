<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.4.0 																		 *
 *****************************************************************************************/
 
require_once('system/initiater.php');
$site->restricted_page('login.php');

$site->template_show_header();

?>
<div class="row">
	<?php
		echo $site->showCategories();
	?>
</div>
<?php
$site->template_show_footer();