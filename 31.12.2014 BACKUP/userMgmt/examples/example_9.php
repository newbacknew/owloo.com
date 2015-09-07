<?php
require_once('../system/initiater.php');

//Notification 1, to be displayed in section 1
$site->setNotification('section1', 'This is the section 1 notification!', 'See me!', 'info');

//Notification 2, to be displayed in section 2
$site->setNotification('section2', 'This is the section 2 notification!', 'See me!', 'warning');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

<h4>Notification System</h4>
The notification system has been coded to make it as easy to use as possible, while making it possible to have multiple notifications on the same page.<br/>
Notifications are created using the function: <code>$site->setNotification('where', 'body', 'title', 'type'){</code>.<br />
The "where" variable is a unique identifier for when showing notifications. Eg. the system can create multiple notifications to be displayed in different areas on the website, at the same time.<br />
The "type" variable is which type of notification you wish to display. The options are: success, error, warning and info.<br />
<br />
This page already have 2 notifications set when you loaded this page. The first one is set to display in "Section 1" the second one is set to display in "Section 2". If no notification for specified "where" variable is set, it will just return empty.<br />
<br />
The code for the 2 notification already set on this page is:<br />
<code>$site->setNotification('section1', 'This is the section 1 notification!', 'See me!', 'info');</code><br />
<code>$site->setNotification('section2', 'This is the section 2 notification!', 'See me!', 'warning');</code>
<br />
<hr />
<strong>Section 1</strong><br />
<code>$site->showNotification('section1');</code>
<?php echo $site->showNotification('section1'); ?>
<br />
<strong>Section 2</strong><br />
<code>$site->showNotification('section2');</code>
<?php echo $site->showNotification('section2'); ?>

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>