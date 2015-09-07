<?php
require_once('../system/initiater.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>
Hi there, here is a list of the examples in this folder:<br />
<br />
<strong>Limiting Content/Pages</strong>
<ol>
	<li><a href="example_1.php">Example 1</a> - How to limit a page to logged in members only</li>
	<li><a href="example_2.php">Example 2</a> - How to limit a page to Admins only</li>
	<li><a href="example_3.php">Example 3</a> - How to limit a page to specific member groups only</li>
	<li><a href="example_4.php">Example 4</a> - How to limit a page to a minimum view level only</li>
	<li><a href="example_5.php">Example 5</a> - How to limit content in many ways</li>
</ol>
<br />
<strong>Misc. Helper Functions</strong>
<ol>
	<li><a href="example_6.php">Example 6</a> - Using the caching system.</li>
	<li><a href="example_7.php">Example 7</a> - Show "Time Since", based on timestamp.</li>
	<li><a href="example_8.php">Example 8</a> - John's or Jonas' - Format username endings</li>
	<li><a href="example_9.php">Example 9</a> - Using the notification system</li>
	<li><a href="example_10.php">Example 10</a> - Using the pagination system</li>
	<li><a href="example_11.php">Example 11</a> - Check if in use (username, email etc)</li>
	<li><a href="example_12.php">Example 12</a> - Get/Show user information</li>
	<li><a href="example_13.php">Example 13</a> - Get/Show system settings</li>
</ol>
<br />
<strong>Security Functions</strong>
<ol>	
	<li><a href="example_14.php">Example 14</a> - Protecting forms with the CSRF system</li>
	<li><a href="example_15.php">Example 15</a> - Sanitizing user input</li>
</ol>
<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>