<?php
namespace Cz\Framework\Callbacks;

/**
 * Callback
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
	 * @var  array
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
	 * @return  array
	 */
	public function getArguments()
	{
		return $this->arguments;
	}

	/**
	 * @return  $this
	 */
	public function setArguments(array $arguments)
	{
		$this->arguments = $arguments;
		return $this;
	}
}
