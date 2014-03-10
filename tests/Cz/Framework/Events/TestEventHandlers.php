<?php
namespace Cz\Framework\Events;

/**
 * TestEventHandlers
 * 
 * @package    Framework
 * @category   Events
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
abstract class TestEventHandlers
{
	public $callbackArguments = array();

	/**
	 * Test event handler.
	 */
	public function eventHandler1()
	{
		$this->logEvent(__FUNCTION__, func_get_args());
	}

	/**
	 * Another test event handler.
	 */
	public function eventHandler2()
	{
		$this->logEvent(__FUNCTION__, func_get_args());
	}

	/**
	 * Logs event handler calls.
	 * 
	 * @param  string  $method
	 * @param  array   $arguments
	 */
	private function logEvent($method, $arguments)
	{
		array_unshift($arguments, $method);
		$this->callbackArguments[] = $arguments;
	}
}
