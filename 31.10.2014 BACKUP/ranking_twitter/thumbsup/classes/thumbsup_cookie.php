<?php defined('THUMBSUP_DOCROOT') or exit('No direct script access');
/**
 * ThumbsUp
 *
 * @author     Geert De Deckere <geert@idoe.be>
 * @link       http://geertdedeckere.be/shop/thumbsup/
 * @copyright  Copyright 2009-2010
 */
class ThumbsUp_Cookie {

	/**
	 * @var  string  Cleaned cookie contents: item ids separated by dots, e.g. "1.22.9.347.98".
	 */
	protected static $cookie;

	/**
	 * Return clean cookie contents.
	 * Invalid cookies will be deleted automatically.
	 *
	 * @return  string
	 */
	public static function get()
	{
		// The cookie has already been loaded
		if (self::$cookie !== NULL)
			return self::$cookie;

		// If no cookie exists, we're out of here
		if ( ! isset($_COOKIE[ThumbsUp::config('cookie_name')]))
			return self::$cookie = '';

		// Make sure our cookie value is a string
		self::$cookie = (string) $_COOKIE[ThumbsUp::config('cookie_name')];

		// The cookie should should only contain ids separated by dots
		if ( ! preg_match('~^(?:\d+\.)*+\d+$~D', self::$cookie))
		{
			// Delete invalid cookie
			self::delete();
		}

		// Return the cookie string
		return self::$cookie;
	}

	/**
	 * Looks for a single item id in the cookie.
	 *
	 * @param   integer  item id
	 * @return  boolean  id found or not?
	 */
	public static function find_id($id)
	{
		// Look for the given id in a cookie enclosed by dots
		return (strpos('.'.self::get().'.', '.'.$id.'.') !== FALSE);
	}

	/**
	 * Adds a single item id to the cookie.
	 *
	 * @param   integer  item id
	 * @return  boolean  was setcookie() successful or not?
	 */
	public static function add_id($id)
	{
		// Don't add double ids
		if (self::find_id($id))
			return TRUE;

		// Add the id to the cookie string.
		// The trim is needed for when adding the first id.
		$cookie = ltrim(self::get().'.'.$id, '.');

		// A cookie lifetime of 0 will keep the cookie until the session ends
		$expire = ( ! ThumbsUp::config('cookie_lifetime')) ? 0 : time() + (int) ThumbsUp::config('cookie_lifetime');

		// If any output has been sent, setcookie() will fail.
		// If we're not in debug mode, we'll fail silently.
		if (headers_sent() AND ! ThumbsUp::config('debug'))
			return FALSE;

		// Should return TRUE; does not necessarily mean the user accepted the cookie, though
		return setcookie(ThumbsUp::config('cookie_name'), $cookie, $expire, ThumbsUp::config('cookie_path'), ThumbsUp::config('cookie_domain'));
	}

	/**
	 * Deletes the cookie completely.
	 *
	 * @return  boolean  was setcookie() successful or not?
	 */
	public static function delete()
	{
		// Delete cookie contents
		self::$cookie = '';
		unset($_COOKIE[ThumbsUp::config('cookie_name')]);

		// If any output has been sent, setcookie() will fail.
		// If we're not in debug mode, we'll fail silently.
		if (headers_sent() AND ! ThumbsUp::config('debug'))
			return FALSE;

		// Setting a cookie with a value of FALSE will try to delete it
		return setcookie(ThumbsUp::config('cookie_name'), FALSE, time() - 86400, ThumbsUp::config('cookie_path'), ThumbsUp::config('cookie_domain'));
	}

}