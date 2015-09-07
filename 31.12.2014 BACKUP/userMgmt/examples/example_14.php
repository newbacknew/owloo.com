<?php
require_once('../system/initiater.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

<h4>CSRF Protection System (<a href="http://en.wikipedia.org/wiki/Cross-site_request_forgery">Wiki</a>)</h4>
To help you protect your forms against CSRF attacks, I will show you how to implement the CSRF Protection System this system uses, into your own forms. Please remember, with everything security I cannot guarantee nothing will get past it.<br />
<br />
<strong>1)</strong> Add a hidden input field to your form with the CSRF token like this:<br />
<code>&lt;input type="hidden" name="UniqueIdentifier" value="&lt;?php echo $this->csrfGenerate('UniqueIdentifier'); ?>"></code><br />
The name of the field must be the same as the "UniqueIdentifier" value you pass inside the csrfGenerate() function. The UniqueIdentifier is used to retrive the correct CSRF token which we need to check the input field value with.<br />
<br />
<strong>2)</strong> Add the following check to your processing php script:<br />
<code>
if(!$this->csrfCheck('UniqueIdentifier')){<br />
// Possible CSRF Attack!<br />
}else{<br />
// CSRF Check verified, continue..<br />
}
</code>
<br />
<br />
This will help protect you and your users from CSRF Attacks on your site.

<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>