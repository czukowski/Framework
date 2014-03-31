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
 * 
 * @property  object  $object  Object to return when invoked.
 */
class ObjectCallback extends Callback
{
	/**
	 * @param  object  $object
	 * @param  mixed   $arguments
	 */
	public function __construct($object, $arguments = NULL)
	{
		$this->validateCallback($object);
		$this->validateArguments($arguments);
		$this->callback = $object;
	}

	/**
	 * @param   object  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function validateCallback($callback)
	{
		if ( ! is_object($callback))
		{
			throw new Exceptions\InvalidArgumentException('Invalid callback type.');
		}
	}

	/**
	 * @param   mixed  $arguments
	 * @throws  Exceptions\NotSupportedException
	 */
	private function validateArguments($arguments)
	{
		if ($arguments !== NULL)
		{
			throw new Exceptions\NotSupportedException('Object callback type does not support arguments.');
		}
	}

	/**
	 * @param   mixed  $arguments
	 * @return  object
	 */
	public function invoke($arguments = NULL)
	{
		$this->validateArguments($arguments);
		return $this->callback;
	}

	/**
	 * @throws  Exceptions\NotSupportedException
	 */
	public function getArguments()
	{
		$this->throwException();
	}

	/**
	 * @throws  Exceptions\NotSupportedException
	 */
	public function setArguments($arguments)
	{
		$this->throwException($arguments);
	}

	/**
	 * @throws  Exceptions\NotSupportedException
	 */
	private function throwException()
	{
		throw new Exceptions\NotSupportedException('Object callback does not support arguments.');
	}
}
