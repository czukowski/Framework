<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions,
	Traversable;

/**
 * Callback
 * 
 * Generic callback class, implements methods commonly used by the specific callback classes.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
abstract class CallbackBase implements CallbackInterface
{
	/**
	 * @var  array  Default callback invocation arguments, used when invoked without arguments.
	 */
	protected $arguments = array();
	/**
	 * @var  mixed  Actual callback object (each Callback type implements it a bit differently).
	 */
	protected $callback;

	/**
	 * Sets callback and default arguments.
	 * 
	 * @param  mixed  $callback   Actual callback object
	 * @param  array  $arguments  Default invocation arguments
	 */
	public function __construct($callback, $arguments = array())
	{
		$this->setCallback($callback);
		$this->setArguments($arguments);
	}

	/**
	 * Returns actual callback object.
	 * 
	 * @return  mixed
	 */
	public function getCallback()
	{
		return $this->callback;
	}

	/**
	 * Sets actual callback object.
	 * 
	 * @param   mixed  $callback
	 * @return  $this
	 */
	public function setCallback($callback)
	{
		$this->validateCallback($callback);
		$this->callback = $callback;
		return $this;
	}

	/**
	 * Used to validate actual callback object when set. Implemented differently in each
	 * Callback type.
	 * 
	 * @param   mixed  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	abstract protected function validateCallback($callback);

	/**
	 * Returns default invocation arguments.
	 * 
	 * @return  array
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * Sets default invocation arguments. Takes array or Traversable object.
	 * 
	 * @param   array|Traversable  $arguments
	 * @return  $this
	 */
	public function setArguments($arguments)
	{
		$this->validateArguments($arguments);
		$this->arguments = $arguments;
		return $this;
	}

	/**
	 * Used to validate invocation arguments. Throws `InvalidArgumentException` if not array,
	 * nor Traversable object. Also, array must not be associative (unsupported for now).
	 * Specific Callback types may have additional checks.
	 * 
	 * @param   array|Traversable  $arguments
	 * @throws  Exceptions\InvalidArgumentException
	 */
	protected function validateArguments($arguments)
	{
		if ( ! is_array($arguments) && ! $arguments instanceof Traversable)
		{
			throw new Exceptions\InvalidArgumentException('Invalid callback arguments, expected array or Traversable object.');
		}
		elseif (($keys = array_keys( (array) $arguments)) && array_keys($keys) !== $keys)
		{
			throw new Exceptions\InvalidArgumentException('Associative array of arguments is not supported.');
		}
	}

	/**
	 * Called when a Callback instance is invoked using `call_user_function()` or similar
	 * function.
	 * 
	 * @return  mixed
	 */
	public function __invoke()
	{
		return $this->invoke(func_get_args());
	}

	/**
	 * Returns "clean" arguments to be used for callback invocation. Also validates passed
	 * whatever arguments are passed to it, if any.
	 * 
	 * @param   array|Traversable  $arguments
	 * @return  array
	 */
	protected function getInvocationArguments($arguments)
	{
		$this->validateArguments($arguments);
		return $arguments ? : $this->getArguments();
	}
}
