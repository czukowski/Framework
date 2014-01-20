<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions;

/**
 * Method
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
class Object extends Callback
{
	/**
	 * @param  object  $object
	 */
	public function __construct($object)
	{
		$this->validateCallback($object);
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
	 * @return  object
	 */
	public function invoke()
	{
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
	public function setArguments(array $arguments)
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
