<?php
require_once('../system/initiater.php');

// This will include the page header content, eg. the navigation etc.
$site->template_show_header();
?>

<h4>Input Sanitation</h4>
It is very important that you sanitise user input, and to help with this take we have the function "sanitize()". To use this function simply wrap it around the user input and it will return the sanitised input. Please note that this function will help you sanitise the input, but please remember that with everything security I cannot guarantee nothing will get past it.<br />
<br />
Useage: <code>$site->sanitize($user_input, $type);</code><br />
$type is what the input you want. There are several to choose from so I have added a list below:<br />
<ul>
	<li><strong>integer</strong>. Allowed input: 0-9</li>
	<li><strong>mixedint</strong>. Allowed input: 0-9 , and .</li>
	<li><strong>float</strong>. Allowed input: 0-9 and .</li>
	<li><strong>string</strong>. Allowed input: all letters, digits and special characters</li>
	<li><strong>purestring</strong>. Allowed input: all letters, digits and special characters (HTML is stripped)</li>
	<li><strong>email</strong>. Allowed input: all letters, digits and $-_.+!*'{}|^~[]`#%/?=@&amp;</li>
	<li><strong>phone</strong>. Allowed input: digits and . + -</li>
</ul>
All the input will be XSS sanitised as well. To just remove XSS, use the "string" type.<br />
<br />
<br />
<strong>Example</strong><br />
Input: <code>&lt;script>alert(\'ATTACK!\')&lt;/script>&lt;strong>Hello&lt;/strong> &lt;a href="www.google.com">World&lt;/a></code><br />
Sanitation type: <code>string</code><br />
Output: <?php echo $site->sanitize('<script>alert(\'ATTACK!\')</script><strong>Hello</strong> <a href="www.google.com">World</a>', 'string'); ?><br />
<br />
<strong>Example</strong><br />
Input: <code>&lt;script>alert(\'ATTACK!\')&lt;/script>&lt;strong>Hello&lt;/strong> &lt;a href="www.google.com">World&lt;/a></code><br />
Sanitation type: <code>purestring</code><br />
Output: <?php echo $site->sanitize('<script>alert(\'ATTACK!\')</script><strong>Hello</strong> <a href="www.google.com">World</a>', 'purestring'); ?><br />
<br />
<strong>Example</strong><br />
Input: <code>abc123</code><br />
Sanitation type: <code>integer</code><br />
Output: <?php echo $site->sanitize('abc123', 'integer'); ?><br />
<br />
<strong>Example</strong><br />
Input: <code>abc123.123,1</code><br />
Sanitation type: <code>mixedint</code><br />
Output: <?php echo $site->sanitize('abc123.123,1', 'mixedint'); ?><br />
<br />
<strong>Example</strong><br />
Input: <code>abc123.123,1</code><br />
Sanitation type: <code>float</code><br />
Output: <?php echo $site->sanitize('abc123.123,1', 'float'); ?><br />
<br />
<strong>Example</strong><br />
Input: <code>&lt;script>alert(\'ATTACK!\')&lt;/script>&lt;strong>Hello&lt;/strong> test@email.com</code><br />
Sanitation type: <code>email</code><br />
Output: <?php echo $site->sanitize('<script>alert(\'ATTACK!\')</script><strong>Hello</strong> test@email.com', 'email'); ?><br />
<br />
<strong>Example</strong><br />
Input: <code>abc123.0495,344+44</code><br />
Sanitation type: <code>phone</code><br />
Output: <?php echo $site->sanitize('abc123.0495,344+44', 'phone'); ?>
<?php
// This will include the page footer content, eg. the copyright bar etc.
$site->template_show_footer();
?>