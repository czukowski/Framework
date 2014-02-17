<?php
namespace Cz\Framework\Events;
use Cz\PHPUnit,
	Cz\Framework\Callbacks,
	Cz\Framework\Exceptions;

/**
 * EventObjectTest
 * 
 * @package    Framework
 * @category   Events
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  EventObject  $object
 */
class EventObjectTest extends PHPUnit\Testcase
{
	/**
	 * Tests adding event by calling the `addEvent()` method several times and then
	 * checking the internal event handlers container.
	 * 
	 * @dataProvider  provideAddEvent
	 */
	public function testAddEvent($addEvents, $expected)
	{
		$this->setExpectedExceptionFromArgument($expected);
		foreach ($addEvents as $addEvent)
		{
			list ($type, $handler) = $addEvent;
			$object = $this->object->addEvent($type, $handler);
			$this->assertInstanceOf($this->getClassName(), $object);
		}
		$actual = $this->getObjectEvents();
		$this->assertSame($expected, $actual);
	}

	public function provideAddEvent()
	{
		$this->setupObject();
		$callback = array($this->object, 'eventHandler1');
		$factory = new Callbacks\CallbackFactory;
		$callbackObject = $factory->createCallback($callback);
		return array(
			// Test adding two events of the same type.
			array(
				array(
					array('event1', $callback),
					array('event2', $callback),
					array('event1', $callback),
				),
				array(
					'event1' => array($callback, $callback),
					'event2' => array($callback),
				),
			),
			// Test adding event handler of a Framework's Callback type.
			array(
				array(
					array('event3', $callbackObject),
				),
				array(
					'event3' => array($callbackObject->getCallback()),
				),
			),
			// Test adding event handler that is not a valid callback.
			array(
				array('this_is_not_valid_callback'),
				new Exceptions\InvalidArgumentException,
			),
		);
	}

	/**
	 * Tests adding events by calling `addEvents()` method and then checking
	 * the internal event handlers container.
	 * 
	 * @dataProvider  provideAddEvents
	 */
	public function testAddEvents($events, $expected)
	{
		$this->setExpectedExceptionFromArgument($expected);
		$object = $this->object->addEvents($events);
		$this->assertInstanceOf($this->getClassName(), $object);
		$actual = $this->getObjectEvents();
		$this->assertSame($expected, $actual);
	}

	public function provideAddEvents()
	{
		$this->setupObject();
		$callback = array($this->object, 'eventHandler1');
		return array(
			array(
				// Test adding multiple event handlers from an array.
				array(
					'event1' => $callback,
					'event2' => $callback,
				),
				array(
					'event1' => array($callback),
					'event2' => array($callback),
				),
			),
			array(
				// Test adding multiple event handlers from a Traversable object.
				new \ArrayObject(array(
					'event2' => $callback,
					'event3' => $callback,
				)),
				array(
					'event2' => array($callback),
					'event3' => array($callback),
				),
			),
			// Test adding event handlers from neither array or object.
			array(
				'this is not an array',
				new Exceptions\InvalidArgumentException,
			),
			// Test adding event handlers from non-Traversable object.
			array(
				new \DateTime, // this is not Traversable object
				new Exceptions\InvalidArgumentException,
			),
		);
	}

	/**
	 * Tests removing events by calling `removeEvent()` method and then checking
	 * the internal event handlers container.
	 * 
	 * @dataProvider  provideRemoveEvent
	 */
	public function testRemoveEvent($events, $removeEvents, $expected)
	{
		$this->setObjectEvents($events);
		$this->setExpectedExceptionFromArgument($expected);
		foreach ($removeEvents as $removeEvent)
		{
			list ($type, $handler) = $removeEvent;
			$object = $this->object->removeEvent($type, $handler);
			$this->assertInstanceOf($this->getClassName(), $object);
		}
		$actual = $this->getObjectEvents();
		$this->assertSame($expected, $actual);
	}

	public function provideRemoveEvent()
	{
		$this->setupObject();
		$callback1 = array($this->object, 'eventHandler1');
		$callback2 = array($this->object, 'eventHandler2');
		$factory = new Callbacks\CallbackFactory;
		$callback1Object = $factory->createCallback($callback1);
		return array(
			// Test removing a specific event handler.
			array(
				array(
					'event1' => array($callback1),
					'event2' => array($callback1),
				),
				array(
					array('event1', $callback1),
				),
				array(
					'event1' => array(),
					'event2' => array($callback1),
				),
			),
			// Test removing a specific event handler by passing a Framework's Callback object.
			array(
				array(
					'event1' => array($callback1, $callback2),
					'event2' => array($callback1),
				),
				array(
					array('event1', $callback1Object),
				),
				array(
					'event1' => array(1 => $callback2),
					'event2' => array($callback1),
				),
			),
			// Test removing event handler that was not registered.
			array(
				array(
					'event1' => array($callback1),
				),
				array(
					array('event1', $callback2),
				),
				array(
					'event1' => array($callback1),
				),
			),
		);
	}

	/**
	 * Tests removing events by calling `removeEvents()` method and then checking
	 * the internal event handlers container.
	 * 
	 * @dataProvider  provideRemoveEvents
	 */
	public function testRemoveEvents($events, $removeEvents, $expected)
	{
		$this->setObjectEvents($events);
		$this->setExpectedExceptionFromArgument($expected);
		$object = $this->object->removeEvents($removeEvents);
		$this->assertInstanceOf($this->getClassName(), $object);
		$actual = $this->getObjectEvents();
		$this->assertSame($expected, $actual);
	}

	public function provideRemoveEvents()
	{
		$this->setupObject();
		$callback1 = array($this->object, 'eventHandler1');
		$callback2 = array($this->object, 'eventHandler2');
		return array(
			// Test remove some event handlers.
			array(
				array(
					'event1' => array($callback1, $callback2, $callback1),
					'event2' => array($callback1),
				),
				array(
					'event1' => $callback1,
					'event2' => $callback1,
				),
				array(
					'event1' => array(1 => $callback2),
					'event2' => array(),
				),
			),
			// Test remove some event handlers that did not exist.
			array(
				array(
					'event1' => array($callback1),
					'event2' => array($callback2),
				),
				array(
					'event1' => $callback2,
					'event2' => $callback1,
				),
				array(
					'event1' => array($callback1),
					'event2' => array($callback2),
				),
			),
			// Test alias for `removeAllEvents()` with string parameter.
			array(
				array(
					'event1' => array($callback1, $callback2),
					'event2' => array($callback1),
				),
				'event1',
				array(
					'event1' => array(),
					'event2' => array($callback1),
				),
			),
			// Test alias for `removeAllEvents()` with `NULL` parameter.
			array(
				array(
					'event1' => array($callback1, $callback2),
					'event2' => array($callback1),
				),
				NULL,
				array(),
			),
		);
	}

	/**
	 * Tests removing events by calling `removeAllEvents()` method and then checking
	 * the internal event handlers container.
	 * 
	 * @dataProvider  provideRemoveAllEvents
	 */
	public function testRemoveAllEvents($events, $type, $expected)
	{
		$this->setObjectEvents($events);
		$this->setExpectedExceptionFromArgument($expected);
		$object = $this->object->removeAllEvents($type);
		$this->assertInstanceOf($this->getClassName(), $object);
		$actual = $this->getObjectEvents();
		$this->assertSame($expected, $actual);
	}

	public function provideRemoveAllEvents()
	{
		$this->setupObject();
		$callback1 = array($this->object, 'eventHandler1');
		$callback2 = array($this->object, 'eventHandler2');
		return array(
			// Test remove all events of type 'event1'.
			array(
				array(
					'event1' => array($callback1, $callback2, $callback1),
					'event2' => array($callback1),
				),
				'event1',
				array(
					'event1' => array(),
					'event2' => array($callback1),
				),
			),
			// Test remove all events.
			array(
				array(
					'event1' => array($callback1, $callback2, $callback1),
					'event2' => array($callback1),
				),
				NULL,
				array(),
			),
			// Test remove all events by passing invalid event type.
			array(
				array(
					'event1' => array($callback1, $callback2, $callback1),
					'event2' => array($callback1),
				),
				TRUE, // NULL or string expected.
				new Exceptions\InvalidArgumentException,
			),
		);
	}

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
		$this->setupObject();
		$callback1 = array($this->object, 'eventHandler1');
		$callback2 = array($this->object, 'eventHandler2');
		return array(
			// Test firing event when no event handlers are set.
			array(
				array(),
				array('event1', array('param1', 'param2')),
				$this->object,
				array(),
			),
			// Test firing event.
			array(
				array(
					'event1' => array($callback1, $callback2, $callback1),
					'event2' => array($callback1),
				),
				array('event2', array('param1', 'param2')),
				$this->object,
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
				$this->object,
				array(
					array('eventHandler1', 'param1', 'param2'),
					array('eventHandler2', 'param1', 'param2'),
					array('eventHandler1', 'param1', 'param2'),
				),
			),
		);
	}

	/**
	 * Tests events firing by calling `fireEventBinded()` method and checking which event
	 * handlers were called and whether the last argument was event origin's `$this` reference.
	 * 
	 * @dataProvider  provideFireEventBinded
	 */
	public function testFireEventBinded($events, $fireEvent, $handler, $expected)
	{
		foreach ($expected as &$eventArgs)
		{
			$eventArgs[] = $this->object;
		}
		$handler->callbackArguments = array();
		$this->setObjectEvents($events);
		list ($type, $arguments) = $fireEvent;
		$object = $this->object->fireEventBinded($type, $arguments);
		$this->assertInstanceOf($this->getClassName(), $object);
		$actual = $handler->callbackArguments;
		$this->assertSame($expected, $actual);
	}

	public function provideFireEventBinded()
	{
		$this->setupObject();
		$callback1 = array($this->object, 'eventHandler1');
		$callback2 = array($this->object, 'eventHandler2');
		return array(
			// Test firing event on multiple copies of event handler.
			array(
				array(
					'event1' => array($callback1, $callback2, $callback1),
					'event2' => array($callback1),
				),
				array('event1', array('param1', 'param2')),
				$this->object,
				array(
					array('eventHandler1', 'param1', 'param2'),
					array('eventHandler2', 'param1', 'param2'),
					array('eventHandler1', 'param1', 'param2'),
				),
			),
		);
	}

	private function getObjectEvents()
	{
		return $this->getObjectProperty($this->object, '_events')
			->getValue($this->object);
	}

	private function setObjectEvents(array $events)
	{
		$this->getObjectProperty($this->object, '_events')
			->setValue($this->object, $events);
	}

	public function setUp()
	{
		$this->setupObject();
	}
}
