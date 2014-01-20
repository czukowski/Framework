<?php
namespace Cz\Framework\Callbacks;

/**
 * ConstructorTest
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  Constructor  $object
 */
class ConstructorTest extends Testcase
{
	/**
	 * @dataProvider  provideInvoke
	 */
	public function testInvoke($classname, $arguments)
	{
		$this->setupObject(array(
			'arguments' => array($classname, $arguments),
		));
		$actual = $this->object->invoke();
		$this->assertInstanceOf($classname, $actual);
	}

	public function provideInvoke()
	{
		return array(
			array('stdClass', array()),
		);
	}

	public function provideConstruct()
	{
		return array(
			// Invalid definitions.
			array('PHPUnit_Framework_TestCase::any', TRUE),
			array(array($this, 'provideConstruct'), TRUE),
			array(new \stdClass, TRUE),
			array(function() {return TRUE;}, TRUE),
			// Classname definitions.
			array(get_class($this), FALSE),
			array($this->getClassName(), FALSE),
			array('ArrayObject', FALSE),
			array('Cz\Framework\Callbacks\CallbackInterface', TRUE),
		);
	}
}
