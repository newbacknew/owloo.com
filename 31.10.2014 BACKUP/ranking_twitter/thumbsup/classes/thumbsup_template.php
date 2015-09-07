<?php defined('THUMBSUP_DOCROOT') or exit('No direct script access');
/**
 * ThumbsUp
 *
 * @author     Geert De Deckere <geert@idoe.be>
 * @link       http://geertdedeckere.be/shop/thumbsup/
 * @copyright  Copyright 2009-2010
 */
class ThumbsUp_Template {

	/**
	 * @var  string  Template file.
	 */
	protected $file;

	/**
	 * @var  array  Data to pass on to the template.
	 */
	protected $data;

	/**
	 * Creates a new template object. This method is chainable.
	 *
	 * @param   string  template file
	 * @return  object  ThumbsUp_Template
	 */
	public function factory($file = NULL)
	{
		return new ThumbsUp_Template($file);
	}

	/**
	 * Constructor. Creates a new template object.
	 *
	 * @param   string  template file
	 * @return  void
	 */
	public function __construct($file = NULL)
	{
		$this->file = (string) $file;
	}

	/**
	 * Sets template data. This method is chainable.
	 *
	 * @param   mixed   key string or an associative array for multiple variables at once
	 * @param   mixed   value
	 * @return  object  ThumbsUp_Template
	 */
	public function set($key, $value = NULL)
	{
		// Set multiple template variables at once
		if (is_array($key))
		{
			foreach ($key as $key2 => $value)
			{
				$this->set($key2, $value);
			}

			// Don't continue further if $key is an array
			return $this;
		}

		// Render nested templates first
		if ($value instanceof ThumbsUp_Template)
		{
			$value = $value->render();
		}

		// Update the data array
		$this->data[$key] = $value;

		// Chainable method
		return $this;
	}

	/**
	 * Sets the template file. Overwrites the template file from the constructor.
	 * This method is chainable.
	 *
	 * @param   string  template file
	 * @return  object  ThumbsUp_Template
	 */
	public function set_file($file)
	{
		$this->file = (string) $file;

		// Chainable method
		return $this;
	}

	/**
	 * Sets template data.
	 *
	 * @param   string  template data key
	 * @param   mixed   template data value
	 * @return  void
	 */
	public function __set($key, $value)
	{
		$this->set($key, $value);
	}

	/**
	 * Gets template data.
	 *
	 * @param   string  template data key
	 * @return  mixed   template data; NULL if not found
	 */
	public function __get($key)
	{
		return (isset($this->data[$key])) ? $this->data[$key] : NULL;
	}

	/**
	 * Checks whether certain template data is set (as of PHP 5.1.0).
	 *
	 * @param   string   template data key
	 * @return  boolean
	 */
	public function __isset($key)
	{
		return isset($this->data[$key]);
	}

	/**
	 * Unsets template data (as of PHP 5.1.0).
	 *
	 * @param   string  template data key
	 * @return  void
	 */
	public function __unset($key)
	{
		unset($this->data[$key]);
	}

	/**
	 * Renders a template and returns it.
	 *
	 * @return  string   template output
	 */
	public function render()
	{
		// Start output buffering
		ob_start();

		// Pass on the data to the template
		extract((array) $this->data);

		// Load and parse the template
		include $this->file;

		// End output buffering
		$output = ob_get_contents();
		ob_end_clean();

		// Return the output as a string
		return $output;
	}

	/**
	 * Renders the template.
	 *
	 * @return  string  template output
	 */
	public function __toString()
	{
		return $this->render();
	}

}