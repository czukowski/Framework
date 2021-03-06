<?php
namespace Cz\Framework\Objects;

/**
 * AllAccessObject
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class AllAccessObject extends ObjectBase implements \ArrayAccess
{
	use CamelCaseFormat;
	use ArrayAccess;
	use MethodAccess;
	use PropertyAccess;

	/**
	 * Initialize values.
	 */
	public function __construct($values = array())
	{
		$this->_initialize($values);
	}
}
