<?php
require_once('../system/initiater.php');
$site->restricted_page('index.php', true);

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

This page can only be accessed if the visitor is an <strong>Admin</strong>!<br />
<br />
To limit a whole page to only admin users, add the code below to the top of your page just after you included the system.<br />
<code>$site->restricted_page('index.php', true);</code><br />
This line of code will redirect any user who is not an admin, to the "index.php" page (Remember to change the "index.php" to the correct path!).<br />
<br />
Open this file in you text editor to see how it is done on this page.

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>