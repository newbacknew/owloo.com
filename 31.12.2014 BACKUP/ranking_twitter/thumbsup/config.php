<?php
/**
 * ThumbsUp
 *
 * @author     Geert De Deckere <geert@idoe.be>
 * @link       http://geertdedeckere.be/shop/thumbsup/
 * @copyright  Copyright 2009-2010
 */
 
 return array(

	/**
	 * (string) The URL of your website, pointing to the thumbsup folder.
	 * If in doubt, visit this address with your browser. You should see a smiley.
	 * Example: 'http://yoursite.com/path/to/thumbsup/'
	 */
	'url' => ((isset($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] != 'off')?'HTTPS':'HTTP').'://www.owloo.com/ranking_twitter/thumbsup/',

	/**
	 * (string) The Data Source Name, or DSN, contains the information required
	 * to connect to the database.
	 * See: http://php.net/manual/pdo.construct.php
	 * Example: 'mysql:dbname=yourdatabase;host=localhost'
	 */
	'database_dsn' => 'mysql:dbname=owloo_thumbsup;host=localhost',

	/**
	 * (string) The username for the DSN string.
	 */
	'database_user' => 'owloo_admin',

	/**
	 * (string) The password for the DSN string.
	 */
	'database_pass' => 'fblatamx244',

	/**
	 * (array) An optional key=>value array of driver-specific connection options.
	 * Most of the time, you can just leave this as is.
	 */
	'database_driver_options' => array(),

	/**
	 * (string) The prefix that is prepended to the ThumbsUp table names.
	 * If you change this prefix, be sure to also update your actual table names in the database.
	 * Example: the prefix 'tup_' corresponds to the tables 'tup_items' and 'tup_votes'
	 */
	'database_table_prefix' => 'thumbsup_',

	/**
	 * (string) The default ThumbsUp template to use. If you don't choose a template
	 * when creating a ThumbsUp item, this template will be used.
	 * All available templates can be found in the templates directory.
	 */
	'default_template' => 'mini_thumbs',

	/**
	 * (array) List of the default formats to use for each template. Each format string
	 * defines which values to output and how. Six variables are available (wrapped in braces):
	 * - {UP} for the number of up votes
	 * - {DOWN} for the number of down votes
	 * - {PCT_UP} for the percentage of votes that is up
	 * - {PCT_DOWN} for the percentage of votes that is down
	 * - {TOTAL} for the total number of votes (up + down)
	 * - {BALANCE} for the vote balance (up - down)
	 * See the online documentation for a more extensive explanation.
	 */
	'default_formats' => array(
		'buttons'        => '{UP} out of {TOTAL} people like this.',
		'mini_poll'      => '{PCT_UP}% || {PCT_DOWN}%',
		'mini_thumbs'    => '{+BALANCE}',
		'thumbs_up'      => '{UP}',
		'thumbs_up_down' => '{+UP} || {-DOWN}',
		'up_down'        => '{+BALANCE}',
	),

	/**
	 * (boolean) Enable or disable a cookie check when a user votes. If a cookie is found
	 * that contains the current item ID, the user won't be able to vote for it again.
	 * Note: disabling this check will turn off any thumbsup cookies to be sent.
	 */
	'cookie_check' => TRUE, // TRUE or FALSE

	/**
	 * (string) The name of the ThumbsUp cookie.
	 */
	'cookie_name' => 'thumbsup',

	/**
	 * (integer) The lifetime of the cookie. In other words, the number of seconds
	 * since the last vote before the cookie expires and gets deleted.
	 * If set to 0, the cookie will expire when the browser closes.
	 */
	'cookie_lifetime' => 3600 * 24 * 365, // 1 year

	/**
	 * (string) The path on the server in which the cookie will be available on.
	 * If set to '/', the cookie will be available within the entire domain.
	 * See: http://php.net/manual/function.setcookie.php
	 * Most of the time, you can just leave this as is.
	 */
	'cookie_path' => '/',

	/**
	 * (string) The domain that the cookie is available on. You can make the cookie
	 * available across subdomains if you need to. Example: '.yoursite.com'
	 * See: http://php.net/manual/function.setcookie.php
	 * Most of the time, you can just leave this as is.
	 */
	'cookie_domain' => '',

	/**
	 * (boolean) Enable or disable an IP check when a user votes. If a previous vote
	 * for the item is found with the same IP, the user won't be able to vote for it again.
	 * Note: disabling this check will stop IP addresses from being stored upon vote.
	 */
	'ip_check' => FALSE, // TRUE or FALSE

	/**
	 * (integer) The lifetime of an IP address. A user with the same IP address can vote
	 * for an item after this number of seconds has past since the last vote from the IP.
	 * If set to 0, IP addresses will not expire.
	 */
	'ip_lifetime' => 0,

	/**
	 * (boolean) Enable or disable a user ID check when a user votes. This will prevent
	 * registered users to cast multiple votes, regardless of the cookie and IP check settings.
	 * Note: this check does not prevent guests from voting. Set user_login_required to TRUE if you want to do so.
	 * Note: in order for this check to work, you need to supply a user_id_callback.
	 */
	'user_id_check' => FALSE, // TRUE or FALSE

	/**
	 * (callback) A callback for ThumbsUp to use to retrieve the ID of the currently logged in user.
	 * The callback should return the user ID as an integer. If an empty value like 0 or FALSE
	 * is returned, ThumbsUp will assume the user is not logged in.
	 * See: http://php.net/manual/language.pseudo-types.php#language.types.callback
	 */
	'user_id_callback' => '',

	/**
	 * (boolean) If set to TRUE, users will have to be logged in in order to vote.
	 * Guests won't be able to vote.
	 * Note: in order for this check to work, you need to supply a user_id_callback.
	 */
	'user_login_required' => FALSE, // TRUE or FALSE

	/**
	 * (array) List of users who can login into the ThumbsUp admin area.
	 * A key=>value array with keys beings usernames and values being passwords.
	 * Note: the passwords must be stored as SHA1 hashes, for security.
	 * Online SHA1 encoder: http://www.sha1.cz/
	 */
	'admin_users' => array(
		// An example user with username and password set to "demo":
		// 'demo' => '89e495e7941cf9e40e6980d14a16bf023ccd4c91',
	),

	/**
	 * (boolean) Enable or disable debug mode. You should only enable this if
	 * something is going wrong with your ThumbsUp installation.
	 * Enabling debug mode will show errors.
	 */
	'debug' => false, // TRUE or FALSE

);