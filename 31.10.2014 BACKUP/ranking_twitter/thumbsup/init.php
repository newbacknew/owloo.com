<?php
/**
 * ThumbsUp
 *
 * @author     Geert De Deckere <geert@idoe.be>
 * @link       http://geertdedeckere.be/shop/thumbsup/
 * @copyright  Copyright 2009-2010
 */

// The path pointing to this thumbsup directory
define('THUMBSUP_DOCROOT', realpath(dirname(__FILE__)).'/');

// Load the required ThumbsUp classes
require THUMBSUP_DOCROOT.'classes/thumbsup.php';
require THUMBSUP_DOCROOT.'classes/thumbsup_cookie.php';
require THUMBSUP_DOCROOT.'classes/thumbsup_item.php';
require THUMBSUP_DOCROOT.'classes/thumbsup_items.php';
require THUMBSUP_DOCROOT.'classes/thumbsup_template.php';

// Debug mode is enabled
if (ThumbsUp::config('debug'))
{
	// Enable all error reporting
	ThumbsUp::debug_mode();

	// Show an error if the headers are already sent
	if (headers_sent())
	{
		trigger_error('thumbsup/init.php must be included before any output has been sent. Include it at the very top of your page.');
	}
}

// Enable support for json functions
ThumbsUp::json_support();

// Register new votes if any
ThumbsUp::catch_vote();
