<?php
require_once('../system/initiater.php');
$site->restricted_page('index.php', null, null, '2');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

This page can only be accessed if the visitor is in the <strong>VIP</strong> group!<br />
<br />
To limit a whole page to only specific membergroups, add the code below to the top of your page just after you included the system.<br />
<code>$site->restricted_page('index.php', null, null, null, '2');</code><br />
This line of code will redirect any user who is not in the VIP group (id: 2) to the "index.php" page (Remember to change the "index.php" to the correct path!).<br />
You can add as many groups as you wish to the list of "allowed" groups, simply by comma separating the group ID's like this:<br />
<code>$site->restricted_page('index.php', null, null, null, '2,3,6,9');</code><br />
<br />
Open this file in you text editor to see how it is done on this page.

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>