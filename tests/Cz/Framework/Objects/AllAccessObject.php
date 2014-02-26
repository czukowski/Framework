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
class AllAccessObject extends BaseObject implements \ArrayAccess
{
	use CamelCaseFormat;
	use ArrayAccess;
	use MethodAccess;
	use PropertyAccess;
}
