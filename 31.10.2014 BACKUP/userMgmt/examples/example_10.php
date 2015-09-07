<?php
require_once('../system/initiater.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

<h4>Pagination System</h4>
The pagination system is a bit more tricky to learn how to use, but I will do what I can make it as easy to understand as possible.<br />
Please note that the pagination system will only generate the buttons for you, and make them link to the same page with either a hashtag and/or page GET value.<br />
<br />
The code for generating the pagination buttons is like this:<br />
<code>$site->generatePagination($total, $currentPage, $hash = null, $limit = 10);</code><br />
$total = the total number of items (eg. if you want to show pagination for search results, and the result returns 200 hits, then you need to set this to 200.<br />
$currentPage = the page the user is currently on. This could normally be a $_POST or $_GET variable.<br />
$hash = if you use a hash tag to keep a specific tab open, add the hashtag here<br />
$limit = The number of items to show per page.<br />
<br />
Example code: <code>$site->generatePagination(200, $_GET['page'], null, 10);</code> will generate this:<br />
<?php echo $site->generatePagination(200, $_GET['page'], null, 10); ?>
<br />
You can wrap a div around the output with the class <code>pagination</code>, to manpulate it to look like this:<br />
<div class="pagination"><?php echo $site->generatePagination(200, $_GET['page'], null, 10); ?></div>

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>