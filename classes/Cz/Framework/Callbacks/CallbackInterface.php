<?php
namespace Cz\Framework\Callbacks;

/**
 * CallbackInterface
 * 
 * This interface defines the basic methods that must be used by callback classes.
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
	 * @param   array|NULL  $arguments
	 * @return  $this
	 */
	function setArguments($arguments);

	/**
	 * Return "raw" callback object.
	 * 
	 * @return  mixed
	 */
	function getCallback();

	/**
	 * Invoke callback and return the resulting value.
	 * 
	 * @param  array|NULL  $arguments  Optional arguments.
	 */
	function invoke($arguments = NULL);
}
