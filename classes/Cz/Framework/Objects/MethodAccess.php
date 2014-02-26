<?php
namespace Cz\Framework\Objects;
use Cz\Framework\Exceptions;

/**
 * MethodAccess
 * 
 * Used to provide method access to the Object, even if these methods are not implemented.
 * See examples:
 * 
 *   - `$object->getKey()` equals to `$object->get('key')`
 *   - `$object->setKey('value')` equals to `$object->set('key', 'value')`
 *   - `$object->existsKey()` equals to `$object->exists('key')`
 *   - `$object->eraseKey()` equals to `$object->erase('key')`
 * 
 * Obviously, the custom methods may still be implemented to override the default logic.
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
trait MethodAccess
{
	/**
	 * Generic trap for all custom access method calls for keys that do not have any
	 * custom access methods, that will transform the call to the generic access method
	 * call, eg:
	 * 
	 *     $object->getSomething('default value')
	 *     // Will call `$this->_get('something', 'default value')`
	 * 
	 * @param   string  $method
	 * @param   array   $args
	 * @return  mixed
	 */
	public function __call($method, $args)
	{
		list($accessType, $key) = $this->_getAccessParameters($method);
		switch ($accessType)
		{
			case 'get':
				return count($args) > 0
					? $this->_get($key, reset($args))
					: $this->_get($key);
			case 'set':
				if (count($args) > 0)
				{
					return $this->_set($key, reset($args));
				}
				throw new Exceptions\InvalidArgumentException('Cannot call without argument: '.$method);
			case 'exists':
				return $this->_exists($key);
			case 'erase':
				return $this->_erase($key);
		}
		throw new Exceptions\InvalidArgumentException('Call to undefined method: '.$method);
	}

	/**
	 * Abstract methods declaration to ensure this trait is applied to the right kind of class.
	 */
	abstract protected function _getAccessParameters($method);
	abstract public function erase($key = NULL);
	abstract public function exists($key);
	abstract public function get($key, $default = NULL);
	abstract public function set($key, $value = NULL);
}
