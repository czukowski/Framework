<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions;

/**
 * MethodCallback
 * 
 * This type of the callback, when invoked, calls a function or method using the specified
 * arguments and returns the result. The following callback arguments are allowed:
 * 
 *   - string: must be valid functon or method name and must pass the `is_callable()` check,
 *   - array: must have exactly two members, the first is an object, second is a string, must
 *     also pass the `is_callable()` check,
 *   - object: must be instance of `\Closure`.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  mixed  $callback  Callback to method.
 */
class MethodCallback extends CallbackBase
{
	/**
	 * @param   mixed  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	protected function validateCallback($callback)
	{
		if (is_string($callback))
		{
			$this->validateString($callback);
		}
		elseif (is_array($callback))
		{
			$this->validateArray($callback);
		}
		elseif (is_object($callback))
		{
			$this->validateObject($callback);
		}
		else
		{
			throw new Exceptions\InvalidArgumentException('Invalid callback type.');
		}
	}

	/**
	 * @param   string  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function validateString($callback)
	{
		if ( ! is_callable($callback))
		{
			throw new Exceptions\InvalidArgumentException('String "'.$callback.'" is not callable.');
		}
	}

	/**
	 * @param   array  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function validateArray($callback)
	{
		$callback = array_values($callback);
		if (count($callback) < 2)
		{
			throw new Exceptions\InvalidArgumentException('Array has less than two items.');
		}
		elseif ( ! is_object($callback[0]))
		{
			throw new Exceptions\InvalidArgumentException('Array ('.$callback[0].', '.$callback[1].') first item is not an object.');
		}
		elseif ( ! is_string($callback[1]))
		{
			throw new Exceptions\InvalidArgumentException('Array ('.get_class($callback[0]).', '.$callback[1].') second item is not a string.');
		}
		elseif ( ! is_callable($callback))
		{
			throw new Exceptions\InvalidArgumentException('Array ('.get_class($callback[0]).', '.$callback[1].') is not callable.');
		}
	}

	/**
	 * @param   object  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function validateObject($callback)
	{
		if ( ! $callback instanceof \Closure)
		{
			throw new Exceptions\InvalidArgumentException('Object '.get_class($callback).' is not a Closure.');
		}
	}

	/**
	 * @param   array  $arguments
	 * @return  object
	 */
	public function invoke($arguments = array())
	{
		return call_user_func_array($this->callback, $arguments ? : $this->getArguments());
	}
}
