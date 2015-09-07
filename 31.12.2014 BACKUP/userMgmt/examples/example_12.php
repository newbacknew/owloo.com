<?php
require_once('../system/initiater.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

<h4>Get/Show User Information</h4>
There are a lot of information about the current user in the system.. but a lot of ways to retrive that information too.<br />
<code>$site->username;</code> will return the username of the user (blank if not logged in).<br />
<pre><?php echo $site->username; ?></pre>
<br />
<code>$site->uid;</code> will return the user id of the user (blank if not logged in).<br />
<pre><?php echo $site->uid; ?></pre>
<br />
<code>$site->avatar;</code> will return the avatar of the current user (blank if no custom avatar is in use)<br />
<pre><?php echo $site->avatar; ?></pre>
<br />
<code>$site->permissions;</code> Will return an array of the permissions the user got (blank if not logged in).<br />
<pre><?php print_r($site->permissions); ?></pre>
<br />
<code>$site->showPurchases();</code> Will return a table of payments the user has made, if any.<br />
<pre><?php echo $site->showPurchases(); ?></pre>
<br />
<code>$site->get_profile($usr, $how);</code> Will return a table of payments the user has made, if any.<br />
$usr can be with the username or ID of a user. If it is the users ID you will be using, you need to set the "how" to 'id'. If not, you can remove the 'how' variable or set it to 'username' (it is username by default).<br />
<pre><?php print_r($site->get_profile($site->uid, 'id')); ?></pre>
<br />
<code>$site->generateProfile();</code> Will generate the users profile fields.<br />
<pre><?php echo $site->generateProfile(); ?></pre>
<br />
<code>$site->retrive_pmlist( $asArray );</code> asArray can be true or false (false by default). It is set to true below, so the function will return all the PM's in array form:<br />
<pre><?php print_r($site->retrive_pmlist(true)); ?></pre>
<br />
<code>$site->get_friendlist( $asArray );</code> asArray can be true or false (false by default). It is set to true below, so the function will return all the users friends in array form:<br />
<pre><?php print_r($site->get_friendlist(true)); ?></pre>
<br />
<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>