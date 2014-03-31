<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions;

/**
 * ConstructorCallbackTest
 * 
 * Tests that the created objects are instances of the callback classnames.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  ConstructorCallback  $object
 */
class ConstructorCallbackTest extends Testcase
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
		// [callback definition, callback arguments, expected exception]
		return array(
			// Invalid classname definitions.
			array('PHPUnit_Framework_TestCase::any', NULL, new Exceptions\InvalidArgumentException),
			array(array($this, 'provideConstruct'), NULL, new Exceptions\InvalidArgumentException),
			array(new \stdClass, NULL, new Exceptions\InvalidArgumentException),
			array(function() {return TRUE;}, NULL, new Exceptions\InvalidArgumentException),
			// Valid classname definitions, but unsupported arguments (unsupported).
			array(get_class($this), array(1, 2), new Exceptions\NotImplementedException),
			array($this->getClassName(), array(1), new Exceptions\NotImplementedException),
			// Valid classname definitions and arguments.
			array(get_class($this), NULL, NULL),
			array($this->getClassName(), NULL, NULL),
			array('ArrayObject', NULL, NULL),
			array('ArrayObject', array(), NULL),
			// Valid classname definition, but not instanciable.
			array('Cz\Framework\Callbacks\CallbackInterface', NULL, new Exceptions\InvalidArgumentException),
		);
	}
}
