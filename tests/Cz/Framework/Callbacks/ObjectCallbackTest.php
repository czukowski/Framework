<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions;

/**
 * ObjectCallbackTest
 * 
 * Tests that the same object is returned when the Callback is invoked.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  ObjectCallback  $object
 */
class ObjectCallbackTest extends Testcase
{
	/**
	 * Tests that exception is thrown, when attempted to set any arguments.
	 * 
	 * @expectedException  Cz\Framework\Exceptions\NotSupportedException
	 */
	public function testSetArguments()
	{
		$this->object->setArguments(array('anything'));
	}

	/**
	 * Tests that the default arguments are ampty.
	 */
	public function testGetArguments()
	{
		$actual = $this->object->getArguments();
		$this->assertSame(array(), $actual);
	}

	/**
	 * Tests invocation return values.
	 * 
	 * @dataProvider  provideInvoke
	 */
	public function testInvoke($object)
	{
		$this->setupObject(array(
			'arguments' => array($object),
		));
		$actual = $this->object->invoke();
		$this->assertSame($object, $actual);
	}

	/**
	 * Tests that the exception is thrown when attempted to invoke with any arguments.
	 * 
	 * @dataProvider       provideInvoke
	 * @expectedException  Cz\Framework\Exceptions\NotSupportedException
	 */
	public function testInvokeWithArguments($object)
	{
		$this->setupObject(array(
			'arguments' => array($object),
		));
		$this->object->invoke(array('any', 'arguments'));
	}

	/**
	 * Provides test cases for the `testInvoke` test and related tests.
	 */
	public function provideInvoke()
	{
		return array(
			array($this),
			array(new \stdClass),
		);
	}

	/**
	 * Provides test cases for the common `testConstruct` test, that's in the Testcase class.
	 */
	public function provideConstruct()
	{
		return array(
			// Invalid object callback definitions.
			array('PHPUnit_Framework_TestCase::any', NULL, new Exceptions\InvalidArgumentException),
			array(array($this, 'provideConstruct'), NULL, new Exceptions\InvalidArgumentException),
			// Valid bject definitions.
			array(new \stdClass, NULL, NULL),
			array($this, NULL, NULL),
			array($this->getMock('Cz\Entities\EntityInterface'), NULL, NULL),
			array(function() {return TRUE;}, NULL, NULL),
		);
	}

	public function setUp()
	{
		$this->setupObject(array(
			'arguments' => array(new \stdClass),
		));
	}
}
