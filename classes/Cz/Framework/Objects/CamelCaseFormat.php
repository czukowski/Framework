<?php
namespace Cz\Framework\Objects;

/**
 * CamelCaseFormat
 * 
 * Formats custom acces method names using a camel-case naming convention. Attach it as a trait
 * to the your object class implementation.
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
trait CamelCaseFormat
{
	/**
	 * Returns the name of a custom access method:
	 * 
	 *     // Returns 'getSomething'
	 *     $this->_getMethodName('get', 'something');
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
}
