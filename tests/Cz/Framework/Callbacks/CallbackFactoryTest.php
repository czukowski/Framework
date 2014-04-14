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
	public function testCreateCallback($callback, $expected)
	{
		if ( ! $expected)
		{
			$this->setExpectedException('Cz\Framework\Exceptions\NotSupportedException');
		}
		$actual = $this->object->createCallback($callback);
		$this->assertInstanceOf($expected, $actual);
	}

	/**
	 * Tests the `createCallback` factory method, used with a Callback type instance. It should
	 * not create callback of a callback, but instead use the raw callback object.
	 * 
	 * @dataProvider  provideCreateCallback
	 */
	public function testCreateCopy($callback, $expected)
	{
		if ( ! $expected)
		{
			$this->setExpectedException('Cz\Framework\Exceptions\NotSupportedException');
		}
		$source = $this->object->createCallback($callback);
		$actual = $this->object->createCallback($source);
		$this->assertInstanceOf($expected, $actual);
		$this->assertSame($source->getCallback(), $actual->getCallback());
	}

	/**
	 * Provides test cases for `testCreateCallback` and `testCreateCopy`.
	 */
	public function provideCreateCallback()
	{
		return array(
			array(get_class($this), 'Cz\Framework\Callbacks\ConstructorCallback'),
			array($this->getClassName(), 'Cz\Framework\Callbacks\ConstructorCallback'),
			array('ArrayObject', 'Cz\Framework\Callbacks\ConstructorCallback'),
			array('count', 'Cz\Framework\Callbacks\MethodCallback'),
			array('PHPUnit_Framework_TestCase::any', 'Cz\Framework\Callbacks\MethodCallback'),
			array(array($this, 'provideCreateCallback'), 'Cz\Framework\Callbacks\MethodCallback'),
			array(function() {return TRUE;}, 'Cz\Framework\Callbacks\MethodCallback'),
			array($this, 'Cz\Framework\Callbacks\ObjectCallback'),
			array(3.14, FALSE),
		);
	}

	public function setUp()
	{
		$this->setupObject();
	}
}
