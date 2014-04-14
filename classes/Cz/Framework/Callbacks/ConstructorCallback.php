<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions;

/**
 * ConstructorCallback
 * 
 * This type of the callback, when invoked, creates and returns a new class instance
 * of the given type, optionally using arguments.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class ConstructorCallback extends CallbackBase
{
	/**
	 * Validates that a callback is a string and that a class with that name exists.
	 * 
	 * @param   string  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	protected function validateCallback($callback)
	{
		if (is_string($callback))
		{
			if ( ! class_exists($callback))
			{
				throw new Exceptions\InvalidArgumentException('String "'.$callback.'" does not refer to a classname.');
			}
			return;
		}
		throw new Exceptions\InvalidArgumentException('Invalid callback type.');
	}

	/**
	 * @param   array  $arguments
	 * @throws  Exceptions\NotImplementedException
	 */
	protected function validateArguments($arguments)
	{
		if ($arguments)
		{
			throw new Exceptions\NotImplementedException('Callback arguments are not supported.');
		}
	}

	/**
	 * Creates a new class instance and returns it.
	 * 
	 * @param   array  $arguments
	 * @return  object
	 */
	public function invoke($arguments = array())
	{
		// FIXME: implement constructor arguments.
		$this->validateArguments($arguments);
		return new $this->callback;
	}
}
