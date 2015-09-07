<?php defined('THUMBSUP_DOCROOT') or exit('No direct script access');
/**
 * ThumbsUp
 *
 * @author     Geert De Deckere <geert@idoe.be>
 * @link       http://geertdedeckere.be/shop/thumbsup/
 * @copyright  Copyright 2009-2010
 */
class ThumbsUp_Item {

	/**
	 * @var  string  The template to use for rendering the item.
	 */
	protected $template;

	/**
	 * @var  array  Item options (also passed to the template).
	 */
	protected $options;

	/**
	 * @var  integer  (Database field) Item id.
	 */
	public $id;

	/**
	 * @var  string  (Database field) Item name.
	 */
	public $name;

	/**
	 * @var  integer  (Database field) Timestamp of the creation date of the item.
	 */
	public $date;

	/**
	 * @var  boolean  (Database field) Is voting on this item closed or not?
	 */
	public $closed;

	/**
	 * @var  integer  (Database field) The number of "up" votes for this item.
	 */
	public $votes_up;

	/**
	 * @var  integer  (Database field) The number of "down" votes for this item.
	 */
	public $votes_down;

	/**
	 * @var  integer  The total number of votes cast for this item.
	 */
	public $votes_total;

	/**
	 * @var  integer  The votes balance for this item ("up" votes minus "down" votes)
	 */
	public $votes_balance;

	/**
	 * @var  integer  The percentage of all the votes for this item that are "up" votes.
	 */
	public $votes_pct_up;

	/**
	 * @var  integer  The percentage of all the votes for this item that are "down" votes.
	 */
	public $votes_pct_down;

	/**
	 * @var  boolean  Has the current user already cast a vote on this item or not?
	 */
	public $user_voted;

	/**
	 * @var  string  String with the format used to render the result.
	 */
	public $format;

	/**
	 * @var  array  A nicely formatted result based on the given format.
	 */
	public $result;

	/**
	 * Sets up a ThumbsUp item object.
	 *
	 * This constructor is protected which will prevent direct creation of an object.
	 *
	 * @return  void
	 */
	protected function __construct()
	{
		// Use the default template... by default :-)
		$this->template = ThumbsUp::config('default_template');

		// Also initialize all options (for all templates) with a default value
		$this->options = array(
			'align'       => 'center',
			'question'    => 'And you?',
			'up'          => 'Yes',
			'down'        => 'No',
			'color_up'    => '#ccc',
			'color_down'  => '#ccc',
		);
	}

	/**
	 * Loads an existing ThumbsUp item.
	 *
	 * @param   mixed  item name or id
	 * @return  mixed  ThumbsUp_Item object if the item could be found, FALSE otherwise
	 */
	public static function load($name)
	{
		// Are we loading by id or by name?
		$key = (is_int($name)) ? 'id' : 'name';

		// Load the item
		$sth = ThumbsUp::db()->prepare('SELECT id, name, date, closed, votes_up, votes_down FROM '.ThumbsUp::config('database_table_prefix').'items WHERE '.$key.' = ?');
		$sth->execute(array($name));

		// Fetch the item record if it was found
		if ( ! $row = $sth->fetch(PDO::FETCH_OBJ))
			return FALSE;

		// Setup the item object
		$item = new self;

		// Store the item values as properties
		$item->id         = (int) $row->id;
		$item->name       = $row->name;
		$item->date       = (int) $row->date;
		$item->closed     = (bool) $row->closed;
		$item->votes_up   = (int) $row->votes_up;
		$item->votes_down = (int) $row->votes_down;

		// Calculate the vote results
		$item->calculate_votes();

		// Initial default value
		$item->user_voted = FALSE;

		// Check cookie for a vote
		if (ThumbsUp::config('cookie_check') AND ThumbsUp_Cookie::find_id($item->id))
		{
			$item->user_voted = TRUE;
		}

		// Check for a previous vote by IP
		if ( ! $item->user_voted AND ThumbsUp::config('ip_check') AND $ip = ThumbsUp::get_ip())
		{
			// Because of the ip_lifetime config setting, it's possible multiple records contain the same IP.
			// We only load the most recent one to check the lifetime later on.
			$sth = ThumbsUp::db()->prepare('SELECT date FROM '.ThumbsUp::config('database_table_prefix').'votes WHERE item_id = ? AND ip = ? ORDER BY date DESC LIMIT 1');
			$sth->execute(array($item->id, $ip));

			// A record with the IP was found
			if ($date = (int) $sth->fetchColumn())
			{
				if ( ! ThumbsUp::config('ip_lifetime') OR $date > time() - ThumbsUp::config('ip_lifetime'))
				{
					// If the IP lifetime is unlimited or the vote date
					// still falls within the lifetime, mark the item as voted on.
					$item->user_voted = TRUE;
				}
			}
		}

		// Check for a previous vote by user id
		if ( ! $item->user_voted AND ThumbsUp::config('user_id_check') AND $user_id = ThumbsUp::get_user_id())
		{
			$sth = ThumbsUp::db()->prepare('SELECT 1 FROM '.ThumbsUp::config('database_table_prefix').'votes WHERE item_id = ? AND user_id = ? LIMIT 1');
			$sth->execute(array($item->id, $user_id));
			$item->user_voted = (bool) $sth->fetchColumn();
		}

		return $item;
	}

	/**
	 * Creates a new ThumbsUp item.
	 *
	 * @param   string  item name
	 * @return  mixed   ThumbsUp_Item object if the item could be created, FALSE otherwise
	 */
	public static function create($name)
	{
		try
		{
			// Attempt to create a new item
			$sth = ThumbsUp::db()->prepare('INSERT INTO '.ThumbsUp::config('database_table_prefix').'items (name, date) VALUES (?, ?)');
			$sth->execute(array($name, $date = time()));
		}
		catch (PDOException $e)
		{
			// Item creation failed.
			// If another item with the same name already existed, PDO will throw a PDOException:
			// "Integrity constraint violation. Duplicate entry 'xxx' for key 'UNIQUE_NAME'."
			// By relying on this exception, we save an extra query to check the uniqueness of the name in advance.
			return FALSE;
		}

		// Setup the item object
		$item = new self;

		// Store the item values as properties
		$item->id             = (int) ThumbsUp::db()->lastInsertId();
		$item->name           = $name;
		$item->date           = $date;
		$item->closed         = FALSE;
		$item->votes_up       = 0;
		$item->votes_down     = 0;
		$item->votes_total    = 0;
		$item->votes_balance  = 0;
		$item->votes_pct_up   = 0;
		$item->votes_pct_down = 0;
		$item->user_voted     = FALSE;

		return $item;
	}
	
	public function register_vote_in_owloo($id, $vote){
		$db_host = "localhost";
		$db_username = "owloo_admin";
		$db_password = "fblatamx244";
		$db_name = "owloo_twitter";
		$conn = mysql_connect($db_host, $db_username, $db_password) or die("not connected");
		mysql_select_db($db_name);
		
		$sql = "INSERT INTO owloo_user_rating VALUES(NULL, (SELECT owloo_user_id FROM owloo_user_master WHERE owloo_user_twitter_id LIKE '".$id."' LIMIT 1), ".$vote.", '".$_SERVER['REMOTE_ADDR']."', NOW());";
		
		mysql_query($sql, $conn) or die(mysql_error());
	}

	/**
	 * Adds a single up/down vote to the item and recalculates the vote results.
	 * This method performs no checks to see whether the current user has already voted for the item.
	 *
	 * @param   integer  0 = down, 1 = up
	 * @param   string   IP number
	 * @return  void
	 */
	public function cast_vote($vote, $ip = NULL)
	{
		// Vote value must be either 0 or 1
		$vote = min(1, max(0, (int) $vote));
		$aux_vote = 0;
		if ($vote)
		{
			// Add an "up" vote
			$this->votes_up += 10;
			$sql = 'votes_up = votes_up + 10';
			$aux_vote = 10;
		}
		else
		{
			$aux_vote = -10;
			// Add a "down" vote
			$this->votes_down += 10;
			$sql = 'votes_down = votes_down + 10';
		}

		// Recalculate the vote results, no need to reload the item from database
		$this->calculate_votes();

		// Update the item record
		$sth = ThumbsUp::db()->prepare('UPDATE '.ThumbsUp::config('database_table_prefix').'items SET '.$sql.' WHERE id = ?');
		$sth->execute(array($this->id));

		// The current user has just cast a vote
		$this->user_voted = TRUE;
		
		$this->register_vote_in_owloo($this->name , $aux_vote);
		

		// Add the item id to a cookie
		if (ThumbsUp::config('cookie_check'))
		{
			ThumbsUp_Cookie::add_id($this->id);
		}

		// Combine the storage of the IP and user id into one query for optimization
		$ip = (ThumbsUp::config('ip_check')) ? ThumbsUp::get_ip() : NULL;
		$user_id = (ThumbsUp::config('user_id_check')) ? ThumbsUp::get_user_id() : NULL;

		if ($ip OR $user_id)
		{
			$sth = ThumbsUp::db()->prepare('INSERT INTO '.ThumbsUp::config('database_table_prefix').'votes (item_id, ip, user_id, value, date) VALUES (?, ?, ?, ?, ?)');
			$sth->execute(array($this->id, $ip, $user_id, $vote, time()));
		}
	}

	/**
	 * Calculates the vote results based on the current votes_up and votes_down values.
	 *
	 * @return  void
	 */
	public function calculate_votes()
	{
		$this->votes_total    = $this->votes_up + $this->votes_down;
		$this->votes_balance  = $this->votes_up - $this->votes_down;

		// Note: division by zero must be prevented
		$this->votes_pct_up   = ($this->votes_total === 0) ? 0 : $this->votes_up / $this->votes_total * 100;
		$this->votes_pct_down = ($this->votes_total === 0) ? 0 : $this->votes_down / $this->votes_total * 100;
	}

	/**
	 * Sets the template to use for rendering the item.
	 *
	 * @param   string  template name
	 * @return  object  ThumbsUp_Item
	 */
	public function template($template = NULL)
	{
		// No template name provided
		if ($template === NULL)
			return $this;

		// We are a bit flexible in the names we accept
		$this->template = str_replace(array('-', ' '), '_', strtolower(trim($template)));

		// Chainable method
		return $this;
	}

	/**
	 * Sets options for this item. New options will be merged with existing options.
	 *
	 * @param   mixed   item options (as array or query string)
	 * @return  object  ThumbsUp_Item
	 */
	public function options($options = NULL)
	{
		// No options provided
		if ($options === NULL)
			return $this;

		// Convert a query string to an array
		if (is_string($options))
		{
			parse_str($options, $options);
		}

		// Store and merge the item options in the object
		$this->options = (array) $options + (array) $this->options;

		// Chainable method
		return $this;
	}

	/**
	 * Generates nicely formatted results for each result area.
	 *
	 * @param   string  format string
	 * @return  object  ThumbsUp_Item
	 */
	public function format($format = NULL)
	{
		if ($format === NULL)
			return $this;

		$this->format = (string) $format;

		// Update the result for this item
		$this->result = preg_replace_callback(
			'/\{([+-])?+(up|down|total|balance|pct_(?:up|down))(?:([.,])(\d++))?\}/i',
			array($this, 'format_callback'),
			$this->format
		);

		// Split into different result areas separated by "||"
		$this->result = preg_split('/\s*\|\|\s*/', $this->result);

		// Chainable method
		return $this;
	}

	/**
	 * Used by the format() method to format numbers.
	 *
	 * @param   array   preg_replace_callback matches
	 * @return  string  a formatted number
	 */
	protected function format_callback($matches)
	{
		// Load the correct number to display
		$property = 'votes_'.strtolower($matches[2]);
		$number = $this->$property;

		// Decimals need to be added
		if ( ! empty($matches[4]))
		{
			// $matches[4] contains the number of desired decimals.
			// $matches[3] contains the decimal separator (dot or comma).
			$number = number_format($number, $matches[4], $matches[3], '');
		}
		// No decimals wanted
		else
		{
			$number = (int) round($number);
		}

		// Prepend a "+" or "-" sign if the result is greater than zero.
		// Note: if the property is lower than zero, a "-" sign is already prepended automatically.
		if ( ! empty($matches[1]) AND $this->$property > 0)
		{
			$number = $matches[1].$number;
		}

		return (string) $number;
	}

	/**
	 * Returns the rendered HTML template.
	 *
	 * @return  string  the item rendered in HTML
	 */
	public function render()
	{
		// The formatted result has not been generated yet
		if (empty($this->result))
		{
			// Generate the result using the default format for the chosen template
			$formats = ThumbsUp::config('default_formats');
			$this->format($formats[$this->template]);
		}

		// Prepare item data for template
		$item = get_object_vars($this);
		unset($item['template'], $item['options']);

		// Load the chosen template
		$template = new ThumbsUp_Template(THUMBSUP_DOCROOT.'templates/'.$this->template.'.php');

		// Pass on all item data to the template
		$template
			->set('item', (object) $item)
			->set('template', $this->template)
			->set('options', (object) $this->options);

		// Render the template output
		return $template->render();
	}

	/**
	 * Magic method to convert to item to a string.
	 *
	 * @return  string  the item rendered in HTML
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Saves the current item. Item properties that match a database field are updated.
	 *
	 * @return  void
	 */
	public function save()
	{
		// Update the record
		$sth = ThumbsUp::db()->prepare('UPDATE '.ThumbsUp::config('database_table_prefix').'items SET name = ?, closed = ?, votes_up = ?, votes_down = ?, date = ? WHERE id = ?');
		$sth->execute(array($this->name, $this->closed, $this->votes_up, $this->votes_down, $this->date, $this->id));

		// Recalculate votes since votes_up or votes_down could have been changed
		$this->calculate_votes();
	}

	/**
	 * Deletes the item and all votes for it.
	 *
	 * @return  void
	 */
	public function delete()
	{
		// Delete all registered votes for this item
		$sth = ThumbsUp::db()->prepare('DELETE FROM '.ThumbsUp::config('database_table_prefix').'votes WHERE item_id = ?');
		$sth->execute(array($this->id));

		// Delete the item itself
		$sth = ThumbsUp::db()->prepare('DELETE FROM '.ThumbsUp::config('database_table_prefix').'items WHERE id = ?');
		$sth->execute(array($this->id));
	}

}