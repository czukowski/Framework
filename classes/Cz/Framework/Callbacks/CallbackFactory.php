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
	 * @param   string  $callback
	 * @param   array   $arguments
	 * @return  CallbackInterface
	 * @throws  Exceptions\NotSupportedException
	 */
	public function createCallback($callback, array $arguments = array())
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
	 * @param   array   $arguments
	 * @return  ConstructorCallback
	 */
	protected function createConstructor($callback, $arguments)
	{
		return new ConstructorCallback($callback, $arguments);
	}

	/**
	 * @param   string  $callback
	 * @param   array   $arguments
	 * @return  CallbackInterface
	 */
	protected function createCopy($callback, $arguments)
	{
		return $this->createCallback($callback->getCallback(), $arguments);
	}

	/**
	 * @param   mixed  $callback
	 * @param   array  $arguments
	 * @return  MethodCallback
	 */
	protected function createMethod($callback, $arguments)
	{
		return new MethodCallback($callback, $arguments);
	}

	/**
	 * @param   object  $callback
	 * @param   array   $arguments
	 * @return  MethodCallback
	 */
	protected function createObject($callback, $arguments)
	{
		return new ObjectCallback($callback, $arguments);
	}
}
