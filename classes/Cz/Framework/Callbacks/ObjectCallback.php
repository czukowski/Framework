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
	 * @param  array   $arguments
	 */
	public function __construct($object, $arguments = array())
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
	 * @param   array  $arguments
	 * @throws  Exceptions\NotSupportedException
	 */
	private function validateArguments($arguments)
	{
		if ($arguments)
		{
			throw new Exceptions\NotSupportedException('Object callback type does not support arguments.');
		}
	}

	/**
	 * @param   array  $arguments
	 * @return  object
	 */
	public function invoke($arguments = array())
	{
		$this->validateArguments($arguments);
		return $this->callback;
	}

	/**
	 * @throws  Exceptions\NotSupportedException
	 */
	public function setArguments($arguments)
	{
		throw new Exceptions\NotSupportedException('Object callback does not support arguments.');
	}
}
