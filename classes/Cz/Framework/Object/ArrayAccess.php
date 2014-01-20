<?php
namespace Cz\Framework\Object;

/**
 * ArrayAccess
 * 
 * @package    Framework
 * @category   Object
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
trait ArrayAccess
{
	/**
	 * @param   string  $key
	 * @return  boolean
	 */
	public function offsetExists($key)
	{
		return $this->exists($key);
	}

	/**
	 * @param   string  $key
	 * @return  mixed
	 */
	public function offsetGet($key)
	{
		return $this->get($key);
	}

	/**
	 * @param   string  $key
	 * @param   mixed   $value
	 * @return  $this
	 */
	public function offsetSet($key, $value)
	{
		return $this->set($key, $value);
	}

	/**
	 * @param   string  $key
	 * @return  $this
	 */
	public function offsetUnset($key)
	{
		return $this->erase($key);
	}

	abstract public function erase($key = NULL);
	abstract public function exists($key);
	abstract public function get($key, $default = NULL);
	abstract public function set($key, $value = NULL);
}
