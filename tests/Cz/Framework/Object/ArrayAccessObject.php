<?php
namespace Cz\Framework\Object;

/**
 * ArrayAccessObject
 * 
 * @package    Framework
 * @category   Object
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class ArrayAccessObject extends BaseObject implements \ArrayAccess, \Countable
{
	use ArrayAccess;
}
