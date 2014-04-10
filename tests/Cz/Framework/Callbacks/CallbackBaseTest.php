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
	 * @dataProvider  provideArguments
	 */
	public function testSetArguments($arguments, $exception = NULL)
	{
		$this->setExpectedExceptionFromArgument($exception);
		$this->object->setArguments($arguments);
		$actual = $this->getObjectProperty($this->object, 'arguments')
			->getValue($this->object);
		$this->assertSame($arguments, $actual);
	}

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
				NULL,
				new Exceptions\InvalidArgumentException,
			),
		);
	}

	/**
	 * @dataProvider       provideSetInvalidArguments
	 * @expectedException  Cz\Framework\Exceptions\InvalidArgumentException
	 */
	public function testSetInvalidArguments($arguments)
	{
		$this->object->setArguments($arguments);
	}

	public function provideSetInvalidArguments()
	{
		return array(
			array('not array'),
			array(1),
			array(FALSE),
			array(TRUE),
		);
	}

	/**
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
	 * @dataProvider  provideCallback
	 */
	public function testSetCallback($callback)
	{
		$this->object->setCallback($callback);
		$actual = $this->getObjectProperty($this->object, 'callback')
			->getValue($this->object);
		$this->assertSame($callback, $actual);
	}

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

	public function setUp()
	{
		$this->setupMock(array(
			'arguments' => array('callback'),
		));
	}
}
