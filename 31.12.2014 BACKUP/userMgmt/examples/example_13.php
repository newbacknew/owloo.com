<?php
require_once('../system/initiater.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

<h4>Get/Show System Settings</h4>
There are 2 ways of getting access to all the system configuration information.<br />
<code>$this->config['The_Setting_Name']</code>: This way is only possible from within the system class files.<br />In the 'The_Setting_Name' place, you can add any setting key you want to retreive, eg. <code>$this->config['base_url']</code>.<br />
<br />
To retreive config information outside the system, we need to use the <code>$site->config('The_Setting_Name');</code> function. Some data will not be retriveable with this function for security reasons (like database information, hash keys and like).<br />
<br />
Example: <code>$site->config('base_url');</code><br />
Output: <code><?php echo $site->config('base_url'); ?></code><br/>
<br />
Example: <code>$site->config('version');</code><br />
Output: <code><?php echo $site->config('version'); ?></code>

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>