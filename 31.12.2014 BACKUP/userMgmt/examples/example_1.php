<?php
require_once('../system/initiater.php');
$site->restricted_page('index.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

Hi, This page can only be accessed if the visitor is logged in!<br />
<br />
To limit a whole page to only logged in users, add the code below to the top of your page just after you included the system.<br />
<code>$site->restricted_page('../login.php');</code><br />
This line of code will redirect any user who is not logged in, to the "login.php" page (Remember to change the "../login.php" to the correct path!).<br />
<br />
Open this file in you text editor to see how it is done on this page.

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>