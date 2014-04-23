<?php
namespace Cz\Framework\Objects;

/**
 * PropertyAccessObject
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class PropertyAccessObject extends ObjectBase
{
	use CamelCaseFormat;
	use PropertyAccess;
}
