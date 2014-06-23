<?php
namespace Cz\Framework\Callbacks;
use Cz\PHPUnit;

/**
 * CallbackFactoryTest
 * 
 * Tests the correct Callback class instances are being created.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  CallbackFactory  $object
 */
class CallbackFactoryTest extends PHPUnit\Testcase
{
	/**
	 * Tests the `createCallback` factory method.
	 * 
	 * @dataProvider  provideCreateCallback
	 */
	public function testCreateCallback($callback, $arguments, $expected)
	{
		if ( ! $expected)
		{
			$this->setExpectedException('Cz\Framework\Exceptions\NotSupportedException');
		}
		$actual = $this->object->createCallback($callback, $arguments);
		$this->assertInstanceOf($expected, $actual);
		$this->assertSame($arguments, $actual->getArguments());
	}

	/**
	 * Tests the `createCallback` factory method, used with a Callback type instance. It should
	 * not create callback of a callback, but instead use the raw callback object.
	 * 
	 * @dataProvider  provideCreateCallback
	 */
	public function testCreateCopy($callback, $arguments, $expected)
	{
		if ( ! $expected)
		{
			$this->setExpectedException('Cz\Framework\Exceptions\NotSupportedException');
		}
		$source = $this->object->createCallback($callback, $arguments);
		$actual = $this->object->createCallback($source);
		$this->assertInstanceOf($expected, $actual);
		$this->assertSame($source->getCallback(), $actual->getCallback());
		$this->assertSame($arguments, $actual->getArguments());
	}

	/**
	 * Provides test cases for `testCreateCallback` and `testCreateCopy`.
	 */
	public function provideCreateCallback()
	{
		// [callback, arguments, expected callback class]
		return array(
			array(get_class($this), array(), 'Cz\Framework\Callbacks\ConstructorCallback'),
			array($this->getClassName(), array(1, 2), 'Cz\Framework\Callbacks\ConstructorCallback'),
			array('ArrayObject', array(3.14), 'Cz\Framework\Callbacks\ConstructorCallback'),
			array('count', array('arguments'), 'Cz\Framework\Callbacks\MethodCallback'),
			array('PHPUnit_Framework_TestCase::any', array(), 'Cz\Framework\Callbacks\MethodCallback'),
			array(array($this, 'provideCreateCallback'), array(), 'Cz\Framework\Callbacks\MethodCallback'),
			array(function() {return TRUE;}, array(), 'Cz\Framework\Callbacks\MethodCallback'),
			array($this, array(), 'Cz\Framework\Callbacks\ObjectCallback'),
			array(3.14, array(), FALSE),
		);
	}

	public function setUp()
	{
		$this->setupObject();
	}
}
