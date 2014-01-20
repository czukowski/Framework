<?php
namespace Cz\Framework\Callbacks;

/**
 * ConstructorTest
 * 
 * Tests that the created objects are instances of the callback classnames.
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
		// [callback definition, expect exception?]
		return array(
			// Invalid definitions.
			array('PHPUnit_Framework_TestCase::any', TRUE),
			array(array($this, 'provideConstruct'), TRUE),
			array(new \stdClass, TRUE),
			array(function() {return TRUE;}, TRUE),
			// Valid classname definitions.
			array(get_class($this), FALSE),
			array($this->getClassName(), FALSE),
			array('ArrayObject', FALSE),
			// Valid classname definition, but not instanciable.
			array('Cz\Framework\Callbacks\CallbackInterface', TRUE),
		);
	}
}
