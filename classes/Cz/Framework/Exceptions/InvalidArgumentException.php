<?php
namespace Cz\Framework\Exceptions;
use Cz\Framework;

/**
 * InvalidArgumentException
 * 
 * @package    Framework
 * @category   Exceptions
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class InvalidArgumentException extends \InvalidArgumentException implements Framework\Exception
{}
