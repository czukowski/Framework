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
	 * @var  array
	 */
	protected $arguments = array();
	/**
	 * @var  mixed
	 */
	protected $callback;

	/**
	 * @return  mixed
	 */
	public function getCallback()
	{
		return $this->callback;
	}

	/**
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
	 * @param   mixed  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	abstract protected function validateCallback($callback);

	/**
	 * @return  array
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * @param   array|Traversable  $callback
	 * @return  $this
	 */
	public function setArguments($arguments)
	{
		$this->validateArguments($arguments);
		$this->arguments = $arguments;
		return $this;
	}

	/**
	 * @param   array|Traversable  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	protected function validateArguments($arguments)
	{
		if ( ! is_array($arguments) && ! $arguments instanceof Traversable)
		{
			throw new Exceptions\InvalidArgumentException('Invalid callback arguments, expected array or Traversable object.');
		}
	}

	/**
	 * Invoke magic function.
	 * 
	 * @return  mixed
	 */
	public function __invoke()
	{
		return $this->invoke(func_get_args());
	}
}
