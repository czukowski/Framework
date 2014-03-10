<?php
namespace Cz\Framework\Events;

/**
 * FireBindedObjectTest
 * 
 * @package    Framework
 * @category   Events
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  EventsObject  $object
 */
class FireBindedObjectTest extends Testcase
{
	/**
	 * Tests events firing by calling `fireEvent()` method and checking which event handlers
	 * were called and whether the last argument was event origin's `$this` reference.
	 * 
	 * @dataProvider  provideFireEvent
	 */
	public function testFireEvent($events, $fireEvent, $handler, $expected)
	{
		foreach ($expected as &$eventArgs)
		{
			$eventArgs[] = $this->object;
		}
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
