<?php
require_once('../system/initiater.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

<h4>Convert timestamp to "time since"</h4>
This functions will able you to convert unix timestamps into text like: "2 hours ago" or "7 days ago". You can also make it count down to a point set in the future (obviously).<br />
The timestamp I will use in this example will be the "3rd of Dec 2012 @ 16:50:39" and "3rd of Dec 2013 @ 16:50:39".<br />
<br />
<strong>"Time Since"</strong><br />
<code>This example page was created: &lt;?php echo $site->processtime(1354553439); ?></code><br />
This example page was created: <?php echo $site->processtime(1354553439); ?><br />
(With Label) This example page was created: <?php echo $site->processtime(1354553439, true); ?><br />
<br />
<strong>"Countdown"</strong><br />
<code>This example page was created: &lt;?php echo $site->processtime(1354553439, false, true); ?></code><br />
This page will be 1 year old in: <?php echo $site->processtime(1386089439, false, true); ?><br />
(With Label) This page will be 1 year old in: <?php echo $site->processtime(1386089439, true, true); ?>

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>