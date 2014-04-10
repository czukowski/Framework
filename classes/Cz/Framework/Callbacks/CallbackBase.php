<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions;

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
	 * @return  array
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * @return  $this
	 */
	public function setArguments($arguments)
	{
		if ( ! is_array($arguments) && ! $arguments instanceof \Traversable)
		{
			throw new Exceptions\InvalidArgumentException('Invalid callback arguments, expected array or Traversable object.');
		}
		$this->arguments = $arguments;
		return $this;
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
