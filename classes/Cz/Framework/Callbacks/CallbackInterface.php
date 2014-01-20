<?php
namespace Cz\Framework\Callbacks;

/**
 * CallbackInterface
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
interface CallbackInterface
{
	/**
	 * Return arguments for the callback.
	 * 
	 * @return  array
	 */
	function getArguments();

	/**
	 * Set arguments for the callback.
	 * 
	 * @param   array  $arguments
	 * @return  $this
	 */
	function setArguments(array $arguments);

	/**
	 * Return "raw" callback object.
	 * 
	 * @return  mixed
	 */
	function getCallback();

	/**
	 * Invoke callback and return the resulting value.
	 */
	function invoke();
}
