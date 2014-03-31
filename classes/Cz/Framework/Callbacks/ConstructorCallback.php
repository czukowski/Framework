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
 * 
 * @property  string  $callback  Classname to create an object from.
 */
class ConstructorCallback extends Callback
{
	/**
	 * @param  string  $classname
	 * @param  array   $arguments
	 */
	public function __construct($classname, $arguments = array())
	{
		$this->validateCallback($classname);
		$this->validateArguments($arguments);
		$this->callback = $classname;
		$this->setArguments($arguments);
	}

	/**
	 * @param   string  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function validateCallback($callback)
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
	 * @param   mixed  $arguments
	 * @throws  Exceptions\NotImplementedException
	 */
	private function validateArguments($arguments)
	{
		if ($arguments)
		{
			throw new Exceptions\NotImplementedException('Callback arguments are not supported.');
		}
	}

	/**
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
