<?php
namespace Cz\Framework\Objects;
use Cz\Framework\Exceptions;

/**
 * EventObjectTest
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  EventObject  $object
 */
class EventObjectTest extends Testcase
{
	/**
	 * @var  array
	 */
	private $callbackArguments;

	/**
	 * Tests object getter event firing by setting up event listeners and calling the `get` method.
	 * 
	 * @dataProvider  provideGet
	 */
	public function testGet($arguments, $expected)
	{
		$this->setupEvents('get');
		$this->invokeObjectMethod('get', $arguments);
		$this->assertBindedArguments($this->object);
		$this->assertSame($expected, $this->callbackArguments);
	}

	public function provideGet()
	{
		return array(
			// Test with default value.
			array(
				array('any', 'default'),
				array(
					array('before', 'any', 'default'),
					array('after', 'any', 'default'),
				),
			),
			// Test with NULL default value.
			array(
				array('any', NULL),
				array(
					array('before', 'any', NULL),
					array('after', 'any', NULL),
				),
			),
			// Test without default value.
			array(
				array('any'),
				array(
					array('before', 'any', NULL),
				),
			),
		);
	}

	/**
	 * Tests object setter event firing by setting up event listeners and calling the `set` method.
	 * 
	 * @dataProvider  provideSet
	 */
	public function testSet($arguments, $expected)
	{
		$this->setupEvents('set');
		$this->invokeObjectMethod('set', $arguments);
		$this->assertBindedArguments($this->object);
		$this->assertSame($expected, $this->callbackArguments);
	}

	public function provideSet()
	{
		return array(
			// Test setting single value.
			array(
				array('any', 'default'),
				array(
					array('before', 'any', 'default'),
					array('after', 'any', 'default'),
				),
			),
			// Test setting multiple values.
			array(
				array(
					array(
						'some' => 'key',
						'anotner' => NULL,
					),
				),
				array(
					array('before', 'some', 'key'),
					array('after', 'some', 'key'),
					array('before', 'anotner', NULL),
					array('after', 'anotner', NULL),
				),
			),
		);
	}

	/**
	 * Tests object issetter event firing by setting up event listeners and calling the `exists`
	 * method.
	 * 
	 * @dataProvider  provideExists
	 */
	public function testExists($arguments, $expected)
	{
		$this->setupEvents('exists');
		$this->invokeObjectMethod('exists', $arguments);
		$this->assertBindedArguments($this->object);
		$this->assertSame($expected, $this->callbackArguments);
	}

	public function provideExists()
	{
		return array(
			array(
				array('any'),
				array(
					array('before', 'any'),
					array('after', 'any'),
				),
			),
		);
	}

	/**
	 * Tests object unsetter event firing by setting up event listeners and calling the `erase`
	 * method.
	 * 
	 * @dataProvider  provideErase
	 */
	public function testErase($values, $arguments, $expected)
	{
		$this->invokeObjectMethod('set', array($values));
		$this->setupEvents('erase');
		$this->invokeObjectMethod('erase', $arguments);
		$this->assertBindedArguments($this->object);
		$this->assertSame($expected, $this->callbackArguments);
	}

	public function provideErase()
	{
		return array(
			// Test erasing single value.
			array(
				array(
					'any' => 'key',
				),
				array('any'),
				array(
					array('before', 'any'),
					array('after', 'any'),
				),
			),
			// Test erasing all values.
			array(
				array(
					'any' => 'key',
					'another' => 'value',
				),
				array(),
				array(
					array('before', 'any'),
					array('after', 'any'),
					array('before', 'another'),
					array('after', 'another'),
				),
			),
		);
	}

	/**
	 * When `fireEventBinded` method is used to fire events, `$this` parameter is added at the end
	 * of the parameters list. This method asserts it is present in the events log _and removes it_
	 * so that the remaining parameters may be tested against the expected array from the data
	 * providers.
	 * 
	 * @param  EventObject  $expected
	 */
	protected function assertBindedArguments($expected)
	{
		foreach ($this->callbackArguments as &$arguments)
		{
			$lastArgument = array_pop($arguments);
			$this->assertSame($expected, $lastArgument);
		}
	}

	/**
	 * Setup fixture and initialize event log.
	 */
	public function setUp()
	{
		$this->setupMock();
		$this->callbackArguments = array();
	}

	/**
	 * Setup event handlers on object for a specific access method.
	 * 
	 * @param  string  $method
	 */
	private function setupEvents($method)
	{
		$this->object->addEvent('before-'.$method, array($this, 'logBefore'));
		$this->object->addEvent('after-'.$method, array($this, 'logAfter'));
	}

	/**
	 * Invokes a specified method on object with arguments. Catches any exceptions in order
	 * to assert the events fired.
	 * 
	 * @param  string  $method
	 * @param  array   $arguments
	 */
	private function invokeObjectMethod($method, $arguments)
	{
		try
		{
			call_user_func_array(array($this->object, $method), $arguments);
		}
		catch (\Exception $e)
		{
			// Continue silently. The test will verify the events fired before the exception.
		}
	}

	/**
	 * Event handler for 'before' events.
	 */
	public function logBefore()
	{
		$this->logEvent(array_merge(array('before'), func_get_args()));
	}

	/**
	 * Event handler for 'after' events.
	 */
	public function logAfter()
	{
		$this->logEvent(array_merge(array('after'), func_get_args()));
	}

	/**
	 * Event handler for 'before' events.
	 * 
	 * @param  array  $arguments
	 */
	private function logEvent($arguments)
	{
		$this->callbackArguments[] = $arguments;
	}
}
