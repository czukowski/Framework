<?php
namespace Cz\Framework\Object;
use Cz\Framework;

/**
 * AllAccessObject
 * 
 * @package    Framework
 * @category   Object
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class AllAccessObject extends Framework\Object implements \ArrayAccess
{
	use ArrayAccess;
	use MethodAccess;
	use PropertyAccess;
}
