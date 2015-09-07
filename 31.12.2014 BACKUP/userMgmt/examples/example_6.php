<?php
require_once('../system/initiater.php');
$site->restricted_page('index.php', true);

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

<h4>How to use the caching system.</h4>

To use the caching system, simply follow this recipe:<br />
<br />
<strong>1).</strong> Fetch the cached content based on the "unique_name_here" eg. "statistics".<br />
<code>$output = $site->cache_show('unique_name_here');</code><br />
<br />
<strong>2).</strong> if the cached content was not found or if the caching is outdated by the systems default cache time, we need to recache the content<br />
<code>if(empty($output)){<br />
&nbsp;&nbsp;&nbsp;$output = 'This is the output we need to cache or recache.';</code><br />
<strong>2).</strong> Cache the new content to the "unique_name_here" file.<br />
<code>&nbsp;&nbsp;&nbsp;$site->cache($output, 'unique_name_here');<br />
}</code><br />
<br />

<strong>3).</strong> show the cached or the newly cached output<br />
<code>echo $output;
</code>
<br />
<br />

<hr />
Complete code:<br />
<code>$output = $site->cache_show('unique_name_here');<br />
<br />
if(empty($output)){<br />
&nbsp;&nbsp;&nbsp;$output = 'This is the output we need to cache or recache.';<br />
&nbsp;&nbsp;&nbsp;$site->cache($output, 'unique_name_here');<br />
}<br />
<br />
echo $output;
</code>

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>