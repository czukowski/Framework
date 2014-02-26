<?php
namespace Cz\Framework\Object;

/**
 * AllAccessObject
 * 
 * @package    Framework
 * @category   Object
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class AllAccessObject extends BaseObject implements \ArrayAccess
{
	use ArrayAccess;
	use MethodAccess;
	use PropertyAccess;
}
