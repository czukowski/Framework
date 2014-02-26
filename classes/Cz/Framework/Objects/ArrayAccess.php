<?php
namespace Cz\Framework\Objects;

/**
 * ArrayAccess
 * 
 * Used to provide array access to the Object. See examples:
 * 
 *   - `$object['key']` equals to `$object->get('key')`
 *   - `$object['key'] = 'value'` equals to `$object->set('key', 'value')`
 *   - `isset($object['key'])` equals to `$object->exists('key')`
 *   - `unset($object['key'])` equals to `$object->erase('key')`
 * 
 * @package    Framework
 * @category   Objects
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

	/**
	 * Abstract methods declaration to ensure this trait is applied to the right kind of class.
	 */
	abstract public function erase($key = NULL);
	abstract public function exists($key);
	abstract public function get($key, $default = NULL);
	abstract public function set($key, $value = NULL);
}
