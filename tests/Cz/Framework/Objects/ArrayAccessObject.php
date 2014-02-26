<?php
namespace Cz\Framework\Objects;

/**
 * ArrayAccessObject
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class ArrayAccessObject extends BaseObject implements \ArrayAccess, \Countable
{
	use ArrayAccess;
}
