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
	 * Tests invocation return values.
	 * 
	 * @dataProvider  provideInvoke
	 */
	public function testInvoke($classname, $default, $arguments, $reflection)
	{
		$this->setupInvoke($classname, $default, $reflection);
		$actual = $this->object->invoke($arguments);
		$this->assertInstanceOf($classname, $actual);
	}

	/**
	 * Setup fixture object depending on `$reflection` argument. If we want to test as if
	 * Reflection was not available, got to create a mock object.
	 */
	private function setupInvoke($classname, $default, $reflection)
	{
		if ($reflection)
		{
			$this->setupObject(array(
				'arguments' => array($classname, $default),
			));
		}
		else
		{
			$this->setupMock(array(
				'arguments' => array($classname, $default),
				'methods' => $reflection ? array('none') : array('isReflectionAvailable'),
			));
			$this->object->expects($this->any())
				->method('isReflectionAvailable')
				->will($this->returnValue(FALSE));
		}
	}

	/**
	 * Provides test cases for the `testInvoke` test.
	 */
	public function provideInvoke()
	{
		// [classname, default arguments, arguments, reflection available]
		return array(
			array('stdClass', array(), array(), TRUE),
			array('ArrayObject', array(array('arg 1')), array(), TRUE),
			array('ArrayObject', array(array('arg 1', 'arg 2')), array(), FALSE),
			array('ArrayObject', array(array('arg 1')), array(array('arg 1', 'arg 2')), FALSE),
		);
	}

	/**
	 * Provides test cases for the common `testConstruct` test, that's in the Testcase class.
	 */
	public function provideConstruct()
	{
		// [callback definition, callback arguments, expected exception]
		return array(
			// Invalid classname definitions.
			array('PHPUnit_Framework_TestCase::any', NULL, new Exceptions\InvalidArgumentException),
			array(array($this, 'provideConstruct'), NULL, new Exceptions\InvalidArgumentException),
			array(new \stdClass, NULL, new Exceptions\InvalidArgumentException),
			array(function() {return TRUE;}, NULL, new Exceptions\InvalidArgumentException),
			// Valid classname definitions, with arguments.
			array(get_class($this), array(1, 2), NULL),
			array($this->getClassName(), array(1), NULL),
			array('ArrayObject', array(array(1, 2, 3)), NULL),
			// Valid classname definitions without arguments.
			array(get_class($this), NULL, NULL),
			array($this->getClassName(), NULL, NULL),
			array('ArrayObject', NULL, NULL),
			array('ArrayObject', array(), NULL),
			// Valid classname definition, but not instanciable.
			array('Cz\Framework\Callbacks\CallbackInterface', NULL, new Exceptions\InvalidArgumentException),
		);
	}
}
