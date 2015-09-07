<?php
require_once('../system/initiater.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>
This page can be access by anyone, but the content below will be limited in the same way we limit pages (See first 4 examples).<br />
<br />

<br />
If you are NOT logged in, you can see the text in this box:<br />
<div class="well">
<?php
	if(!$site->loggedin){
		echo 'Hello Guest, login to see more!';
	}
?>
</div>

<br />
If you are logged in, you can see the text in this box:<br />
<div class="well">
<?php
	if($site->loggedin){
		echo 'You are logged in!';
	}
?>
</div>

<br />
If you are an admin, you can see the text in this box:<br />
<div class="well">
<?php
	if($site->restricted_page(null, true)){
		echo 'You are an Admin, cool!';
	}
?>
</div>

<br />
If you are in the VIP group, you can see the text in this box:<br />
<div class="well">
<?php
	if($site->restricted_page(null, null, null, '2')){
		echo 'You are in the VIP group! (Admins can see everything)';
	}
?>
</div>

<br />
If you have 50 or more in "view level", you can see the text in this box:<br />
<div class="well">
<?php
	if($site->restricted_page(null, null, 50)){
		echo 'Wow, you have 50 or more in view level (Admins can see everything)';
	}
?>
</div>

<br />
If your username is 'john', you can see the text in this box:<br />
<div class="well">
<?php
	if($site->username == 'john'){
		echo 'Hi John, nice to see you. ';
	}
?>
</div>

<br />
If you are allowed to send Private Messages, you can see the text in this box:<br />
<div class="well">
<?php
	if($site->permissions['pm_write']){
		echo '<button>Send PM now!</button>';
	}
?>
</div>

<br />
If your account ID = 1, you can see the text in this box:<br />
<div class="well">
<?php
	if($site->uid == 1){
		echo 'Hi '.$site->username;
	}
?>
</div><br />
<br />
You can see how all these are done by viewing this file's source code. But to elaborate on the "$site->restricted_page()" functions:<br />
if the redirect url/file is left empty (null) like for example: <code>$site->restricted_page(null, true)</code> it will not redirect the user if they do not forfil the requirements, the script will simply return "false"

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>