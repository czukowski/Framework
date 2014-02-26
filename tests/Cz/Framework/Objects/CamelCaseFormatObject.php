<?php
namespace Cz\Framework\Objects;

/**
 * CamelCaseFormatObject
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class CamelCaseFormatObject
{
	use CamelCaseFormat;

	/**
	 * Convenience function to access protected methods added by trait.
	 * 
	 * @param   string  $type
	 * @param   string  $name
	 * @return  string
	 */
	public function getMethodName($type, $name)
	{
		return $this->_getMethodName($type, $name);
	}

	/**
	 * Convenience function to access protected methods added by trait.
	 * 
	 * @param   string  $method
	 * @return  array
	 */
	public function getAccessParameters($method)
	{
		return $this->_getAccessParameters($method);
	}
}
