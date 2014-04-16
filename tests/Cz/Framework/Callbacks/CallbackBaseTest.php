<?php
namespace Cz\Framework\Callbacks;
use Cz\PHPUnit,
	Cz\Framework\Exceptions;

/**
 * ConstructorTest
 * 
 * Tests the methods defined in the abstract class.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  CallbackBase  $object
 */
class CallbackBaseTest extends PHPUnit\Testcase
{
	/**
	 * Tests that the `getArguments` method returns the object's `arguments` property value.
	 * 
	 * @dataProvider  provideArguments
	 */
	public function testGetArguments($arguments)
	{
		$this->getObjectProperty($this->object, 'arguments')
			->setValue($this->object, $arguments);
		$actual = $this->object->getArguments();
		$this->assertSame($arguments, $actual);
	}

	/**
	 * Tests that the `setArguments` method sets the object's `arguments` property.
	 * 
	 * @dataProvider  provideArguments
	 */
	public function testSetArguments($arguments)
	{
		$this->object->setArguments($arguments);
		$actual = $this->getObjectProperty($this->object, 'arguments')
			->getValue($this->object);
		$this->assertSame($arguments, $actual);
	}

	/**
	 * Provides test cases for `testGetArguments` and `testSetArguments`.
	 */
	public function provideArguments()
	{
		return array(
			array(
				array(),
			),
			array(
				array(NULL),
			),
			array(
				array(TRUE, FALSE),
			),
			array(
				array(1, 2, 3),
			),
			array(
				array(new \stdClass),
			),
			array(
				new \ArrayObject,
			),
			array(
				new \ArrayObject(array(1, 2, 3)),
			),
		);
	}

	/**
	 * Tests that setting invalid arguments results in exception thrown.
	 * 
	 * @dataProvider       provideSetInvalidArguments
	 * @expectedException  Cz\Framework\Exceptions\InvalidArgumentException
	 */
	public function testSetInvalidArguments($arguments)
	{
		$this->object->setArguments($arguments);
	}

	/**
	 * Provides test cases for `testSetInvalidArguments`.
	 */
	public function provideSetInvalidArguments()
	{
		// [arguments]
		return array(
			// Invalid argument type
			array(NULL),
			array('not array'),
			array(1),
			array(FALSE),
			array(TRUE),
			// Associative arrays
			array(array(1 => 1, 2 => 2, 3 => 3)),
			array(new \ArrayObject(array(1 => 1, 2 => 2, 3 => 3))),
		);
	}

	/**
	 * Tests that the `getCallback` method returns the object's `callback` property value.
	 * 
	 * @dataProvider  provideCallback
	 */
	public function testGetCallback($object)
	{
		$this->getObjectProperty($this->object, 'callback')
			->setValue($this->object, $object);
		$actual = $this->object->getCallback();
		$this->assertSame($object, $actual);
	}

	/**
	 * Tests that the `setCallback` method sets the object's `callback` property.
	 * 
	 * @dataProvider  provideCallback
	 */
	public function testSetCallback($callback)
	{
		$this->object->setCallback($callback);
		$actual = $this->getObjectProperty($this->object, 'callback')
			->getValue($this->object);
		$this->assertSame($callback, $actual);
	}

	/**
	 * Provides test cases for `testGetCallback` and `testSetCallback`.
	 */
	public function provideCallback()
	{
		return array(
			array(
				new \stdClass,
			),
			array(
				array($this, 'provideCallback'),
			),
			array(
				'ArrayObject',
			),
		);
	}

	/**
	 * Tests `__invoke` magick method by calling it using `call_user_func_array`, passing some
	 * arguments and verifying it's returning value from the mocked `invoke` method.
	 * 
	 * @dataProvider  provideInvokeMagic
	 */
	public function testInvokeMagic($arguments, $expected)
	{
		$this->object->expects($this->any())
			->method('invoke')
			->will($this->returnValue($expected));
		$actual = call_user_func_array($this->object, $arguments);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Provides test cases for `testInvokeMagic`.
	 */
	public function provideInvokeMagic()
	{
		// [arguments, expected]
		return array(
			array(
				array(), TRUE,
			),
			array(
				array(3.14), new \stdClass,
			),
		);
	}

	/**
	 * Tests that the internal `getInvocationArguments` method returns arguments passed to it,
	 * if any, and otherwise returns object's default arguments using `getArguments` method.
	 * 
	 * @dataProvider  provideGetInvocationArguments
	 */
	public function testGetInvocationArguments($default, $arguments, $expected)
	{
		$this->setExpectedExceptionFromArgument($expected);
		$this->object->setArguments($default);
		$actual = $this->getObjectMethod($this->object, 'getInvocationArguments')
			->invoke($this->object, $arguments);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Provides test cases for `testGetInvocationArguments`.
	 */
	public function provideGetInvocationArguments()
	{
		// [default arguments, arguments, expected]
		return array(
			array(
				array(), array(), array(),
			),
			array(
				array(1, 2), array(), array(1, 2),
			),
			array(
				array(), array(3, 4), array(3, 4),
			),
			array(
				array(1, 2), array(3, 4), array(3, 4),
			),
			array(
				array(1, 2), 'not array', new Exceptions\InvalidArgumentException,
			),
		);
	}

	public function setUp()
	{
		$this->setupMock(array(
			'arguments' => array('callback'),
		));
	}
}
