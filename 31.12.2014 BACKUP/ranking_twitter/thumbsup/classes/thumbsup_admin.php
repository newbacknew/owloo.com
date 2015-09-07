<?php defined('THUMBSUP_DOCROOT') or exit('No direct script access');
/**
 * ThumbsUp
 *
 * @author     Geert De Deckere <geert@idoe.be>
 * @link       http://geertdedeckere.be/shop/thumbsup/
 * @copyright  Copyright 2009-2010
 */
class ThumbsUp_Admin {

	/**
	 * Sets up the admin environment and routes the request to the correct
	 * action_xxx method, while making sure the user is logged in.
	 *
	 * @return  void
	 */
	public function __construct($action = NULL)
	{
		// Start a session
		session_start();

		// Set the correct default Content-Type header
		if (ThumbsUp::is_ajax())
		{
			header('Content-Type: application/json; charset=utf-8');
		}
		else
		{
			header('Content-Type: text/html; charset=utf-8');
		}

		// Force the user to login
		if ( ! self::logged_in())
			return $this->action_login();

		// If no action is specified, show the dashboard by default
		if ($action === NULL)
			return $this->action_dashboard();

		// Look for a corresponding action_method
		if (in_array($action = 'action_'.$action, get_class_methods($this)))
			return $this->$action();

		// Show an error for invalid actions
		header('HTTP/1.1 404 Not Found');
		exit('Page not found');
	}

	/**
	 * Redirects the browser to another page. Exits PHP execution.
	 *
	 * @param   string  admin action name or full URL
	 * @return  void
	 */
	public function redirect($url)
	{
		if (strpos($url, '://') === FALSE)
		{
			// Generate URL for an admin action
			$url = ThumbsUp::config('url').'admin/?action='.$url;
		}

		// Redirection headers
		header('HTTP/1.1 302 Found');
		header('Location: '.$url);
		exit;
	}

	/**
	 * Checks whether the current user is logged in or not.
	 *
	 * @return  boolean  logged in or not
	 */
	public function logged_in()
	{
		return isset($_SESSION['thumbsup_admin']);
	}

	/**
	 * Shows a login form and attempts to log a user in into the admin.
	 *
	 * @return  void
	 */
	public function action_login()
	{
		// If the user is already logged in, show the dashboard
		if ($this->logged_in())
			$this->redirect('dashboard');

		// Setup login form template
		$template = new ThumbsUp_Template(THUMBSUP_DOCROOT.'admin/html/login.php');

		// Form submitted
		if (isset($_POST['username']) AND isset($_POST['password']))
		{
			// Basic user/pass preparation
			$username = (string) $_POST['username'];
			$password = sha1((string) $_POST['password']);

			// Load all configured users
			$users = ThumbsUp::config('admin_users');

			// Do username and password match?
			if (array_key_exists($username, $users) AND $users[$username] === $password)
			{
				// Update session id for security if possible
				if ( ! headers_sent())
				{
					session_regenerate_id();
				}

				// Set the thumbsup_admin session
				$_SESSION['thumbsup_admin'] = $username;

				// The user has successfully logged in, show the dashboard
				$this->redirect('dashboard');
			}

			// Used to show error message in HTML
			$template->username = $username;
			$template->error = 'Invalid username and/or password';
		}

		// Output the form
		echo $template->render();
	}

	/**
	 * Logs a user out of the admin.
	 *
	 * @return  void
	 */
	public function action_logout()
	{
		// Delete the admin session
		unset($_SESSION['thumbsup_admin']);

		// Update session id for security if possible
		if ( ! headers_sent())
		{
			if (version_compare(PHP_VERSION, '5.1.0', '>='))
			{
				// Delete the old session file
				session_regenerate_id(TRUE);
			}
			else
			{
				session_regenerate_id();
			}
		}

		// Redirect to the login form and exit
		$this->redirect('login');
	}

	/**
	 * Shows the admin dashboard: an overview of all ThumbsUp items.
	 *
	 * @return  void
	 */
	public function action_dashboard()
	{
		$template = new ThumbsUp_Template(THUMBSUP_DOCROOT.'admin/html/dashboard.php');

		// Filter on closed
		$filter_closed = (isset($_GET['filter_closed']) AND preg_match('/^[01]$/D', (string) $_GET['filter_closed'])) ? (int) $_GET['filter_closed'] : '';
		if ($filter_closed !== '')
		{
			$sql_filter[] = 'closed = '.$filter_closed;
		}

		// Filter on name
		$filter_name = (isset($_GET['filter_name'])) ? (string) $_GET['filter_name'] : '';
		if ($filter_name !== '')
		{
			$sql_filter[] = 'name LIKE "%'.substr(ThumbsUp::db()->quote($filter_name), 1, -1).'%"';
		}

		// Build a WHERE clause if needed
		$sql_where = (empty($sql_filter)) ? '' : ' WHERE '.implode(' AND ', $sql_filter);

		// Count the total items
		$sth = ThumbsUp::db()->prepare('SELECT COUNT(1) FROM '.ThumbsUp::config('database_table_prefix').'items '.$sql_where);
		$sth->execute();
		$total_items = (int) $sth->fetchColumn();

		// Build the dropdown options for items_per_page
		foreach (array(10, 20, 50, 100, 200, 500, 1000, 2000, 5000) as $i)
		{
			$items_per_page_select[] = $i;

			// Don't bother showing values higher than needed
			if ($total_items < $i)
				break;
		}

		// Grab the current items_per_page setting
		if (isset($_GET['items_per_page']))
		{
			// A value of "0" equals "view all"
			$items_per_page = ($_GET['items_per_page'] === '0') ? 0 : max(1, (int) $_GET['items_per_page']);
		}
		else
		{
			// Default value
			$items_per_page = min(50, end($items_per_page_select));
		}

		// Support custom entered items_per_page GET values
		if ($items_per_page !== 0 AND ! in_array($items_per_page, $items_per_page_select))
		{
			$items_per_page_select[] = $items_per_page;
			sort($items_per_page_select);
		}

		// Add an option for "view all" at the end
		$items_per_page_select[] = 0;

		// More pagination variables
		$total_pages = ($items_per_page === 0) ? 1 : max(1, (int) ceil($total_items / $items_per_page));
		$page = (isset($_GET['page'])) ? min($total_pages, max(1, (int) $_GET['page'])) : 1;

		// Limit the results if "view all" has not been selected
		$sql_limit = ($items_per_page === 0) ? '' : ' LIMIT '.$items_per_page.' OFFSET '.(($page - 1) * $items_per_page);

		// Load the items
		$sth = ThumbsUp::db()->prepare(
			'SELECT
				id, name, date, closed, votes_up, votes_down,
				votes_up - votes_down AS votes_balance,
				votes_up + votes_down AS votes_total,
				votes_up / (votes_up + votes_down) * 100 AS votes_pct_up,
				votes_down / (votes_up + votes_down) * 100 AS votes_pct_down
			FROM '.ThumbsUp::config('database_table_prefix').'items '.
			$sql_where.
			' ORDER BY name '.
			$sql_limit
		);
		$sth->execute();

		$items = array();
		while ($row = $sth->fetch(PDO::FETCH_OBJ))
		{
			$items[(int) $row->id] = $row;
		}

		// Pass on all data we need to the template
		$template->filter_closed = $filter_closed;
		$template->filter_name = $filter_name;
		$template->page = $page;
		$template->total_items = $total_items;
		$template->total_pages = $total_pages;
		$template->items_per_page = $items_per_page;
		$template->items_per_page_select = $items_per_page_select;
		$template->items = $items;
		echo $template->render();
	}

	/**
	 * Ajax handler to save an edited item.
	 *
	 * @return  void
	 */
	public function action_edit()
	{
		// A valid item ID is required
		if ( ! isset($_POST['id']) OR ! $item = ThumbsUp_Item::load((int) $_POST['id']))
		{
			$errors['id'] = 'Invalid ID';
		}

		// Update the closed status
		if (isset($_POST['closed']))
		{
			$item->closed = min(1, max(0, (int) $_POST['closed']));
		}

		// Update the name
		if (isset($_POST['name']))
		{
			$_POST['name'] = trim((string) $_POST['name']);

			if ($_POST['name'] === '')
			{
				$errors['name'] = 'A name is required.';
			}
			elseif (strlen($_POST['name']) > 255)
			{
				$errors['name'] = 'The name is too long (max 255 chars).';
			}
			elseif (ThumbsUp::item_name_exists($_POST['name'], $item->id))
			{
				$errors['name'] = 'Another item with this name already exists.';
			}
			else
			{
				$item->name = $_POST['name'];
			}
		}

		// The votes need to be changed
		foreach (array('votes_up', 'votes_down') as $key)
		{
			if (isset($_POST[$key]))
			{
				$_POST[$key] = trim((string) $_POST[$key]);

				if ($_POST[$key] === '')
				{
					$errors[$key] = 'Required';
				}
				elseif ( ! preg_match('/^[0-9]++$/D', $_POST[$key]))
				{
					$errors[$key] = 'Digits only';
				}
				else
				{
					$item->$key = (int) $_POST[$key];
				}
			}
		}

		// No errors so far
		if (empty($errors))
		{
			$item->save();

			echo json_encode(array('item' => $item));
			exit;
		}
		// Oh no, we've got some errors!
		else
		{
			echo json_encode(array('errors' => $errors));
			exit;
		}
	}

	/**
	 * Ajax handler to delete an item and its votes.
	 *
	 * @return  void
	 */
	public function action_delete()
	{
		if (isset($_POST['id']) AND $item = ThumbsUp_Item::load((int) $_POST['id']))
		{
			$item->delete();
		}
	}

	/**
	 * Helper function to create the URL for an admin page.
	 *
	 * @param   mixed    string or array containing query string contents
	 * @param   boolean  automatically add existing GET parameters again
	 * @return  string   complete url
	 */
	public static function url($params = NULL, $preserve_get = TRUE)
	{
		$url = ThumbsUp::config('url').'admin/';

		// Convert to params to an array
		if ( ! is_array($params))
		{
			parse_str((string) $params, $params);
		}

		// Add existing GET params to the query string
		if ($preserve_get)
		{
			$params += $_GET;
		}

		// Only prepend "?" if the query string is not empty
		$query = rtrim('?'.http_build_query($params, '', '&'), '?');

		return $url.$query;
	}

}