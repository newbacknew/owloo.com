<?php
/**
 * ThumbsUp
 *
 * @author     Geert De Deckere <geert@idoe.be>
 * @link       http://geertdedeckere.be/shop/thumbsup/
 * @copyright  Copyright 2009-2010
 */
sleep(1);
// The path pointing to the thumbsup directory.
// We chop off the "admin" part here.
define('THUMBSUP_DOCROOT', substr(realpath(dirname(__FILE__)), 0, -5));

// Load the required ThumbsUp classes
require THUMBSUP_DOCROOT.'classes/thumbsup.php';
require THUMBSUP_DOCROOT.'classes/thumbsup_cookie.php';
require THUMBSUP_DOCROOT.'classes/thumbsup_admin.php';
require THUMBSUP_DOCROOT.'classes/thumbsup_item.php';
require THUMBSUP_DOCROOT.'classes/thumbsup_template.php';

// Debug mode is enabled
if (ThumbsUp::config('debug'))
{
	// Enable all error reporting
	ThumbsUp::debug_mode();
}

// Enable support for json functions
ThumbsUp::json_support();

// Power to the admin class!
new ThumbsUp_Admin(empty($_GET['action']) ? NULL : (string) $_GET['action']);
