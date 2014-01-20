<?php
namespace Cz\Framework;
use Cz\Framework\Exceptions;

/**
 * Object
 * 
 * This class provides a generic container for multiple key-value pairs. Generic access methods
 * are provided: `get($key, $default)`, `set($key, $value)`, `exists($key)` and `erase($key)`.
 * 
 * Custom access methods are supported. For example, you may wish to load a default value
 * for a `name` key if it's not set. In order to do that, you may define a custom method named
 * `getName()` that implements the logic. If you call the object's getter, `$object->get('name')`,
 * it'll detect the `getName()` method and use it. It works similarly with other access types.
 * 
 * Additional traits may be attached to _your_ object extension to provide array, property or
 * method access, or even all of them together. See `Cz\Framework\Object` namespace for the details.
 * 
 * @package    Framework
 * @category   Entities
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class Object
{
	/**
	 * @var  array
	 */
	private $container = array();

	/**
	 * Counts object values that were set.
	 * 
	 * @return  integer
	 */
	public function count()
	{
		return count($this->container);
	}

	/**
	 * Returns the raw array from the object.
	 * 
	 * @return  array
	 */
	public function toArray()
	{
		return $this->container;
	}

	/**
	 * Generic "issetter" method that checks whether a specific key is set in the
	 * object. A custom issetter method will be called, if exists for the key.
	 * 
	 * @param   string  $key
	 * @return  boolean
	 */
	public function exists($key)
	{
		$customIssetter = $this->_getMethodName(__FUNCTION__, $key);
		if (method_exists($this, $customIssetter))
		{
			return $this->$customIssetter();
		}
		return $this->_exists($key);
	}

	/**
	 * Generic getter method. A custom getter method will be called, if exists
	 * for the key.
	 * 
	 * Calls actual getter implementations either with or without default value,
	 * based on number of arguments passed to the generic getter.
	 * 
	 * @param   string  $key
	 * @param   mixed   $default  (optional)
	 * @return  mixed
	 */
	public function get($key, $default = NULL)
	{
		$customGetter = $this->_getMethodName(__FUNCTION__, $key);
		if (method_exists($this, $customGetter))
		{
			return func_num_args() > 1
				? $this->$customGetter($default)
				: $this->$customGetter();
		}
		return func_num_args() > 1
			? $this->_get($key, $default)
			: $this->_get($key);
	}

	/**
	 * Generic setter method. A custom setter method will be called, if exists
	 * for the key. If an array is passed as the first and only argument, each
	 * of its keys is being set individually.
	 * 
	 * @param   string  $key
	 * @param   mixed   $value  (optional)
	 * @return  $this
	 */
	public function set($key, $value = NULL)
	{
		if (func_num_args() === 1 AND (is_array($key) OR $key instanceof \Traversable))
		{
			return $this->_setMultiple($key);
		}
		$customSetter = $this->_getMethodName(__FUNCTION__, $key);
		if (method_exists($this, $customSetter))
		{
			return $this->$customSetter($value);
		}
		return $this->_set($key, $value);
	}

	/**
	 * Helper method to set multiple values, one by one, from the argument, which is
	 * array or Traversable object.
	 * 
	 * @param   array|\Traversable  $values
	 * @return  $this
	 */
	private function _setMultiple($values)
	{
		foreach ($values as $key => $value)
		{
			$this->set($key, $value);
		}
		return $this;
	}

	/**
	 * Generic "unsetter" method. A custom unsetter method will be called, if exists
	 * for the key. If no argument passed, erases all the values.
	 * 
	 * @param   string  $key  (optional)
	 * @return  $this
	 */
	public function erase($key = NULL)
	{
		if (func_num_args() === 0)
		{
			return $this->clear();
		}
		$customUnsetter = $this->_getMethodName(__FUNCTION__, $key);
		if (method_exists($this, $customUnsetter))
		{
			return $this->$customUnsetter();
		}
		return $this->_erase($key);
	}

	/**
	 * Alias method for calling `erase()` method without arguments.
	 * 
	 * @return  $this
	 */
	public function clear()
	{
		foreach (array_keys($this->container) as $key)
		{
			$this->erase($key);
		}
		return $this;
	}

	/**
	 * Returns the name of a custom access method:
	 * 
	 *     // Returns 'getSomething'
	 *     $this->_getMethodName('get', 'something');
	 * 
	 * Child classes may override this method or use a trait that will do it for another
	 * method naming convention. In this case, be sure to override `_getAccessParameters()`
	 * method accordingly.
	 * 
	 * @param   string  $type
	 * @param   string  $name
	 * @return  string
	 */
	protected function _getMethodName($type, $name)
	{
		return $type.ucfirst($name);
	}

	/**
	 * Returns access type and object key from the custom access method name:
	 * 
	 *     // Returns ['get', 'something']
	 *     $this->_getAccessParameters('getSomething');
	 * 
	 * Child classes may override this method or use a trait that will do it for another
	 * method naming convention. In this case, be sure to override `_getMethodName()`
	 * method accordingly.
	 * 
	 * @param   string  $method
	 * @return  array
	 */
	protected function _getAccessParameters($method)
	{
		if (strpos($method, 'get') === 0)
		{
			return array('get', lcfirst(substr($method, 3)));
		}
		elseif (strpos($method, 'set') === 0)
		{
			return array('set', lcfirst(substr($method, 3)));
		}
		elseif (strpos($method, 'exists') === 0)
		{
			return array('exists', lcfirst(substr($method, 6)));
		}
		elseif (strpos($method, 'erase') === 0)
		{
			return array('erase', lcfirst(substr($method, 5)));
		}
		return array(NULL, NULL);
	}

	/**
	 * The "actual" issetter method. Intended to be called by the public generic issetter
	 * or the custom methods.
	 * 
	 * @param   string  $key
	 * @return  boolean
	 */
	protected function _exists($key)
	{
		return isset($this->container[$key]);
	}

	/**
	 * The "actual" getter method. Intended to be called by the public generic getter or
	 * the custom methods.
	 * 
	 * @param   string  $key
	 * @param   mixed   $default
	 * @return  mixed
	 */
	protected function _get($key, $default = NULL)
	{
		if (isset($this->container[$key]))
		{
			return $this->container[$key];
		}
		elseif (func_num_args() > 1)
		{
			return $default;
		}
		throw new Exceptions\InvalidArgumentException('Key '.$key.' not exists.');
	}

	/**
	 * The "actual" setter method. Intended to be called by the public generic setter or
	 * the custom methods.
	 * 
	 * @param   string  $key
	 * @param   mixed   $value
	 * @reutrn  $this
	 */
	protected function _set($key, $value)
	{
		$this->container[$key] = $value;
		return $this;
	}

	/**
	 * The "actual" unsetter method. Intended to be called by the public generic unsetter
	 * or the custom methods.
	 * 
	 * @param   string  $key
	 * @return  $this
	 */
	protected function _erase($key)
	{
		unset($this->container[$key]);
		return $this;
	}
}
