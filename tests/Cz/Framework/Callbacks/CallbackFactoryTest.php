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

	public function provideCreateCallback()
	{
		return array(
			array(get_class($this), 'Cz\Framework\Callbacks\Constructor'),
			array($this->getClassName(), 'Cz\Framework\Callbacks\Constructor'),
			array('ArrayObject', 'Cz\Framework\Callbacks\Constructor'),
			array('count', 'Cz\Framework\Callbacks\Method'),
			array('PHPUnit_Framework_TestCase::any', 'Cz\Framework\Callbacks\Method'),
			array(array($this, 'provideCreateCallback'), 'Cz\Framework\Callbacks\Method'),
			array(function() {return TRUE;}, 'Cz\Framework\Callbacks\Method'),
			array($this, 'Cz\Framework\Callbacks\Object'),
			array(3.14, FALSE),
		);
	}

	public function setUp()
	{
		$this->setupObject();
	}
}
