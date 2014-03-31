<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions;

/**
 * CallbackFactory
 * 
 * This class creates the appropriate Callback instance based on the callback argument type.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class CallbackFactory
{
	/**
	 * Creates callback depending on argument types.
	 * 
	 * @param   string  $callback   String to build callback from
	 * @param   array   $arguments  Optional callback arguments
	 * @return  CallbackInterface
	 * @throws  Exceptions\NotSupportedException
	 */
	public function createCallback($callback, $arguments = array())
	{
		if ($callback instanceof CallbackInterface)
		{
			return $this->createCopy($callback, $arguments);
		}
		if (is_string($callback) && class_exists($callback))
		{
			return $this->createConstructor($callback, $arguments);
		}
		elseif (is_callable($callback))
		{
			return $this->createMethod($callback, $arguments);
		}
		elseif (is_object($callback))
		{
			return $this->createObject($callback, $arguments);
		}
		else
		{
			throw new Exceptions\NotSupportedException('Callback type not supported.');
		}
	}

	/**
	 * @param   string  $callback
	 * @param   mixed   $arguments
	 * @return  ConstructorCallback
	 */
	protected function createConstructor($callback, $arguments)
	{
		return new ConstructorCallback($callback, $arguments);
	}

	/**
	 * @param   string  $callback
	 * @param   mixed   $arguments
	 * @return  CallbackInterface
	 */
	protected function createCopy($callback, $arguments)
	{
		return $this->createCallback($callback->getCallback(), $arguments ? : $callback->getArguments());
	}

	/**
	 * @param   mixed  $callback
	 * @param   mixed  $arguments
	 * @return  MethodCallback
	 */
	protected function createMethod($callback, $arguments)
	{
		return new MethodCallback($callback, $arguments);
	}

	/**
	 * @param   object  $callback
	 * @param   mixed   $arguments
	 * @return  MethodCallback
	 */
	protected function createObject($callback, $arguments)
	{
		return new ObjectCallback($callback, $arguments);
	}
}
