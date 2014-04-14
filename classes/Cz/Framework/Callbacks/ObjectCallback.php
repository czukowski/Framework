<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions;

/**
 * ObjectCallback
 * 
 * This type of the callback, when invoked, returns the object instance it contains.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class ObjectCallback extends CallbackBase
{
	/**
	 * Validates the callback is an object.
	 * 
	 * @param   object  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	protected function validateCallback($callback)
	{
		if ( ! is_object($callback))
		{
			throw new Exceptions\InvalidArgumentException('Invalid callback type.');
		}
	}

	/**
	 * This type of callback doesn't take any arguments, throws exception if any.
	 * 
	 * @param   array  $arguments
	 * @throws  Exceptions\NotSupportedException
	 */
	protected function validateArguments($arguments)
	{
		if ($arguments)
		{
			throw new Exceptions\NotSupportedException('Object callback type does not support arguments.');
		}
	}

	/**
	 * Returns callback object.
	 * 
	 * @param   array  $arguments
	 * @return  object
	 */
	public function invoke($arguments = array())
	{
		$this->validateArguments($arguments);
		return $this->callback;
	}
}
