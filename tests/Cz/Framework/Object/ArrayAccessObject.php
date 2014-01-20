<?php
namespace Cz\Framework\Object;
use Cz\Framework;

/**
 * ArrayAccessObject
 * 
 * @package    Framework
 * @category   Object
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class ArrayAccessObject extends Framework\Object implements \ArrayAccess, \Countable
{
	use ArrayAccess;
}
