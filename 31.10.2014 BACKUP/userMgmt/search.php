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
<h2>Search</h2>
<form action=""> 
	
	<div>
		<label>Search Query<small>Type who or what you are looking for<br />(a-z/A-Z, 0-9, - and _ is allowed).</small></label>
		<input type="text" placeholder="Search Query" name="query" />
	</div>
	
	<div>
		<label>Search Areas<small>Select which areas of the website you would like to search.</small></label>   
		<div>
			<input type="checkbox" name="srch_members" value="members" />
			<label for="srch_members">Members</label>
		</div>
	</div>
	<div>
		<button type="submit">Search Now!</button>
	</div>
</form>

<h2>Search Result</h2>
<div id="search_result">
	<p>No search query submitted yet..</p>
</div>
<?php
$site->template_show_footer();