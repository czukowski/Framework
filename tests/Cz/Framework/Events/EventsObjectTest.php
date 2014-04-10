<?php
namespace Cz\Framework\Events;
use Cz\Framework\Callbacks,
	Cz\Framework\Exceptions;

/**
 * EventsObjectTest
 * 
 * @package    Framework
 * @category   Events
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  EventsObject  $object
 */
class EventsObjectTest extends Testcase
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
		list ($callback1) = $this->createCallbacks();
		$factory = new Callbacks\CallbackFactory;
		$callbackObject = $factory->createCallback($callback1);
		return array(
			// Test adding two events of the same type.
			array(
				array(
					array('event1', $callback1),
					array('event2', $callback1),
					array('event1', $callback1),
				),
				array(
					'event1' => array($callback1, $callback1),
					'event2' => array($callback1),
				),
			),
			// Test adding event handler of a Framework's Callback type.
			array(
				array(
					array('event3', $callbackObject),
				),
				array(
					'event3' => array($callbackObject),
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
		list ($callback1) = $this->createCallbacks();
		return array(
			array(
				// Test adding multiple event handlers from an array.
				array(
					'event1' => $callback1,
					'event2' => $callback1,
				),
				array(
					'event1' => array($callback1),
					'event2' => array($callback1),
				),
			),
			array(
				// Test adding multiple event handlers from a Traversable object.
				new \ArrayObject(array(
					'event2' => $callback1,
					'event3' => $callback1,
				)),
				array(
					'event2' => array($callback1),
					'event3' => array($callback1),
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
		list ($callback1, $callback2) = $this->createCallbacks();
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
					'event1' => array($callback1Object, $callback2),
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
		list ($callback1, $callback2) = $this->createCallbacks();
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
		list ($callback1, $callback2) = $this->createCallbacks();
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
}
