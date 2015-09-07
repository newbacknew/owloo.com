<?php
require_once('../system/initiater.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

<h4>Check If In Use</h4>
There are a couple of neat functions which helps you check if a username, email or like is already in use by another member.<br />
<code>$site->check_email('test@domain.com');</code> will return true if in use, and false if not in use.<br />
<code>$site->check_username('JohnDoe');</code> will return true if already taken, and false if not.

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>