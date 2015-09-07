<?php defined('THUMBSUP_DOCROOT') or exit('No direct script access');
/**
 * ThumbsUp
 *
 * @author     Geert De Deckere <geert@idoe.be>
 * @link       http://geertdedeckere.be/shop/thumbsup/
 * @copyright  Copyright 2009-2010
 */
class ThumbsUp_Items {

	/**
	 * @var  string  Filter on the name field. Can contain wildcards: % and _.
	 */
	protected $name;

	/**
	 * @var  boolean  Filter on the closed field. A value of NULL means no filter.
	 */
	protected $closed;

	/**
	 * @var  string  Order the items should be returned in. Inserted as ORDER BY part in the query.
	 */
	protected $orderby;

	/**
	 * @var  integer  The maximum number of items to return. A value of 0 means no limit.
	 */
	protected $limit;

	/**
	 * Constructor which sets some default values.
	 *
	 * @param   string  name filter
	 * @return  void
	 */
	public function __construct($name = NULL)
	{
		// Set a name filter
		$this->name($name);

		// Set the default values
		$this->limit = 10;
	}

	/**
	 * Filters the items on their name.
	 *
	 * @param   string  name filter
	 * @return  object  ThumbsUp_Items
	 */
	public function name($name = NULL)
	{
		// Setting a NULL value removes this filter
		$this->name = ($name === NULL) ? NULL : (string) $name;

		return $this;
	}

	/**
	 * Filters the items on their "closed" status.
	 *
	 * @param   boolean  open or not
	 * @return  object   ThumbsUp_Items
	 */
	public function open($open = NULL)
	{
		// Setting a NULL value removes this filter
		$this->closed = ($open === NULL) ? NULL : ! (bool) $open;

		return $this;
	}

	/**
	 * Orders the items by the given value.
	 *
	 * @param   string  item order
	 * @return  object  ThumbsUp_Items
	 */
	public function orderby($orderby = NULL)
	{
		// Setting a NULL value removes this filter
		$this->orderby = ($orderby === NULL) ? NULL : (string) $orderby;

		// Do some precautionary cleaning
		$this->orderby = preg_replace('~[^\w\s,]+~', '', $this->orderby);

		return $this;
	}

	/**
	 * Sets the maximum number of results to return.
	 *
	 * @param   integer  limit
	 * @return  object   ThumbsUp_Items
	 */
	public function limit($limit = NULL)
	{
		// Setting a NULL value removes this filter
		$this->limit = ($limit === NULL) ? NULL : (int) $limit;

		return $this;
	}

	/**
	 * Generates and executes the query.
	 *
	 * @return  array  array of items
	 */
	public function get()
	{
		// Start building the query
		$sql  = 'SELECT id, name, closed, date, votes_up, votes_down, ';
		$sql .= 'votes_up - votes_down AS votes_balance, ';
		$sql .= 'votes_up + votes_down AS votes_total, ';
		$sql .= 'votes_up / (votes_up + votes_down) * 100 AS votes_pct_up, ';
		$sql .= 'votes_down / (votes_up + votes_down) * 100 AS votes_pct_down ';
		$sql .= 'FROM '.ThumbsUp::config('database_table_prefix').'items ';

		// Select only either open or closed items
		if ($this->closed !== NULL)
		{
			$where[] = 'closed = '.(int) $this->closed;
		}

		// Select only either open or closed items
		if ($this->name !== NULL)
		{
			// Note: substr() is used to chop off the wrapping quotes
			$where[] = 'name LIKE "%'.substr(ThumbsUp::db()->quote($this->name), 1, -1).'%"';
		}

		// Append all query conditions if any
		if ( ! empty($where))
		{
			$sql .= ' WHERE '.implode(' AND ', $where);
		}

		// We need to order the results
		if ($this->orderby)
		{
			$sql .= ' ORDER BY '.$this->orderby;
		}
		else
		{
			// Default order
			$sql .= ' ORDER BY name ';
		}

		// A limit has been set
		if ($this->limit)
		{
			$sql .= ' LIMIT '.(int) $this->limit;
		}

		// Wrap this in an try/catch block just in case something goes wrong
		try
		{
			// Execute the query
			$sth = ThumbsUp::db()->prepare($sql);
			$sth->execute(array($this->name));
		}
		catch (PDOException $e)
		{
			// Rethrow the exception in debug mode
			if (ThumbsUp::config('debug'))
				throw $e;

			// Otherwise, fail silently and just return an empty item array
			return array();
		}

		// Initialize the items array that will be returned
		$items = array();

		// Fetch all results
		while ($row = $sth->fetch(PDO::FETCH_OBJ))
		{
			// Return an item_id => item_name array
			$items[] = array(
				'id' => (int) $row->id,
				'name' => $row->name,
				'closed' => (bool) $row->closed,
				'date' => (int) $row->date,
				'votes_up' => (int) $row->votes_up,
				'votes_down' => (int) $row->votes_down,
				'votes_pct_up' => (float) $row->votes_pct_up,
				'votes_pct_down' => (float) $row->votes_pct_down,
				'votes_balance' => (int) $row->votes_balance,
				'votes_total' => (int) $row->votes_total,
			);
		}

		return $items;
	}

}