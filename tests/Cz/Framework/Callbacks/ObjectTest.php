<?php
namespace Cz\Framework\Callbacks;

/**
 * ObjectTest
 * 
 * Tests that the same object is returned when the Callback is invoked.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  Object  $object
 */
class ObjectTest extends Testcase
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
			// Invalid definitions.
			array('PHPUnit_Framework_TestCase::any', TRUE),
			array(array($this, 'provideConstruct'), TRUE),
			// Object definitions.
			array(new \stdClass, FALSE),
			array($this, FALSE),
			array($this->getMock('Cz\Entities\EntityInterface'), FALSE),
			array(function() {return TRUE;}, FALSE),
		);
	}

	public function setUp()
	{
		$this->setupObject(array(
			'arguments' => array(new \stdClass),
		));
	}
}
