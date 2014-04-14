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
	 * Creates a new class instance and returns it.
	 * 
	 * @param   array  $arguments
	 * @return  object
	 */
	public function invoke($arguments = array())
	{
		$arguments = $this->getInvocationArguments($arguments);
		if (empty($arguments))
		{
			// Create class instance using the easiest way, if arguments were not supplied.
			return new $this->callback;
		}
		elseif ($this->isReflectionAvailable())
		{
			// Create class instance using Reflection, if available.
			$class = new \ReflectionClass($this->callback);
			return $class->newInstanceArgs($arguments);
		}
		else
		{
			// Create class instance using `eval`, if all else fails.
			// Potentially hazardous, but oh well.
			$evalString = 'return new '.$this->callback.'('
				.implode(', ', array_map(function($key) {
					return '$arguments['.$key.']';
				}, array_keys($arguments)))
				.');';
			return eval($evalString);
		}
	}

	/**
	 * Checks the Reflection extension availablilty (for our purpose, it's suffice to check for
	 * the `ReflectionClass` existence).
	 * 
	 * @return  boolean
	 */
	protected function isReflectionAvailable()
	{
		return class_exists('ReflectionClass');
	}
}
