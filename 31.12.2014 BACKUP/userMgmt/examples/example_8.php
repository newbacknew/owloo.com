<?php
require_once('../system/initiater.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

<h4>Username Formatting</h4>
This function will help format the username ending like " John's " or " Anders' ".<br />
<br />
<code>$site->format_ending('John');</code> Will output: <code><?php echo $site->format_ending('John'); ?></code><br />
<code>$site->format_ending('Jonas');</code> Will output: <code><?php echo $site->format_ending('Jonas'); ?></code>

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>