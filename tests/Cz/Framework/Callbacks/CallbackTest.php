<?php
namespace Cz\Framework\Callbacks;
use Cz\PHPUnit;

/**
 * ConstructorTest
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  Callback  $object
 */
class CallbackTest extends PHPUnit\Testcase
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
	public function testSetArguments($arguments)
	{
		$this->object->setArguments($arguments);
		$actual = $this->getObjectProperty($this->object, 'arguments')
			->getValue($this->object);
		$this->assertSame($arguments, $actual);
	}

	/**
	 * @dataProvider  provideArguments
	 */
	public function testGetCallback($object)
	{
		$this->getObjectProperty($this->object, 'callback')
			->setValue($this->object, $object);
		$actual = $this->object->getCallback();
		$this->assertSame($object, $actual);
	}

	public function provideArguments()
	{
		return array(
			array(
				array(1, 2, 3),
			),
			array(
				array(NULL),
			),
			array(
				array(TRUE, FALSE),
			),
			array(
				array(new \stdClass),
			),
		);
	}

	public function setUp()
	{
		$this->setupMock();
	}
}
