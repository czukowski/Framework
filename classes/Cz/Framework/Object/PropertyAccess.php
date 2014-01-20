<?php
namespace Cz\Framework\Object;

/**
 * PropertyAccess
 * 
 * @package    Framework
 * @category   Object
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
trait PropertyAccess
{
	/**
	 * @param   string  $key
	 * @return  boolean
	 */
	public function __isset($key)
	{
		return $this->exists($key);
	}

	/**
	 * @param   string  $key
	 * @return  mixed
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

	/**
	 * @param   string  $key
	 * @param   mixed   $value
	 * @return  $this
	 */
	public function __set($key, $value)
	{
		return $this->set($key, $value);
	}

	/**
	 * @param   string  $key
	 * @return  $this
	 */
	public function __unset($key)
	{
		return $this->erase($key);
	}

	abstract public function erase($key = NULL);
	abstract public function exists($key);
	abstract public function get($key, $default = NULL);
	abstract public function set($key, $value = NULL);
}
