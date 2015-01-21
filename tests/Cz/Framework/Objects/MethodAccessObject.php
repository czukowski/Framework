<?php
namespace Cz\Framework\Objects;

/**
 * MethodAccessObject
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class MethodAccessObject extends ObjectBase
{
	use CamelCaseFormat;
	use MethodAccess;

	/**
	 * Initialize values.
	 */
	public function __construct($values = array())
	{
		$this->_initialize($values);
	}
}
