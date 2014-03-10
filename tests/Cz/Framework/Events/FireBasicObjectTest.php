<?php
namespace Cz\Framework\Events;

/**
 * FireBasicObjectTest
 * 
 * @package    Framework
 * @category   Events
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  EventsObject  $object
 */
class FireBasicObjectTest extends Testcase
{
	/**
	 * Tests events firing by calling `fireEvent()` method and checking which event handlers
	 * were called.
	 * 
	 * @dataProvider  provideFireEvent
	 */
	public function testFireEvent($events, $fireEvent, $handler, $expected)
	{
		$handler->callbackArguments = array();
		$this->setObjectEvents($events);
		list ($type, $arguments) = $fireEvent;
		$object = $this->object->fireEvent($type, $arguments);
		$this->assertInstanceOf($this->getClassName(), $object);
		$actual = $handler->callbackArguments;
		$this->assertSame($expected, $actual);
	}

	public function provideFireEvent()
	{
		list ($callback1, $callback2, $handler) = $this->createCallbacks();
		return array(
			// Test firing event when no event handlers are set.
			array(
				array(),
				array('event1', array('param1', 'param2')),
				$handler,
				array(),
			),
			// Test firing event.
			array(
				array(
					'event1' => array($callback1, $callback2, $callback1),
					'event2' => array($callback1),
				),
				array('event2', array('param1', 'param2')),
				$handler,
				array(
					array('eventHandler1', 'param1', 'param2'),
				),
			),
			// Test firing event on multiple copies of event handler.
			array(
				array(
					'event1' => array($callback1, $callback2, $callback1),
					'event2' => array($callback1),
				),
				array('event1', array('param1', 'param2')),
				$handler,
				array(
					array('eventHandler1', 'param1', 'param2'),
					array('eventHandler2', 'param1', 'param2'),
					array('eventHandler1', 'param1', 'param2'),
				),
			),
		);
	}
}
