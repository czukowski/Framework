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
abstract class Callback implements CallbackInterface
{
	/**
	 * @var  array|NULL
	 */
	protected $arguments;
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
	 * @return  array|NULL
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
		if ($arguments !== NULL && ! is_array($arguments))
		{
			throw new Exceptions\InvalidArgumentException('Invalid callback arguments, expected array or NULL.');
		}
		$this->arguments = $arguments;
		return $this;
	}
}
