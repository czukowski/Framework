<?php
namespace Cz\Framework\Objects;
use Cz\Framework\Exceptions;

/**
 * ObjectEventsTest
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  ObjectEvents  $object
 */
class ObjectEventsTest extends Testcase
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
	 * Tests object issetter event firing by setting up event listeners and calling the `has`
	 * method.
	 * 
	 * @dataProvider  provideHas
	 */
	public function testHas($arguments, $expected)
	{
		$this->setupEvents('has');
		$this->invokeObjectMethod('has', $arguments);
		$this->assertSame($expected, $this->callbackArguments);
	}

	public function provideHas()
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
	 * Setup fixture and initialize event log.
	 */
	public function setUp()
	{
		$this->setupMock(array(
			'methods' => array_merge($this->getClassAbstractMethods($this->getClassName()), array('fireEvent')),
		));
		$this->object->expects($this->any())
			->method('fireEvent')
			->will($this->returnCallback(array($this, 'callbackFireEvent')));
		$this->callbackArguments = array();
	}

	/**
	 * Callback function to simulate `Events\FireBasic` trait. Fires events with the arguments
	 * exactly as called.
	 * 
	 * @param  string  $type
	 * @param  array   $arguments
	 */
	public function callbackFireEvent($type, $arguments)
	{
		$this->getObjectMethod($this->object, '_fireEvent')
			->invoke($this->object, $type, $arguments);
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
