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
	 * @expectedException  Cz\Framework\Exceptions\NotSupportedException
	 */
	public function testGetArguments()
	{
		$this->object->getArguments();
	}

	/**
	 * @expectedException  Cz\Framework\Exceptions\NotSupportedException
	 */
	public function testSetArguments()
	{
		$this->object->setArguments(array('anything'));
	}

	/**
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

	public function provideInvoke()
	{
		return array(
			array($this),
			array(new \stdClass),
		);
	}

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
